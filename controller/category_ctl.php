<?php
include_once 'model/category_mdl.php';

class category_ctl extends category_mdl
{
	public function __construct()
	{
		
	}

	public function category_select_ctl()
	{
		return parent::category_select_mdl();
	}
}
