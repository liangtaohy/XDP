<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/30
 * Time: 上午11:19
 */

namespace Xdp\Framework\Foundation\Bootstrap;

use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Xdp\Framework\Application;
use \Exception;
use Xdp\Contract\Debug\ExceptionHandler;

class HandleException
{
    /**
     *
     * @var \Xdp\Framework\Application $app
     */
    protected $app;

    /**
     * @param \Xdp\Framework\Application $app
     */
    public function bootstrap(Application $app)
    {
        $this->app = $app;

        // Report all PHP errors
        error_reporting(E_ALL);

        set_error_handler([$this, 'handleError']);

        set_exception_handler([$this, 'handleException']);

        register_shutdown_function([$this, 'handleShutdown']);

        if (! $app->environment(Application::ENV_DEVELOPMENT)) {
            ini_set('display_errors', 'off');
        }
    }

    public function handleError($errno , $errstr, $errfile = '', $errline = 0, array $errcontext = [] )
    {
        if (error_reporting() & $errno) {
            throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        }
    }

    public function handleException($e)
    {
        if (! $e instanceof Exception) {
            $e = new FatalThrowableError($e);
        }

        try {
            $this->exceptionHandler()->report($e);
        } catch (Exception $e) {
            // do nothing
        }

        if ($this->app->runningInConsole()) {
            $this->renderInConsole($e);
        } else {
            $this->renderHttpResponse($e);
        }
    }

    public function renderInConsole(Exception $e)
    {
        // do nothing
        echo "console log: " . $e->getCode() . ":" . $e->getMessage() . "," . $e->getFile() . ":" . $e->getLine() . PHP_EOL;
    }

    public function renderHttpResponse(Exception $e)
    {
        $this->exceptionHandler()->render($this->app['request'], $e)->send();
    }

    /**
     * Handle the PHP shutdown event.
     *
     * @return void
     */
    public function handleShutdown()
    {
        if (! is_null($error = error_get_last()) && $this->isFatal($error['type'])) {
            $this->handleException($this->fatalExceptionFromError($error, 0));
        }
    }

    /**
     * Create a new fatal exception instance from an error array.
     *
     * @param  array  $error
     * @param  int|null  $traceOffset
     * @return \Symfony\Component\Debug\Exception\FatalErrorException
     */
    protected function fatalExceptionFromError(array $error, $traceOffset = null)
    {
        return new FatalErrorException(
            $error['message'], $error['type'], 0, $error['file'], $error['line'], $traceOffset
        );
    }

    /**
     * Determine if the error type is fatal.
     *
     * @param  int  $type
     * @return bool
     */
    protected function isFatal($type)
    {
        return in_array($type, [E_COMPILE_ERROR, E_CORE_ERROR, E_ERROR, E_PARSE]);
    }

    protected function exceptionHandler()
    {
        return $this->app[ExceptionHandler::class];
    }
}