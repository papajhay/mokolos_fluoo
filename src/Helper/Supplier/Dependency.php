<?php declare(strict_types=1);

namespace App\Helper\Supplier;

class Dependency
{
    /**
     * tableau des options value classé par id option source puis id option value source.
     * @var TOptionValue[]
     */
    private static array $_aOptionValueByIdSource = [];

    /**
     * tableau des id options avec en clef les id options source.
     * @var int[]
     */
    private static array $_aIdOptionByIdOptionSource = [];

    /**
     * tableau contenant les dépendance : des id options values.
     * @var int
     */
    private int|array $_aDependency = [];

    /**
     * getteur du tableau contenant les dépendance : des id options values.
     * @return int[]
     */
    public function getADependency(): array|int
    {
        return $this->_aDependency;
    }

    /**
     * ajoute une id option à notre tableau par id option source.
     */
    public static function addIdOptionByIdOptionSource(int $idOption, int $idOptionSource): void
    {
        self::$_aIdOptionByIdOptionSource[$idOptionSource] = $idOption;
    }

    /**
     * ajoute une option à notre tableau par id source.
     */
    public static function addOptionValueByIdSource(type $optionValue, int $idOptionSource, int $idOptionValueSource): void
    {
        self::$_aOptionValueByIdSource[$idOptionSource][$idOptionValueSource] = $optionValue;
    }

    /**
     * Renvoi un id option par rapport a un id option source.
     * @param  int      $idOptionSource id de l'option chez le fournisseur
     * @return int|null l'id de l'option ou FALSE en cas de probléme
     */
    public static function idOptionByIdOptionSource(int $idOptionSource): bool|int|null
    {
        // si on ne connait pas cette option value
        if (!isset(self::$_aIdOptionByIdOptionSource[$idOptionSource])) {
            // on quiite la fonction
            return false;
        }

        // on renvoi notre id option
        return self::$_aIdOptionByIdOptionSource[$idOptionSource];
    }

    /**
     * Renvoi un id option value par rapport a un id option source et un id option value source.
     * @param  int      $idOptionSource      id de l'option chez le fournisseur
     * @param  int      $idOptionValueSource id de l'option value chez le fournisseur
     * @return int|null l'id de l'option ou FALSE en cas de probléme
     */
    public static function idOptionValueByIdSources(int $idOptionSource, int $idOptionValueSource): bool|int|null
    {
        // si on ne connait pas cette option value
        if (!isset(self::$_aOptionValueByIdSource[$idOptionSource][$idOptionValueSource])) {
            // on quiite la fonction
            return false;
        }

        // on renvoi notre id option
        return self::$_aOptionValueByIdSource[$idOptionSource][$idOptionValueSource]->getIdOptionValue();
    }

    /**
     * Ajoute une dépendance par rapport à un id option value.
     * @return int|false l'id de l'option value si tout est bon et FALSE sinon
     */
    public function addDependency(int $idOptionValue): bool|int
    {
        // si on n'a pas d'option

        if (false === $idOptionValue) {
            // on quitte la fonction
            return false;
        }

        // on ajoute à notre tableau
        $this->_aDependency[$idOptionValue] = $idOptionValue;

        // on renvoi la dépendancce
        return $idOptionValue;
    }

    /**
     * Ajoute une dépendance par rapport à un id option source et un id option value source.
     * @param int $idOptionSource
     * @param int $idOptionValueSource
     */
    public function addDependencyByIdSources(int $idOptionSource, int $idOptionValueSource): void
    {
        // on récupére l'id option value et on ajoute la dépendance
        $this->addDependency($this->idOptionValueByIdSources($idOptionSource, $idOptionValueSource));
    }

    /**
     * Renvoi les dépendance sous forme de chaine. les id option value sépré par des -.
     * @return string
     */
    public function dependencyString(): string
    {
        // transforme le tableau en chaine
        return implode('-', $this->getADependency());
    }
}
