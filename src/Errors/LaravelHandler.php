<?php

namespace Errors;

use BadMethodCallException;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler;
use Prophecy\Exception\Doubler\MethodNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LaravelHandler extends Handler
{
    /**
     * Render an exception into an HTTP response.
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception               $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $exception = $this->prepareException($exception);
        
        if($request->expectsJson()) {
            return response()->json([
                'error'   => $this->getHttpMessage($exception),
                '__error' => true,
            ], $this->getHttpStatusCode($exception));
        }
        
        if(!$request->user() && ($exception instanceof AuthenticationException || $exception instanceof AuthorizationException)) {
            return $this->unauthenticated();
        }
        
        return parent::render($request, $exception);
    }
    
    /**
     * Prepare exception for rendering.
     * @param  \Exception $e
     * @return \Exception
     */
    protected function prepareException(Exception $e)
    {
        $e = parent::prepareException($e);
        
        if($e instanceof MethodNotFoundException || $e instanceof BadMethodCallException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }
        
        return $e;
    }
    
    /**
     * Convert an authentication exception into an unauthenticated response.
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated()
    {
        return redirect()->guest(isset($this->loginRoute) ? $this->loginRoute : '/');
    }
    
    /**
     * Get the HTTP status code for an exception.
     * @param \Exception $e
     * @return int
     */
    protected function getHttpStatusCode(Exception $e)
    {
        if($e instanceof AuthenticationException) {
            return Response::HTTP_UNAUTHORIZED;
        } else if($e instanceof AuthorizationException) {
            return Response::HTTP_FORBIDDEN;
        } else if($e instanceof NotFoundHttpException) {
            return Response::HTTP_NOT_FOUND;
        }
        
        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }
    
    /**
     * Get the HTTP status message for an exception.
     * @param \Exception $e
     * @return string
     */
    protected function getHttpMessage(Exception $e)
    {
        if($e instanceof AuthenticationException) {
            return 'You need to be logged in to do that.';
        } else if($e instanceof AuthorizationException) {
            return 'You aren\'t allowed to do that.';
        } else if($e instanceof NotFoundHttpException) {
            return 'We couldn\'t find what you were after.';
        }
        
        return 'Oops! An unknown error occurred';
    }
}