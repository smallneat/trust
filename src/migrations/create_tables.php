<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;

class SetupRolesPermissionsTables extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Creates the roles table
        Schema::create(Config::get('trust::tables.roles'), function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Creates the user_roles (Many-to-Many relation) table
        // This is different from teh alphabetical convention, but makes a lot more sense.
        Schema::create(Config::get('trust::tables.user_role'), function($table)
        {
            $table->increments('id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        // Creates the permissions table
        Schema::create(Config::get('trust::tables.permissions'), function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });

        // Creates the permission_role (Many-to-Many relation) table
        Schema::create(Config::get('trust::tables.permission_role'), function($table)
        {
            $table->increments('id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->integer('permission_id')->unsigned();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove the foreign keys for the user_role table
        $userRoleTable = Config::get('trust::tables.user_role');
        Schema::table($userRoleTable, function(Blueprint $table) use ($userRoleTable) {
            $table->dropForeign($userRoleTable.'_user_id_foreign');
            $table->dropForeign($userRoleTable.'_role_id_foreign');
        });

        // Remove the foreign keys for the permission_role table
        $permissionRoleTable = Config::get('trust::tables.permission_role');
        Schema::table('permission_role', function(Blueprint $table) use ($permissionRoleTable) {
            $table->dropForeign($permissionRoleTable.'_permission_id_foreign');
            $table->dropForeign($permissionRoleTable.'_role_id_foreign');
        });

        // Drop all the tables
        Schema::drop($permissionRoleTable);
        Schema::drop(Config::get('trust::tables.permissions'));
        Schema::drop($userRoleTable);
        Schema::drop(Config::get('trust::tables.roles'));
    }

}
