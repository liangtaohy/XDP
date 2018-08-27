<?php
namespace Xdp\Http;

use ArrayObject;
use JsonSerializable;
use Xdp\Contract\Support\Jsonable;
use Xdp\Contract\Support\Arrayable;
use Xdp\Contract\Support\Renderable;
use Symfony\Component\HttpFoundation\Response as BaseResponse;
use Xdp\Utils\Traits\Macroable;

class Response extends BaseResponse
{
    use ResponseTrait, Macroable {
        Macroable::__call as macroCall;
    }

    /**
     * 设置response的content
     *
     * @override parent::setContent
     *
     * @param mixed $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->original = $content;

        if ($this->shouldBeJson($content)) {
            $this->header('Content-Type', 'application/json');
            $content = $this->morphToJson($content);
        } else if ($content instanceof Renderable) {
            $content = $content->render();
        }

        parent::setContent($content);

        return $this;
    }

    /**
     * 检查content是否可以转为json
     *
     * @param mixed $content
     * @return bool
     */
    public function shouldBeJson($content)
    {
        return $content instanceof Arrayable ||
            $content instanceof Jsonable ||
            $content instanceof ArrayObject ||
            $content instanceof JsonSerializable ||
            is_array($content);
    }

    /**
     * 将$content转为json字符串
     *
     * @param mixed $content
     * @return string
     */
    protected function morphToJson($content)
    {
        if ($content instanceof Jsonable) {
            return $content->toJson();
        } elseif ($content instanceof Arrayable) {
            return json_encode($content->toArray());
        }

        return json_encode($content);
    }
}