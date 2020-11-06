<?php
class App
{
	var $setting;
	var $db;

	function __construct(){
		include APPPATH.'/config/config.php';
		$this->setting = $config;

		if(empty($this->setting)){
			exit('Config not found');
		}

		self::connection();
	}

	private function connection(){
		$hostname = isset($this->setting['db']['hostname']) ? $this->setting['db']['hostname'] : '';
		$username = isset($this->setting['db']['username']) ? $this->setting['db']['username'] : '';
		$password = isset($this->setting['db']['password']) ? $this->setting['db']['password'] : '';
		$database = isset($this->setting['db']['database']) ? $this->setting['db']['database'] : '';

		try{
			$this->db = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXEPTION);
			$this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			die('COnnection fail : '. $e->getMessage());
		}
 	}

 	function baseUrl(){
 		$baseurl = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
		$baseurl .= "://".$_SERVER['HTTP_HOST'];
		$baseurl .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);

		return $baseurl;
 	}

 	function loadView($filename)
 	{
 		if(file_exists(APPPATH.'/view/'.$filename.'php')){
 			require(APPPATH.'/view/'.$filename.'php');
 		}else{
 			header("HTTP/1.1 503 Service Temporarily Unavailable");
			echo 'File not found';
			exit(1);
 		}
 	}

 	function show404(){
 		header("HTTP/1.0 404 Not Found");
 		self::loadView('error/404');
 		exit();
 	}

}