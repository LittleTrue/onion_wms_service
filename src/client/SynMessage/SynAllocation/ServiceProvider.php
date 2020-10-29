<?php

namespace onion\onionWmsClient\SynMessage\SynAllocation;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider.
 */
class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['syn_allocation'] = function ($app) {
            return new Client($app);
        };
    }
}
