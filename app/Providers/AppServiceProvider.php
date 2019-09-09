<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Arr;
use Sendgrid;
use SendGrid\Mail\Mail as SendgridMail;
use Mailgun\Mailgun;
use Mailjet\Client as MailjetClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Mailgun', function ($app) {
            $config = $app->make('config')->get('services.mailgun', []);

            $apiKey = Arr::pull($config, 'api_key', null);

            return Mailgun::create($apiKey);
        });

        $this->app->singleton(Sendgrid::class, function ($app) {
            $config = $app->make('config')->get('services.sendgrid', []);

            $api_key = Arr::pull($config, 'api_key', null);

            return new Sendgrid($api_key);
        });

        $this->app->bind('SendgridMail', function () {
            return new SendgridMail();
        });

        $this->app->singleton('Mailjet', function ($app) {
            $config = $app->make('config')->get('services.mailjet', []);

            $apiKey = Arr::pull($config, 'api_key', null);
            $secret = Arr::pull($config, 'secret', null);

            return new MailjetClient($apiKey, $secret);
        });

        $this->app->tag(['Sendgrid', 'Mailgun', 'Mailjet'], 'providers');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
