<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VendorsBusinessDetail;

class VendorsBusinessDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $VendorRecords = [
            ['id'=>1,'vendor_id'=>1,'shop_name'=>'Rock Store','shop_address'=>'7 matha','shop_city'=>'Bogura','shop_state'=>'Rajshahi','shop_country'=>'Bangladesh','shop_pincode'=>'2315','shop_mobile'=>'32165145650','shop_website'=>'www.rockstore.com','shop_email'=>'rock@store.com','address_proof'=>'passport','address_proof_image'=>'test.jpg','business_license_number'=>'1556456213235','gst_number'=>'65465465ssfhh4','pan_number'=>'231564dsd5526'],
        ];
        VendorsBusinessDetail::insert($VendorRecords);
    }
}
