<?php namespace Smallneat\Trust\Traits;

/**
 * Class PermissionTrait
 *
 * @package Smallneat\Trust\Traits
 */
trait PermissionTrait
{
  /**
   * Many-to-Many relations with Roles
   */
  public function roles()
  {
    // Map the Permission model to the Role model using the roles table and the permission_role table.
    return $this->belongsToMany(config('trust::models.role'), config('trust::tables.permission_role'));
  }
}
