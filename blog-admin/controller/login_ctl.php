<?php
include_once 'model/login_mdl.php';

class login_ctl extends login_mdl
{
	public function __construct()
	{
		$this->login_select_ctl();
		$this->forgot_password_ctl();
		$this->reset_password_ctl();
	}

	public function login_select_ctl()
	{
		if (common::getVal("method") != "0" && common::getVal("method") == "login") {
			$this->email = common::getVal("email");
			$this->password = common::getVal("password");
			$this->csrf_token = common::getVal("csrfToken");

			parent::login_select_mdl();
		}
	}

	public function forgot_password_ctl()
	{
		if (common::getVal("method") != "0" && common::getVal("method") == "forgot_password") {
			$this->email = common::getVal("email");
			$this->csrf_token = common::getVal("csrfToken");

			parent::forgot_password_mdl();
		}
	}

	public function reset_password_ctl()
	{
		if (common::getVal("method") != "0" && common::getVal("method") == "reset_password") {
			$this->token = common::getVal("token");
			$this->password = common::getVal("password");
			$this->csrf_token = common::getVal("csrfToken");

			parent::reset_password_mdl();
		}
	}
}
