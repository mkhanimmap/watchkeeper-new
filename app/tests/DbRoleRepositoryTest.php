<?php
use Mockery as m;
use Way\Tests\Factory;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model\Eloquent as Eloquent;
use Immap\Watchkeeper\Repositories\DbRoleRepository as DbRoleRepository;
use Immap\Watchkeeper\Role as Role;
use Immap\Watchkeeper\Permission as Permission;
use Illuminate\Support\Facades\Event as Event;
class DbRoleRepositoryTest extends TestCase {

    public function __construct()
    {

    }

    public function setUp()
    {
        parent::setUp();
        $this->mockRole = m::mock('lluminate\Database\Eloquent\Model\Eloquent','Immap\Watchkeeper\Role');
        $this->mockPermission = m::mock('lluminate\Database\Eloquent\Model\Eloquent','Immap\Watchkeeper\Permission');
        $this->mockTranslator = m::mock('Symfony\Component\Translation\TranslatorInterface');
        $this->mockValidator = m::mock('Illuminate\Validation\Factory');
        $this->mockCollection = m::mock('Illuminate\Database\Eloquent\Collection')->shouldDeferMissing();
        $this->role = new Role();
        $this->permission = new Permission();
        $this->roleRepo = new DbRoleRepository($this->role);
        $this->app->instance('Immap\Watchkeeper\Role', $this->role);
        $this->app->instance('Immap\Watchkeeper\Repositories\DbRoleRepository', $this->roleRepo);
    }

    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }

    public function test_add_many_permissions()
    {
        $expect = true;

        $manageUsers = new \Immap\Watchkeeper\Permission();
        $manageUsers->name = 'manage_users';
        $manageUsers->display_name = 'Manage Users';
        $manageUsers->action_name = 'admin.users.index';
        $manageUsers->group_name = "Administration";
        $manageUsers->save();

        $manageRisk = new \Immap\Watchkeeper\Permission();
        $manageRisk->name = 'manage_risk';
        $manageRisk->display_name = 'Manage Risk Rating Areas';
        $manageRisk->action_name = '';
        $manageRisk->group_name = "Administration";
        $manageRisk->save();

        $role = new Role();
        $role->name = 'super_admin';
        $role->display_name = 'super Administrator';
        $role->save();
        $this->roleRepo->savePermissions($role->id, array($manageUsers->id,$manageRisk->id));
        $this->assertEquals($expect,$role->perms->contains($manageUsers->id));
        $this->assertEquals($expect,$role->perms->contains($manageRisk->id));
    }

    public function test_add_permission()
    {
        $expect = true;
        $this->permission->name = 'manage_countries';
        $this->permission->display_name = 'Manage Countries';
        $this->permission->action_name = 'admin.countries.index';
        $this->permission->group_name = "Administration";
        $this->permission->save();

        $role = new Role();
        $role->name = 'super_admin';
        $role->display_name = 'super Administrator';
        $role->save();

        $this->roleRepo->attachPermission($role->id, $this->permission);

        $this->assertEquals($expect,$role->perms->contains($this->permission->id));
    }

    public function test_get_model()
    {
        $this->assertEquals($this->roleRepo->getModel(),$this->role);
    }

    public function test_get_byIdWithPermissions()
    {
        $this->permission->name = 'manage_countries';
        $this->permission->display_name = 'Manage Countries';
        $this->permission->action_name = 'admin.countries.index';
        $this->permission->group_name = "Administration";
        $this->permission->save();

        $role = new Role();
        $role->name = 'super_admin';
        $role->display_name = 'super Administrator';
        $role->save();

        $this->roleRepo->attachPermission($role->id, $this->permission);

        $expect = $this->roleRepo->getmodel()->with('perms')->firstOrFail();
        $result = $this->roleRepo->byIdWithPermissions($this->permission->id);

        $this->assertEquals($expect,$result);
    }

    public function test_orderBy()
    {
        $role = new Role();
        $role->name = 'super_admin';
        $role->display_name = 'super Administrator';
        $role->save();

        $role = new Role();
        $role->name = 'administrator';
        $role->display_name = 'Administrator';
        $role->save();

        $expect = $this->roleRepo->getmodel()->orderBy('display_name', 'ASC')->get();
        $result = $this->roleRepo->orderBy(array('sort' => 'display_name', 'direction' => 'ASC'));

        $this->assertEquals($expect,$result);
    }

    public function testCreateRole()
    {
        $data['name'] = "role";
        $data['display_name'] = "role123";
        $expect = true;
        $this->role = new Role();
        $this->roleRepo = new DbRoleRepository($this->role);
        Event::shouldReceive("fire")->once()->with('role.saving',array($data));
        $result = $this->roleRepo->create($data);
        $this->assertEquals($expect,$result);
    }

    public function testUpdateRole()
    {
        $data['id'] = 1;
        $data['name'] = "role";
        $data['display_name'] = "role123";
        $this->prepareForUpdate();
        $this->roleRepo = new DbRoleRepository($this->role);
        Event::shouldReceive("fire")->once()->with('role.saving',array($data));
        $result = $this->roleRepo->update($data);
        $this->assertTrue($result);
        $expectRole = $this->roleRepo->byId($data['id'])->first();
        $this->assertEquals($expectRole->name,$data['name']);
        $this->assertEquals($expectRole->display_name,$data['display_name']);
    }

    public function prepareForUpdate()
    {
        $this->role = new Role();
        $this->role->id = 1;
        $this->role->name = "austin";
        $this->role->display_name = "power";
        $this->role->save();
        return $this->role;
    }

}
