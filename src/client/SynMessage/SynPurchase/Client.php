<?php

namespace onion\onionWmsClient\SynMessage\SynPurchase;

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

    private $method = 'createPO';

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->credentialValidate = $app['credential'];
    }

    /**
     * 采购单同步.
     *
     * @throws ClientError
     */
    public function startSyncing(array $infos, $declareConfig)
    {
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
            'cusgoodsno'  => 'require|max:50',
            'goodsname'   => 'require|max:100',
            'pcs'         => 'require',
            'totalvolume' => 'number|max:14',
            'unitprice'   => 'number|max:14',
            'packageType' => 'max:30',
            'batchno'     => 'max:30',
            'desp'        => 'max:100',
            'boxnum'      => 'number|max:14',
            'outtype'     => 'max:30',
        ];

        $header_rules = [
            'cusbillno'   => 'require|max:40',
            'intype'      => 'reuqire|max:30',
            'cuscode'     => 'max:30',
            'preintime'   => 'require',
            'billstat'    => 'max:30',
            'supplier'    => 'max:30',
            'totalweight' => 'number|max:14',
            'totalvolume' => 'number|max:14',
            'desp'        => 'max:100',
            'fromsite'    => 'max:30',
            'frombillno'  => 'max:40',
            'vipid'       => 'max:40',
            'brandcode'   => 'max:30',
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
