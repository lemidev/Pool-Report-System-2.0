<?php

namespace App\PRS\Helpers;

use App\PRS\Traits\HelperTrait;
use Illuminate\Support\Collection;

/**
 * Helpers for supervisor elements
 */
class SupervisorHelpers
{
    use HelperTrait;


    /**
     * Transform collection of supervisors to generate dropdown options
     * @param  Collection $supervisors
     * @return Collection
     */
    public function transformForDropdown(Collection $supervisors)
    {
        return $supervisors
                ->transform(function($item){
                    return (object) array(
                        'key' => $item->seq_id,
                        'label' => $item->name.' '.$item->last_name,
                        'icon' => url($item->icon()),
                    );
                });
    }



}
