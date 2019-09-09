<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Markdown;
use App\Email;
use App\Traits\Providers;
use SendGrid\Mail\Mail as SendgridMail;
use Mailjet\Resources;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Providers;

    /**
     * The number of times the job may be attempted.
     * 
     * @var int
     */
    private $tries = 3;
    private $email;

    /**
     * Create a new job instance.
     * 
     * @param Email $email object
     * 
     * @return void
     */
    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Log job start
        Log::info('Email Job Started at: ' . date('Y-m-d h:i:s'));

        //Get Providers array
        $providers = $this->getProviders();

        switch ($this->email->type) {
            case "text/plain":
                $messageFormat = "text/plain";
                $messageBody = $this->email->body;
                break;
            case "text/html":
                $messageFormat = "text/html";
                $messageBody = $this->email->body;
                break;
            case "text/markdown":
                $messageFormat = "text/html";
                $messageBody = (string) Markdown::parse($this->email->body);
                break;
            default:
                $messageFormat = "text/plain";
                $messageBody = $this->email->body;
                break;
        }

        //if the email recipients are to be included in the same message
        // 1 = true - send email to recipients separately
        // 0 = false - send email to recipients in one message
        if ($this->email->recipients_settings === 1) {
            foreach ($this->email->recipients as $recipient) {
                foreach ($providers as $providerName) {
                    $providerClient = app()->make($providerName);

                    if ($providerName === 'Sendgrid') {
                        $mail = new SendgridMail();
                        $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                        $mail->setSubject($this->email->subject);
                        $mail->setReplyTo(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                        $mail->addContent($messageFormat, $messageBody);
                        $mail->addTo($recipient->address);

                        //Try with the default driver
                        Log::info('Trying to send to ' . $recipient->address . ' using ' . $providerName);
                        $response = $providerClient->send($mail);

                        if ($response->statusCode() !== 202) {
                            //Retry with other providers
                            $recipient->pivot->status = "failed";
                            $recipient->pivot->service_provider = $providerName;
                            $recipient->pivot->save();
                            Log::error('Email send failed to ' . $recipient->address . ' with error no. ' . $response->statusCode() . ' using ' . $providerName);
                        } else {
                            $recipient->pivot->status = "processed";
                            $recipient->pivot->service_provider = $providerName;
                            $recipient->pivot->save();
                            Log::info('Email sent successfully with ' . $providerName);
                            break;
                        }
                    } elseif ($providerName === 'Mailjet') {
                        $body = [
                            'FromEmail' => env('MAIL_FROM_ADDRESS'),
                            'FromName' => env('MAIL_FROM_NAME'),
                            'Subject' => $this->email->subject,
                            'Text-part' => $messageBody,
                            'Html-part' => $messageBody,
                            'Recipients' => [['Email' => $recipient->address]],
                        ];

                        //Try with the default driver
                        Log::info('Trying to send to ' . $recipient->address . ' using ' . $providerName);

                        $response = $providerClient->post(Resources::$Email, ['body' => $body]);

                        if (!$response->success()) {
                            //Retry with other providers
                            $recipient->pivot->status = "failed";
                            $recipient->pivot->service_provider = $providerName;
                            $recipient->pivot->save();
                            Log::error('Email send failed to ' . $recipient->address . ' with error no. ' . $response->getData() . ' using ' . $providerName);
                        } else {
                            $recipient->pivot->status = "processed";
                            $recipient->pivot->service_provider = $providerName;
                            $recipient->pivot->save();
                            Log::info('Email sent successfully with ' . $providerName);
                            break;
                        }
                    }
                }
            }
        } else {
            foreach ($providers as $providerName) {
                $providerClient = app()->make($providerName);

                switch ($providerName) {
                    case 'Sendgrid':
                        $mail = new SendgridMail();
                        $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                        $mail->setSubject($this->email->subject);
                        $mail->setReplyTo(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                        $mail->addContent((string) $messageFormat, (string) $messageBody);

                        foreach ($this->email->recipients as $recipient) {
                            $mail->addBcc($recipient->address);
                        }

                        //Try with the default driver
                        Log::info('Trying to send to email No. ' . $this->email->id . ' using ' . $providerName);
                        $response = $providerClient->send($mail);

                        if ($response->statusCode() !== 202) {
                            //Retry with other providers
                            foreach ($this->email->recipients as $rec) {
                                $rec->pivot->status = "failed";
                                $rec->pivot->service_provider = $providerName;
                                $rec->pivot->save();
                            }
                            Log::error('Email send failed with error no. ' . $response->statusCode() . ' using ' . $providerName);
                        } else {
                            foreach ($this->email->recipients as $rec) {
                                $rec->pivot->status = "processed";
                                $rec->pivot->service_provider = $providerName;
                                $rec->pivot->save();
                            }
                            Log::info('Email sent successfully using ' . $providerName);
                            break;
                        }
                        break;
                    case 'Mailjet':
                        $recipients = [];

                        foreach ($this->email->recipients as $rec) {
                            $recipients[]['Email'] = $rec->address;
                        }

                        $body = [
                            'FromEmail' => env('MAIL_FROM_ADDRESS'),
                            'FromName' => env('MAIL_FROM_NAME'),
                            'Subject' => $this->email->subject,
                            'Text-part' => $messageBody,
                            'Html-part' => $messageBody,
                            'Recipients' => $recipients,
                        ];

                        //Try with the default driver
                        Log::info('Trying to send to ' . $recipient->address . ' using ' . $providerName);

                        $response = $providerClient->post(Resources::$Email, ['body' => $body]);

                        if (!$response->success()) {
                            foreach ($this->email->recipients as $rec) {
                                $rec->pivot->status = "failed";
                                $rec->pivot->service_provider = $providerName;
                                $rec->pivot->save();
                            }
                            Log::error('Email send failed with error no. ' . $response->statusCode() . ' using ' . $providerName);
                        } else {
                            foreach ($this->email->recipients as $rec) {
                                $rec->pivot->status = "processed";
                                $rec->pivot->service_provider = $providerName;
                                $rec->pivot->save();
                            }
                            Log::info('Email sent successfully using ' . $providerName);
                            break;
                        }
                        break;
                }
            }
        }
        Log::info('Email Job Ended');
    }

    /**
     * Handle a failed email send
     * 
     * @param Exception $exception
     * 
     * @return void
     */
    public function failed(Exception $exception)
    {
        Log::critical('Email Job Failed With Message: ' . $exception->getMessage());
    }
}
