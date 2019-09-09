<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Email;
use App\Jobs\SendEmailJob;

class SendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
        email:send
        {email_id : The ID for the created email to send}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a previously created email with ID';

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
    public function handle(){
        $email = Email::find((int) $this->argument('email_id'));

        if(!$email){
            $this->error("Cannot find an email with this ID: ".$this->argument('email_id'));
            die;
        }

        SendEmailJob::dispatch($email)->onQueue('emails');
    }
}
