<?php
declare(strict_types=1);
namespace App\Entity;

use App\Repository\AchattodbEmailRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

//$_SQL_TABLE_NAME = 'webmail.achattodb_email';
#[ORM\Entity(repositoryClass: AchattodbEmailRepository::class)]
class AchattodbEmail
{
    /**
     * statut du mail : mail non traité
     */
    //const STATUS_NOT_PROCESSED = 0;

    /**
     * statut du mail : mail traité
     */
    const STATUS_PROCESSED = 1;

    /**
     * statut du mail : mail nécessitant un nouveau traitement (1er essai)
     */
    //const STATUS_NEED_REPROCESSED_1 = 10;

    /**
     * statut du mail : mail nécessitant un nouveau traitement (2e essai)
     */
    //const STATUS_NEED_REPROCESSED_2 = 11;

    /**
     * statut du mail : mail nécessitant un nouveau traitement (3e essai)
     */
    //const STATUS_NEED_REPROCESSED_3 = 12;

    /**
     * statut du mail : mail nécessitant un nouveau traitement, echec de tous les traitements
     */
    //const STATUS_NEED_REPROCESSED_ERROR = 20;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $emailFrom = null;

    #[ORM\Column(length: 255)]
    private ?string $emailFromP = null;

    #[ORM\Column(length: 255)]
    private ?string $emailTo = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateE = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateDb = null;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\Column]
    private ?int $del = null;

    #[ORM\Column(length: 255)]
    private ?string $subject = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $messageHtml = null;

    #[ORM\Column]
    private ?int $messageSize = null;

    #[ORM\Column]
    //sous objet DateHeure de la date d'emission du mail
    private ?\DateTimeImmutable $datetimeSend = null;

    /**
     * tableau des piéce jointes des mails
     * @var AchattodbAttach[]
     */
    //private $_aAttach = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmailFrom(): ?string
    {
        return $this->emailFrom;
    }

    public function setEmailFrom(string $emailFrom): static
    {
        $this->emailFrom = $emailFrom;

        return $this;
    }

    public function getEmailFromP(): ?string
    {
        return $this->emailFromP;
    }

    public function setEmailFromP(string $emailFromP): static
    {
        $this->emailFromP = $emailFromP;

        return $this;
    }

    public function getEmailTo(): ?string
    {
        return $this->emailTo;
    }

    public function setEmailTo(string $emailTo): static
    {
        $this->emailTo = $emailTo;

        return $this;
    }

    public function getDateE(): ?\DateTimeImmutable
    {
        return $this->dateE;
    }

    public function setDateE(\DateTimeImmutable $dateE): static
    {
        $this->dateE = $dateE;

        return $this;
    }

    public function getDateDb(): ?\DateTimeImmutable
    {
        return $this->dateDb;
    }

    public function setDateDb(\DateTimeImmutable $dateDb): static
    {
        $this->dateDb = $dateDb;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDel(): ?int
    {
        return $this->del;
    }

    public function setDel(int $del): static
    {
        $this->del = $del;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getMessageHtml(): ?string
    {
        return $this->messageHtml;
    }

    public function setMessageHtml(string $messageHtml): static
    {
        $this->messageHtml = $messageHtml;

        return $this;
    }

    public function getMessageSize(): ?int
    {
        return $this->messageSize;
    }

    public function setMessageSize(int $messageSize): static
    {
        $this->messageSize = $messageSize;

        return $this;
    }

    public function getDatetimeSend(): ?\DateTimeImmutable
    {
        return $this->datetimeSend;
    }

    public function setDatetimeSend(\DateTimeImmutable $datetimeSend): static
    {
        $this->datetimeSend = $datetimeSend;

        return $this;
    }

    //TODO Repository
    /**
     * Renvoi tous les mail dont le staut demande un retraitement
     * @return AchattodbEmail
     */
//    public static function findAllInStatusToReprocess()
//    {
//        // construction des paramétres de la requête
//        $param = self::makeFieldAndValueArrayForFindAll('status', array(AchattodbEmail::STATUS_NEED_REPROCESSED_1, AchattodbEmail::STATUS_NEED_REPROCESSED_2, AchattodbEmail::STATUS_NEED_REPROCESSED_3), 'IN');
//
//        // execution de la requête
//        return AchattodbEmail::findAllBy($param['aChamp'], $param['aValue']);
//    }
//


    //TODO Service
    /**
     * renvoi toutes les piéce jointe lié à notre mails
     * @return AchattodbAttach
     */
//    public function getAAttach()
//    {
//        // si on a pas encore cherché
//        if($this->_aAttach == null)
//        {
//            // on récupére les piéce jointe
//            $this->_aAttach = AchattodbAttach::findAllByIdEmail($this->getId());
//        }
//
//        return $this->_aAttach;
//    }
    /**
     * getteur du sous objet DateHeure de la date d'emission du mail
     * @return DateHeure
     */
//    public function getDateHeureSend(): DateHeure
//    {
//        // si on a pas encore cherché
//        if($this->_dateHeureSend == null)
//        {
//            // on récupére les piéce jointe
//            $this->_dateHeureSend = new DateHeure($this->getDateE());
//        }
//
//        return $this->_dateHeureSend;
//    }
    /**
     * Crée un nouvel objet "AchattodbEmail" et le retourne
     * @param string $emailFrom
     * @param string $emailFromP
     * @param string $emailTo
     * @param datetime $dateE
     * @param string $subject
     * @param int $msgSize
     * @param string $message
     * @param string $messageHtml
     * @param int $status
     * @param int $del
     * @return AchattodbEmail Nouvel Objet inserer un base
     */
//    public static function createNew($emailFrom, $emailFromP, $emailTo, $dateE, $subject, $msgSize, $message = '', $messageHtml = '', $status = 0, $del = 0)
//    {
//        // création de l'objet
//        $achattodbEmail = new AchattodbEmail();
//        $achattodbEmail->setEmailFrom($emailFrom)
//            ->setEmailFromP($emailFromP)
//            ->setEmailTo($emailTo)
//            ->setDateE($dateE)
//            ->setDateDb(System::today()->format(DateHeure::DATETIMEMYSQL))
//            ->setStatus($status)
//            ->setDel($del)
//            ->setSubject($subject)
//            ->setMessage($message)
//            ->setMessageHtml($messageHtml)
//            ->setMsgSize($msgSize)
//            ->save();
//
//        return $achattodbEmail;
//    }
    /**
     * vérifie l'existence en base par rapport à un mail d'expéditeur, une date d'émission, une taille de mail et un sujet
     * @param string $from le mail de l'expéditeur
     * @param string $date la date d'émission du mail
     * @param int $size la taille du mail
     * @param string $subject le sujet du mail
     * @return bool TRUE si le mail existe en base
     */
//    public static function existByFromDateSizeSubjet($from, $date, $size, $subject)
//    {
//        return self::existBy(array('email_from', 'date_e', 'msg_size', 'subject'), array($from, $date, $size, $subject));
//    }
    /**
     * ajoute du contenu au body HTML du mail
     * @param string $html le code html
     */
//    public function addHtmlBody($html)
//    {
//        $this->setMessageHtml($this->getMessageHtml() . $html)
//            ->save();
//    }


    /**
     * ajoute du contenu au body texte brut du mail
     * @param string $texte le message
     */
//    public function addTextBody($texte)
//    {
//        $this->setMessage($this->getMessage() . $texte)
//            ->save();
//    }


    /**
     * indique que ce mail doit être retraité
     * @return boolean
     */
//    public function needReprocess()
//    {
//        // suivant le statut du mail
//        switch($this->getStatus())
//        {
//            // on est au premier traitement
//            case AchattodbEmail::STATUS_NOT_PROCESSED:
//                // on change le statut du mail pour le retraiter
//                $this->setStatus(AchattodbEmail::STATUS_NEED_REPROCESSED_1)
//                    ->save();
//
//                // on indique que l'on va retraité le mail
//                return true;
//
//            // on est au 1er reretraitement
//            case AchattodbEmail::STATUS_NEED_REPROCESSED_1:
//                // on change le statut du mail pour le retraiter
//                $this->setStatus(AchattodbEmail::STATUS_NEED_REPROCESSED_2)
//                    ->save();
//
//                // on indique que l'on va retraité le mail
//                return true;
//
//            // on est au 2e retraitement
//            case AchattodbEmail::STATUS_NEED_REPROCESSED_2:
//                // on change le statut du mail pour le retraiter
//                $this->setStatus(AchattodbEmail::STATUS_NEED_REPROCESSED_3)
//                    ->save();
//
//                // on indique que l'on va retraité le mail
//                return true;
//
//            // on est au 3e retraitement
//            case AchattodbEmail::STATUS_NEED_REPROCESSED_3:
//                // on change le statut du mai pour le mettre en erreur
//                $this->setStatus(AchattodbEmail::STATUS_NEED_REPROCESSED_ERROR)
//                    ->save();
//
//                // on indique que l'on ne peux plus retraiter le mail
//                return false;
//
//            // autre cas (en théorie impossible)
//            default:
//                // on quitte la fonction
//                return false;
//        }
//    }
}
