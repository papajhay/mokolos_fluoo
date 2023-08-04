<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\TLockProcessRepository;
use Doctrine\ORM\Mapping as ORM;

// $_SQL_TABLE_NAME = 'lesgrand.t_lock_process';
#[ORM\Entity(repositoryClass: TLockProcessRepository::class)]
class TLockProcess
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    // nom du process
    private ?string $name = null;

    #[ORM\Column]
    // timestamp de l'execution du process
    private ?int $timestamp = null;

    #[ORM\Column(length: 255)]
    // Etape du process (facultatif)
    private ?string $stage = null;

    #[ORM\Column]
    // timestamp du lancement de l'étape
    private ?int $stageTimestamp = null;

    #[ORM\Column]
    // mémoire utilisé en octet
    private ?int $memory = null;

    #[ORM\Column]
    // indique si on doit supprimer en base notre lcok à la destruction de notre objet
    private ?bool $deleteMeOnDestruct = null;

    /**
     * objet TLog (si disponible).
     * @var log
     */
    // private $_log = NULL;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }

    public function setTimestamp(int $timestamp): static
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getStage(): ?string
    {
        return $this->stage;
    }

    public function setStage(string $stage): static
    {
        $this->stage = $stage;

        return $this;
    }

    public function getStageTimestamp(): ?int
    {
        return $this->stageTimestamp;
    }

    public function setStageTimestamp(int $stageTimestamp): static
    {
        $this->stageTimestamp = $stageTimestamp;

        return $this;
    }

    public function getMemory(): ?int
    {
        return $this->memory;
    }

    public function setMemory(int $memory): static
    {
        $this->memory = $memory;

        return $this;
    }

    public function isDeleteMeOnDestruct(): ?bool
    {
        return $this->deleteMeOnDestruct;
    }

    public function setDeleteMeOnDestruct(bool $deleteMeOnDestruct): static
    {
        $this->deleteMeOnDestruct = $deleteMeOnDestruct;

        return $this;
    }

    // TODO Repository
    /**
     * renvoi un TLockProcess par rapport à un nom. renvoi null si on a rien trouvé.
     * @return TLockProcess|null notre objet ou null
     */
    //    public static function findByName($name)
    //    {
    //        return TLockProcess::findBy(array('loc_pro_name'), array($name));
    //    }

    // TODO Service
    /**
     * Renvoi le délai depuis le lancement du cton.
     * @return Duree
     */
    //    public function getDelayFromStart()
    //    {
    //        // calcul du délai à partir de maintenant
    //        return Duree::dureeEntreDate($this->getLocProTimestamp(), time());
    //    }

    /**
     * Renvoi le délai depuis la derniére étape.
     * @return Duree
     */
    //    public function getDelayFromStage()
    //    {
    //        // calcul du délai à partir de maintenant
    //        return Duree::dureeEntreDate($this->getLocProStageTimestamp(), time());
    //    }

    /**
     * créé un nouvel objet. fonction privée.
     * @return \TLockProcess
     */
    //    private static function _createNew($name)
    //    {
    //        // on récupére un timestamp
    //        $timestamp = time();
    //
    //        // on créé notre TLockProcess
    //        $lockProcess = new TLockProcess();
    //        $lockProcess->setLocProName($name)
    //            ->setLocProTimestamp($timestamp)
    //            ->setLocProStage('Lancé')
    //            ->setLocProStageTimestamp($timestamp)
    //            ->save();
    //
    //        // on indique qu'il faudra supprimé en base notre objet à sa destruction
    //        $lockProcess->setDeleteMeOnDestruct(true);
    //
    //        // création du log.
    //        $lockProcess->setLog(TLog::initLog($name));
    //
    //        // on le renvoi
    //        return $lockProcess;
    //    }

    /**
     * S'execute avant le save pour la mise à jour de l'utilisation de la mémoire.
     */
    //    protected function _preSave()
    //    {
    //        // appel du parent
    //        parent::_preSave();
    //
    //        // mise à jour de l'utilisation de la mémoire
    //        $this->setLocProMemory(memory_get_usage(TRUE));
    //    }

    /**
     * Cré un nouvel objet "TLockProcess" et le retourne.
     * @param varchar(250) $locProName nom du process
     * @param varchar(500) $locProStage Etape du process (facultatif)
     * @return TLockProcess Nouvel Objet inseré en base
     */
    //    public static function createNewOrDie($maxExecutionTime, $name)
    //    {
    //        // on récupére un éventuel process avec ce nom
    //        $oldLockProcess = TLockProcess::findByName($name);
    //
    //        // si on n'a pas encore de lock
    //        if($oldLockProcess == null)
    //        {
    //            // on en créé un nouveau
    //            return TLockProcess::_createNew($name);
    //        }
    //
    //        // si il existe déjà un lock et qu'on n'a pas dépassé le temps maximum
    //        if($oldLockProcess->getDelayFromStart()->getMinutesTotal() < $maxExecutionTime)
    //        {
    //            // on fait un die
    //            exit();
    //        }
    //
    //        // on supprime l'ancien lock
    //        $oldLockProcess->_deleteLock();
    //
    //        // on renvoi un nouveau lock
    //        return TLockProcess::_createNew($name);
    //    }
    /**
     * Renvoi la mémoire utilisé sous un format plus adapté aux humain.
     * @return int
     */
    //    public function memoryString()
    //    {
    //        // si on a une taille à 0
    //        if($this->getLocProMemory() == 0)
    //        {
    //            // on renvoi direct 0 sinon on a un soucis avec certaines fonction
    //            return 0;
    //        }
    //
    //        $unit	 = array('', ' k', ' M', ' G', ' T', ' P');
    //        return round($this->getLocProMemory() / pow(1024, ($i		 = floor(log($this->getLocProMemory(), 1024)))), 2) . $unit[$i];
    //    }

    /**
     * met à jour l'étape du process en cours
     * si un TLog a été fourni on ajoute l'étape dedans.
     * @param string $stageName nom de l'étape
     * @param bool   $addToLog  [=true] Doit-on ajouter dans le TLog ?
     */
    public function updateStage($stageName, $addToLog = true)
    {
        // si notre lock n'existe plus
        if (!$this->existRow()) {
            //            // si on a un log
            //            if($this->getLog() != NULL)
            //            {
            //                // on ajoute l'erreur dans le log
            //                $this->getLog()->Erreur('Lockprocess Killed avant étape :');
            //                $this->getLog()->Erreur($stageName);
            //            }

            // on quitte la page
            exit;
        }

        // mise à jour de l'étape
        $this->setStage($stageName)
            ->setStageTimestamp(time())
            ->save();

        //        // si on a un objet de TLog et qu'on doit ajouter dedans
        //        if($this->getLog() != null && $addToLog)
        //        {
        //            // on ajoute notre étape au log
        //            $this->getLog()->addLogContent($stageName);
        //        }
    }

    /*
     * Cette méthode supprime le lock si il existe.
     * On a besoin d'une fonction spécifique car on ne peux pas appeler delete qui appel le destructeur
     */
    //    private function _deleteLock()
    //    {
    //        // on attend 1s avant de supprimer le lock pour éviter des problèmes si on essaye de mettre à jour en même temps
    //        sleep(1);
    //
    //        // si on a un log
    //        if($this->getLog() != NULL)
    //        {
    //            //ajout d'info dans le log
    //            $this->getLog()->addLogContent('Suppression du lock');
    //        }
    //
    //        // suppression du lockprocess en base
    //        DB::prepareDeleteAndExecute(TLockProcess::$_SQL_TABLE_NAME, array(array('id_lock_process', $this->getIdLockProcess(), 'i')));
    //    }

    /*
     * Destructeur qui va nous supprimer le lock si besoin
     */
    //    public function __destruct()
    //    {
    //        // si on doit supprimer notre objet
    //        if($this->getDeleteMeOnDestruct())
    //        {
    //            // on supprime notre objet
    //            $this->_deleteLock();
    //        }
    //    }
}
