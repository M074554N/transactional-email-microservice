<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipient extends Model{
	protected $fillable = ['address'];

    public function emails(){
        return $this->belongsToMany(Email::class)->withPivot('status','service_provider')->withTimestamps();
    }
}
