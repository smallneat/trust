<?php namespace Smallneat\Trust\Traits;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class RoleTrait
 *
 * @package Smallneat\Trust\Traits
 */
trait RoleTrait
{

    /**
     * Many-to-Many relations with Users
     */
    public function users()
    {
        // Map the Role model to the User model using the users table and the user_role table
        return $this->belongsToMany(config('trust::models.user'), config('trust::tables.user_role'));
    }

    /**
     * Many-to-Many relations with Permission
     * named perms as permissions is already taken.
     */
    public function perms()
    {
        // Map the Role model to the Permission model using the permissions table and the permission_role table.
        return $this->belongsToMany(config('trust::models.permission'), config('trust::tables.permission_role'));
    }




    /**
     * Attach multiple permissions to current role
     *
     * @param $permissions - an array of permission objects or ids
     * @return void
     */
    public function attachPermissions($permissions)
    {
        // Make sure we have an array
        if (!($permissions instanceof Collection) && !is_array($permissions)) {
            $permissions = [ $permissions ];
        }

        // Add them all
        foreach ($permissions as $permission)
        {
            if (is_object($permission)) {
                $permission = $permission->getKey();
            }

            if (is_array($permission)) {
                $permission = $permission['id'];
            }

            $this->perms()->attach( $permission );
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
        // Make sure we have an array
        if (!($permissions instanceof Collection) && !is_array($permissions)) {
            $permissions = [ $permissions ];
        }

        // detach them all
        foreach ($permissions as $permission)
        {
            if (is_object($permission)) {
                $permission = $permission->getKey();
            }

            if (is_array($permission)) {
                $permission = $permission['id'];
            }

            $this->perms()->detach( $permission );
        }
    }

}
