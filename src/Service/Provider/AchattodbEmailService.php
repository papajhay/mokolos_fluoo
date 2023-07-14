<?php
declare(strict_types=1);

namespace App\Service\Provider;


use App\Entity\AchattodbEmail;
use Doctrine\ORM\EntityManagerInterface;
class AchattodbEmailService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ){}

    /**
    //     * indique que ce mail doit être retraité
    //     * @return boolean
    //     */
    public function needReprocess(AchattodbEmail $achattodbEmail): bool
    {
        // suivant le statut du mail
        switch($achattodbEmail->getStatus())
        {
            // on est au premier traitement
            case AchattodbEmail::STATUS_NOT_PROCESSED:
                // on change le statut du mail pour le retraiter
                $achattodbEmail->setStatus(AchattodbEmail::STATUS_NEED_REPROCESSED_1);
                $this->entityManager->persist($achattodbEmail);
                $this->entityManager->flush();

                // on indique que l'on va retraité le mail
                return true;

            // on est au 1er reretraitement
            case AchattodbEmail::STATUS_NEED_REPROCESSED_1:
                // on change le statut du mail pour le retraiter
                $achattodbEmail->setStatus(AchattodbEmail::STATUS_NEED_REPROCESSED_2);
                $this->entityManager->persist($achattodbEmail);
                $this->entityManager->flush();

                // on indique que l'on va retraité le mail
                return true;

            // on est au 2e retraitement
            case AchattodbEmail::STATUS_NEED_REPROCESSED_2:
                // on change le statut du mail pour le retraiter
                $achattodbEmail->setStatus(AchattodbEmail::STATUS_NEED_REPROCESSED_3);
                $this->entityManager->persist($achattodbEmail);
                $this->entityManager->flush();

                // on indique que l'on va retraité le mail
                return true;

            // on est au 3e retraitement
            case AchattodbEmail::STATUS_NEED_REPROCESSED_3:
                // on change le statut du mai pour le mettre en erreur
                $achattodbEmail->setStatus(AchattodbEmail::STATUS_NEED_REPROCESSED_ERROR);
                $this->entityManager->persist($achattodbEmail);
                $this->entityManager->flush();

                // on indique que l'on ne peux plus retraiter le mail
                return false;

            // autre cas (en théorie impossible)
            default:
                // on quitte la fonction
                return false;
        }
    }
}