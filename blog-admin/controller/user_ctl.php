<?php
include_once 'model/user_mdl.php';

class user_ctl extends user_mdl
{
	public function __construct()
	{
		$this->user_insert_ctl();
		$this->user_update_ctl();
		$this->user_delete_ctl();
		$this->user_inactive_ctl();
	}

	public function user_select_ctl()
	{
		return parent::user_select_mdl();
	}

	public function user_select_id_ctl()
	{
		$this->id = common::getVal("userid");

		return parent::user_select_id_mdl();
	}

	public function user_insert_ctl()
	{
		if (common::getVal("method") != "0" && common::getVal("method") == "add_user") {
			$this->role = common::getVal("role");
			$this->name = common::getVal("name");
			$this->email = common::getVal("email");
			$this->password = common::getVal("password");
			$this->csrf_token = common::getVal("csrfToken");

			parent::user_insert_mdl();
		}
	}

	public function user_update_ctl()
	{
		if (common::getVal("method") != "0" && common::getVal("method") == "edit_user") {
			$this->id = common::getVal("userid");
			$this->role = common::getVal("role");
			$this->name = common::getVal("name");
			$this->email = common::getVal("email");
			$this->password = common::getVal("password");
			$this->csrf_token = common::getVal("csrfToken");

			parent::user_update_mdl();
		}
	}

	public function user_delete_ctl()
	{
		if (common::getVal("method") != "0" && common::getVal("method") == "delete_user") {
			$this->id = common::getVal("userid");

			parent::user_delete_mdl();
		}
	}

	public function user_inactive_ctl()
	{
		if (common::getVal("method") != "0" && common::getVal("method") == "inactive_user") {
			$this->id = common::getVal("userid");
			$this->status = common::getVal("status");

			parent::user_inactive_mdl();
		}
	}
}
