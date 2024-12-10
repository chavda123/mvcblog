<?php
class common
{
	#region - Connection Variables
	public const DB_HOST_NAME = "localhost";
	public const DB_NAME = "blog_database";
	public const DB_USERNAME = "root";
	public const DB_PASSWORD = "";
	#endregion

	#region - Site Variables
	public const SITE_URL = "http://localhost/mvc-blog/";
	public const ADMIN_SITE_URL = "http://localhost/mvc-blog/blog-admin/";
	public const SITE_NAME = "Simple Blog";
	public const ADMIN_SESSION = "admin";
	public const POST_IMAGE_URL = __DIR__."/../uploads/";
	public const POST_LIST_LIMIT = 4;
	#endregion

	public static function getPageName()
	{
		return substr(basename($_SERVER['PHP_SELF']), 0, -4);
	}

	public static function getVal($value)
	{
		return $_REQUEST[$value] ?? 0;
	}

	public static function isGET()
	{
		return $_GET ? true : false;
	}

	public static function isPOST()
	{
		return $_POST ? true : false;
	}

	public static function GUID()
	{
		if (function_exists('com_create_guid') === true)
			return trim(com_create_guid(), '{}');

		return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', random_int(0, 65535), random_int(0, 65535), random_int(0, 65535), random_int(16384, 20479), random_int(32768, 49151), random_int(0, 65535), random_int(0, 65535), random_int(0, 65535));
	}

	function get_client_ip()
	{
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if (isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if (isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if (isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

	public static function isSession($sessionName)
	{
		return isset($_SESSION[$sessionName]);
	}

	public static function getSession($sessionName)
	{
		return $_SESSION[$sessionName];
	}

	public static function setSession($sessionName, $sessionValue)
	{
		$_SESSION[$sessionName] = $sessionValue;
	}

	public static function getRootPath()
	{
		// return $_SERVER['DOCUMENT_ROOT'];
		return str_replace("model", "", dirname(__FILE__));
	}

	public static function validateSimpleCaptcha($captcha)
	{
		if (empty($captcha)) {
			return false;
		}

		if($captcha == common::getSession('mps_captcha')) {
			return true;
		}
		return false;
	}

	public function CheckLoginSession()
	{
		if (self::isSession(self::PARTNER_SESSION)) {
		} else {
			if (!$this->loginPage) {
				$queryString = http_build_query($_GET);
				if(!empty($queryString)) {
					header("location:login?" . $queryString);
				} else {
					header("location:login");
				}
				exit;
			}
		}
		
		$this->page = substr(basename($_SERVER['PHP_SELF']), 0, -4);
	}

	public function CheckAdminLoginSession()
	{
		if (self::isSession(self::PARTNER_ADMIN_SESSION)) {
		} else {
			if (!$this->adminLoginPage) {
				$queryString = http_build_query($_GET);
				if(!empty($queryString)) {
					header("location:login?" . $queryString);
				} else {
					header("location:login");
				}
				exit;
			}
		}

		$this->page = substr(basename($_SERVER['PHP_SELF']), 0, -4);
	}

	public static function sendJson($obj, $isResponseEnd = true)
	{
		header('Content-Type: application/json');
		echo json_encode($obj);
		if ($isResponseEnd)
			exit;
	}

	public static function csrf_token() {
		$token = bin2hex(random_bytes(32));
		self::setSession('csrf_token', $token);
		return $token;
	}
}
