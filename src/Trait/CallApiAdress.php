<?php
namespace App\Trait;

use App\Entity\Lieu;
use App\Entity\Ville;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;

trait CallApiAdress
{
    public function fetchApi(Lieu $lieu,Ville $ville): array
    {
        $adresse = $lieu->getRue();
//        $param = str_replace(" ","+",$adresse);
        $codePostal = $ville->getCodePostal();
        $nomVille = $ville->getNom();

        $client = HttpClient::create();
        $response = $client->request('GET', "https://api-adresse.data.gouv.fr/search/?q=".$adresse."+".$codePostal."+".$nomVille);

//        $response = $client->request('GET', "https://api-adresse.data.gouv.fr/search/?q=12+avenue+des+fauvettes+44470+carquefou");

        $statusCode = $response->getStatusCode();

        $contentType = $response->getHeaders()['content-type'][0];

        $content = $response->getContent();

        $data =json_decode($content);

        $result = $data->features[0]->geometry->coordinates;
        return $result;


    }
}