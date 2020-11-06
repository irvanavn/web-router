<?php

session_start();
date_default_timezone_set('Asia/Jakarta');

$appfolder = 'app';

if(!is_dir($appfolder)){
	header("HTTP/1.1 503 Service Temporarily Unavailable");
	echo 'App folder not found';
	exit(1);
}

define('APPPATH', $appfolder);

$routes = [];
function route($action, Closure $callback){
	global $routes;
	$routes[$action] = $callback;
}

require_once APPPATH.'/config/app.php';
$app = new App();
global $app;

define('BASEURL', $app->baseUrl());

require_once APPPATH.'/config/router.php';

if(isset($_SERVER['PATH_INFO'])){
	if(!isset($routes[$_SERVER['PATH_INFO']])) $app->show404();
	call_user_func($routes[$_SERVER['PATH_INFO']], $app);
}else{
	call_user_func($routes['/'], $app);
}


