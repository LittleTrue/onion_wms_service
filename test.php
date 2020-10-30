<?php

require_once __DIR__ . '/vendor/autoload.php';

use onion\onionWmsClient\Application;
use onion\onionWmsService\SynAllocationService;
use onion\onionWmsService\SynPurchaseService;
use onion\onionWmsService\SynSalesBillService;

$config = [
    'base_uri' => 'http://203.195.149.21:8080/x2oms/x2wms/api/index.do',
    'secret'   => '123456',
];

$ioc_con_app = new Application($config);

$synPurchaseService   = new SynPurchaseService($ioc_con_app);
$synAllocationService = new SynAllocationService($ioc_con_app);
$synSalesBillService  = new SynSalesBillService($ioc_con_app);

//---------------------------------------
//*************************************
// //采购单同步
// $info = [
//     'details' => [
//         [
//             'batchno'     => '',
//             'boxnum'      => '0',
//             'cusgoodsno'  => 'FW01027667543725',
//             'desp'        => '',
//             'goodsname'   => '测试品01保质期',
//             'packageType' => '',
//             'pcs'         => 1000,
//             'totalvolume' => '0',
//             'totalweight' => '0',
//             'unitboxnum'  => '0',
//             'unitprice'   => '0',
//         ],
//     ],
//     'header' => [
//         'billstat'    => '007002',
//         'cusbillno'   => '2017090210',
//         'desp'        => '',
//         'intype'      => '001001',
//         'preintime'   => '2017-09-04 23:07:30',
//         'supplier'    => '',
//         'totalvolume' => 0,
//         'totalweight' => 0,
//     ],
// ];

// $declareConfig = [
//     'customerCode' => 'TT',
//     'sitecode'     => 'TEST01',
// ];
// $tmp = $synPurchaseService->startSyncing($info, $declareConfig);
// var_dump($tmp);
// die();

//---------------------------------------
//*************************************
//调拨同步
$info = [
    'details' => [
        [
            'batchno'         => '',
            'cusgoodsno'      => 'FW01027667543725',
            'desp'            => '',
            'expireddateend'  => null,
            'expireddatefrom' => null,
            'goodsname'       => '测试品01保质期',
            'packageType'     => '',
            'pcs'             => 1000,
            'unitprice'       => '0',
            'volume'          => '0',
            'weight'          => '0',
        ],
    ],
    'header' => [
        'bighead'             => '',
        'billstat'            => '009000',
        'billtime'            => null,
        'brandcode'           => 'CS',
        'codfee'              => '0',
        'cusbillno'           => '2017090211',
        'desp'                => '',
        'destcode'            => '',
        'idcard'              => '',
        'insurefee'           => 0,
        'logisticscode'       => 'tt',
        'logisticsno'         => '',
        'monthpayno'          => '',
        'orginalregioncode'   => '',
        'outtype'             => '002002',
        'postcode'            => '',
        'recvaddr'            => '',
        'recvarea'            => '',
        'recvcity'            => '',
        'recvcus'             => '鲜生',
        'recvmobile'          => '13530731922',
        'recvphone'           => '',
        'recvprovince'        => '',
        'sndaddr'             => '',
        'sndcus'              => '',
        'sndmobile'           => '',
        'sndphone'            => '',
        'totalpcs'            => 0,
        'totalvolume'         => 0,
        'ytothree'            => '',
        'globalLogisticsNo'   => '',
        'globalLogisticsCode' => '',
        'globalLogisticsUrl'  => 'http://www.pdfpath.com/pdfinfo',
        'destsite'            => 'test',
        'destCuscode'         => 'test',
    ],
];

$declareConfig = [
    'customerCode' => 'TT',
    'sitecode'     => 'TEST01',
];
$tmp = $synAllocationService->startSyncing($info, $declareConfig);
var_dump($tmp);
die();

//---------------------------------------
//*************************************
//销售单同步
$info = [
    'details' => [
        [
            'batchno'         => '',
            'cusgoodsno'      => 'FW01027667543725',
            'desp'            => '',
            'expireddateend'  => null,
            'expireddatefrom' => null,
            'goodsname'       => '测试品01保质期',
            'packageType'     => '',
            'pcs'             => 1000,
            'unitprice'       => '0',
            'volume'          => '0',
            'weight'          => '0',
        ],
    ],
    'header' => [
        'bighead'             => '',
        'billstat'            => '009000',
        'billtime'            => null,
        'brandcode'           => 'CS',
        'codfee'              => '0',
        'cusbillno'           => '2017090211',
        'desp'                => '',
        'destcode'            => '',
        'idcard'              => '',
        'insurefee'           => 0,
        'logisticscode'       => 'tt',
        'logisticsno'         => '',
        'monthpayno'          => '',
        'orginalregioncode'   => '',
        'outtype'             => '002001',
        'postcode'            => '',
        'recvaddr'            => '',
        'recvarea'            => '',
        'recvcity'            => '',
        'recvcus'             => '鲜生',
        'recvmobile'          => '13530731922',
        'recvphone'           => '',
        'recvprovince'        => '',
        'sndaddr'             => '',
        'sndcus'              => '',
        'sndmobile'           => '',
        'sndphone'            => '',
        'totalpcs'            => 0,
        'totalvolume'         => 0,
        'ytothree'            => '',
        'globalLogisticsNo'   => '',
        'globalLogisticsCode' => '',
        'globalLogisticsUrl'  => 'http://www.pdfpath.com/pdfinfo',
    ],
];

$declareConfig = [
    'customerCode' => 'TT',
    'sitecode'     => 'TEST01',
];
$tmp = $synSalesBillService->startSyncing($info, $declareConfig);
var_dump($tmp);
die();
