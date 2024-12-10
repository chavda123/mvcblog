<?php
include_once '../model/config.php';

class post_mdl extends config
{
	protected $id = null;
	protected $title = null;
	protected $description = null;
	protected $image = null;
	protected $user_id = null;
	protected $category_id = null;
	protected $published = null;
	protected $csrf_token = null;

	protected function post_insert_mdl()
	{
		$mysql = parent::connect();

		$resultArray = array();

		if (!isset($this->csrf_token) || $this->csrf_token !== common::getSession('csrf_token')) {
			http_response_code(403);
			
			$resultArray["msg"] = "Invalid CSRF token";
		} else {

			$stmt = $mysql->prepare("INSERT INTO posts (`user_id`, `category_id`, `title`, `description`, `image`, `published`) VALUES(?, ?, ?, ?, ?, ?)");

			$published = date('Y-m-d');
			if(!empty($this->published)) {
				$published = date('Y-m-d', strtotime($this->published));
			}
	
			$stmt->bind_param('iissss', $this->user_id, $this->category_id, $this->title, $this->description, $this->image, $published);

			$stmt->execute();

			$resultArray["isSuccess"] = 1;
			$resultArray["msg"] = "Post added successfully";
					
			$stmt->free_result();

			$stmt->close();

			parent::disconnect($mysql);
		}

		/* Ajax Select Example Start */
		common::sendJson($resultArray);
		/* Ajax Select Example End */

		/* Page Load Select Example Start */
		return $resultArray;
		/* Page Load Select Example End */
	}

	public function post_select_id_mdl() {
		$id = null;
		$user_id = null;
		$category_id = null;
		$title = null;
		$description = null;
		$image = null;
		$status = null;
		$published = null;
		$mysql = parent::connect();

		$dataArray = array();
		
		$stmt = $mysql->prepare("SELECT id, `user_id`, `category_id`, `title`, `description`, `image`, `status`, `published` FROM `posts` WHERE id = ?");

		$stmt->bind_param('i', $this->id);

		$stmt->execute();

		$stmt->store_result();

		if ($stmt->num_rows > 0) {
			$stmt->bind_result($id, $user_id, $category_id, $title, $description, $image, $status, $published);

			while ($stmt->fetch()) {
				$dataArray["id"] = $id;
				$dataArray["user_id"] = $user_id;
				$dataArray["category_id"] = $category_id;
				$dataArray["title"] = $title;
				$dataArray["description"] = $description;
				$dataArray["image"] = $image;
				$dataArray["status"] = !empty($status) ?  "Active" : "Inactive";
				$dataArray["published"] = $published;
			}
			$stmt->free_result();
		}

		$stmt->close();

		parent::disconnect($mysql);

		/* Page Load Select Example Start */
		return $dataArray;
		/* Page Load Select Example End */
	}

	public function post_select_mdl() {
		$id = null;
		$user_id = null;
		$category_id = null;
		$title = null;
		$description = null;
		$image = null;
		$status = null;
		$published = null;
		$created = null;
		$updated = null;
		$mysql = parent::connect();

		$resultArray = array();

		$stmt = $mysql->prepare("SELECT id, `user_id`, `category_id`, `title`, `description`, `image`, `status`, `published`, `created`, `updated` FROM `posts`");

		$stmt->execute();

		$stmt->store_result();

		if ($stmt->num_rows > 0) {
			$stmt->bind_result($id, $user_id, $category_id, $title, $description, $image, $status, $published, $created, $updated);

			$resultArray["isSuccess"] = 1;

			while ($stmt->fetch()) {
				$dataArray["id"] = $id;
				$dataArray["user_id"] = $user_id;
				$dataArray["category_id"] = $category_id;
				$dataArray["title"] = $title;
				$dataArray["description"] = $description;
				$dataArray["image"] = $image;
				$dataArray["status"] = !empty($status) ?  "Active" : "Inactive";
				$dataArray["published"] = !empty($published) ? date('d-m-Y', strtotime($published)) : '';
				$dataArray["created"] = !empty($created) ? date('d-m-Y H:i A', strtotime($created)) : '';
				$dataArray["updated"] = !empty($updated) ? date('d-m-Y H:i A', strtotime($updated)) : '';
				$resultArray["rows"][] = $dataArray;
			}
			$stmt->free_result();
		}

		$stmt->close();

		parent::disconnect($mysql);

		/* Page Load Select Example Start */
		return $resultArray;
		/* Page Load Select Example End */
	}

	protected function post_update_mdl() {

		$mysql = parent::connect();

		$resultArray = array();

		if (!isset($this->csrf_token) || $this->csrf_token !== common::getSession('csrf_token')) {
			http_response_code(403);
			
			$resultArray["msg"] = "Invalid CSRF token";
		} else {

			$published = date('Y-m-d');
			if(!empty($this->published)) {
				$published = date('Y-m-d', strtotime($this->published));
			}
			if(!empty($this->image)) {
				$stmt = $mysql->prepare("UPDATE posts SET `user_id` = ?, `category_id` = ?, `title` = ?, `description` = ?, `image` = ?, `published` = ? WHERE `id` = ?");
	
				$stmt->bind_param('iissssi', $this->user_id, $this->category_id, $this->title, $this->description, $this->image, $published, $this->id);
			} else {
				$stmt = $mysql->prepare("UPDATE posts SET `user_id` = ?, `category_id` = ?, `title` = ?, `description` = ?, `published` = ? WHERE `id` = ?");
	
				$stmt->bind_param('iisssi', $this->user_id, $this->category_id, $this->title, $this->description, $published, $this->id);
			}
			

			$stmt->execute();

			$stmt->close();

			$resultArray["isSuccess"] = 1;

			parent::disconnect($mysql);
		}
		
		/* Ajax Select Example Start */
		common::sendJson($resultArray);
		/* Ajax Select Example End */

		/* Page Load Select Example Start */
		return $resultArray;
		/* Page Load Select Example End */
	}

	protected function post_delete_mdl() {

		$mysql = parent::connect();

		$resultArray = array();

		$stmt = $mysql->prepare("DELETE FROM posts WHERE id = ?");

		$stmt->bind_param('i', $this->id);

		$stmt->execute();

		$stmt->close();

		$resultArray["isSuccess"] = 1;

		parent::disconnect($mysql);
		
		/* Ajax Select Example Start */
		common::sendJson($resultArray);
		/* Ajax Select Example End */

		/* Page Load Select Example Start */
		return $resultArray;
		/* Page Load Select Example End */
	}
}
