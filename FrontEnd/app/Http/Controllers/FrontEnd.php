<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use App\Models\Book;

class FrontEnd extends Controller
{
    public static $CatalogServer_API = 'http://project1:8000';
    public static $OrderServer_API = 'http://project3:8000';

    //info about book
    public function info($id)
    {
        $client = new Client();
        
        $apiInfoBook =  FrontEnd::$CatalogServer_API . '/CatalogServer/info/' . $id;
        $response = $client->get($apiInfoBook);
        $responseBody = $response->getBody()->getContents();
        $responseData = json_decode($responseBody, true);

        return response()->json( $responseData );
    }

    //search about topics
    public function search($topic)
    {
        $client = new Client();
        
        $apiInfoBook = FrontEnd::$CatalogServer_API . '/CatalogServer/search/' . $topic;
        $response = $client->get($apiInfoBook);
        $responseBody = $response->getBody()->getContents();
        $responseData = json_decode($responseBody, true);

        return response()->json( $responseData );
    }

    //search about topics
    public function purchase($id)
    {
        $client = new Client();
        
        $apiInfoBook = FrontEnd::$OrderServer_API . '/OrderServer/purchase/' . $id;
        $response = $client->put($apiInfoBook);
        $responseBody = $response->getBody()->getContents();
        $responseData = json_decode($responseBody, true);

        return response()->json( $responseData );
    }
}