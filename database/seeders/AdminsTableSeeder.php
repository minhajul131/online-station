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
            ['id'=>1,'name'=>'Super Admin','trpe'=>'superadmin','vendor_id'=>0,'mobile'=>'01478523691','email'=>'admin@admin.com','password'=>'$2y$10$/vfi/0vS6SBJTSNt/V6tjOq1OoNGz6EaNVC8q5gEsuKT1OoP4fwUu','image'=>'','status'=>1]
        ];
        Admin::insert($adminRecords);
    }
}
