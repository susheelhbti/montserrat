<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Roomstate extends Model
{
    use SoftDeletes;

    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'statechange_at'];  //

    //
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }
}
