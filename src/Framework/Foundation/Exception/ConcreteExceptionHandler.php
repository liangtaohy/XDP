<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/30
 * Time: 下午12:11
 */

namespace Xdp\Framework\Foundation\Exception;

use Exception;
use Xdp\Contract\Container\ContainerInterface as Container;
use Xdp\Contract\Support\Responsable;
use Xdp\Http\Request;
use Xdp\Http\Response;
use Xdp\Contract\Debug\ExceptionHandler;
use Xdp\Routing\Router;

class ConcreteExceptionHandler implements ExceptionHandler
{
    /**
     * Report or log an exception.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        // write into log
        echo "Exception occurred: {$e->getCode()}, {$e->getMessage()}, {$e->getFile()}, {$e->getLine()}" . PHP_EOL;
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Xdp\Http\Request  $request
     * @param  \Exception  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $e)
    {
        if (method_exists($e, 'render') && $response = $e->render($request))
        {
            return Router::toResponse($request, $response);
        } elseif ($e instanceof Responsable) {
            return $e->toResponse($request);
        }

        $e = $this->prepareException($e);

        return Response::create($e);
    }

    /**
     * prepare exception
     *
     * @param Exception $e
     * @return array
     */
    public function prepareException(Exception $e)
    {
        return env('DEBUG') ? [
            'message' => $e->getMessage(),
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTrace(),
        ] : [
            'message' => $e->getMessage(), 'code' => $e->getCode(),
        ];
    }

    /**
     * Render an exception to the console.
     *
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @param  \Exception  $e
     * @return void
     */
    public function renderForConsole($output, Exception $e)
    {
        return [
            'message' => $e->getMessage(),
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTrace(),
        ];
    }
}