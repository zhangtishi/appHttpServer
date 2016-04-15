<?php
class conf_common {
	// ---------------------------------------
	// develop open 是否为开发模式
	// ---------------------------------------
	public static $log_report = true; // 玩家请求log开关
	public static $error_report = true; // 错误log开关
	public static $secret_key = 'nijun'; // 服务器key
	
	public static $area_id	=	1;	
	public static $area_key	=	'tony';

    public static $is_encrypt = false;
    public static $is_hash = false;
	
	public static $center	=	'http://test-rplan.findchat.cn/raceCenterHttpServer/';	//中心服务器地址
	
	//扭蛋相关配置
	public static $gashapon_free_max	=	1;
	public static $gashapon_cost	=	100;
	//如果扭蛋抽到现在有的车补偿的金币数额
	public static $gashapon_car_compensate_coin	=	50;
	
	public static $car_max_hp	=	'100';
	public static $car_max_speed_x	=	'100';
	public static $car_max_speed_y	=	'100';
	public static $car_max_acc_x	=	'100';
	public static $car_max_acc_y	=	'100';
}
