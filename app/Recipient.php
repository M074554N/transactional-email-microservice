<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Email;

class Recipient extends Model{
    public function emails(){
        return $this->belongsToMany(Email::class);
    }
}
