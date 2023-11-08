<?php declare(strict_types=1);

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Result;
use Symfony\Bridge\Doctrine\ManagerRegistry;

class BaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $entityManager, $entityClass)
    {
        parent::__construct($entityManager, $entityClass);
    }

    /**
     * @throws Exception
     */
    public function insert(array $data, $ignore = false): void
    {
        // return $data;
        $table = $this->_class->getTableName();
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sqlBase = $ignore ? 'INSERT IGNORE INTO' : 'INSERT INTO';

        $sql = "$sqlBase $table ($columns) VALUES ($placeholders)";

        $connection = $this->_em->getConnection();
        $stmt = $connection->prepare($sql);

        $stmt->executeQuery(array_values($data));
    }

    /**
     * Prepare les champs de la condition Where d'une requete SQL preparee.
     * @param string     $field          Nom du champ de la table
     * @param string     $value          Valeur du champ de la table
     * @param string|int $type           type de champ (s pour string ou d pour decimal)
     * @param string     $operator       operateur de la condition Where (=, >, <, LIKE, etc)
     * @param array      $whereField     construction par reference du tableau des champs
     * @param array      $finalValueList construction par reference du tableau des valeurs
     * @param array      $whereType      construction par reference du tableau des types
     */
    public function _prepareSqlWhereFrom($field, $value, $type, $operator, &$whereField, &$finalValueList, &$whereType)
    {
        // si la valeur est NULL
        if (null === $value) {
            // suivant l'operateur
            $whereField[] = match ($operator) {
                '<>', '!=', 'IS NOT', 'NOT' => $field.' IS NOT NULL ',
                default => $field.' IS NULL ',
            };
        } // valeur différente de NULL
        else {
            $whereField[] = match ($operator) {
                'AGAINST' => $field.' '.$operator.' (?) ',
                default => $field.' '.$operator.' ? ',
            };

            $finalValueList[] = $value; // NULL, [], string, number,
            $whereType[] = $type; // string
        }
    }

    /**
     * Prépare et execute un requete preparé simple pour un select. le résultat est bindé au tableau $data.
     * @param  array|string         $pTable           String si on a sql sans jointure
     *                                                ou array sous forme $pTable[0]['table'] = 'nom_de_la_table'
     *                                                $pTable[0]['alias'] = 'alias_de_la_table')
     * @param  array                $champs           un tableau des noms des champs (AVEC ALIAS)
     * @param  array                $where            ex : $values = array(
     *                                                array('date_add',$this->dateAdd , 's'),
     *                                                array('date_modif',$this->dateModif , 's'),
     *                                                array('format_id', $this->formatId, 'i'),
     *                                                array('customer_id', $this->customerId, 'i'),
     *                                                array('pdf_file_name', $this->pdfFileName, 's'),
     *                                                array('pdf_preview',$this->pdfPreview , 's'),
     *                                                array('use_doc_name',$this->useDocName , 's', 'LIKE'),
     *                                                array('use_doc_statut',$this->useDocStatut , 'i', '>=')
     *                                                );
     * @param  int                  $limit            Nombre de resultats a afficher (0 pour ne pas avoir de limite)
     *                                                ou tableau avec la limite inferieure et superieure (ex : array(0,10))
     * @param  array                $order            Valeur du order by
     * @param  array                $joinCondition    (NULL si on une requette simple, SANS jointure.
     *                                                ARRAY si on a jointure exemple:
     *                                                array("Alias.Cahmps1 = Alias2.champs2", "Alias.Cahmps1 = Alias2.champs2 AND Alias.Cahmps1 = Alias2.champs2");
     *                                                array("t.id_produit = tl.id_produit AND t.id_option_value = tl.id_option_value")
     * @param  array                $groupByList      Tableau VIDE ou liste des champs pour le regroupement :
     *                                                array('t.champ1', 't.champ2');
     * @param  bool                 $distinct         =FALSE mettre TRUE si on veux un SELECT DISTINCT
     * @param  bool                 $sqlCalcFoundRows =FALSE mettre TRUE si on veux un SELECT SQL_CALC_FOUND_ROWS
     * @return mysqli_stmt          la requête préparé
     * @throws \ReflectionException
     */
    public function prepareSelectAndExecute($pTable, $champs, $where, $limit = 0, $order = [], $joinCondition = null, $groupByList = [], $distinct = false, $sqlCalcFoundRows = false): Result
    {
        // temps de début pour la durée d'execustion des requête
        $startTime = microtime(true);

        $finalValueList = [];
        $whereType = [];
        $whereField = [];

        // on met la limite dans un tableau
        if (!is_array($limit)) {
            $limit = [0, $limit];
        }

        // si le nom de la table est sous forme de string
        if (is_string($pTable)) {
            // on transforme en tableau
            $aTable = [];
            $aTable[0]['table'] = $pTable;
            $aTable[0]['alias'] = '';
        } // le nom de table est déjà un tableau
        else {
            // On renome la variable
            $aTable = $pTable;
        }

        // si l'ordre est en string
        if (is_string($order)) {
            // on le met dans un array
            $order = [$order];
        }

        // On construit le contenu de la jointure
        if (null !== $joinCondition && is_string($joinCondition)) {
            $joinCondition = [$joinCondition];
        }

        // On construit le contenu de la clause where
        foreach ($where as $value) {
            $operator = '='; // Operateur egal par defaut
            if (isset($value[3])) {
                $operator = $value[3];
            }

            if (is_array($value[0])) {
                // Plusieurs champs
                if (!is_array($value[1])) {
                    // Plusieurs champs et une seule valeur
                    $orStrList = [];
                    foreach ($value[0] as $field) {
                        $this->_prepareSqlWhereFrom($field, $value[1], $value[2], $operator, $orStrList, $finalValueList, $whereType);
                    }
                    $whereField[] = '('.implode(' OR ', $orStrList).')';
                }
            } else {
                // Un seul champ
                if (is_array($value[1])) {
                    // Un seul champ et plusieurs valeurs
                    switch (trim(strtoupper($operator))) {
                        case 'IN':
                        case 'NOT IN':
                            $inStrList = [];
                            if (is_array($value[1])) {
                                foreach ($value[1] as $val) {
                                    $inStrList[] = '?';
                                    $finalValueList[] = $val;
                                    $whereType[] = $value[2];
                                }
                            } else {
                                $inStrList[] = '?';
                                $finalValueList[] = $value[1];
                                $whereType[] = $value[2];
                            }
                            $whereField[] = $value[0].' '.$operator.'('.implode(', ', $inStrList).')';
                            break;

                        default: // OR
                            $orStrList = [];
                            foreach ($value[1] as $val) {
                                $this->_prepareSqlWhereFrom($value[0], $val, $value[2], $operator, $orStrList, $finalValueList, $whereType);
                            }
                            $whereField[] = '('.implode(' OR ', $orStrList).')';
                            break;
                    }
                } else {
                    // Un seul champ et une seule valeur
                    $this->_prepareSqlWhereFrom($value[0], $value[1], $value[2], $operator, $whereField, $finalValueList, $whereType);
                }
            }
        }

        // base de la requéte
        $sqlBase = 'SELECT ';

        // si on veux un le nombre de ligne pour les limit
        if ($sqlCalcFoundRows) {
            // on ajoute la fonction sql
            $sqlBase .= 'SQL_CALC_FOUND_ROWS ';
        }

        // si on veux un distinct
        if ($distinct) {
            $sqlBase .= 'DISTINCT ';
        }

        $fullEntityName = $aTable[0]['table'];
        $classMetada = $this->_em->getClassMetadata($fullEntityName);
        $tableName = $classMetada->getTableName();
        $sqlBase .= implode(', ', $champs).' FROM '.$tableName.' '.$aTable[0]['alias'];

        // Si on a minimum 2 table : on fait jointure
        if (count($aTable) > 1) {
            // On supprime la table principale "FROM TABLE"
            // $joinCondition[$i] : pour extraire chaque condition de jointure
            // $aTable[1] avec $joinCondition[0] ,  $aTable[2] avec $joinCondition[1] ainsi de suite
            unset($aTable[0]);
            $i = 0;
            foreach ($aTable as $table) {
                if (isset($table['join'])) {
                    $sqlBase .= ' '.$table['join'].' '.$table['table'].' '.$table['alias'].' ON '.$joinCondition[$i];
                } else {
                    $sqlBase .= ' JOIN '.$table['table'].' '.$table['alias'].' ON '.$joinCondition[$i];
                }
                ++$i;
            }
        }

        // On rajoute les filtre
        if (count($whereField) > 0) {
            $sqlBase .= ' WHERE '.implode(' AND ', $whereField);
        }

        // On rajoute le GROUP BY
        if (count($groupByList) > 0) {
            $sqlBase .= ' GROUP BY '.implode(', ', $groupByList);
        }

        // si on a un order
        if (count($order) > 0) {
            // on a rajouter notre limitte
            $sqlBase .= 'ORDER BY '.implode(', ', $order);
        }

        // Ajout de la limite
        $nbLimit = count($limit);
        if (2 === $nbLimit) {
            if ($limit[0] >= 0 && $limit[1] >= 1) {
                $sqlBase .= ' LIMIT '.$limit[0].', '.$limit[1];
            }
        }

        $connection = $this->_em->getConnection();
        $params = [];

        // str_contain, strpos ????
        if (str_contains($sqlBase, '?')) {
            foreach ($finalValueList as $key => $value) {
                $newKey =  sprintf('a%d', $key);
                $sqlBase = preg_replace('/\?/', sprintf(':%s', $newKey), $sqlBase, 1);
                $params[$newKey] = $value;
            }
        }

        $stmt = $connection->prepare($sqlBase);

        return $stmt->executeQuery($params);
    }

    /**
     * Prépare et execute un requete preparé simple pour un select. le résultat est renvoyé comme pour un fetchAll.
     * @param  array|string $aTable           String si on a sql sans jointure
     *                                        ou array sous forme $pTable[0]['table'] = 'nom_de_la_table'
     *                                        $pTable[0]['alias'] = 'alias_de_la_table')
     * @param  array        $where            ex : $values = array(
     *                                        array('date_add',$this->dateAdd , 's'),
     *                                        array('date_modif',$this->dateModif , 's'),
     *                                        array('format_id', $this->formatId, 'i'),
     *                                        array('customer_id', $this->customerId, 'i'),
     *                                        array('pdf_file_name', $this->pdfFileName, 's'),
     *                                        array('pdf_preview',$this->pdfPreview , 's'),
     *                                        array('use_doc_name',$this->useDocName , 's', 'LIKE'),
     *                                        array('use_doc_statut',$this->useDocStatut , 'i', '>=')
     *                                        );
     * @param  int          $limit            Nombre de resultats a afficher (0 pour ne pas avoir de limite)
     *                                        ou tableau avec la limite inferieure et superieure (ex : array(0,10))
     * @param  array        $order            Valeur du order by
     * @param  array        $joinCondition    (NULL si on une requette simple, SANS jointure.
     *                                        ARRAY si on a jointure exemple:
     *                                        array("Alias.Cahmps1 = Alias2.champs2", "Alias.Cahmps1 = Alias2.champs2 AND Alias.Cahmps1 = Alias2.champs2");
     *                                        array("t.id_produit = tl.id_produit AND t.id_option_value = tl.id_option_value")
     * @param  array        $groupByList      Tableau VIDE ou liste des champs pour le regroupement :
     *                                        array('t.champ1', 't.champ2');
     * @param  bool         $distinct         =FALSE mettre TRUE si on veux un SELECT DISTINCT
     * @param  bool         $sqlCalcFoundRows =FALSE mettre TRUE si on veux un SELECT SQL_CALC_FOUND_ROWS
     * @return array        le résultat
     */
    public function prepareSelectAndExecuteAndFetchAll($aTable, $fields, $where, $limit = 0, $order = [], $joinCondition = null, $groupByList = [], $distinct = false, $sqlCalcFoundRows = false)
    {
        // on met la limite dans un tableau
        if (!is_array($limit)) {
            $limit = [0, $limit];
        }

        // on execute la requete préparé
        $stmt = $this->prepareSelectAndExecute($aTable, $fields, $where, $limit, $order, $joinCondition, $groupByList, $distinct, $sqlCalcFoundRows);

        return $stmt->fetchAllAssociative();
    }
}
