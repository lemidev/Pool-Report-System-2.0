<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Intervention;

use App\Administrator;
use App\PRS\Traits\Model\ImageTrait;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Client extends Model
{
	use ImageTrait;

	/**
	 * variables that can be mass assign
	 * @var array
	 */
	protected $fillable = [
		'name',
		'last_name',
		'email',
		'cellphone',
		'type',
		'language',
		'get_reports_emails',
		'comments',
		'admin_id',
	];

    /**
     * hidden variables
     * @var array
     */
	protected $hidden = [
		'password',
		'admin_id',
	];

	/**
	 * Gets client morphed user
	 * @return $User
	 * tested
	 */
	public function user()
    {
      return $this->morphOne('App\User', 'userable')->first();
    }

	/*
	 * associated services with this client
	 * tested
	 */
    public function services(){
    	return $this->belongsToMany('App\Service');
    }

	/**
	 * Checks if client has service with this $seq_id
	 * @param  integer  $seq_id
	 * @return boolean
	 */
	public function hasService($seq_id)
	{
		return $this->services()->get()->contains('seq_id', $seq_id);
	}

	public function setServices(array $seq_ids)
	{
	    foreach ($seq_ids as $seq_id) {
			$service_id = $this->admin()->serviceBySeqId($seq_id)->id;
			if(!$this->hasService($seq_id)){
				$this->services()->attach($service_id);
			}
	    }
	}

	public function admin()
	{
	    return Administrator::findOrFail($this->admin_id);
	}


}
