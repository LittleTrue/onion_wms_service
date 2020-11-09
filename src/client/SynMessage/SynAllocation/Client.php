<?php

namespace onion\onionWmsClient\SynMessage\SynAllocation;

use onion\onionWmsClient\Application;
use onion\onionWmsClient\Base\BaseClient;
use onion\onionWmsClient\Base\Exceptions\ClientError;

/**
 * 客户端.
 */
class Client extends BaseClient
{
    /**
     * @var Application
     */
    protected $credentialValidate;

    private $method = 'createSO';

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->credentialValidate = $app['credential'];
    }

    /**
     * 调拨单同步.
     */
    public function startSyncing(array $infos)
    {
        $declareConfig = [
            'secret'        => $this->app['config']->get('secret'),
            'sitecode'      => $this->app['config']->get('sitecode'),
            'customerCode'  => $this->app['config']->get('customerCode'),
        ];

        //参数验证
        $rule = [
            'customerCode' => 'require',
            'sitecode'     => 'require',
        ];

        $this->credentialValidate->setRule($rule);

        if (!$this->credentialValidate->check($declareConfig)) {
            throw new ClientError('报文传输配置' . $this->credentialValidate->getError());
        }

        $this->checkInfo($infos);
        
        $send_data = $this->setParams($infos, $declareConfig, $this->method);

        return $this->httpGet('?' . $send_data);
    }

    public function checkInfo($data)
    {
        //检验申报表信息
        $details = $data['details'];
        $header  = $data['header'];

        $detail_rules = [
            'cusgoodsno'      => 'require|max:50',
            'cuscode'         => 'max:30',
            'pcs'             => 'require',
            'batchno'         => 'number',
            'weight'          => 'number',
            'volume'          => 'number',
            'packagetype'     => 'max:30',
            'unitprice'       => 'number',
            'desp'            => 'max:100',
            'sellingPrice'    => 'float',
            'discountFee'     => 'float',
            'distributorComm' => 'float',
            'goodsnameEn'     => 'max:200',
            'material'        => 'max:50',
        ];

        $header_rules = [
            'cusbillno'           => 'require|max:40',
            'outtype'             => 'reuqire|max:30',
            'billstat'            => 'reuqire|max:30',
            'brandcode'           => 'reuqire|max:30',
            'cuscode'             => 'max:30',
            'logisticscode'       => 'reuqire|max:30',
            'logisticsno'         => 'max:30',
            'vipid'               => 'max:30',
            'sndcus'              => 'max:30',
            'sndmobile'           => 'max:30',
            'sndphone'            => 'max:30',
            'sndaddr'             => 'max:100',
            'recvcus'             => 'reuqire|max:30',
            'recvmobile'          => 'max:30',
            'recvphone'           => 'max:30',
            'recvprovince'        => 'max:30',
            'recvcity'            => 'max:30',
            'recvarea'            => 'max:30',
            'recvaddr'            => 'max:100',
            'postcode'            => 'max:30',
            'idcard'              => 'max:30',
            'recvCountry'         => 'max:50',
            'recvEmail'           => 'max:30',
            'codfee'              => 'number',
            'insurefee'           => 'number',
            'totalweight'         => 'number',
            'totalvolume'         => 'number',
            'desp'                => 'max:100',
            'bighead'             => 'max:30',
            'orginalregioncode'   => 'max:30',
            'ytothree'            => 'max:30',
            'destcode'            => 'max:30',
            'monthpayno'          => 'max:30',
            'destsite'            => 'max:30',
            'destCuscode'         => 'max:30',
            'lockedreason'        => 'max:50',
            'globalLogisticsNo'   => 'max:40',
            'globalLogisticsCode' => 'max:30',
            'globalLogisticsUrl'  => 'max:200',
            'erpOrderno'          => 'max:40',
            'encryptedData'       => 'max:500',
            'signature'           => 'max:200',
            'templateUrl'         => 'max:200',
            'packageNo'           => 'max:50',
            'currencyCode'        => 'max:30',
            'currencyName'        => 'max:30',
            'expressext'          => 'max:100',
        ];

        $this->credentialValidate->setRule($detail_rules);

        foreach ($details as $key => $value) {
            if (!$this->credentialValidate->check($value)) {
                throw new ClientError('报文清单数据: ' . $this->credentialValidate->getError());
            }
        }

        $this->credentialValidate->setRule($header_rules);

        if (!$this->credentialValidate->check($header)) {
            throw new ClientError('报文清单数据: ' . $this->credentialValidate->getError());
        }

        return true;
    }
}
