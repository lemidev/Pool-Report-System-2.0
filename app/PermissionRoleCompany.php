<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Role;
use App\Permission;

class PermissionRoleCompany extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permission_role_company';

    public function scopeOfRole($query, ...$roles)
    {
        $rolesIds = Role::whereIn('name', $roles)->pluck('id');
        return $query->whereIn('role_id', $rolesIds);
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }

}
