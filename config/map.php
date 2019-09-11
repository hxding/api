<?php
/**
 * 系统统一的状态码的配置
 */
return [
    'merchant'=> [
        'EXP_TIME'=> 180,          //接口过期的时间，单位秒  
    ],
    'customer'=> [                 //用户相关配置
        'CUSTOMER_TYPE'=> 1,
    ],
    'currency_type_map' => [       //货币类型 
        0 => 'CNY',
	    1 => 'CNY',
	    2 => 'BTC',
	    3 => 'VND',
    ],
    'intech_deposit_config'=> [    //INTECH存款配置
        'NEW_ACCOUNT_FLAG'=> 0,
    ],
    'deposit_status'=> [
        'DEFAULT_WAIT'=> 0,
        'SUCCESS'=> 2,
        'MAKE_UP'=> 4,
    ],
    'withdraw_status'=> [          //取款状态配置
        'DEFAULT_WAIT'=> 0,
        'WAIT_DISPENSING'=> 1,
        'SUCCESS'=> 2,
        'REJECT'=> -1,  
    ],
];