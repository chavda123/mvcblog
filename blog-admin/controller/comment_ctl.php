<?php
include_once 'model/comment_mdl.php';

class comment_ctl extends comment_mdl
{
	public function __construct()
	{
		$this->comment_delete_ctl();
		$this->comment_approve_ctl();
	}

	public function comment_select_ctl()
	{
		return parent::comment_select_mdl();
	}

	public function comment_approve_ctl()
	{
		if (common::getVal("method") != "0" && common::getVal("method") == "approve_comment") {
			$this->id = common::getVal("commentid");

			parent::comment_approve_mdl();
		}
	}

	public function comment_delete_ctl()
	{
		if (common::getVal("method") != "0" && common::getVal("method") == "delete_comment") {
			$this->id = common::getVal("commentid");

			parent::comment_delete_mdl();
		}
	}
}
