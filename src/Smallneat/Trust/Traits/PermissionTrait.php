<?php

namespace Smallneat\Trust;


use Illuminate\Support\Facades\Config;

trait PermissionTrait
{


    /**
     * Many-to-Many relations with Roles
     */
    public function roles()
    {
        // Map the Permission model to the Role model using the roles table and the permission_role table.
        return $this->belongsToMany(Config::get('trust::models.role'), Config::get('trust::tables.permission_role'));
    }

}
