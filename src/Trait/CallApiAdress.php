<?php

use Symfony\Component\HttpClient\HttpClient;

trait CallApiAdress
{
    public function fetchApi(): array
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api-adresse.data.gouv.fr/search/?q=12+avenue+des+fauvettes+44470+carquefou');

        $statusCode = $response->getStatusCode();
// $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
// $contentType = 'application/json'
        $content = $response->getContent();
// $content = '{"id":521583, "name":"symfony-docs", ...}'
//            $content = $response->toArray();
// $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        $test =json_decode($content);

        dd($test->features[0]->geometry->coordinates);
    }
}