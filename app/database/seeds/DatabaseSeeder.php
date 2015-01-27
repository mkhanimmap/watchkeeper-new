<?php

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Eloquent::unguard();
        //$pathMainDb = __DIR__."/../testCodecept_main.sqlite";
        //$pathCodeceptDb = __DIR__."/../testCodecept.sqlite";
        //if ( File::exists($pathCodeceptDb) ) {
        //    File::delete($pathCodeceptDb);
        //}
        //File::copy($pathMainDb, $pathCodeceptDb);
        //Artisan::call('migrate');
		$this->call('StartUPDBTableSeeder');
	}

}
