<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productsRecords = [
            ['id'=>1,'section_id'=>8,'category_id'=>5,'brand_id'=>10,'vendor_id'=>1,'admin_id'=>0,'admin_type'=>'vendor','product_name'=>'Huawei Y7 Pro','product_code'=>'Y7','product_color'=>'Royal Blue','product_price'=>15000,'product_discount'=>15,'product_weight'=>156,'product_image'=>'','description'=>'','product_video'=>'','meta_title'=>'','meta_description'=>'','meta_keyword'=>'','is_featured'=>'Yes','status'=>1],
            ['id'=>2,'section_id'=>1,'category_id'=>6,'brand_id'=>2,'vendor_id'=>0,'admin_id'=>1,'admin_type'=>'admin','product_name'=>'Casual T-Shirt','product_code'=>'XL','product_color'=>'Black','product_price'=>1000,'product_discount'=>15,'product_weight'=>90,'product_image'=>'','description'=>'','product_video'=>'','meta_title'=>'','meta_description'=>'','meta_keyword'=>'','is_featured'=>'Yes','status'=>1],
        ];

        Product::insert($productsRecords);
    }
}
