<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model{
    public function recipients(){
        return $this->belongsToMany('App\Recipient')->withPivot('status','service_provider');
    }
}
