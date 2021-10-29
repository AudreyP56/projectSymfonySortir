<?php
namespace App\Trait;

use App\Entity\Lieu;
use App\Entity\Ville;
use PHPUnit\Util\Exception;
use Symfony\Component\HttpClient\HttpClient;

trait CallApiAdress
{
    public function fetchApi(Lieu $lieu,Ville $ville): array
    {
        $result=[];

        $adresse = $lieu->getRue();
        $paramAdresse = str_replace(" ","+",$adresse);
        $codePostal = $ville->getCodePostal();
        $nomVille = $ville->getNom();

        $client = HttpClient::create();
        $response = $client->request('GET', "https://api-adresse.data.gouv.fr/search/?q=".$paramAdresse."+".$codePostal."+".$nomVille);


        if (200 !== $response->getStatusCode()) {
            throw new Exception('Response status code is different than expected.the actual statusCode is :'.$response->getStatusCode());
        }

        $contentType = $response->getHeaders()['content-type'][0];

        $content = $response->getContent();

        $data =json_decode($content);
        if ($data){
            $result = $data->features[0]->geometry->coordinates;
        }

        return $result;

    }
}