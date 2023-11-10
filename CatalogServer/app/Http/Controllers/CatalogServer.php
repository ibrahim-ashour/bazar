<?php

namespace App\Http\Controllers;

use App\Models\Book;

class CatalogServer extends Controller
{
    //info about book
    public function info($id)
    {
        return response()->json( Book::select('title','items_count as quntity','cost as price')->where('id', $id)->get(), 200);
    }

    //search about topics
    public function search($topic)
    {
        $new = str_replace('%20', ' ', $topic); // replace %20 with space
        return response()->json( Book::select('id','title')->where('topic', $new)->get(), 200);
    }

    //update [ cost , count of items ]
    public function update($id , $type , $value)
    {
        $book = Book::where('id', $id)->first();

        if ( $type == "cost" )
            $book->cost = $value;   
         else if ( $type == "items" ) 
            $book->items_count = $book->items_count + $value;
        
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
            "title" => " Xen and the Art of Surviving Undergraduate School.",
            "items_count" => 4,
            "cost" => 70,
            "topic" => "undergraduate school",
        ]);

        book::create([
            "title" => " Cooking for the Impatient Undergrad.",
            "items_count" => 5,
            "cost" => 15,
            "topic" => "undergraduate school",
        ]);


    }

    public function all()
    {
        return response()->json(['books' =>  book::all() ] , 200);
    }
}