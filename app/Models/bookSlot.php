<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\slotitem;

class bookSlot extends Model
{
    //

    public $fillable = [
        "slot_id",
        "appointment_id",
    ];

    public function slot()
    {
        return $this->hasOne(slotitem::class, 'id', 'slot_id');
    }
}
