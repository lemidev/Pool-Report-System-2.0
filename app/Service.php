<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Intervention;

use App\PRS\Traits\Model\ImageTrait;
use App\Chemical;
use App\ServiceContract;

use Carbon\Carbon;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Service extends Model
{
    use ImageTrait;

    /**
     * variables that can be mass assign
     * @var array
     */
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'address_line',
        'city',
        'state',
        'postal_code',
        'country',
        'type',
        'service_days',
        'amount',
        'currency',
        'start_time',
        'end_time',
        'status',
        'comments',
        'admin_id',
    ];


    /**
     * hidden variables
     * @var array
     */
    protected $hidden = [
        'admin_id',
    ];

    //******** RELATIONSHIPS ********

    /**
	 * Get the associated Administrator with this service
	 * tested
	 */
    public function admin(){
    	return $this->belongsTo('App\Administrator')->first();
    }

    /**
     * Get the associated clients with this service
     * tested
     */
    public function clients(){
    	return $this->belongsToMany('App\Client');
    }

    /**
     * Get associated reports with this service
     * tested
     */
    public function reports(){
    	return $this->hasMany('App\Report');
    }

    /**
     * Get associated equipment with this service
     */
    public function equipment(){
    	return $this->hasMany('App\Equipment');
    }

    /**
     * Get associated ServiceContract with this service
     * tested
     */
    public function serviceContract()
    {
        return $this->hasOne(ServiceContract::class);
    }

    public function hasServiceContract()
    {
        return ($this->serviceContract()->get()->count() > 0);
    }

    /**
     * Get associated ServiceContract with this service
     */
    public function chemicals()
    {
        return $this->hasMany(Chemical::class);
    }

    /**
     * Get associated work orders with this service
     */
    public function workOrders(){
    	return $this->hasMany('App\WorkOrder');
    }

    public function hasWorkOrders()
    {
        return ($this->workOrders()->get()->count() > 0);
    }


    //******** MISCELLANEOUS ********

    /**
     * check if this service is scheduled for a date
     * tested
     * @param  Carbon $date is in Administrator timezone
     * @return boolean
     */
    public function checkIfIsDo(Carbon $date)
    {
        $admin = $this->admin();
        $dayToCheck = strtolower($date->format('l'));
        return $this->serviceDays()->asArray()[$dayToCheck];
    }

    /**
     * check if there is a single report for this service already done in a date
     * tested
     * @param  Carbon $date is in Administrator timezone
     * @return boolean
     */
    public function checkIfIsDone(Carbon $date)
    {
        $admin = $this->admin();
        $strDate = $date->toDateTimeString();
        $count = $this->reports()
                ->where(\DB::raw('DATEDIFF(CONVERT_TZ(completed,\'UTC\',\''.$admin->timezone.'\'), "'.$strDate.'")'), '=', '0')
                ->count();
        if($count > 0){
            return true;
        }
        return false;
    }

}
