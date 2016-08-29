<?php

namespace montserrat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Attachment extends Model
{
    use SoftDeletes; 
    protected $table = 'file';
    protected $dates = ['upload_date', 'created_at', 'updated_at', 'deleted_at'];  //
    
    public function file_type() {
        return $this->hasOne('\montserrat\FileType','id','file_type_id');
    }
      
}