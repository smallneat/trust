<?php

namespace Smallneat\Trust;


trait UserRoleTrait
{
    /**
     * Many-to-Many relations with Role
     */
    public function roles()
    {
        return $this->belongsToMany('Role', 'user_role');
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
     * Alias to eloquent many-to-many relation's
     * attach() method
     *
     * @param mixed $role
     * @return void
     */
    public function attachRole( $role )
    {
        if (is_object($role))
            $role = $role->getKey();

        if (is_array($role))
            $role = $role['id'];

        $this->roles()->attach( $role );
    }




    /**
     * Alias to eloquent many-to-many relation's
     * detach() method
     *
     * @param mixed $role
     * @return void
     */
    public function detachRole( $role )
    {
        if (is_object($role))
            $role = $role->getKey();

        if (is_array($role))
            $role = $role['id'];

        $this->roles()->detach( $role );
    }



    /**
     * Attach multiple roles to a user
     *
     * @param $roles
     * @return void
     */
    public function attachRoles($roles)
    {
        foreach ($roles as $role)
        {
            $this->attachRole($role);
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
        foreach ($roles as $role)
        {
            $this->detachRole($role);
        }
    }
}
