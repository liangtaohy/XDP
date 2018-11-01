<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/21
 * Time: 下午10:19
 */
namespace Xdp\Redis\Connection;

use RedisException;

class PhpRedisConnection
{
    /**
     * Phpredis client
     *
     * @var \Redis
     */
    private $client;

    public $errmsg;

    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Get the value related to the specified key
     *
     * @note https://github.com/phpredis/phpredis/#get
     * @param string $key
     * @return null|string
     */
    public function get($key)
    {
        $v = $this->client->get($key);
        return $key !== false ? $v : null;
    }

    /**
     * Set the string value in argument as value of the key. If you're using Redis >= 2.6.12, you can pass extended options as explained below
     *
     * @param $key
     * @param $value string value
     * @param null $options timeout or an options array
     * @return bool
     */
    public function set($key, $value, $options = null)
    {
        return $this->client->set($key, $value, $options);
    }

    /**
     * Set the string value in argument as value of the key, with a time to live. PSETEX uses a TTL in milliseconds.
     *
     * @param $key
     * @param $ttl
     * @param $value
     * @return bool
     */
    public function setex($key, $ttl, $value)
    {
        return $this->client->setex($key, $ttl, $value);
    }

    /**
     * 如果redis中不存在该键key，则将参数中的字符串值$value设置为键的值。
     *
     * @param $key
     * @param $value
     * @return bool
     */
    public function setnx($key, $value)
    {
        return $this->client->setnx($key, $value);
    }

    /**
     * 检查Key是否存在
     *
     * @param $redisKey
     * @return bool|int
     */
    public function existsKey($redisKey)
    {
        return $this->client->exists($redisKey);
    }

    /**
     * 自增
     *
     * @param $key
     * @param int $step
     * @return bool|int 返回新值或false
     */
    public function incrBy($key, $step = 1)
    {
        $type = gettype($step);

        if ($type == 'integer') {
            return $this->command('incrBy', [$key, $step]);
        } elseif ($type == 'double') {
            return $this->command('incrByFloat', [$key, $step]);
        }
        return false;
    }

    /**
     * 自减
     *
     * @param $key
     * @param int $step
     * @return bool
     */
    public function decrBy($key, $step = 1)
    {
        $type = gettype($step);

        if ($type == 'integer') {
            return $this->command('decrBy', [$key, $step]);
        } elseif ($type == 'double') {
            return $this->command('incrBy', [$key, -1 * $step]);
        }

        return false;
    }

    /**
     * 获取给定keys的所有值，不存在的值设定为null
     *
     * @param array $keys
     * @return array
     */
    public function mGet(array $keys)
    {
        return array_map(function ($v) { return $v !== false ? $v : null; }, $this->client->mget($keys));
    }

    /**
     * 将值添加到哈希
     *
     * @param $key
     * @param $field 域，也称hash key
     * @param $val 值
     * @return bool|int 1 - 新添加一个hash键值 0 - 替换已存在的hash键值, False - 如果发生错误的话
     */
    public function hSet($key, $field, $val)
    {
        return $this->client->hSet($key, $field, $val);
    }

    /**
     * 当field不存在时，设置field的值为val，如果存在，则field维持原值
     *
     * @param $key
     * @param $field
     * @param $val
     * @return bool true - val设置成功 false - field已存在，设置失败
     */
    public function hSetNx($key, $field, $val)
    {
        return $this->client->hSetNx($key, $field, $val);
    }

    /**
     * 获取指定field的值
     *
     * @param $key
     * @param $field
     * @return null|mixed 如果field不存在，则返回null
     */
    public function hGet($key, $field)
    {
        $r = $this->client->hGet($key, $field);
        if ($r === false) {
            return null;
        }
        return $r;
    }

    /**
     * 删除hash列表的元素
     *
     * @param $key
     * @param array ...$fields
     * @return int|bool false - key不存在
     */
    public function hDel($key, ...$fields)
    {
        return $this->client->hDel($key, ...$fields);
    }

    /**
     * 检查field是否存在
     *
     * @param $key
     * @param $field
     * @return bool 如果field存在，则返回true; 否则返回false
     */
    public function hExists($key, $field)
    {
        return $this->client->hExists($key, $field);
    }

    /**
     * 自增
     *
     * @param $key
     * @param $field
     * @param $step
     * @return float|int
     * @throws \InvalidArgumentException
     */
    public function hIncrBy($key, $field, $step)
    {
        $type = gettype($step);

        if ($type == "integer") {
            return $this->client->hIncrBy($key, $field, $step);
        } elseif ($type == "double") {
            return $this->client->hIncrByFloat($key, $field, $step);
        }

        throw new \InvalidArgumentException("invalid parameter step[{$step}]");
    }

    /**
     * 返回hash列表的长度
     *
     * @param $key
     * @return int|bool false - 如果key不存在或者不为hash类型
     */
    public function hLen($key)
    {
        return $this->client->hLen($key);
    }

    /**
     * @param $key
     * @param array $fields
     * @return mixed
     */
    public function hMGet($key, array $fields)
    {
        return array_filter($this->command('hMGet', [$key, $fields]), function ($v) { return $v !== false; });
    }

    /**
     * 设置hash列表的值
     *
     * @param $key
     * @param array $fields
     * @return bool
     */
    public function hMSet($key, array $fields)
    {
        return $this->command('hMSet', [$key, $fields]);
    }

    /**
     * Add one or more members to a sorted set or update its score if it already exists
     *
     * @param $key
     * @param $score
     * @param $val
     * @return int 1 - 新增数据时返回, 0 - 更新数据时返回
     */
    public function zAdd($key, $score, $val)
    {
        return $this->client->zAdd($key, $score, $val);
    }

    /**
     * 返回zset中满足start<=score<=end范围的元素个数
     *
     * @param $key
     * @param $start
     * @param $end
     * @return int
     */
    public function zCount($key, $start, $end)
    {
        return $this->client->zCount($key, $start, $end);
    }

    /**
     * zset元素个数
     *
     * @param $key
     * @return int
     */
    public function zCard($key)
    {
        return $this->client->zCard($key);
    }

    /**
     * 增加指定member的分值
     *
     * @param string $key string
     * @param float $step
     * @param string $member
     * @return float
     */
    public function zIncrBy($key, $step, $member)
    {
        return $this->client->zIncrBy($key, $step, $member);
    }

    /**
     * 返回指定score范围的数据
     *
     * @param $key
     * @param int $start
     * @param int $end
     * @param bool $withScores
     * @return array ['value' => score, ...], 如果withScores为false，则score为0，不具有实际意义，主要为了形式统一考虑。
     */
    public function zRange($key, $start = 0, $end = -1, $withScores = false)
    {
        $res = $this->client->zRange($key, $start, $end, $withScores);
        if (!$withScores && is_array($res) && !empty($res)) {
            $r = [];
            foreach ($res as $re) {
                $r[$re] = 0;
            }
            $res = $r;
        }

        return $res;
    }

    /**
     * 返回指定范围的zset元素
     *
     * @param $key
     * @param int $start 起始score
     * @param int $end 结束score
     * @param bool $withScores 是否返回score数据，如果为false，则统一返回score为0
     * @param int $offset 偏移
     * @param int $count 数量
     * @return array
     */
    public function zRangeByScore($key, $start, $end, $withScores = false, $offset = 0, $count = -1)
    {
        $options = $withScores ? ['withscores' => $withScores] : [];

        if ($count > 0) {
            $options['limit'] = [
                $offset, $count
            ];
        }

        $res = $this->client->zRangeByScore($key, $start, $end, $options);
        if (!$withScores && is_array($res) && !empty($res)) {
            $r = [];
            foreach ($res as $re) {
                $r[$re] = 0;
            }
            $res = $r;
        }

        return $res;
    }

    /**
     * 删除值为member的元素
     *
     * @param $key
     * @param $member
     * @return int 1 - 成功，0 - 失败
     */
    public function zRem($key, $member)
    {
        return $this->client->zRem($key, $member);
    }

    /**
     * 删除值为member的元素
     *
     * @param $key
     * @param $member
     * @return int
     */
    public function zDelete($key, $member)
    {
        return $this->client->zDelete($key, $member);
    }

    /**
     * 向list的左端push一个元素
     *
     * @param $key
     * @param $val
     * @return int|bool 成功 - 返回list的元素个数，失败 - 返回false
     */
    public function lPush($key, $val)
    {
        return $this->client->lPush($key, $val);
    }

    /**
     * 向list左端添加一个元素，如果指定的list存在的话，不存在则返回0
     *
     * @param $key
     * @param $val
     * @return int|bool 成功 - 返回list的元素个数，失败 - 返回false
     */
    public function lPushx($key, $val)
    {
        return $this->client->lPushx($key, $val);
    }

    public function lRange($key, $start, $end)
    {
        return $this->client->lRange($key, $start, $end);
    }

    /**
     * @param $key
     * @param $val
     * @param int $count
     * @return int|bool 成功 - 返回删除的元素个数，如果list不存在，则返回false
     */
    public function lRem($key, $val, $count = 0)
    {
        return $this->client->lRem($key, $val, $count);
    }

    /**
     * 从列表尾端弹出一个元素
     *
     * @param $key
     * @return string
     */
    public function rPop($key)
    {
        return $this->client->rPop($key);
    }

    /**
     * Adds the string value to the tail (right) of the list. Creates the list if the key didn't exist.
     * If the key exists and is not a list, FALSE is returned.
     *
     * @param $key
     * @param $value
     * @return int|bool false - if failure 否则，返回队列长度
     */
    public function rPush($key, $value)
    {
        return $this->client->rPush($key, $value);
    }

    /**
     * 从srckey尾端弹出一个元素x，并把x放入dstkey的头端。然后，返回x
     * @param $srckey
     * @param $dstkey
     * @return mixed
     */
    public function rPopLPush($srckey, $dstkey)
    {
        return $this->client->rPopLPush($srckey, $dstkey);
    }

    /**
     * 返回元素个数
     *
     * @param $key
     * @return int|bool false - 如果key指定的类型不是list
     */
    public function lLen($key)
    {
        return $this->client->lLen($key);
    }

    /**
     * @param $key
     */
    public function lSize($key)
    {
        return $this->client->lSize($key);
    }

    /**
     * a blocking lPop(rPop) primitive
     *
     * @param array $keys
     * @param int $timeout 秒
     * @return array ['key', 'value']
     */
    public function blPop(array $keys, int $timeout = 0)
    {
        return $this->client->blPop($keys, $timeout);
    }

    protected function command($method, array $parameters = [])
    {
        try {
            $res = $this->client->{$method}(...$parameters);
        } catch (RedisException $e) {
            $p = json_encode($parameters);
            $this->errmsg = "redis_error: redis is down or overload cmd[{$method}] parameters[{$p}] " .
                " errno[{$e->getCode()}] errmsg[{$e->getMessage()}]";
            throw new \Exception($this->errmsg);
        }

        return $res;
    }

    /**
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->command($method, $parameters);
    }
}