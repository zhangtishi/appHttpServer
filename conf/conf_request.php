<?php

class conf_request
{
    /**
     * 请求与控制器
     * request_id request_ctrl
     */
    public static $request = [
    		'1005' => 'areaPing',     	//ping
    		'1006' => 'login',     		//区域角色注册登陆
    		'1007' => 'playerChange',   //角色更新
    		'1008' => 'message',   		//扭蛋 系统消息等内容
    		'1009' => 'gashapon',   	//扭蛋 系统消息等内容
            '1010' => 'buyitem',        //购买道具
            '1011' => 'buyef',          //购买特效
            '1012' => 'chargelist',     //充值列表
            '1013' => 'charge',         //充值
            '1014' => 'buycar',         //买英雄
            '1015' => 'checkout'        //结算
    ];
    
    /**
     * 请求与参数
     * @var array
     */
    public static $request_param = [
    		'1005' => ['userId','time','hash'],     	            //ping
    		'1006' => ['userId','time','hash'],     	            //区域登陆注册
    		'1007' => ['userId','playerInfo'],     		            //角色更新
    		'1008' => ['userId','time','hash'],     	            //扭蛋 系统消息等内容
    		'1009' => ['userId','time','hash'],   		            //扭蛋 系统消息等内容
            '1010' => ['userId','time','hash'],             //购买道具
            '1011' => ['userId','time','hash','efId'],              //购买特效
            '1012' => ['userId','time','hash'],                     //充值列表
            '1013' => ['userId','time','hash','shopId'],            //充值
            '1014' => ['userId','time','hash','carId'],             //买英雄
            '1015' => ['userId','time','hash','rank'],
    ];
    
    /**
     * 错误信息列表
     * @var array
     */
    public static $error    =   [
    		101 => '系统错误',
    		102 => '参数格式错误',
    		103 => '参数非法',
    		104 => '验证失败',
    		105 => '秘钥已过期',     
    		106 => '账号已经被使用',
    		107 => '账号不存在',
    		108 => '密码错误',
    		109 => '昵称已存在',
    		110 => '昵称包含不恰当字符',
    		111 => '该昵称已被占用',
            112 => '金币不够',
            113 => '钻石不够',
    ];
}