<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use GuzzleHttp\Client;
use App\Models\Book;

class FrontEnd extends Controller
{
    // public static $CatalogServer_API = 'http://project1:8000';
    // public static $OrderServer_API = 'http://project3:8000';

    public static $CatalogServer_API = 'http://localhost:8000';
    public static $CatalogServer_API2 = 'http://localhost:8004';
    
    public static $OrderServer_API = 'http://localhost:8002';
    public static $OrderServer_API2 = 'http://localhost:8003';


    //info about book
    public function info($id)
    {
        $search_book = Book::where('book_id', $id)->get();

        if (count( $search_book ) == 0 )
        {
            $client = new Client();

            $apiInfoBook;

            if ( $this->getValue("catalog") == "0" )
            {
                $apiInfoBook = FrontEnd::$CatalogServer_API . '/CatalogServer/info/' . $id;
                $this->saveValue("catalog" , "1");
                echo "get value from catalog 0\n";
            }
            else
            {
                echo "get value from catalog 1\n";
                $apiInfoBook = FrontEnd::$CatalogServer_API2 . '/CatalogServer/info/' . $id;
                $this->saveValue("catalog" , "0");
            }

            
            $response = $client->get($apiInfoBook);
            $responseBody = $response->getBody()->getContents();
            $book = json_decode($responseBody, JSON_FORCE_OBJECT)[0];

            echo " get it from catalog server\n";

            $new = book::create([
                "book_id" => $book["id"],
                "title" => $book["title"],
                "items_count" => $book["items_count"],
                "cost" => $book["cost"],
                "topic" =>  $book["topic"],
            ]);
            return $new;
        }
        else // cache
        {
            echo " get it from cache ";
            return $search_book;
        }
    }

    //search about topics
    public function search($topic)
    {
        $new = str_replace('%20', ' ', $topic);
        $search_book = Book::where('topic', $new)->get();
        if (count( $search_book ) == 0 )
        {
            $client = new Client();
            // $apiInfoBook = FrontEnd::$CatalogServer_API . '/CatalogServer/search/' . $topic;

            
            $apiInfoBook;

            if ( $this->getValue("catalog") == "0" )
            {
                $apiInfoBook = FrontEnd::$CatalogServer_API . '/CatalogServer/search/' . $topic;
                $this->saveValue("catalog" , "1");
                echo "get value from catalog 0\n";
            }
            else
            {
                echo "get value from catalog 1\n";
                $apiInfoBook = FrontEnd::$CatalogServer_API2 . '/CatalogServer/search/' . $topic;
                $this->saveValue("catalog" , "0");
            }

            $response = $client->get($apiInfoBook);
            $responseBody = $response->getBody()->getContents();

            $book = json_decode($responseBody, JSON_FORCE_OBJECT)[0];

            echo " get it from catalog server ";

            $new = book::create([
                "book_id" => $book["id"],
                "title" => $book["title"],
                "items_count" => $book["items_count"],
                "cost" => $book["cost"],
                "topic" =>  $book["topic"],
            ]);
            return $new;
        }
        else
        {
            echo " get it from cache ";
            return $search_book;
        }

    }

    //search about topics
    public function purchase($id)
    {
        $client = new Client([
            'timeout' => 120,
        ]);
      
        $apiInfoBook; // = FrontEnd::$OrderServer_API . '/OrderServer/purchase/' . $id;

        if ( $this->getValue("order") == "0" )
        {
            $apiInfoBook = FrontEnd::$OrderServer_API . '/OrderServer/purchase/' . $id;
            $this->saveValue("order" , "1");
            echo "get value from order 0\n";
        }
        else
        {
            echo "get value from order 1\n";
            $apiInfoBook = FrontEnd::$OrderServer_API2 . '/OrderServer/purchase/' . $id;
            $this->saveValue("order" , "0");
        }


        $response = $client->put($apiInfoBook);
        $responseBody = $response->getBody()->getContents();
        $responseData = json_decode($responseBody, true);

        $this->invalidate( $id );

        return ( $responseData );

    }

    public function invalidate($id)
    {
        $book = Book::where('book_id', $id); // get from cache
        $book->delete();
        return "invalidate ";
        
    }


    public function saveValue($name,$value)
    {
        $path = storage_path('app/' . $name . '.txt');

        // Read the current value
        // $currentValue = (int) file_get_contents($path);

        // Update the value
        // $currentValue += $value;

        // Save the updated value
        file_put_contents($path, $value);

        // Optionally, you may want to handle the update success or failure here
        // return response()->json(['message' => 'Value saved successfully']);
    }

    public function getValue($name)
    {
        $path = storage_path('app/' . $name . '.txt');

        // Read the current value
        $currentValue = (int) file_get_contents($path);

        return $currentValue;
    }

}