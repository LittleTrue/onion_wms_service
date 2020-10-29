<?php

namespace onion\onionWmsService;

use onion\onionWmsClient\Application;
use onion\onionWmsClient\Base\Exceptions\ClientError;

/**
 * 采购单同步.
 */
class SynPurchaseService
{
    /**
     * @var SynPurchase
     */
    private $_synPurchase;

    public function __construct(Application $app)
    {
        $this->_synPurchase = $app['syn_purchase'];
    }

    /**
     * 采购单同步.
     *
     * @throws ClientError
     * @throws \Exception
     */
    public function startSyncing(array $declareParams = [], array $declareConfig = [])
    {
        if (empty($declareConfig) || empty($declareParams)) {
            throw new ClientError('参数缺失', 1000001);
        }

        return $this->_synPurchase->startSyncing($declareParams, $declareConfig);
    }
}
