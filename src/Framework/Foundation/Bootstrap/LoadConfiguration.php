<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/9/3
 * Time: 上午9:10
 */

namespace Xdp\Framework\Foundation\Bootstrap;

use Xdp\Framework\Application;
use Xdp\Config\Config as Configuration;

class LoadConfiguration
{
    public function bootstrap(Application $app)
    {
        $items = [];

        //$app->singleton('config', $config = new Configuration($items));
        $app->instance('config', $config = new Configuration($items));

        $this->loadConfigurationFiles($app, $config);

        date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));
        mb_internal_encoding('UTF-8');
    }

    protected function loadConfigurationFiles(Application $app, Configuration $config)
    {
        $files = $this->getFiles($app->configPath());

        foreach ($files as $key => $file)
        {
            $config->set($key, require $file);
        }
    }

    protected function getFiles($conf_dir)
    {
        echo PHP_EOL . $conf_dir . PHP_EOL;
        $conf_dir = rtrim($conf_dir, '\/');

        $files = [];

        if (is_dir($conf_dir)) {
            $d = dir($conf_dir);
            while ($file = $d->read()) {
                $realfile = $conf_dir . DIRECTORY_SEPARATOR . $file;
                if (is_file($realfile) && ($file != ".") && ($file != "..")) {
                    echo PHP_EOL . "----: " . $realfile . PHP_EOL;
                    $files[basename($file, '.php')] = $realfile;
                }
            }
        }

        return $files;
    }
}