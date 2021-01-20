<?php

use Illuminate\Database\Seeder;
 use Carbon\Carbon; 
class gameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dt = Carbon::now();
        $date = $dt->subYears(11);
        DB::table('games')->insert([
            'name' => 'Call of Duty',
            'created_at' => $date,
        ]);
        DB::table('games')->insert([
            'name' => 'Mortal Kombat',
            'created_at' => $date,
        ]);
        DB::table('games')->insert([
            'name' => 'FIFA',
            'created_at' => $date,
        ]);
        DB::table('games')->insert([
            'name' => 'Just Cause',
            'created_at' => $date,
        ]);
        DB::table('games')->insert([
            'name' => 'Apex Legend',
            'created_at' => $date,
        ]);

    }
}
