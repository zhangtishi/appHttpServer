<?php
//设置编码
header ( 'Content-Type:text/html; charset=utf-8' );

//设置时区
date_default_timezone_set ( 'PRC' );

//设置报错级别
error_reporting(E_ALL);

/**
 * 设置自动加载
 */
spl_autoload_register ( function ($class_name) {
	
	$class_path = str_replace ( "_", "/", substr ( $class_name, 0, strrpos ( $class_name, "_" ) ) );
	
	if ($class_path) {
		$class_path .= "/";
	}
	
	if (file_exists ( $class_path . '/' .  $class_name  . '.php' )) {
		require_once ($class_path . '/' .  $class_name . '.php');
		return;
	}
	
});


/**
 * --------------------------
 * 			业务逻辑处理
 * --------------------------
 */
class_util::log_debug("===================================================================");
class_util::log_debug("Received : ".json_encode($_POST,JSON_UNESCAPED_UNICODE));

if (isset ($_REQUEST ['data'])) {
	
	class_util::log_debug("Request : ".$_REQUEST ['data']);

    $post_json = "";
    if(conf_common::$is_encrypt)
	    $post_json = class_util::decrypt($_REQUEST ['data']);
    else
        $post_json = $_REQUEST ['data'];
	
	class_util::log_debug( "Request decrypt : ".$post_json);
	
	$post_arr = json_decode($post_json,true);
	
	$server = new class_server ($post_arr);
	
	$response =  $server->route ();	
	
	echo  $response;
	
	class_util::log_debug( "response : ".$response);
	
}else {
	
	class_util::log_error(__FILE__,__LINE__,"error msg: 102!");
	class_util::set_error_response(102);
	echo class_util::show_error_response();
}