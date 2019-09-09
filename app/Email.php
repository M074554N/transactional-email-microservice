<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model{
    public function recipients(){
        return $this->belongsToMany(Recipient::class)->withPivot('status','service_provider')->withTimestamps();
    }
}
