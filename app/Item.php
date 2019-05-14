<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Item extends Model
{
    //public $directory = "/images/";
    protected $fillable = [
        'id',
        'insco_id',
        'branch_id',
        'bs_id',
        'item_number',
        'plate',
        'due_date',
        'expired',
        'image_path',
        'sarlaf',
        'sarlaf_duedate',
        'sarlaf_expired',
        'comments',
        'item_type'
    ];

    protected $table = 'items';

    protected $dates = [
        'due_date',
        'sarlaf_duedate',
    ];
    //The table field is called: image_path.  Elloquent changes to ImagePath
    // public function getImagePathAttribute($value) {
    //     return $this->directory . $value;
    // }

    public function iusers() {
        return $this
            ->belongsToMany('App\Iuser')
            ->withTimestamps();
    }

    public function branches() {
        return $this->belongsTo('App\Branch','id', 'id' );
    }

    public function companies() {
        return $this->belongsTo('App\InsuranceCo','id', 'id' );
    }
}
