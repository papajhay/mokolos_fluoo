<?php declare(strict_types=1);

namespace App\Helper;

use Doctrine\DBAL\Exception;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class Curl
{
    /**
     * url utilisé pour la requête curl.
     */
    protected string $url;

    /**
     * paramétre permettant de savoir si il faut faire un utf8encode à la fin du exec.
     * @var bollean
     */
    protected $utf8encode = false;

    /**
     * la réponse du curl_exec : le code HTML de la page si on a utilisé CURLOPT_RETURNTRANSFER.
     * @var string|null
     */
    protected $reponseExec;

    /**
     * contient les header de la page venant de la requête curl si toutes les conditions sont remplis.
     * @var string|null
     */
    protected $headers;

    /**
     * contient le body de la page venant de la requête curl si toutes les conditions sont remplis.
     * @var string|null
     */
    protected $body;

    protected $curlOptions = [];

    protected $client;

    protected $httpMethod = 'POST';

    protected string $error;

    protected \CurlHandle $curlHandle;

    /**
     * Constructeur, créé la requête curl.
     */
    public function __construct(
        string $url,
        array $param = null
    ) {
        $this->url = $url;
        $this->client = HttpClient::create();
        if (null !== $param) {
            $this->setBody($param);
        }
    }

    /**
     * @throws \JsonException
     */
    public function setBody(array $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function execute(bool $followLocation = false, $disablePostAfterLocation = false, int $nbrRedirectionRestante = 5, bool $showHeader = false): string
    {
        $options = [
            'extra' => [
                'curl' => $this->curlOptions,
                'headers' => $this->headers,
                'body' => $this->body,
            ],
        ];

        $response = $this->client->request(
            $this->httpMethod,
            $this->url,
            [
                'body' => $this->body,
            ]
        );

        // $resultatExtractUrl = [];

        // si on doit suivre les redirection
        //        if ($followLocation) {
        //            // on active les header et le return transferer pour pouvoir voir les Location
        //            $this->setOptHeader(true)
        //                ->setOptReturnTransferer(true);
        //        }

        // execution de la requête curl
        //   $this->reponseExec = curl_exec($this->_connection);

        $statusCode = $response->getStatusCode();

        $content = $response->getContent();

        return $content;

        //
        //        // on extrait la location si on a une
        //        $location = $this->headerLocation();
        //
        //        // si on doit suivre les redirection, qu'on a pas atteint la limitte et qu'il y a une redirection
        //        if ($followLocation && $nbrRedirectionRestante > 0 && false !== $location) {
        //            // on diminue le nombre de redirection restante
        //            --$nbrRedirectionRestante;
        //
        //            // si on doit désactiver le post pour les redirection
        //            if ($disablePostAfterLocation) {
        //                // désactivation du post
        //                $this->setOptPost(0);
        //            }
        //
        //            // on regarde si il s'agit d'une url en absolu
        //            if (preg_match('#^https?://#', $location)) {
        //                // maj de l'url avec celle dans le location
        //                $this->setOptUrl($location);
        //            }
        //            // url en relatif commencant pat un /
        //            elseif (preg_match('#^/#', $location)) {
        //                // si on extrait l'adresse du site de la derniére url
        //                if (preg_match('#^((?:https?://)[^/]+)/#', $this->url, $resultatExtractUrl)) {
        //                    // maj de l'url
        //                    $this->setOptUrl($resultatExtractUrl[1].$location);
        //                }
        //                // sinon pas l'url ne contenait que le nom du site
        //                else {
        //                    // maj de l'url
        //                    $this->setOptUrl($this->url.$location);
        //                }
        //            }
        //            // url en relatif ne commencant pas pat un /
        //            else {
        //                // on extrait l'adresse du site de la derniére url avec tous les repertoire
        //                if (preg_match('#^(.*)/[^/]*$#', $this->url, $resultatExtractUrl)) {
        //                    // 	// maj de l'url
        //                    $this->setOptUrl($resultatExtractUrl[1].$location);
        //                }
        //                // sinon pas l'url ne contenait que le nom du site
        //                else {
        //                    // maj de l'url
        //                    $this->setOptUrl($this->url.'/'.$location);
        //                }
        //            }
        //
        //            // on fait le exec sur l'url du location
        //            $exec = $this->exec($followLocation, $disablePostAfterLocation, $nbrRedirectionRestante, $showHeader);
        //
        //            // si on doit faire un utf8encode
        //            if ($this->utf8encode) {
        //                return 'UTF-8'($exec);
        //            }
        //            // pas de utf8encode
        //            else {
        //                return $exec;
        //            }
        //        }
        //
        //        // si on doit suivre les redirection
        //        if ($followLocation) {
        //            // on met à jour la propriété des header à la fin
        //            $this->setOptHeader($showHeader);
        //        }
        //
        //        // si on doit faire un utf8encode
        //        if ($this->utf8encode) {
        //            return 'UTF-8'($this->reponseExec);
        //        }
        //        // pas de utf8encode
        //        else {
        //            return $this->reponseExec;
        //        }
    }

    /**
     * Retourne une chaîne contenant le dernier message d'erreur cURL.
     * @return strin[g Retourne le message d'erreur ou '' (chaîne vide) si aucune erreur n'est survenue
     */
    public function error()
    {
        try {
            $response = $this->client->request('POST', $this->url);
            $this->error = $response->getInfo('error');
        } catch (TransportExceptionInterface $e) {
            echo $e->getMessage();
        }
    }

    /**
     * renvoi le header de la page si toutes les conditions sont remplis.
     */
    public function getHeader()
    {
        // si on a pas encore extrait le header
        if (null === $this->headers) {
            // extraction du header
            $this->headers = substr($this->reponseExec, 0, $this->getinfo(CURLINFO_HEADER_SIZE));
        }

        return $this->headers;
    }

    /**
     * renvoi le body de la page. Attention si la setOptHeader n'a pas était appelé, cela va renvoyé un body tronqué.
     */
    public function getBody()
    {
        // si on a pas encore extrait le header
        if (null === $this->body) {
            // extraction du header
            $this->body = substr($this->reponseExec, $this->getinfo(CURLINFO_HEADER_SIZE));
        }

        return $this->body;
    }

    /**
     * Lit les informations détaillant un transfert cURL wrapper de la fonction curl_getinfo.
     * @param  int   $opt constante CURLINFO_XXX
     * @return mixed Si opt est fourni, la valeur sera retournée. Sinon, ce sera un tableau associatif
     */
    public function getinfo($opt = null)
    {
        // si on n'a pas d'option
        if (null === $opt) {
            $response = $this->client->request('POST', $this->url);
        }

        return $opt = $response->getInfo();
    }

    /**
     * Si l'appel a échoué.
     * @return int Erreur
     */
    public function hasFailed()
    {
        // return curl_errno($this->curlHandle, $this->_connection);
        try {
            throw new Exception();
        } catch (TransportExceptionInterface $e) {
            echo "L'appel a échoué";
        }
    }

    /**
     * Message d'erreur si l'appel a échoué.
     */
    public function getErrorMessage()
    {
        //  return curl_error($this->_connection);
        try {
            $response = $this->client->request('POST', $this->url);
            $this->error = $response->getInfo('error');
        } catch (TransportExceptionInterface $e) {
            return $e->getMessage();
        }
    }

    /**
     * extrait le location d'un header si il existe.
     * @return bool|string FALSE ou la valeur de la location
     */
    public function headerLocation()
    {
        $resultatLocation = [];

        // on a extrait une location
        if (preg_match('#[\r\n]location:\s*([^\r\n]+)[\r\n]#i', $this->getHeader(), $resultatLocation)) {
            return $resultatLocation[1];
        }
        // pas de location
        else {
            return false;
        }
    }

    /**
     * renvoi le dernier code HTTP reçu.
     * @return int
     */
    public function httpResponseCode()
    {
        return $this->getinfo(CURLINFO_HTTP_CODE);
    }

    /**
     * Définit une option de transmission cURL.
     * @param type $option L'option CURLOPT_XXX à définir
     * @param type $value  la valeur à définir pour option
     */
    public function setOpt($option, $value)
    {
        // $this->curlOptions[$option] = $value;

        return $this;
    }

    /**
     * modifie les timeout de connection et d'éxacution de la requête curl.
     * @param  int  $secondes nombre de secondes
     * @return Curl
     */
    public function setOptAllTimeout(int $secondes)
    {
        $this->curlOptions[CURLOPT_TIMEOUT] = $secondes;
        $this->curlOptions[CURLOPT_CONNECTTIMEOUT] = $secondes;

        return $this;
    }

    /**
     * indique le fichier dans lequel le cookie va être sauvegardé.
     * @return Curl
     */
    //     public function setOptCookieFile($filename = '/tmp/curl_cookie/cookie.txt')
    //     {
    //     //création d'un nouvel objet fichier
    //     $fichier = new Fichier();
    //     $fichier->setCheminComplet($filename);
    //
    //    // on créé le répertoire qui va contenir le fichier si besoin
    //      	$fichier->getRepertoire()->mk();
    //
    //     	$this->curlOptions[CURLOPT_COOKIEJAR] = $fichier->getCheminComplet();
    //     	return $this;
    //      }

    /**
     * contenu du cookie.
     * @param  array $aValue tableau contenant toutes les caleurs de cookie sous la forme array('fruit=pomme', 'couleur=rouge)
     * @return Curl
     */
    public function setOptCookieValue($aValue = [])
    {
        // le séparateur entre les champs des cookie est ';' suivi d'un espace
        $this->curlOptions[CURLOPT_COOKIE] = $aValue;

        return $this;
    }

    /**
     * configure l'a variable'option curl followLocation.
     * @param  bool  $enable         veux-t-on activer cette option ?
     * @param  int   $maxRedirection nombre de redirection maximum
     * @return \Curl
     */
    public function setOptFollowLocation($enable = true, $maxRedirection = 5)
    {
        $this->curlOptions[CURLOPT_FOLLOWLOCATION] = $enable;
        $this->curlOptions[CURLOPT_MAXREDIRS] = $maxRedirection;

        return $this;
    }

    /**
     * actve ou desactive l'envoi des header.
     * @param  bool $enable veux-t-on activer cette option ?
     * @return Curl
     */
    public function setOptHeader(bool $enable = true): static
    {
        $this->curlOptions[CURLOPT_HEADER] = $enable;

        return $this;
    }

    /**
     * modifie les header que l'on envoi.
     * @param  array $header un tableau avec un élément pour chaque ligne de header
     * @return Curl
     */
    public function setOptHttpHeader($header = [])
    {
        $this->curlOptions[CURLOPT_HTTPHEADER] = $header;

        return $this;
    }

    /**
     * actve ou desactive l'envoi de donnée post.
     * @param  int    $enable 1 pour envoyer du post, 0 pour ne pas en envoyer
     * @param  string $data   les données à envoyer en post ex : identifiant=achat@fluoo.com&mot_de_passe=U6KTUV97&exa_auth_remember=0&connexion_submit=Se connecter
     * @return Curl
     */
    public function setOptPost($enable, $data = '')
    {
        // on met à jour le paramétre POST et POSTFIELDS
        $this->curlOptions[CURLOPT_POST] = $enable;
        $this->curlOptions[CURLOPT_POSTFIELDS] = $data;

        // si on a désactivé le post
        if (0 === $enable) {
            // on repasse en méthode get
            $this->getCurlOptions();
        }

        return $this;
    }

    /**
     * renvoi les donnée au lieu de les affiché si $enable est à TRUE.
     * @return Curl
     */
    public function setOptReturnTransferer(bool $enable = true): static
    {
        // si on active la fonction
        if (true === $enable) {
            $this->curlOptions[CURLOPT_RETURNTRANSFER] = 1;
        }
        // si on active pas la fonction
        else {
            $this->curlOptions[CURLOPT_RETURNTRANSFER] = 0;
        }

        return $this;
    }

    /**
     * modifie l'url utilisé par la requête curl.
     * @return Curl
     */
    public function setOptUrl()
    {
        $this->curlOptions[CURLOPT_URL] = $this->url;

        return $this;
    }

    /**
     * change le user agent pour un userAgent Robot Lgi.
     * @return \Curl
     */
    public function setAgentRobotLgi()
    {
        $this->curlOptions[CURLOPT_USERAGENT] = 'Robot LGI (+http://www.lesgrandesimprimeries.com)';

        return $this;
    }

    /**
     * change le user agent pour un fake (IE).
     * @return \Curl
     */
    public function setFakeAgent()
    {
        $this->curlOptions[CURLOPT_USERAGENT] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:80.0) Gecko/20100101 Firefox/80.0';

        return $this;
    }

    /**
     * met à jour la propriété utf8encode qui appliquera la fonctione utf8encode à la fin du exec de la requ^te curl.
     * @param  bool  $enable
     * @return \Curl
     */
    public function setUtf8Encode($enable = true)
    {
        $this->utf8encode = $enable;

        return $this;
    }

    /**
     * active ou desactive le mode debug (ajout des header de retour, ...).
     * @param  bool $activate
     * @return Curl
     */
    public function debug($activate = true)
    {
        $this->setOptHeader($activate);

        return $this;
    }

    /**
     * Destructeur, clot la connection curl.
     */
    // public function __destruct()
    // {
    // 	curl_close($this->curlHandle, $this->_connection);
    // }

    /**
     * Get the value of curlOptions.
     */
    public function getCurlOptions()
    {
        return $this->curlOptions;
    }

    /**
     * Set the value of curlOptions.
     *
     * @return self
     */
    public function setCurlOptions($curlOptions)
    {
        $this->curlOptions = $curlOptions;

        return $this;
    }
}
