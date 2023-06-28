<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DeliveryAddress;

class DeliveryAddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $deliveryRecords = [
            ['id'=>1,'user_id'=>1,'name'=>'moon','address'=>'dfsdfsdfg','city'=>'mfsfsfoon','state'=>'dsfsdfgds','country'=>'adsaff','pincode'=>13545,'mobile'=>54656454644,'status'=>1],
            ['id'=>2,'user_id'=>1,'name'=>'moon','address'=>'mhhjhjh','city'=>'dfddrd','state'=>'dfgdfdfdf','country'=>'dfgfdgfd','pincode'=>135545,'mobile'=>5456644,'status'=>1]
        ];
        DeliveryAddress::insert($deliveryRecords);
    }
}
