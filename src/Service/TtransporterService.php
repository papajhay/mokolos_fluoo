<?php

namespace App\Service;

use App\Entity\Ttransporter;

class TtransporterService
{
    /**
     * fait un findById mais renvoi l'objet spécifique du transporteur comme transporteurDPD pour DPD ou renvoi un objet TTransporteur par défaut
     * @param int $idTransporteur id du transporteur
     * @return TTransporteur|transporteurDPD
     */
    public function findByIdWithChildObject(Ttransporter $ttransporter,int $idTransporteur)
    {
        // si on a déjà chercher ce transporteur
        if(!isset($ttransporter::$_cacheFindAllWithChild[$idTransporteur]))
        {
            // si ce transporteur à sa propre classe
            if(isset($ttransporter::$_classeDeTransporteur[$idTransporteur]))
            {
                // on renverra un objet de cette classe
                $rawClasseName = $ttransporter::$_classeDeTransporteur[$idTransporteur];
            }
            // pas de classe spécifique
            else
            {
                // on renvoi un objet TTransporteur
                $rawClasseName = __CLASS__;
            }

            // on récupére la classe avec d'eventuel modification de transporteur
            $classeName = $rawClasseName::className();

            // on met en cache notre objet
            $ttransporter->_cacheFindAllWithChild[$idTransporteur] = $classeName::findById($idTransporteur);
        }


        return $ttransporter->_cacheFindAllWithChild[$idTransporteur];
    }
}