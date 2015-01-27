<?php
use Immap\Watchkeeper\Permission as Permission;
use Immap\Watchkeeper\Role as Role;
use Immap\Watchkeeper\User as User;
use Immap\Watchkeeper\Country as Country;
use Immap\Watchkeeper\Classification as Classification;
use Immap\Watchkeeper\PointArea as PointArea;
class StartUPDBTableSeeder extends Seeder {

	public function run()
	{
    $user = new User();
    $user->username = "paepod";
    $user->email = "sviboonsitthichok@immap.org";
    $user->password = "abc12345";
    $user->firstname = "Shinnawat";
    $user->lastname = "Viboonsitthichok";
    $user->active = true;
    $user->save();

    $country = new Country();
    $country->code_a2 = "TH";
    $country->code_a3 = "THA";
    $country->name = "Thailand";
    $country->save();
    $user->countries()->attach($country);

    $country = new Country();
    $country->code_a2 = "US";
    $country->code_a3 = "USA";
    $country->name = "United States";
    $country->save();
    $user->countries()->attach($country);

    $country = new Country();
    $country->code_a2 = "YE";
    $country->code_a3 = "YEM";
    $country->name = "Yemen";
    $country->save();
    $user->countries()->attach($country);

    $country = new Country();
    $country->code_a2 = "AF";
    $country->code_a3 = "AFG";
    $country->name = "Afghanistan";
    $country->save();
    $user->countries()->attach($country);

    $country = new Country();
    $country->code_a2 = "AU";
    $country->code_a3 = "AUS";
    $country->name = "Australia";
    $country->save();
    $user->countries()->attach($country);

    $superAdmin = new Role();
    $superAdmin->name = "super_admin";
    $superAdmin->display_name = "Super Administratior";

    $superUser = new Role();
    $superUser->name = "super_user";
    $superUser->display_name = "Super user";

    $user->roles()->save($superAdmin);
    $user->roles()->save($superUser);

    $managePermissions = new Permission();
    $managePermissions->name = "manage_permissions";
    $managePermissions->display_name = "Manage Permissions";
    $managePermissions->group_name = "Super Administration";
    $managePermissions->action_name = "admin.permissions.index";

    $manageRole = new Permission();
    $manageRole->name = "manage_role";
    $manageRole->display_name = "Manage Role";
    $manageRole->group_name = "Super Administration";
    $manageRole->action_name = "admin.roles.index";

    $manageUser = new Permission();
    $manageUser->name = "manage_user";
    $manageUser->display_name = "Manage User";
    $manageUser->group_name = "Super Administration";
    $manageUser->action_name = "admin.users.index";

    $manageUsergroup = new Permission();
    $manageUsergroup->name = "manage_usergroup";
    $manageUsergroup->display_name = "Manage User Group";
    $manageUsergroup->group_name = "Super Administration";
    $manageUsergroup->action_name = "admin.usergroups.index";

    $manageCountry = new Permission();
    $manageCountry->name = "manage_countries";
    $manageCountry->display_name = "Manage Countries";
    $manageCountry->group_name = "Administration";
    $manageCountry->action_name = "admin.countries.index";

    $manageClassifications = new Permission();
    $manageClassifications->name = "manage_classifications";
    $manageClassifications->display_name = "Manage Classifications";
    $manageClassifications->group_name = "Administration";
    $manageClassifications->action_name = "admin.classifications.index";

    $managePointAreas = new Permission();
    $managePointAreas->name = "manage_pointareas";
    $managePointAreas->display_name = "Manage Point and Areas";
    $managePointAreas->group_name = "Super Administration";
    $managePointAreas->action_name = "admin.pointareas.index";

    $superAdmin->perms()->save($managePointAreas);
    $superAdmin->perms()->save($manageClassifications);
    $superAdmin->perms()->save($manageCountry);
    $superAdmin->perms()->save($manageUsergroup);
    $superAdmin->perms()->save($manageUser);
    $superAdmin->perms()->save($manageRole);
    $superAdmin->perms()->save($managePermissions);

    $superUser->perms()->attach($managePointAreas);


    $classification = new Classification();
    $classification->code = "INT01";
    $classification->name = "Army Operation";
    $classification->group_id = 1;
    $classification->save();
    $classification = new Classification();
    $classification->code = "INT02";
    $classification->name = "Demonstration";
    $classification->group_id = 1;
    $classification->save();
    $classification = new Classification();
    $classification->code = "INT03";
    $classification->name = "Human remains find";
    $classification->group_id = 1;
    $classification->save();
    $classification = new Classification();
    $classification->code = "INT04";
    $classification->name = "IED Explosion";
    $classification->group_id = 1;
    $classification->save();
    $classification = new Classification();
    $classification->code = "INT05";
    $classification->name = "IED Find";
    $classification->group_id = 1;
    $classification->save();
    $classification = new Classification();
    $classification->code = "INT06";
    $classification->name = "IED threat";
    $classification->group_id = 1;
    $classification->save();
    $classification = new Classification();
    $classification->code = "INT07";
    $classification->name = "Kidnapping";
    $classification->group_id = 1;
    $classification->save();
    $classification = new Classification();
    $classification->code = "INT08";
    $classification->name = "Police Operation";
    $classification->group_id = 1;
    $classification->save();
    $classification = new Classification();
    $classification->code = "INT09";
    $classification->name = "Rioting";
    $classification->group_id = 1;
    $classification->save();
    $classification = new Classification();
    $classification->code = "INT10";
    $classification->name = "Robbery";
    $classification->group_id = 1;
    $classification->save();
    $classification = new Classification();
    $classification->code = "INT11";
    $classification->name = "Security Forces Operations";
    $classification->group_id = 1;
    $classification->save();
    $classification = new Classification();
    $classification->code = "INT12";
    $classification->name = "Shooting";
    $classification->group_id = 1;
    $classification->save();
    $classification = new Classification();
    $classification->code = "INT13";
    $classification->name = "Shooting (Insurgency)";
    $classification->group_id = 1;
    $classification->save();
    $classification = new Classification();
    $classification->code = "INT14";
    $classification->name = "Shooting (Political)";
    $classification->group_id = 1;
    $classification->save();
    $classification = new Classification();
    $classification->code = "INT15";
    $classification->name = "Vehicle Hijacking";
    $classification->group_id = 1;
    $classification->save();
    $classification = new Classification();
    $classification->code = "INT16";
    $classification->name = "Weapons Cache Find";
    $classification->group_id = 1;
    $classification->save();
    $classification = new Classification();
    $classification->code = "INT17";
    $classification->name = "Other";
    $classification->group_id = 1;
    $classification->save();


    $classification = new Classification();
    $classification->code = "INC01";
    $classification->name = "INC01";
    $classification->group_id = 2;
    $classification->save();
    $classification = new Classification();
    $classification->code = "INC02";
    $classification->name = "INC02";
    $classification->group_id = 2;
    $classification->save();
    $classification = new Classification();
    $classification->code = "INC03";
    $classification->name = "INC03";
    $classification->group_id = 2;
    $classification->save();


    $classification = new Classification();
    $classification->code = "SRCG01";
    $classification->name = "Completely reliable";
    $classification->group_id = 3;
    $classification->save();
    $classification = new Classification();
    $classification->code = "SRCG02";
    $classification->name = "Usually reliable";
    $classification->group_id = 3;
    $classification->save();
    $classification = new Classification();
    $classification->code = "SRCG03";
    $classification->name = "Fairly reliable";
    $classification->group_id = 3;
    $classification->save();
    $classification = new Classification();
    $classification->code = "SRCG04";
    $classification->name = "Reliable";
    $classification->group_id = 3;
    $classification->save();


    $classification = new Classification();
    $classification->code = "POI01";
    $classification->name = "POI01";
    $classification->group_id = 4;
    $classification->save();
    $classification = new Classification();
    $classification->code = "POI02";
    $classification->name = "POI02";
    $classification->group_id = 4;
    $classification->save();

    $classification = new Classification();
    $classification->code = "THC01";
    $classification->name = "THC01";
    $classification->group_id = 5;
    $classification->save();
    $classification = new Classification();
    $classification->code = "THC02";
    $classification->name = "THC02";
    $classification->group_id = 5;
    $classification->save();

    $classification = new Classification();
    $classification->code = "THT01";
    $classification->name = "THT01";
    $classification->group_id = 6;
    $classification->save();
    $classification = new Classification();
    $classification->code = "THT02";
    $classification->name = "THT02";
    $classification->group_id = 6;
    $classification->save();

    $classification = new Classification();
    $classification->code = "RISK01";
    $classification->name = "High-Extream";
    $classification->group_id = 7;
    $classification->save();
    $classification = new Classification();
    $classification->code = "RISK02";
    $classification->name = "Extream";
    $classification->group_id = 7;
    $classification->save();
    $classification = new Classification();
    $classification->code = "RISK03";
    $classification->name = "High";
    $classification->group_id = 7;
    $classification->save();
    $classification = new Classification();
    $classification->code = "RISK04";
    $classification->name = "Moderate-High";
    $classification->group_id = 7;
    $classification->save();
    $classification = new Classification();
    $classification->code = "RISK05";
    $classification->name = "Moderate";
    $classification->group_id = 7;
    $classification->save();
    $classification = new Classification();
    $classification->code = "RISK06";
    $classification->name = "Low-Moderate";
    $classification->group_id = 7;
    $classification->save();
    $classification = new Classification();
    $classification->code = "RISK07";
    $classification->name = "Low";
    $classification->group_id = 7;
    $classification->save();

    $classification = new Classification();
    $classification->code = "MOV01";
    $classification->name = "Mission Blackout";
    $classification->group_id = 8;
    $classification->save();
    $classification = new Classification();
    $classification->code = "MOV02";
    $classification->name = "Mission Critical";
    $classification->group_id = 8;
    $classification->save();
    $classification = new Classification();
    $classification->code = "MOV03";
    $classification->name = "Mission Eessential";
    $classification->group_id = 8;
    $classification->save();
    $classification = new Classification();
    $classification->code = "MOV04";
    $classification->name = "Normal";
    $classification->group_id = 8;
    $classification->save();

    $pointarea = new PointArea();
    $pointarea->name = "Melbourne";
    $pointarea->description = "Melbourne is a test point";
    $pointarea->geojson = '{
    "type": "MultiPoint",
    "coordinates": [
        [
            [
                -37.81480176428013,
                144.96111273765564
            ]
        ]
    ]
}';
    $pointarea->save();

  }
}
