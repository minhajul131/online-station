<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vendor;

class VendorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vendorRecords = [
            ['id'=>1,'name'=>'Rock','address'=>'Sadar','city'=>'Bogura','division'=>'Rajshahi','country'=>'Bangladesh','postcode'=>'5800','mobile'=>'012564894165','email'=>'rock@admin.com','status'=>0]
        ];
        Vendor::insert($vendorRecords);
    }
}
