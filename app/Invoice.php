<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\PRS\ValueObjects\Invoice\TypeInvoice;
use Carbon\Carbon;

class Invoice extends Model
{

    /**
     * variables that can be mass assigned
     * @var array
     */
    protected $fillable = [
        'closed',
        'amount',
        'currency',
        'description',
        'admin_id',
    ];

    public function admin()
    {
        return $this->invoiceable->admin();
    }

    /**
     * Get all the commentable object
     */
    public function invoiceable()
    {
        return $this->morphTo();
    }

    public function payments()
    {
        return $this->hasMany('App\Payment');
    }


    //******** VALUE OBJECTS ********

    public function closed()
    {
        return (new Carbon($this->closed, 'UTC'))->setTimezone($this->admin()->timezone);
    }

    public function type()
    {
        return new TypeInvoice($this->invoiceable_type);
    }

}
