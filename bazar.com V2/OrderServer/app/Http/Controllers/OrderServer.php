<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;

class OrderServer extends Controller
{
    public function purchase($id)
    {
        $client = new Client(['timeout' => 120]);
        
        //info about requested book
        $apiInfoBook = 'http://localhost:8000/CatalogServer/info/' . $id;
        $response = $client->get($apiInfoBook);
        $responseBody = $response->getBody()->getContents();
        $responseData = json_decode($responseBody, true);

        //get qunitity from request
        $quntity = $responseData[0]["items_count"];
      
        //check from quntity
        if ( $quntity > 0 )
        {
            //purchase book request
            $apiUpdate = 'http://localhost:8000/CatalogServer/update/'.$id.'/items/'.'-1';
            $response2 = $client->put($apiUpdate);
            $responseBody2 = $response2->getBody()->getContents();
            $responseData2 = json_decode($responseBody2, true);


            return response()->json("bought book " . $responseData[0]["title"]);
        }
        else
        { // there is no any item
            return response()->json("There is items in stock");
        }
    }
    
}