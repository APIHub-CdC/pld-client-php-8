<?php

namespace pld\mx\Client;

use \GuzzleHttp\Client;
use \GuzzleHttp\HandlerStack;

use \pld\mx\Client\ApiException;
use \pld\mx\Client\Model\Peticion;
use \pld\mx\Client\Configuration;
use \pld\mx\Client\Api\PLDApi;
use Signer\Manager\Interceptor\MiddlewareEvents;
use Signer\Manager\Interceptor\KeyHandler;

class PLDApiTest extends \PHPUnit\Framework\TestCase 
{
    protected $apiInstance;
    protected $signer;

    public function setUp(): void
    {
        $password = getenv('KEY_PASSWORD');
        $this->signer = new KeyHandler("your-keystore.p12", "circulo_credito-certificate.pem", $password);
        $events = new MiddlewareEvents($this->signer);
        $handler = HandlerStack::create();
        $handler->push($events->add_signature_header('x-signature'));
        $handler->push($events->verify_signature_header('x-signature'));

        $config = new Configuration();
        $config->setHost('https://services.circulodecredito.com.mx/v2/pld');
        $client = new Client(['handler' => $handler]);
        $this->apiInstance = new PLDApi($client, $config);
    }

    public function testGetPLD(): void
    {
        $x_api_key = "your-api-key";
        $username = "your-username"; // Grantor portal (Portal de otorgantes) username
        $password = "your-password"; // Grantor portal (Portal de otorgantes) password
        $body = new Peticion();

        $body->setNombres("JUAN");
        $body->setApellidoPaterno("PRUEBA");
        $body->setApellidoMaterno("CUATRO");

        try {
            $result = $this->apiInstance->getPLD($x_api_key, $username, $password, $body);
            $this->assertTrue($result->getFolioConsulta()!==null);
            $this->signer->close();
            print_r($result);
        } catch (Exception | ApiException $e) {
            echo 'Exception when calling PLDApi->getPLD: ', $e->getMessage(), PHP_EOL;
        }

    }
}
