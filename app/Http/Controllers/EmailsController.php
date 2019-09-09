<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Email;
use App\Http\Resources\EmailResource;
use App\Services\ValidRecipient;
use App\Jobs\SendEmailJob;
use Illuminate\Mail\Markdown;

class EmailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return EmailResource::collection(Email::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $addresses = new ValidRecipient($request->get('recipients'));

        $data = array();
        $data['valid_recipients']       = $addresses->getRecipients();
        $data['subject']                = $request->get('subject');
        $data['body']                   = $request->get('body');
        $data['type']                   = in_array($request->get('type'), ['text/plain', 'text/html', 'text/markdown']) ? $request->get('type') : 'text/plain';
        $data['recipients_settings']    = in_array($request->get('recipients_settings'), [1, 0]) ? $request->get('recipients_settings') : 1;

        $email = new Email();
        $email->subject = $data['subject'];
        $email->body = $data['body'];
        $email->type = $data['type'];
        $email->recipients_settings = $data['recipients_settings'];
        $email->save();

        foreach ($data['valid_recipients'] as $r) {
            $reci = \App\Recipient::firstOrNew(['address' => $r]);
            $reci->save();
            $email->recipients()->attach($reci);
            $email->recipients_count++;
        }
        $email->save();

        if ($email) {
            $data['message'] = "Successfully Created the email with ID: " . $email->id . " and it's on its way.";
        }

        dispatch(new SendEmailJob($email))->onQueue('emails');

        return response()->json($data, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $email = Email::find($id);
        $data = array();

        if (!$email) {
            $data['status'] = 'error';
            $data['message'] = 'The resource you are looking for is not found.';
            return response()->json($data, 404);
        }

        $data['status'] = 'success';
        $data['email'] = $email;
        $data['email']['recipients'] = $email->recipients;

        return response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
}
