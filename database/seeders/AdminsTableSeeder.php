<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRecords= [
            ['id'=>3,'name'=>'khan','trpe'=>'vendor','vendor_id'=>3,'mobile'=>'012564894165','email'=>'khan@admin.com','password'=>'$2a$04$mopKco8zIDpNfEG55XatNexvcr572GKLJ4OL0fM1JC7bHHFAHspjy','image'=>'','status'=>0]
        ];
        Admin::insert($adminRecords);
    }
}
