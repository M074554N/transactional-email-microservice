<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Email;
use App\Services\ValidRecipient;

class CreateEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
                            email:create 
                            {type : The type of the email (text/plain|text/html|text/markdown)} 
                            {subject : The email subject} 
                            {body : The email message content} 
                            {recipients : Comma separated list of recipiens email addresses}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a transactional email';

    /**
     * Sanitize recipients
     * 
     * @var validRecipients
     */
    private $recipients;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        try{
            $valid_recipients = new ValidRecipient($this->argument('recipients'));
            $this->recipients = $valid_recipients->getRecipients();

            $email = new Email();
            $email->type = in_array($this->argument('type'), ['text/plain','text/html','text/markdown']) ? $this->argument('type') : 'text/plain';
            $email->subject = $this->argument('subject');
            $email->body = $this->argument('body');
            $email->save();

            foreach($this->recipients as $r){
                $reci = \App\Recipient::firstOrNew(['address'=>$r]);
                $reci->save();
                $email->recipients()->attach($reci);
            }

            $this->info("Email created successfully with ID: ".$email->id);

        }catch(Exception $e){
            $this->info($error);
        }
    }
}