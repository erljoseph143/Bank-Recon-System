<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SeverSentEventController extends Controller
{
    //
//    public function __construct()
//    {
//        $response = new StreamedResponse();
//        $response->headers->set('Content-Type', 'text/event-stream');
//        $response->headers->set('Cache-Control', 'no-cache');
//        $response->setCallback(
//            function() {
//                echo "retry: 100\n\n"; // no retry would default to 3 seconds.
//                echo "data: Hello There\n\n";
//                ob_flush();
//                flush();
//            });
//        $response->send();
//    }

//    public function loadSSE()
//    {
//        echo view('layouts.test');
//    }

}
