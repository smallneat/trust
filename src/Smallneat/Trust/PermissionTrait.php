<?php

namespace Smallneat\Trust;


trait PermissionTrait
{


    /**
     * Many-to-Many relations with Roles
     */
    public function roles()
    {
        return $this->belongsToMany('Role', 'permission_role');
    }

}
