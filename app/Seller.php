<?php

namespace App;

use App\Product;
use App\Scope\SellerScope;

class Seller extends User
{
	protected static function boot()
	{
		parent::boot();

		return static::addGlobalScope( new SellerScope);
	}
    public function products()
    {
    	return $this->hasMany(Product::class);
    }
}
