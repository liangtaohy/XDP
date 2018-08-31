<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/29
 * Time: 下午4:26
 */

namespace Xdp\Framework\Foundation\Bootstrap;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Dotenv\Exception\InvalidFileException;
use \Xdp\Framework\Application;

class LoadEnvironmentVars
{
    /**
     * load environment variables from {file}
     *
     * @note The variables will be loaded into getenv, $_ENV, $_SERVER automatically.
     * @param $app
     * @link https://github.com/vlucas/phpdotenv/blob/master/README.md
     */
    public function bootstrap(Application $app)
    {
        try {
            (new Dotenv($app->environmentPath(), $app->environmentFile()))->load();
        } catch (InvalidPathException $e) {
            die("invalid path: " . $app->environmentPath());
        } catch (InvalidFileException $e) {
            die("invalid file: " . $app->environmentFile());
        }
    }
}