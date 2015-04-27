<?php

use Illuminate\Database\Seeder;


class KvedUserTableSeeder extends Seeder {

    public function run()
    {
        DB::table('kved_user')->delete();
        $data = array(
            ['user_id' => 1, 'kved_id' => 6, 'main'=>1],
            ['user_id' => 1, 'kved_id' => 7, 'main'=>0],
            ['user_id' => 1, 'kved_id' => 8, 'main'=>0],
            ['user_id' => 2, 'kved_id' => 5, 'main'=>1],
            ['user_id' => 2, 'kved_id' => 4, 'main'=>0],
        );
        DB::table('kved_user')->insert($data);
    }

}