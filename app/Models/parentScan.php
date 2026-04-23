<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\scan;

class parentScan extends Model
{


    public $fillable = [
        "name"
    ];
    public function scan()
    {
        return $this->hasMany(scan::class, 'scan_category_id', 'id');
    }
    //
}
