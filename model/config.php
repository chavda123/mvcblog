<?php
ini_set('session.cookie_samesite', 'None');
ini_set('session.cookie_secure', 'On');
ini_set('session.cookie_httponly', 'On');

session_start();
ob_start();

include_once 'common.php';

class config extends common
{
	private string $hostName = common::DB_HOST_NAME;
	private string $dbName = common::DB_NAME;
	private ?string $userName = common::DB_USERNAME;
	private ?string $password = common::DB_PASSWORD;

	protected function connect()
	{
		$mysql = mysqli_connect($this->hostName, $this->userName, $this->password, $this->dbName);

		$mysql->set_charset("utf8mb4");

		return $mysql;
	}

	protected function disconnect($link)
	{
		$link->close();
	}
}
