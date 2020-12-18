<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Probe;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        Model::unguard();

        DB::table('probes')->truncate();

        $probes = [
            ['id'=>1, 'direction'=>'D', 'xaxis'=>0,'yaxis'=>0]
        ];

        foreach($probes as $probe) {
            Probe::create($probe);
        }

        Model::reguard();
    }
}
