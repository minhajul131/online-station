<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VendorsBankDetail;

class VendorsBankDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $VendorRecords = [
            ['id'=>1,'vendor_id'=>1,'account_holder_name'=>'Rock','bank_name'=>'NCC Bank','account_number'=>'54654121554','bank_code'=>'14582'],
        ];
        VendorsBankDetail::insert($VendorRecords);
    }
}
