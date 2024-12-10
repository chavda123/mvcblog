<?php
include_once 'model/post_mdl.php';

class post_ctl extends post_mdl
{
	public function __construct()
	{
		$this->post_insert_ctl();
		$this->post_update_ctl();
		$this->post_delete_ctl();
	}

	public function post_select_ctl()
	{
		return parent::post_select_mdl();
	}

	public function post_select_id_ctl()
	{
		$this->id = common::getVal("postid");

		return parent::post_select_id_mdl();
	}

	public function post_insert_ctl()
	{
		if (common::getVal("method") != "0" && common::getVal("method") == "add_post") {
			$this->title = common::getVal("title");
			$this->description = common::getVal("description");
			$this->published = common::getVal("published");
			$this->user_id = common::getVal("user_id");
			$this->category_id = common::getVal("category_id");
			$rootDir = dirname(__DIR__); // Get the root directory path (mvc-blog)
			$uploadDir = common::POST_IMAGE_URL;
			$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
			$maxFileSize = 2 * 1024 * 1024; 

			if (!is_dir($uploadDir)) {
				@mkdir($uploadDir, 0777, true);
			}

			if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
				$file = $_FILES['image'];
				
				if (!in_array($file['type'], $allowedTypes)) {
					echo "Invalid file type. Only JPEG, PNG, and GIF are allowed.";
					exit;
				}
				
				if ($file['size'] > $maxFileSize) {
					echo "File size exceeds the maximum limit of 2 MB.";
					exit;
				}
				
				$fileName = uniqid() . "_" . basename($file['name']);
				$targetFile = $uploadDir . $fileName;
		
				if (move_uploaded_file($file['tmp_name'], $targetFile)) {
					$this->image = $fileName;
				}
			}
			$this->csrf_token = common::getVal("csrfToken");

			parent::post_insert_mdl();
		}
	}

	public function post_update_ctl()
	{
		if (common::getVal("method") != "0" && common::getVal("method") == "edit_post") {
			$this->id = common::getVal("postid");
			$this->title = common::getVal("title");
			$this->description = common::getVal("description");
			$this->published = common::getVal("published");
			$this->user_id = common::getVal("user_id");
			$this->category_id = common::getVal("category_id");
			$rootDir = dirname(__DIR__); // Get the root directory path (mvc-blog)
			$uploadDir = common::POST_IMAGE_URL;
			$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
			$maxFileSize = 2 * 1024 * 1024; 

			if (!is_dir($uploadDir)) {
				@mkdir($uploadDir, 0777, true);
			}

			if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
				$file = $_FILES['image'];
				
				if (!in_array($file['type'], $allowedTypes)) {
					echo "Invalid file type. Only JPEG, PNG, and GIF are allowed.";
					exit;
				}
				
				if ($file['size'] > $maxFileSize) {
					echo "File size exceeds the maximum limit of 2 MB.";
					exit;
				}
				
				$fileName = uniqid() . "_" . basename($file['name']);
				$targetFile = $uploadDir . $fileName;
		
				if (move_uploaded_file($file['tmp_name'], $targetFile)) {
					$this->image = $fileName;
				}
			}
			$this->csrf_token = common::getVal("csrfToken");

			parent::post_update_mdl();
		}
	}

	public function post_delete_ctl()
	{
		if (common::getVal("method") != "0" && common::getVal("method") == "delete_post") {
			$this->id = common::getVal("postid");

			parent::post_delete_mdl();
		}
	}
}
