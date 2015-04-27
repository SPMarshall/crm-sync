<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kved extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'kveds';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['kved', 'description','edited','operation'];

    public function users() {
        return $this->belongsToMany('App\User')->withPivot('main');//additional pivat table field
    }

}
