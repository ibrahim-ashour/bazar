<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;

class OrderServer extends Controller
{
    public function purchase($id)
    {
        $client = new Client();
        
        //info about requested book
        $apiInfoBook = 'http://localhost:8000/CatalogServer/info/' . $id;
        $response = $client->get($apiInfoBook);
        $responseBody = $response->getBody()->getContents();
        $responseData = json_decode($responseBody, true);

        //get qunitity from request
        $quntity = $responseData[0]["quntity"];

        //check from quntity
        if ( $quntity > 0 )
        {
            //purchase book request
            $apiUpdate = 'http://localhost:8000/CatalogServer/update/'.$id.'/items/'.'-1';
            $response = $client->put($apiUpdate);
            return response()->json("bought book " . $responseData[0]["title"]);
        }
        else
        { // there is no any item
            return response()->json("There is items in stock");
        }
    }
    
}