<?php

namespace onion\onionWmsClient\Base;

use GuzzleHttp\RequestOptions;
use onion\onionWmsClient\Application;
use onion\onionWmsClient\Base\Exceptions\ClientError;

/**
 * 底层请求.
 */
class BaseClient
{
    use MakesHttpRequests;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var array
     */
    protected $json = [];

    /**
     * @var string
     */
    protected $language = 'zh-cn';

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * 获取特定位数时间戳.
     * @return int
     */
    public function getTimestamp($digits = 10)
    {
        $digits = $digits > 10 ? $digits : 10;

        $digits = $digits - 10;

        if ((!$digits) || (10 == $digits)) {
            return time();
        }

        return number_format(microtime(true), $digits, '', '') - 50000;
    }

    /**
     * 浮点数比较规则.
     * @return int
     */
    public function floatCmp($f1, $f2, $precision = 10)
    {
        $e  = pow(10, $precision);
        $i1 = intval($f1 * $e);
        $i2 = intval($f2 * $e);
        return $i1 == $i2;
    }

    /**
     * Set Headers Language params.
     *
     * @param string $language 请求头中的语种标识
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * Make a get request.
     *
     * @throws ClientError
     */
    public function httpGet($uri, array $options = [])
    {
        $options = $this->_headers($options);

        return $this->request('GET', $uri, $options);
    }

    /**
     * Make a post request.
     *
     * @throws ClientError
     */
    public function httpPostJson($uri)
    {
        return $this->requestPost($uri, [RequestOptions::JSON => $this->json]);
    }

    /**
     * Set json params.
     *
     * @param array $json Json参数
     */
    public function setParams(array $json, $declareConfig, $method)
    {
        $time = $this->getTimestamp(13);

        //生成签名
        $sign = $this->sign($json, $time);

        //数据公共格式
        $param = [
            'data'          => json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'digest'        => $sign,
            'timestamp'     => $time,
            'customerCode'  => $declareConfig['customerCode'],
            'sitecode'      => $declareConfig['sitecode'],
            'version'       => 'V1',
            'serviceBeanId' => 'wmsComApiService',
            'method'        => $method,
        ];

        $send_string = 'customerCode=' . $param['customerCode'] . '&sitecode=' . $param['sitecode'] . '&data='
        . $param['data'] . '&digest=' . $param['digest'] . '&timestamp=' . $param['timestamp'] .
        '&version=' . $param['version'] . '&serviceBeanId=' . $param['serviceBeanId'] . '&method=' . $param['method'];

        $this->json = $send_string;

        return $send_string;
    }

    /**
     * @throws ClientError
     */
    protected function requestPost($uri, array $options = [])
    {
        $options = $this->_headers($options);

        return $this->request('POST', $uri, $options);
    }

    /**
     * set Headers.
     *
     * @return array
     */
    private function _headers(array $options = [])
    {
        $time = time();

        $options[RequestOptions::HEADERS] = [
            'Content-Type' => 'application/json',
            'timestamp'    => $time,
        ];
        return $options;
    }

    /**
     * 生成签名.
     */
    private function sign($data, $time)
    {
        $appsecret = $this->app['config']->get('secret');
        $string    = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '+' . $appsecret . '+' . $time;
        // $string = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . $appsecret . $time;

        return base64_encode(md5($string));
    }
}
