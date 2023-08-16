<?php

namespace App\Helper;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class Client
{
    protected $client;

    protected $httpMethod = 'POST';
    /**
     * url utilisé pour la requête curl.
     * @var string
     */
    protected string $url;
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
    /**
     * Constructeur, créé la requête curl.
     */
    public function __construct(
        string $url,
        array $param = null
    ) {
        $this->url=$url;
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
    public function execute():string
    {

        $response = $this->client->request(
            $this->httpMethod,
            $this->url,
            [
                'body' => $this->body,
            ]
        );

        $statusCode = $response->getStatusCode();

        $content = $response->getContent();

        return $content;

    }
}