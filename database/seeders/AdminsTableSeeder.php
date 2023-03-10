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
            ['id'=>2,'name'=>'Rock','trpe'=>'vendor','vendor_id'=>1,'mobile'=>'012564894165','email'=>'rock@admin.com','password'=>'$2y$10$/vfi/0vS6SBJTSNt/V6tjOq1OoNGz6EaNVC8q5gEsuKT1OoP4fwUu','image'=>'','status'=>0]
        ];
        Admin::insert($adminRecords);
    }
}
