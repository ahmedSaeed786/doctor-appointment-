<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\bookSlot;
use App\Models\bookScan;

class appointment extends Model
{
    //

    public $fillable = [
        "first_name",
        "last_name",
        "email",
        "phone",
        "address",
        "second_address",
        "emergency_contact",
        "dob",
        "gender",
        "disability",
        "disability_type",
        "clinical_indication",
        "capability",
        "representative",
        "appointment_date",
        "Representative_first_name",
        "Representative_last_name",
        "total",
        "promo_code",
    ];
    public function appointmentSlot()
    {
        return $this->hasOne(bookSlot::class, 'appointment_id', 'id')->with('slot');
    }
    public function appointmentScan()
    {
        return $this->hasOne(bookScan::class, 'appointment_id', 'id')->with('scan');
    }
}
