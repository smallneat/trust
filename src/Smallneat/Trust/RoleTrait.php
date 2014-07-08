<?php

namespace Smallneat\Trust;

trait RoleTrait
{

    /**
     * Many-to-Many relations with Users
     */
    public function users()
    {
        return $this->belongsToMany('User', 'user_role');
    }

    /**
     * Many-to-Many relations with Permission
     * named perms as permissions is already taken.
     */
    public function perms()
    {
        return $this->belongsToMany('Permission', 'permission_role');
    }




    /**
     * Attach permission to current role
     * @param $permission - a Permission object or the id of a Permission
     */
    public function attachPermission( $permission )
    {
        if (is_object($permission))
            $permission = $permission->getKey();

        if (is_array($permission))
            $permission = $permission['id'];

        $this->perms()->attach( $permission );
    }



    /**
     * Detach permission form current role
     * @param $permission - a Permission object or the id of a Permission
     */
    public function detachPermission( $permission )
    {
        if (is_object($permission))
            $permission = $permission->getKey();

        if (is_array($permission))
            $permission = $permission['id'];

        $this->perms()->detach( $permission );
    }



    /**
     * Attach multiple permissions to current role
     *
     * @param $permissions - an array of permission objects or ids
     * @return void
     */
    public function attachPermissions($permissions)
    {
        foreach ($permissions as $permission)
        {
            $this->attachPermission($permission);
        }
    }



    /**
     * Detach multiple permissions from current role
     *
     * @param $permissions - an array of permission objects or ids
     * @return void
     */
    public function detachPermissions($permissions)
    {
        foreach ($permissions as $permission)
        {
            $this->detachPermission($permission);
        }
    }

}
