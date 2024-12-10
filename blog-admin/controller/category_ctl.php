<?php
include_once 'model/category_mdl.php';

class category_ctl extends category_mdl
{
	public function __construct()
	{
		$this->category_insert_ctl();
		$this->category_update_ctl();
		$this->category_delete_ctl();
	}

	public function category_select_ctl()
	{
		return parent::category_select_mdl();
	}

	public function category_select_id_ctl()
	{
		$this->id = common::getVal("catid");

		return parent::category_select_id_mdl();
	}

	public function category_insert_ctl()
	{
		if (common::getVal("method") != "0" && common::getVal("method") == "add_category") {
			$this->category = common::getVal("category");
			$this->parent = common::getVal("parent");
			$this->csrf_token = common::getVal("csrfToken");

			parent::category_insert_mdl();
		}
	}

	public function category_update_ctl()
	{
		if (common::getVal("method") != "0" && common::getVal("method") == "edit_category") {
			$this->id = common::getVal("catid");
			$this->category = common::getVal("category");
			$this->parent = common::getVal("parent");
			$this->csrf_token = common::getVal("csrfToken");

			parent::category_update_mdl();
		}
	}

	public function category_delete_ctl()
	{
		if (common::getVal("method") != "0" && common::getVal("method") == "delete_category") {
			$this->id = common::getVal("catid");

			parent::category_delete_mdl();
		}
	}
}
