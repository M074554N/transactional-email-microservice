<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Email;

class EmailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $emails = Email::all();
        
        foreach($emails as $key=>$email){
            $emails[$key]['recipients_count'] = $email->recipients()->count();
            $emails[$key]['success_count'] = $email->recipients()->where('status',['success'])->count();
        }

        return response()->json(['emails'=>$emails]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $data['recipients']     = array_unique(explode(',',$request->get('recipients')));
        $data['subject']        = $request->get('subject');
        $data['body']           = $request->get('body');
        $data['type']           = $request->get('type');

        return response()->json($data,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $email = Email::find($id);
        $data = array();

        if(!$email){
            $data['status'] = 'error';
            $data['message'] = 'The resource you are looking for is not found.';
            return response()->json($data,404);
        }

        $data['status'] = 'success';
        $data['email'] = $email;
        $data['email']['recipients'] = $email->recipients;

        return response()->json($data,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        //
    }
}
