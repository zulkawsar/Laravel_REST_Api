<?php

namespace App\Http\Controllers\Product;

use App\User;
use App\Buyer;
use App\Product;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;

class ProductBuyerTransactionController extends ApiController
{
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product, User $buyer)
    {
        if (! $buyer->id == $product->seller_id) {
            return $this->errorResponser('The buyer must be different from seller', 409);
        }
        if (! $buyer->isVerified()) {
            return $this->errorResponser('The buyer must be verified user', 409);
            
        }
        if (! $product->seller->isVerified()) {
            return $this->errorResponser('The seller must be verified user', 409);
            
        }

        if (! $product->isAvailable()) {
            return $this->errorResponser('The product is not available', 409);
            
        }
        if ($product->quantity < $request->quantity) {
            return $this->errorResponser('The quantity is not available', 409);
        }

        return DB::transaction( function() use ( $request, $product, $buyer ){
            $product->quantity -= $request->quantity;
            $product->save();

            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'product_id' => $product->id,
                'buyer_id' => $buyer->id,
            ]);
            return $this->showOne($transaction, 201);
        });
    }

    
}
