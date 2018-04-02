<?php

namespace App\Exceptions;

use Exception;
use App\Traits\ApiResponser;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }
        if ( $exception instanceof ModelNotFoundException) {
            $modelName = strtolower( class_basename($exception->getModel()));
            return $this->errorResponser("Does not exists any {$modelName} with the specified identification", 404);
        }
        if ($exception instanceof AuthorizationException) {
            return $this->errorResponser($exception->getMessage(), 403);
        }
        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponser("The specific method for the request is invalid", 405);
        }
        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponser("The specific URL not found", 404);
        }
        if ($exception instanceof HttpException) {
            return $this->errorResponser($exception->getMessage(), $exception->getStatusCode());
        }
        if ($exception instanceof QueryException) {
            $errorCode = $exception->errorInfo[1];
            if ($errorCode == 1364 ) {
               return $this->errorResponser( $exception->errorInfo[2], 400 );
            }
            if ($errorCode == 1451 ) {
               return $this->errorResponser( "Cannot remove this resource permanently. It is related with any other resource" , 409 );
            }
        }
        if (config('app.debug')) {
            return parent::render($request, $exception);
        }

        return $this->errorResponser("Unexpected Exception. Try later", 500);
    }

     /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->messages();

        return $this->errorResponser($errors, 422);
                    
    }

        /**
     * Convert a validation exception into a JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    // protected function invalidJson($request, ValidationException $exception)
    // {
    //     return $this->errorResponser([
    //         'message' => $exception->getMessage(),
    //         'errors' => $exception->errors(),
    //     ], $exception->status);
    // }
}
