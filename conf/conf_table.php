<?php
class conf_table
{
	/**
	 *  保留字段
	 */
    public static $table_auto_field_delete  =   'deleted';
    public static $table_auto_field_create  =   'create_at';
    public static $table_auto_field_update  =   'update_at';
    

    
    /**
     * tcp server 配置表
     */
    public static $conf_server				=	'conf_server';
    public static $server_id				=	'server_id';
    public static $server_host				=	'server_host';
    public static $server_port				=	'server_port';
    
	/**
	 * car配置表
	 */
    public static $conf_car					=	'conf_car';
    public static $car_id					=	'car_id';
    public static $car_name					=	'car_name';
    public static $car_pic_name				=	'car_pic_name';
    public static $car_description			=	'car_description';
    public static $car_cost_type			=	'car_cost_type';
    public static $car_cost					=	'car_cost';
    public static $car_hp					=	'car_hp';
    public static $car_speed_x				=	'car_speed_x';
    public static $car_speed_y				=	'car_speed_y';
    public static $car_acc_x				=	'car_acc_x';
    public static $car_acc_y				=	'car_acc_y';
    public static $car_limit_player_level	=	'car_limit_player_level';
    
    /**
     * item配置表
     */
    public static $conf_item				=	'conf_item';
    public static $item_id					=	'item_id';
    public static $item_name				=	'item_name';
    public static $item_pic_name			=	'item_pic_name';
    public static $item_touch_event			=	'item_touch_event';
    public static $item_move_type			=	'item_move_type';
    public static $item_move_speed			=	'item_move_speed';
    public static $item_roation_speed		=	'item_roation_speed';
    public static $item_hp		            =	'item_hp';
    public static $item_delay		        =	'item_delay';
    public static $item_gold                =   'item_gold';
    public static $item_diamond             =   'item_diamond';
    public static $item_desc                =   'item_desc';
    public static $item_is_buy              =   'item_is_buy';
    public static $item_is_gold             =   'item_is_gold';

    /**
     * gashapon概率表
     */
    public static $conf_gashapon			=	'conf_gashapon';
    public static $conf_gashapon_id			=	'id';	
    public static $conf_gashapon_item_id	=	'item_id';
    public static $conf_gashapon_item_num	=	'item_num';
    public static $conf_gashapon_weight		=	'weight';

    /**
     * 基本粒子特效表
     *
     */
    public static $conf_effect				=	'conf_effect';
    public static $conf_ef_id				=	'conf_ef_id';
    public static $conf_ef_name		        =	'conf_ef_name';
    public static $conf_ef_desc		        =	'conf_ef_desc';
    public static $conf_ef_pic_name		    =	'conf_ef_pic_name';
    public static $conf_ef_is_gold			=	'conf_ef_is_gold';
    public static $conf_ef_gold		        =	'conf_ef_gold';
    public static $conf_ef_diamond		    =	'conf_ef_diamond';


    /**
     * 玩家粒子特效表
     *
     */
    public static $db_effect				=	'db_effect';
    public static $effect_id				=	'effect_id';
    public static $effect_player_id		    =	'effect_player_id';
    public static $effect_conf_ef_id		=	'conf_ef_id';

    /**
     * player记录表	
     * 玩家信息
     */
    public static $db_player				=	'db_player';
    public static $player_id				=	'player_id';
    public static $player_uid				=	'player_uid';
    public static $player_name				=	'player_name';
    public static $player_level				=	'player_level';
    public static $player_exp				=	'player_exp';
    public static $player_coin				=	'player_coin';
    public static $player_daminod			=	'player_daminod';
    public static $player_drive				=	'player_drive';
    public static $player_win_rate			=	'player_win_rate';
    public static $player_wins				=	'player_wins';
    public static $player_user_hash			=	'player_user_hash';
    
    /**
     * garage记录表	
     * 玩家拥有车辆信息
     */
    public static $db_garage				=	'db_garage';
    public static $garage_id				=	'garage_id';
    public static $garage_player_id			=	'garage_player_id';
    public static $garage_car_id			=	'garage_car_id';
    public static $garage_car_num			=	'garage_car_num';
    
    /**
     * bag记录表
     * 玩家拥有道具信息
     */
    public static $db_bag					=	'db_bag';
    public static $bag_id					=	'bag_id';
    public static $bag_player_id			=	'bag_player_id';
    public static $bag_item_id				=	'bag_item_id';
    public static $bag_item_num				=	'bag_item_num';

    /**
     * gashapon记录表
     * 玩家扭蛋信息
     */
    public static $db_gashapon				=	'db_gashapon';
    public static $gashapon_id				=	'gashapon_id';
    public static $gashapon_player_id		=	'gashapon_player_id';
    public static $gashapon_free_num		=	'gashapon_free_num';	
    public static $gashapon_free_max		=	'gashapon_free_max';	
    public static $gashapon_count			=	'gashapon_count';
    public static $gashapon_last_time		=	'gashapon_last_time';




}