<?php
/**
 * Created by PhpStorm.
 * User: Liang Tao (liangtaohy@163.com)
 * Date: 2018/8/23
 * Time: 下午3:09
 */

namespace Xdp\Http;

use JsonSerializable;
use InvalidArgumentException;
use Xdp\Utils\Traits\Macroable;
use Xdp\Contract\Support\Jsonable;
use Xdp\Contract\Support\Arrayable;
use Symfony\Component\HttpFoundation\JsonResponse as BaseJsonResponse;

class JsonResponse extends BaseJsonResponse
{
    use ResponseTrait, Macroable {
        Macroable::__call as macroCall;
    }

    /**
     * Constructor.
     *
     * @param  mixed  $data
     * @param  int    $status
     * @param  array  $headers
     * @param  int    $options
     * @return void
     */
    public function __construct($data = null, $status = 200, $headers = [], $options = 0)
    {
        $this->encodingOptions = $options;

        parent::__construct($data, $status, $headers);
    }

    /**
     * JSONP callback
     *
     * @param null $callback
     * @return $this
     */
    public function withCallback($callback = null)
    {
        return $this->setCallback($callback);
    }

    /**
     * Get the json_decoded data from the response.
     *
     * @param  bool  $assoc
     * @param  int  $depth
     * @return mixed
     */
    public function getData($assoc = false, $depth = 512)
    {
        return json_decode($this->data, $assoc, $depth);
    }

    public function setData($data = array())
    {
        $this->original = $data;

        if ($data instanceof Jsonable) {
            $this->data = $data->toJson($this->encodingOptions);
        } elseif ($data instanceof JsonSerializable) {
            $this->data = json_encode($data->jsonSerialize(), $this->encodingOptions);
        } elseif ($data instanceof Arrayable) {
            $this->data = json_encode($data->toArray(), $this->encodingOptions);
        } else {
            $this->data = json_encode($data, $this->encodingOptions);
        }

        if (! $this->hasValidJson(json_last_error())) {
            throw new InvalidArgumentException(json_last_error_msg());
        }

        $this->update();
    }

    protected function hasValidJson($jsonError)
    {
        if ($jsonError === JSON_ERROR_NONE) {
            return true;
        }

        return $this->hasEncodingOption(JSON_PARTIAL_OUTPUT_ON_ERROR) &&
            in_array($jsonError, [
                JSON_ERROR_RECURSION,
                JSON_ERROR_INF_OR_NAN,
                JSON_ERROR_UNSUPPORTED_TYPE,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function setEncodingOptions($options)
    {
        $this->encodingOptions = (int) $options;

        return $this->setData($this->getData());
    }

    /**
     * Determine if a JSON encoding option is set.
     *
     * @param  int  $option
     * @return bool
     */
    public function hasEncodingOption($option)
    {
        return (bool) ($this->encodingOptions & $option);
    }
}