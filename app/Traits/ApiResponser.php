<?php 
namespace App\Traits;

use Illuminate\Support\Collection;

trait ApiReponser
{
	private function SuccessResponser( $data, $code )
	{
		return response()->json(  $data, $code );
	}

	protected function errorResponser( $message, $code )
	{
		return response()->json([ 'error' => $message, 'code' => $code ], $code);
	}

	protected function showAll( Collection $collection, $code = 200 )
	{
		return $this->SuccessResponser([ 'data' => $collection ], $code);
	}

	protected function showOne( Collection $collection, $code = 200 )
	{
		return $this->SuccessResponser([ 'data' => $collection ], $code);
	}


}