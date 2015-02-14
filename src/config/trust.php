<?php

return [

  /*
  |--------------------------------------------------------------------------
  | Trust Models
  |--------------------------------------------------------------------------
  |
  | In order for the traits to reference the correct models, specify them
  | here (including full namespaces if applicable)
  |
  */

  'models' => [
    'user' => 'App\Models\User',
    'role' => 'App\Models\Role',
    'permission' => 'App\Models\Permission',
  ],

  /*
  |--------------------------------------------------------------------------
  | Trust Tables
  |--------------------------------------------------------------------------
  |
  | The Trust migration and model traits will use the below table references
  | when retrieving data
  |
  */

  'tables' => [
    'roles' => 'roles',
    'user_role' => 'user_role',
    'permissions' => 'permissions',
    'permission_role' => 'permission_role',
  ],

];

