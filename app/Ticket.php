<?php

namespace App;

use App\VoceOrdine;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    public function voce()
    {
        return $this->hasOne(VoceOrdine::class);
    }

    public function ordine()
    {
        return $this->hasOneThrough(Ordine::class, VoceOrdine::class);
    }
}
