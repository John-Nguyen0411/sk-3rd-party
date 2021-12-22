<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Mail\WebhookMail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Output\ConsoleOutput;

class RequestController extends Controller
{
    public function detail(Request $request, $id){
        return view('requests.detail_request')->with([
            'request_id' => $id,
        ]);
    }

    public function receiveWebhook(Request $request){
        $output = new ConsoleOutput();
        $output->writeln("<info>Receive webhook successfully $request->request_id </info>");
        dispatch(new SendEmailJob($request->request_id));
        return response()->json([
            'data' => 'Ok',
        ]);
    }
}
