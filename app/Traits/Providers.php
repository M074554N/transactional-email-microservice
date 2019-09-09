<?php

namespace App\Traits;

use Exception;

trait Providers
{
    /**
     * Get all providers
     */
    public function getProviders()
    {
        $providers = config('services.mail_service_providers');
        $validProviders = [];

        if (!$providers) {
            throw new Exception('Mail service providers list cannot be empty!');
        }

        $providers = array_unique(explode(',', $providers));

        if (count($providers) < 2) {
            throw new Exception('Mail service providers list must have at least 2 providers!');
        }

        foreach ($providers as $provider) {
            $validProviders[] = ucfirst($provider);
        }

        return $validProviders;
    }

    /**
     * Get Default Provider from settings
     */
    // public function getDefaultProvider(){
    // 	$default = ucfirst(config('services.default_mail_provider'));

    // 	if(empty($default)){
    // 		throw new Exception('Default mail service provider cannot be empty!');
    // 	}

    // 	if(!in_array($default,$this->getProviders())){
    // 		throw new Exception('Default mail service provider must be included in the service providers list!');
    // 	}

    // 	return $default;
    // }
}
