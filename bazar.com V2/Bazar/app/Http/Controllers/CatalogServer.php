<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use App\Models\Book;

class CatalogServer extends Controller
{
    public static $FrontEnd_API = 'http://localhost:8001';
    public static $CatalogServer_API2 = 'http://localhost:8004';

    //info about book
    public function info($id)
    {
        return response()->json( Book::where('id', $id)->get(), 200);
    }

    //search about topics
    public function search($topic)
    {
        $new = str_replace('%20', ' ', $topic); // replace %20 with space
        return response()->json( Book::where('topic', $new)->get(), 200);
    }

    //update [ cost , count of items ]
    public function update($id , $type , $value)
    {
        $book = Book::where('id', $id)->first();
        if ( $type == "cost" )
            $book->cost = $value;   
         else if ( $type == "items" ) 
            $book->items_count = $book->items_count + $value;

        $this->sendInalidateToFrontEnd();
        // $this->sendToCatalogServerUpdate($book);
        $book->save();
        return response()->json( "success", 200);
    }

    //inilization
    public function create()
    {
        book::create([
            "title" => "How to get a good grade in DOS in 40 minutes a day.",
            "items_count" => 3,
            "cost" => 50,
            "topic" => "distributed systems",
        ]);

        book::create([
            "title" => "RPCs for Noobs.",
            "items_count" => 5,
            "cost" => 30,
            "topic" => "distributed systems",
        ]);

        book::create([
            "title" => "Xen and the Art of Surviving Undergraduate School.",
            "items_count" => 4,
            "cost" => 70,
            "topic" => "undergraduate school",
        ]);

        book::create([
            "title" => "Cooking for the Impatient Undergrad.",
            "items_count" => 5,
            "cost" => 15,
            "topic" => "undergraduate school",
        ]);

        book::create([
            "title" => "How to finish Project 3 on time",
            "items_count" => 100,
            "cost" => 50,
            "topic" => "projects",
        ]);

        book::create([
            "title" => "Why theory classes are so hard",
            "items_count" => 70,
            "cost" => 35,
            "topic" => "projects",
        ]);

        book::create([
            "title" => "Spring in the Pioneer Valley",
            "items_count" => 55,
            "cost" => 30,
            "topic" => "projects",
        ]);


    }

    public function all()
    {
        return response()->json(['books' =>  book::all() ] , 200);
    }

    public function sendInalidateToFrontEnd()
    {
    }

    public function sendToCatalogServerUpdate($data)
    {
        $client = new Client();
        $response = $client->get($CatalogServer_API2 . '/' . $data); // send to catalog2 there is new update
        $responseBody = $response->getBody()->getContents();
    }

}