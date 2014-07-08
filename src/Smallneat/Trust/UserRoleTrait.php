<?php

namespace Smallneat\Trust;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Config;

trait UserRoleTrait
{
    /**
     * Many-to-Many relations with Role
     */
    public function roles()
    {
        // Map the User model to the Role model using the roles table and the user_roles table
        return $this->belongsToMany(Config::get('trust::models.role'), Config::get('trust::tables.user_role'));
    }



    /**
     * Checks if the user has a Role by its name
     *
     * @param string $name Role name.
     * @return boolean
     */
    public function hasRole( $name )
    {
        foreach ($this->roles as $role) {
            if ($role->name == $name) {
                return true;
            }
        }

        return false;
    }




    /**
     * Check if user has a permission by its name
     *
     * @param string $permission Permission string.
     * @return boolean
     */
    public function can( $permission )
    {
        foreach($this->roles as $role) {
            // Validate against the Permission table
            foreach($role->perms as $perm) {
                if ($perm->name == $permission) {
                    return true;
                }
            }
        }

        return false;
    }





    /**
     * Attach multiple roles to a user
     *
     * @param $roles
     * @return void
     */
    public function attachRoles($roles)
    {
        // Make sure we have an array
        if (!($roles instanceof Collection) && !is_array($roles)) {
            $roles = [ $roles ];
        }

        // Attach each one
        foreach ($roles as $role)
        {
            if (is_object($role)) {
                $role = $role->getKey();
            }

            if (is_array($role)) {
                $role = $role['id'];
            }

            $this->roles()->attach( $role );
        }
    }



    /**
     * Detach multiple roles from a user
     *
     * @param $roles
     * @return void
     */
    public function detachRoles($roles)
    {
        // Make sure we have an array
        if (!($roles instanceof Collection) && !is_array($roles)) {
            $roles = [ $roles ];
        }

        // Detach each one
        foreach ($roles as $role)
        {
            if (is_object($role)) {
                $role = $role->getKey();
            }

            if (is_array($role)) {
                $role = $role['id'];
            }

            $this->roles()->detach( $role );
        }
    }
}
