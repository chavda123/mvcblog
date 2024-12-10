<?php
include_once 'model/post_mdl.php';

class post_ctl extends post_mdl
{
	public function __construct()
	{
		$this->post_add_comment_ctl();
	}

	public function post_latest_ctl()
	{
		$this->page_no = common::getVal('page_no');
		$this->search = common::getVal('search');
		return parent::post_latest_mdl();
	}

	public function post_get_ctl()
	{
		$this->id = common::getVal('id');
		$this->is_admin = common::getVal('admin');
		return parent::post_get_mdl();
	}

	public function post_categories_ctl()
	{
		$this->category_id = common::getVal('cat');
		$this->page_no = common::getVal('page_no');
		return parent::post_categories_mdl();
	}

	public function post_add_comment_ctl()
	{
		if (common::getVal("method") != "0" && common::getVal("method") == "add_comment") {
			$this->postid = common::getVal('postid');
			$this->commentid = common::getVal('commentid');
			$this->name = common::getVal('name');
			$this->email = common::getVal('email');
			$this->comment = common::getVal('comment');
			$this->csrf_token = common::getVal("csrfToken");
			
			return parent::post_add_comment_mdl();
		}
	}
}
