<?php

namespace onion\onionWmsClient\SynMessage\SynPurchase;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider.
 */
class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['syn_purchase'] = function ($app) {
            return new Client($app);
        };
    }
}
