<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductsAttribute;


class ProductsAttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productAttributesRecords = [
            ['id'=>1,'product_id'=>6,'size'=>'Small','price'=>550,'stock'=>80,'sku'=>'RC001-S','status'=>1],
            ['id'=>2,'product_id'=>6,'size'=>'Medium','price'=>550,'stock'=>25,'sku'=>'RC001-M','status'=>1],
            ['id'=>3,'product_id'=>6,'size'=>'Large','price'=>550,'stock'=>54,'sku'=>'RC001-L','status'=>1],
        ];

        ProductsAttribute::insert($productAttributesRecords);
    }
}
