<?php
/**
 * Application
 *
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/8/23
 * Time: 下午3:57
 */

namespace Xdp\Framework;

use Xdp\Container\Container;

class Application extends Container
{
    /**
     * 开发环境
     */
    const ENV_DEVELOPMENT = 'development';

    /**
     * 线上环境
     */
    const ENV_PRODUCT = 'product';

    /**
     * 应用名称
     * @note 原框架中的APP_NAME
     * @var string
     */
    protected $app_name;

    /**
     * 应用app部署的根目录
     * @var string
     */
    protected $deploy_root;

    /**
     * app config目录
     * @var string
     */
    protected $config_path;

    /**
     * app log目录
     * @var string
     */
    protected $log_path;

    /**
     * app log level
     * @var int
     */
    protected $log_level;

    /**
     * 设置app的base path
     *
     * @var
     */
    protected $base_path;

    /**
     * environment variables path
     * @var string
     */
    protected $env_path;

    /**
     * environment variables file
     * @var string
     */
    protected $env_file = '.env';

    /**
     * Application constructor.
     *
     * @param null $app_name
     * @param string|null $base_path
     */
    public function __construct(string $app_name = null, string $base_path = null)
    {
        parent::__construct();

        $this->setAppName($app_name);

        $this->setBasePath($base_path);

        $this->setBaseBinding();
    }

    public function bootstrapWith(array $bootstrappers = [])
    {

        foreach ($bootstrappers as $bootstrap) {
            $this->make($bootstrap)->bootstrap($this);
        }
    }

    /**
     * app binding & container binding
     * @return $this
     */
    public function setBaseBinding()
    {
        static::setInstance($this);

        $this->instance('app', $this);
        $this->instance(Container::class, $this);
        return $this;
    }

    /**
     * 设置tag，主要用于区分机房，一般不需要设置，默认值为default
     *
     * @param string $tag
     * @return $this
     */
    public function setTag(string $tag = 'default')
    {
        $this->instance('tag', $tag);
        return $this;
    }

    /**
     * 设置app_name
     *
     * @param string $app_name
     * @return $this
     */
    public function setAppName(string $app_name)
    {
        $this->app_name = $app_name;
        $this->instance('app_name', $app_name);
        return $this;
    }

    /**
     * 获取app_name
     *
     * @return string
     */
    public function appName()
    {
        return $this->app_name;
    }

    /**
     * Deploy Root Dir
     *
     * @return string
     */
    public function basePath()
    {
        return $this->base_path;
    }

    /**
     * 检查应用是否在console模式下运行
     *
     * @return bool
     */
    public function runningInConsole()
    {
        return php_sapi_name() === 'cli' || php_sapi_name() === 'phpdbg';
    }

    /**
     * 设置app的base目录
     *
     * @param string $base_path
     * @return $this
     */
    public function setBasePath(string $base_path)
    {
        $this->base_path = rtrim($base_path, '\/');

        $this->instance('path', $this->path());
        $this->instance('path.base', $this->base_path);
        $this->instance('path.config', $this->configPath());
        $this->instance('path.loglevel', $this->loglevel());
        $this->instance('path.logdir', $this->logdir());
        $this->instance('path.view', $this->viewPath());
        $this->instance('path.public', $this->path());
        $this->instance('path.bootstrap', $this->bootstrapPath());

        return $this;
    }

    /**
     * 检查是否为开发环境
     *
     * @return bool
     */
    public function isDevelopment()
    {
        return env('APP_ENV', 'development') == 'development';
    }

    /**
     * 获取app path
     *
     * @param string $path
     * @return string
     */
    public function path(string $path = '')
    {
        return $this->base_path . DIRECTORY_SEPARATOR . 'app' . ($path ? DIRECTORY_SEPARATOR . $path : DIRECTORY_SEPARATOR . $this->app_name);
    }

    /**
     * 获取app config path
     * @param string $path
     * @return string
     */
    public function configPath(string $path = '')
    {
        return $this->base_path . DIRECTORY_SEPARATOR . 'conf' . ($path ? DIRECTORY_SEPARATOR . $path : DIRECTORY_SEPARATOR . $this->app_name);
    }

    /**
     * 返回loglevel
     *
     * @param int $loglevel
     * @return int
     */
    public function loglevel(int $loglevel = 0xFF)
    {
        return $loglevel;
    }

    /**
     * 返回log path
     *
     * @param string $path
     * @return string
     */
    public function logdir(string $path = '')
    {
        return $this->base_path . DIRECTORY_SEPARATOR . 'logs' . ($path ? DIRECTORY_SEPARATOR . $path : '') . DIRECTORY_SEPARATOR . $this->app_name;
    }

    /**
     * 设置render view的目录，主要是模板文件，如smarty模型或blades模型等
     *
     * @param string $path
     * @return string
     */
    public function viewPath(string $path = '')
    {
        return $this->base_path . DIRECTORY_SEPARATOR . '../templates/templates' . ($path ? DIRECTORY_SEPARATOR . $path : '') . DIRECTORY_SEPARATOR . $this->app_name;
    }

    /**
     * 设置自启目录
     *
     * @return string
     */
    public function bootstrapPath()
    {
        return $this->path() . DIRECTORY_SEPARATOR . 'bootstrap';
    }

    /**
     * 设置环境变量文件的目录
     *
     * @param $path
     * @return $this
     */
    public function setEnvironmentPath($path)
    {
        $this->env_path = $path;
        return $this;
    }

    /**
     * 设置环境变量文件
     *
     * @param $file
     * @return $this
     */
    public function setEnvironmentFile($file)
    {
        $this->env_file = $file;
        return $this;
    }

    /**
     * 返回环境变量文件的目录
     *
     * @return string
     */
    public function environmentPath()
    {
        return $this->path() ?? $this->basePath();
    }

    /**
     * 返回环境变量文件
     *
     * @return string
     */
    public function environmentFile()
    {
        return $this->env_file ?? '.env';
    }

    /**
     * 获取app env
     *
     * @param $key
     * @return mixed
     */
    public function environment($key)
    {
        return env('APP_ENV', self::ENV_DEVELOPMENT);
    }
}