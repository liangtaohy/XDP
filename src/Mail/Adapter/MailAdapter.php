<?php
/**
 * Created by PhpStorm.
 * User: shiwenyuan
 * Date: 2018/9/1 13341007105@163.com
 * Time: 下午5:35
 */

namespace Xdp\Mail\Adapter;

use Xdp\Utils\Traits\Singleton;

/**
 * mail适配器
 * Trait MailAdapter
 * @package Xdp\Mail
 */
trait MailAdapter
{

    use Singleton;


    /**
     * mail配置
     * @var
     */
    private $config = [];

    /**
     * 接收方
     * @var array
     */
    private $to = [];

    /**
     * 暗抄送
     * @var array
     */
    private $bcc = [];

    /**
     * 明抄送
     * @var array
     */
    private $cc = [];

    /**
     * 文本消息
     * @var null
     */
    private $text = null;

    /**
     * 发送方
     * @var array
     */
    private $from = [];

    /**
     * html格式邮件
     * @var null
     */
    private $html = null;

    /**
     * mail主题
     * @var null
     */
    private $subject = null;

    /**
     * 附件
     * @var array
     */
    private $attachment = [];

    /**
     * MailAdapter constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->from($config['username'], $config['name']);
        return $this;
    }


    /**
     * 重新设置config
     * @param $config
     * @return $this
     */
    public function setConfig($config)
    {
        $this->config = $config;
        $this->from($config['username'], $config['name']);
        return $this;
    }


    /**
     * 添加接收方
     * @param $addresses
     * @param null $name
     * @return $this
     */
    public function to($addresses, $name = null)
    {
        if (is_array($addresses)) {
            if (!is_null($name)) {
                foreach ($addresses as $key => $address) {
                    $addresses[$address] = $name;
                    unset($addresses[$key]);
                }
            }
            $this->to = $addresses;
        } else {
            $this->to[$addresses] = $name;
        }
        return $this;
    }

    /**
     * 添加暗抄送
     * @param $addresses
     * @param null $name
     * @return $this
     */
    public function bcc($addresses, $name = null)
    {
        if (is_array($addresses)) {
            if (!is_null($name)) {
                foreach ($addresses as $key => $address) {
                    $addresses[$address] = $name;
                    unset($addresses[$key]);
                }
            }
            $this->bcc = $addresses;
        } else {
            $this->bcc[$addresses] = $name;
        }
        return $this;
    }

    /**
     * 添加明抄送
     * @param $addresses
     * @param null $name
     * @return $this
     */
    public function cc($addresses, $name = null)
    {
        if (is_array($addresses)) {
            if (!is_null($name)) {
                foreach ($addresses as $key => $address) {
                    $addresses[$address] = $name;
                    unset($addresses[$key]);
                }
            }
            $this->cc = $addresses;
        } else {
            $this->cc[$addresses] = $name;
        }
        return $this;
    }

    /**
     * 添加发送方
     * @param string $mail
     * @param null $name
     * @return $this
     */
    public function from(string $mail, $name = null)
    {
        $this->from = [
            'addresses' => $mail,
            'name' => $name
        ];
        return $this;
    }

    /**
     * 添加邮件主题
     * @param string $subject
     * @return $this
     */
    public function subject(string $subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * 添加文本邮件
     * @param string $text
     * @return $this
     */
    public function text(string $text)
    {
        $this->text = $text;
        return $this;
    }


    /**
     * 添加html邮件
     * @param string $html
     * @return $this
     */
    public function html(string $html)
    {
        $this->html = $html;
        return $this;
    }


    /**
     * @param $text
     * @param null $callback
     */
    public function raw($text, $callback = null)
    {
        // TODO: Implement raw() method.
    }


    /**
     * 添加附件
     * @param $path
     * @param string $name
     * @param string $encoding
     * @param string $type
     * @param string $disposition
     * @return $this
     */
    public function attachment($path, $name = '', $encoding = 'base64', $type = '', $disposition = 'attachment')
    {

        $filename = basename($path);
        $name = empty($name) ? $filename : $name;
        $this->attachment = [
            'path' => $path,
            'name' => $name,
            'encoding' => $encoding,
            'type' => $type,
            'disposition' => $disposition
        ];
        return $this;
    }


    /**
     * 清除邮件信息
     */
    public function clear()
    {
        $this->text = null;
        $this->html = null;
        $this->subject = null;
        $this->to = [];
        $this->cc = [];
        $this->bcc = [];
        $this->attachment = [];
        $this->from = null;
    }
}
