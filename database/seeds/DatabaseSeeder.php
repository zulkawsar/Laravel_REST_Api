<?php

use App\User;
use App\Product;
use App\Category;
use App\Transaction;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        User::turncate();
        Category::turncate();
        Product::turncate();
        Transaction::turncate();
        DB::table('category_product')->turncate();


        $userQuantiry = 200;
        $categoryQuantiry = 20;
        $productQuantiry = 1000;
        $transactionQuantiry = 1000;

        factory(User::class, $userQuantiry )->create();
        factory(Category::class, $categoryQuantiry )->create();
        factory(Product::class, $productQuantiry )->create()->each(
        	function($product){
        		$categories = Category::all()->random(mt_rand(1, 5))->pluck('id');

        		$product->categories()->attach($categories);
        });
        factory(Transaction::class, $transactionQuantiry )->create();
    }
}
