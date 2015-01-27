<?php
class UserTableSeeder extends Seeder {

    public function run()
    {
        // !!! All existing users are deleted !!!
        DB::table('users')->delete();

        User::create(array(
            'username'  => 'paepod',
            'firstname'  => 'Shinnawat',
            'lastname'  => 'Viboonsitthichok',
            'password'  => Hash::make('paepod'),
            'email'     => 'paepod@gmail.com'
        ));
    
        User::create(array(
            'username'  => 'scott',
            'firstname'  => 'Scott',
            'middlename'    =>  'Really',
            'lastname'  => 'Tiger',
            'password'  => Hash::make('scott'),
            'email'     => 'sviboonsitthichok@immap.org'
        ));


    }
}