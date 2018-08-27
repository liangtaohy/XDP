<?php
/**
 * Created by PhpStorm.
 * User: Liang Tao (liangtaohy@gmail.com)
 * Date: 2018/8/23
 * Time: 下午1:02
 */

/**
 * Trait InteractsWithInput
 * @note source 输入源，指$_GET, $_SERVER, $_POST, $_FILES等
 */

namespace Xdp\Http\Traits;

use stdClass;
use SplFileInfo;
use Xdp\Utils\Arr;
use Xdp\Utils\Str;
use Xdp\Http\UploadedFile;

trait InteractsWithInput
{
    /**
     * 从request中读取一个server变量
     *
     * @note $_SERVER[$key]
     *
     * @param null $key
     * @param null $default
     * @return mixed
     */
    public function server($key = null, $default = null)
    {
        return $this->getItem('server', $key, $default);
    }

    /**
     * 检查请求头$key是否已设置
     *
     * @param $key
     * @return bool
     */
    public function hasHeader($key)
    {
        return ! is_null($this->header($key));
    }

    /**
     * 从headers中读取指定的请求头
     *
     * @param null $key
     * @param null $default
     * @return mixed
     */
    public function header($key = null, $default = null)
    {
        return $this->getItem('headers', $key, $default);
    }

    /**
     * 检查请求是否含有指定的输入项$key
     *
     * @param  string|array  $key
     * @return bool
     */
    public function exists($key)
    {
        return $this->has($key);
    }

    /**
     * 检查请求是否含有指定的输入项$key
     *
     * @param  string|array  $key
     * @return bool
     */
    public function has($key)
    {
        $keys = is_array($key) ? $key : func_get_args();

        $input = $this->all();

        foreach ($keys as $value) {
            if (! Arr::has($input, $value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine if the request contains any of the given inputs.
     *
     * @param  string|array  $key
     * @return bool
     */
    public function hasAny($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        $input = $this->all();

        foreach ($keys as $key) {
            if (Arr::has($input, $key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 返回所有的输入项（包含文件）
     *
     * @param  array|mixed  $keys
     * @return array
     */
    public function all($keys = null)
    {
        $input = array_replace_recursive($this->input(), $this->allFiles());

        if (! $keys) {
            return $input;
        }

        $results = [];

        foreach (is_array($keys) ? $keys : func_get_args() as $key) {
            Arr::set($results, $key, Arr::get($input, $key));
        }

        return $results;
    }

    /**
     * 返回指定的输入项，或返回所有的输入项，不含有文件
     *
     * @param  string|null  $key
     * @param  string|array|null  $default
     * @return string|array|null
     */
    public function input($key = null, $default = null)
    {
        return data_get(
            $this->getInputSource()->all() + $this->query->all(), $key, $default
        );
    }

    /**
     * 确定请求是否包含非空值的输入项，输入项由$key指定。
     *
     * @param  string|array  $key
     * @return bool
     */
    public function filled($key)
    {
        $keys = is_array($key) ? $key : func_get_args();

        foreach ($keys as $value) {
            if ($this->isEmptyString($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * 确定请求是否包含非空值的输入项，输入项由$keys指定。
     *
     * @param  string|array  $keys
     * @return bool
     */
    public function anyFilled($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        foreach ($keys as $key) {
            if ($this->filled($key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 确定请求是否包含非空值的输入项，输入项由$key指定。
     *
     * @param  string  $key
     * @return bool
     */
    protected function isEmptyString($key)
    {
        $value = $this->input($key);

        return ! is_bool($value) && ! is_array($value) && trim((string) $value) === '';
    }

    /**
     * 返回所有的输入项，含有文件
     *
     * @note $_GET + $_POST + $_FILES
     *
     * @return array
     */
    public function keys()
    {
        return array_merge(array_keys($this->input()), $this->files->keys());
    }

    /**
     * 获取包含提供的键的子集以及输入数据中的值
     *
     * @param  array|mixed  $keys
     * @return array
     */
    public function only($keys)
    {
        $results = [];

        $input = $this->all();

        $placeholder = new stdClass;

        foreach (is_array($keys) ? $keys : func_get_args() as $key) {
            $value = data_get($input, $key, $placeholder);

            if ($value !== $placeholder) {
                Arr::set($results, $key, $value);
            }
        }

        return $results;
    }

    /**
     * 获取除指定的项目数组之外的所有输入
     *
     * @param  array|mixed  $keys
     * @return array
     */
    public function except($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        $results = $this->all();

        Arr::forget($results, $keys);

        return $results;
    }

    /**
     * 从请求中检索查询字符串项
     *
     * @param  string  $key
     * @param  string|array|null  $default
     * @return string|array
     */
    public function query($key = null, $default = null)
    {
        return $this->getItem('query', $key, $default);
    }

    /**
     * 从请求中获取有效内容项
     *
     * @param  string  $key
     * @param  string|array|null  $default
     *
     * @return string|array
     */
    public function post($key = null, $default = null)
    {
        return $this->getItem('request', $key, $default);
    }

    /**
     * 确定是否在请求中设置了cookie
     *
     * @param  string  $key
     * @return bool
     */
    public function hasCookie($key)
    {
        return ! is_null($this->cookie($key));
    }

    /**
     * 从请求中检索cookie
     *
     * @param  string  $key
     * @param  string|array|null  $default
     * @return string|array
     */
    public function cookie($key = null, $default = null)
    {
        return $this->getItem('cookies', $key, $default);
    }

    /**
     * 获取请求中的所有文件的数组
     *
     * @return array
     */
    public function allFiles()
    {
        return $this->files->all();
    }

    /**
     * Determine if the uploaded data contains a file.
     *
     * @param  string  $key
     * @return bool
     */
    public function hasFile($key)
    {
        if (! is_array($files = $this->file($key))) {
            $files = [$files];
        }

        foreach ($files as $file) {
            if ($this->isValidFile($file)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Retrieve a file from the request.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile|array|null
     */
    public function file($key = null, $default = null)
    {
        return data_get($this->allFiles(), $key, $default);
    }

    /**
     * Check that the given file is a valid file instance.
     *
     * @param  mixed  $file
     * @return bool
     */
    protected function isValidFile($file)
    {
        return $file instanceof SplFileInfo && $file->getPath() !== '';
    }

    /**
     * 从指定输入源读取数据项
     *
     * @param $source
     * @param $key
     * @param $default
     * @return mixed
     */
    protected function getItem($source, $key, $default)
    {
        if (is_null($key)) {
            return $this->$source->all();
        }

        return $this->$source->get($key, $default);
    }
}