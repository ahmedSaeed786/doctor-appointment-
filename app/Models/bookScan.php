<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\scan;

class bookScan extends Model
{
    //


    public $fillable = [
        "scan_id",
        "appointment_id",
    ];

    public function scan()
    {
        return $this->hasOne(scan::class, 'id', 'scan_id');
    }
}
