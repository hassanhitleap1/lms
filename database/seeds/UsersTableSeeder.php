<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.	remember_token	varchar(100)
    12	fullname
    13	status	tinyint(1)
    14	views_count	int(11)
    15	sales_count	int(11)
    16	country	varchar(191)
    17	phone	varchar(191)
    18	birthdate	date
    19	gender
    20	activation_code
    21	school_id
    22	user_type
    23	class
    24	level
    25	address
     *
     * @return void
     */
    public function run()
    {
        for ($i=0 ;$i<100;$i++){
            DB::table('users')->insert([
                'created_at' => date('Y-m-d H:m:s'),
                 'uname'=>Str::random(32),
                 'password'=>md5('123'),
                 'email'=> str_random(20).'@gmail.com',
                 'avatar'=>'images/user.png',
                 'permession'=>\App\Users::USER_TEACHER,
                 'views_count'=>0,
                 'fullname'=>Str::random(32),
                 'status'=>1,
                 'sales_count'=>0,
                 'country'=>'JD',
                 'phone'=>rand(100,1000),
                 'birthdate'=>date('Y-m-d H:m:s'),
                 'gender'=> 1,
                 'school_id'=>0,
                 'user_type'=>'teacher',
                 'class'=>-1,
                 'level'=>-1,
                 'address'=>'address',
             ]);
        }
    }
}
