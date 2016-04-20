<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    
    /**
	 * variables that can be mass assign
	 * @var array
	 */
	protected $fillable = [
		'name',
		'last_name',
		'cellphone',
		'address',
		'username',
		'image',
		'tn_image',
		'comments',
	];
    
    /**
     * hidden variables
     * @var array
     */
	protected $hidden = [
		'password',
	];

	/**
	 * associated supervisor with this technician
	 */
    public function supervisor(){
    	return $this->belongsTo('App\Supervisor');
    }

	/**
	 * assaciated reports with this technician
	 */
    public function reports(){
    	return $this->hasMany('App\Report');
    }
}
