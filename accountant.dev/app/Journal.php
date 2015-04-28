<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'journals';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['entity_name', 'entity_identifier','operation','data'];
    
}
