<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrderItemStatus;

class OrderItemStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orderItemStatusRecords = [
            ['id'=>1,'name'=>'Pending','status'=>1],
            ['id'=>2,'name'=>'In Process','status'=>1],
            ['id'=>3,'name'=>'Shipped','status'=>1],
            ['id'=>4,'name'=>'Delivered','status'=>1],
        ];
        OrderItemStatus::insert($orderItemStatusRecords);
    }
}
