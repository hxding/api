<?php
/**
 * 系统统一的状态码的配置
 */
return [
    'customer'=> [                 //用户相关配置
        'CUSTOMER_TYPE'=> 1
    ],
    'currency_type_map' => [       //货币类型 
        0 => 'CNY',
	    1 => 'CNY',
	    2 => 'BTC',
	    3 => 'VND'
    ],
    'intech_deposit_config'=> [    //INTECH存款配置
        'NEW_ACCOUNT_FLAG'=> 0,
    ],
    'withdraw_status'=> [          //取款状态配置
        'DEFAULT_WAIT'=> 0,
        'WAIT_DISPENSING'=> 1,
        'SUCCESS'=> 2,
        'REJECT'=> -1  
    ],
];