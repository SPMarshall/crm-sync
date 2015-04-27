<?php

use Illuminate\Database\Seeder;


class UserTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();
        $users = array(
            ['id' => 1, 'email' => 'test@test.com', 'fio'=>'Павлович Павел Павлов','company_name'=>'ФОП Павлович Павел Павлов', 'inn'=>'3334447711',  'remember_token'=> bcrypt(rand(1,100)), 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => 2, 'email' => 'qwe@qwe.com', 'fio'=>'Иванов Иван иванович','company_name'=>'ФОП Иванов Иван иванович','inn'=>'8887772233', 'remember_token'=> bcrypt(rand(1,100)), 'created_at' => new DateTime, 'updated_at' => new DateTime],
        );
        DB::table('users')->insert($users);
    }

}