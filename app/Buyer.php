<?php

namespace App;
use App\Transaction;
use App\Scope\BuyerScope;


class Buyer extends User
{
	protected static function boot()
	{
		parent::boot();

		return static::addGlobalScope( new BuyerScope);
	}

   	public function transactions()
   	{
   		return $this->hasMany(Transaction::class);
   	}
}
