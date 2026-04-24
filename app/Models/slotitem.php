<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class slotitem extends Model
{
    //
    public $fillable = [
        "time",
        "price",
        "start_time",
        "end_time",
    ];
}
