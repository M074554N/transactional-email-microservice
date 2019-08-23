<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Email;

class SendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
                            email:send 
                            {subject: The email subject} 
                            {body: The email message content} 
                            {type: The type of the email (text|html|markdown)} 
                            {recipients: Comma separated list of recipiens email addresses}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send emails to multiple recipients';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email = Email::find((int) $this->argument('email_id'));
        
        if(!$email){
            Log::error('Couldn\'t find an email with id: '.$this->argument('email_id'));
            die;
        }

        dd($email);
    }
}