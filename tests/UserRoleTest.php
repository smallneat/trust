<?php


/**
 * Class UserRoleTest
 * Testing models inside a workbench package is non-trivial
 * I've solved this by placing the tests here in the package, but updating
 * the phpunit.xml in the workbench project so that is includes this test directory as well as the default.
 *
 * The workbench project, with all the models, migrations and db seeds needed by these tests can be found at
 * https://github.com/smallneat/trust-test
 *
 */
class UserRoleTest extends TestCase {

    /**
     * Default preparation for each test
     *
     */
    public function setUp()
    {
        parent::setUp(); // Don't forget this!

        // migrate the DB and seed it
        Artisan::call('migrate');
        Artisan::call('db:seed');
    }

    /**
     * @test
     */
    public function it_has_roles()
    {
        $user = User::find(1);

        $this->assertTrue($user->hasRole('Admin'));
        $this->assertTrue($user->hasRole('Editor'));
        $this->assertTrue($user->hasRole('Blogger'));

        $this->assertFalse($user->hasRole('Fisherman'));
    }


    /** @test */
    public function it_can_add_role_to_a_user()
    {
        // add a single role to a user
        $user = User::find(3);
        $this->assertFalse($user->hasRole('Admin'), 'Does not have admin role yet');

        $user->attachRoles(1);

        $user = User::find(3);
        $this->assertTrue($user->hasRole('Admin'), 'Has admin role now');
        $this->assertFalse($user->hasRole('Editor'), 'Does not have editor role yet');
    }


    /** @test */
    public function it_can_add_multiple_roles()
    {
        // add a single role to a user
        $user = User::find(3);
        $this->assertFalse($user->hasRole('Admin'), 'Does not have admin role yet');
        $this->assertFalse($user->hasRole('Editor'), 'Does not have editor role yet');
        $this->assertFalse($user->hasRole('Blogger'), 'Does not have blogger role yet');

        $user->attachRoles([1,2,3]);

        $user = User::find(3);
        $this->assertTrue($user->hasRole('Admin'), 'Has admin role now');
        $this->assertTrue($user->hasRole('Editor'), 'Has editor role now');
        $this->assertTrue($user->hasRole('Blogger'), 'Has blogger role now');
    }


    /** @test */
    public function it_can_attach_and_detach_roles()
    {
        // add a single role to a user
        $user = User::find(3);
        $this->assertFalse($user->hasRole('Admin'), 'Does not have admin role yet');
        $this->assertFalse($user->hasRole('Editor'), 'Does not have editor role yet');
        $this->assertFalse($user->hasRole('Blogger'), 'Does not have blogger role yet');

        $user->attachRoles(Role::all());
        $user = User::find(3);
        $this->assertTrue($user->hasRole('Admin'), 'Has admin role now');
        $this->assertTrue($user->hasRole('Editor'), 'Has editor role now');
        $this->assertTrue($user->hasRole('Blogger'), 'Has blogger role now');

        $user->detachRoles(1);
        $user = User::find(3);
        $this->assertFalse($user->hasRole('Admin'), 'no admin role now');
        $this->assertTrue($user->hasRole('Editor'), 'Has editor role now');
        $this->assertTrue($user->hasRole('Blogger'), 'Has blogger role now');

        $user->detachRoles([2,3]);
        $user = User::find(3);
        $this->assertFalse($user->hasRole('Admin'), 'Does not have admin role yet');
        $this->assertFalse($user->hasRole('Editor'), 'Does not have editor role yet');
        $this->assertFalse($user->hasRole('Blogger'), 'Does not have blogger role yet');


        $user->attachRoles(Role::find(2));
        $user = User::find(3);
        $this->assertFalse($user->hasRole('Admin'), 'Does not have admin role yet');
        $this->assertTrue($user->hasRole('Editor'), 'Does have editor role yet');
        $this->assertFalse($user->hasRole('Blogger'), 'Does not have blogger role yet');
    }


    /** @test */
    public function it_can_see_roles_directly()
    {
        $user = User::find(1);
        $this->assertCount(3, $user->roles);

        $user = User::find(2);
        $this->assertCount(1, $user->roles);

        $user = User::find(3);
        $this->assertCount(0, $user->roles);
    }


    /** @test */
    public function it_can_access_permissions()
    {
        $user = User::find(2);

        // Check we have the permissions we expect to have
        $this->assertTrue($user->can('CreatePages'), 'can create pages');
        $this->assertTrue($user->can('CreateTemplates'), 'can create templates');
        $this->assertTrue($user->can('CreateContent'), 'can create content');

        // and that we don't have any other permissions
        $this->assertFalse($user->can('EditContent'), 'not edit content');
        $this->assertFalse($user->can('DeleteContent'), 'not edit content');

        // or made up permissions
        $this->assertFalse($user->can('TakeOverTheWorld'), 'not evil');

    }

}
