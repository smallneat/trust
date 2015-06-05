Trust Roles and Permissions
===========================

User roles and permissions for Laravel 5 via traits

Based on simplified ideas from https://github.com/Zizaco/entrust


### Required setup

In the `require` key of `composer.json` file add the following

    "smallneat/trust": "3.*"

Run the Composer update command

    $ composer update

In your `config/app.php` add `'Smallneat\Trust\TrustServiceProvider'` to the end of the `$providers` array

```php
'providers' => array(

    'Illuminate\Foundation\Providers\ArtisanServiceProvider',
    'Illuminate\Auth\AuthServiceProvider',
    ...
    'Smallneat\Trust\TrustServiceProvider',
),
```


### Configuration

Trust creates a number of tables and makes assumptions about the names of your models. You can configure all these
settings using Laravel's normal config. First you'll need to publish the config from this package into your app config
(you only need to bother with this if the default names clash with existing tables in your application)

    $ php artisan vendor:publish --provider="Smallneat\Trust\TrustServiceProvider"

The default settings assume your models are called `User`, `Role` and `Permission`, and that the tables used to track these
are called `users`, `roles` and `permissions` respectively. Trust also uses 2 pivot tables to provide the many to many
association between users, roles and permissions, which are called `user_role` and `role_permission`.

Once you've set up your config (or left it to the defaults), it's time for the next step.

### Creating a DB migration

As part of the `vendor:publish` command above, the DB migration will have been created under the `/database/migrations`
folder. You can modify this if you wish, adding in your own columns as appropriate. When you are ready, simply run:

    $ php artisan migrate

After the migration, four new tables will be present as described above.

**NOTE:** This does not create the `users` table for you. If you don't already have a users table you will need to add
a migration to create one. We assume this table is called `users` and has a field `user_id`.


### Using Trust in your models

Here we assume you already have a user model (like the one Laravel creates for you when you build a new Laravel app),
and that it has a matching table in the database (normally called `users`).

Add the `UserRoleTrait` trait to the User model class (normally in `app/User.php`), like so...


```php
<?php

...
use Smallneat\Trust\Traits\UserRoleTrait;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

  use Authenticatable, CanResetPassword;
  use UserRoleTrait;

  ...

}
```

Next, Create a `Role` model that looks a little like this...

```php
<?php

use Smallneat\Trust\Traits\RoleTrait;

class Role extends Model {

  use RoleTrait;

  protected $fillable = [];
}
```

The Role model has a `name` attribute, which is the name of the role (eg, 'Admin', 'Editor', 'Manager', 'User').
You can also find the users and permissions linked to the role using the `roles()` and `permissions()` methods.


Next, create a `Permission` model like this...


```php
<?php

use Smallneat\Trust\Traits\PermissionTrait;

class Permission extends Model {

  use PermissionTrait;

	protected $fillable = [];
}
```

The Permission model has a `name` attribute, which is the name of the permission (for example, 'CreatePost', 'EditPost',
'DeletePost'), as well as a `description` attribute that is used to hold a readable description of the permission
(eg, for presenting in your admin area). The `roles()` method will give you access to all the roles with this permission.

Finally, Don't forget to dump composer autoload

    $ composer dump-autoload



## Usage

Each user can be associated with as many roles as you like. Each role can grant as many permissions as you like and
the permissions can be used to control access to various areas of your site.

Roles and Permissions are often created using your DB Seeds, but we will show some examples of creating a range of
permissions and roles and assigning them them a user. Finally, we'll show how you can query the user to find out
what they can and can't do...

First, we'll create some permissions for a fictional blog. These represent areas of your site that you want to protect in some way.

```php
$createPost = Permission::create([
    'name' => 'CreatePost',
    'description' => 'Create new posts'
]);

$editPost = Permission::create([
    'name' => 'EditPost',
    'description' => 'Edit existing posts'
]);

$deletePost = Permission::create([
    'name' => 'DeletePost',
    'description' => 'Delete posts'
]);

$manageUsers = Permission::create([
    'name' => 'ManageUsers',
    'description' => 'Create and Delete user accounts'
]);

// plus loads more for all the areas of the site we want to control
```

Next we'll create some different roles and associate some Permissions with them

```php
// Create 2 new roles for an Admin and an Editor
$admin = Role::create(['name' => 'Admin']);
$editor = Role::create(['name' => 'Editor']);

// Attach some permissions to each of the roles
// You can either pass in a Role object, and id, or an array of Roles / id's
$admin->attachPermissions([$manageUsers, $deletePosts]);
$editor->attachPermissions([$createPosts, $editPosts, $deletePosts]);

// You can access the permissions that a role has (using Eloquent) like so...
$admin->perms()

// Remove them with detachPermissions()
```

Finally, we can give a user some of these roles and query them

```php
// Give a user a role (using a Role model or id or an array of roles / id's)
$user = Auth::user();
$user->attachRoles($editor);

// Query the user
$user->hasRole('Editor');       // true
$user->hasRole('Admin');        // false
$user->can('CreatePost');       // true
$user->can('ManageUsers');      // false

// Access all the roles the user has
$user->roles();

// remove them with detachRoles()
```

## License

Trust is free software distributed under the terms of the MIT license

## Additional information

Any issues, please [report here](https://github.com/smallneat/trust/issues)
