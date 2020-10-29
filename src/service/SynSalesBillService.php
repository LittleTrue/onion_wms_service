<?php

namespace onion\onionWmsService;

use onion\onionWmsClient\Application;
use onion\onionWmsClient\Base\Exceptions\ClientError;

/**
 * 销售单同步.
 */
class SynSalesBillService
{
    /**
     * @var SynAllocation
     */
    private $_synAllocation;

    public function __construct(Application $app)
    {
        $this->_synAllocation = $app['syn_allocation'];
    }

    /**
     * 销售单同步.
     *
     * @throws ClientError
     * @throws \Exception
     */
    public function startSyncing(array $declareParams = [], array $declareConfig = [])
    {
        if (empty($declareConfig) || empty($declareParams)) {
            throw new ClientError('参数缺失', 1000001);
        }

        return $this->_synAllocation->startSyncing($declareParams, $declareConfig);
    }
}
