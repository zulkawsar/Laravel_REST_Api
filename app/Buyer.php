<?php

namespace App;
use App\Transaction;


class Buyer extends User
{
   	public function transaction()
   	{
   		return $this->hasMany(Transaction::class);
   	}
}
