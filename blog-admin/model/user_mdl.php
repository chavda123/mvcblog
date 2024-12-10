<?php
include_once '../model/config.php';

class user_mdl extends config
{
	protected $id = null;
	protected $role = null;
	protected $name = null;
	protected $email = null;
	protected $password = null;
	protected $status = null;
	protected $csrf_token = null;

	protected function user_insert_mdl()
	{
		$mysql = parent::connect();

		$resultArray = array();

		if (!isset($this->csrf_token) || $this->csrf_token !== common::getSession('csrf_token')) {
			http_response_code(403);
			
			$resultArray["msg"] = "Invalid CSRF token";
		} else {

			$stmt = $mysql->prepare("SELECT `id`, `email` FROM `users` WHERE `email` = ?");

			$stmt->bind_param('s', $this->email);

			$stmt->execute();

			$stmt->store_result();	

			if ($stmt->num_rows == 0) {
				
				$stmt = $mysql->prepare("INSERT INTO users (`role`, `name`, `email`, `password`) VALUES(?, ?, ?, ?)");

				$password = password_hash($this->password, PASSWORD_BCRYPT);
				$stmt->bind_param('ssss', $this->role, $this->name, $this->email, $password);

				$stmt->execute();

				$resultArray["isSuccess"] = 1;
				$resultArray["msg"] = "User added successfully";
						
				$stmt->free_result();
			} else {
				$resultArray["msg"] = "User already exists";
			}

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

	public function user_select_id_mdl() {
		$id = null;
		$role = null;
		$name = null;
		$email = null;
		$mysql = parent::connect();

		$dataArray = array();
		
		$stmt = $mysql->prepare("SELECT `id`, `role`, `name`, `email`  FROM `users` WHERE id = ?");

		$stmt->bind_param('i', $this->id);

		$stmt->execute();

		$stmt->store_result();

		if ($stmt->num_rows > 0) {
			$stmt->bind_result($id, $role, $name, $email);

			while ($stmt->fetch()) {
				$dataArray["id"] = $id;
				$dataArray["role"] = $role;
				$dataArray["name"] = $name;
				$dataArray["email"] = $email;
			}
			$stmt->free_result();
		}

		$stmt->close();

		parent::disconnect($mysql);

		/* Page Load Select Example Start */
		return $dataArray;
		/* Page Load Select Example End */
	}

	public function user_select_mdl() {
		$id = null;
		$name = null;
		$email = null;
		$status = null;
		$created = null;
		$mysql = parent::connect();

		$resultArray = array();

		$stmt = $mysql->prepare("SELECT `id`, `name`, `email`, `status`, `created` FROM `users`");

		$stmt->execute();

		$stmt->store_result();

		if ($stmt->num_rows > 0) {
			$stmt->bind_result($id, $name, $email, $status, $created);

			$resultArray["isSuccess"] = 1;

			while ($stmt->fetch()) {
				$dataArray["id"] = $id;
				$dataArray["name"] = $name;
				$dataArray["email"] = $email;
				$dataArray["status"] = !empty($status) ?  "Active" : "Inactive";
				$dataArray["status_num"] = $status;
				$dataArray["created"] = date('d-m-Y H:i A', strtotime($created));
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

	protected function user_update_mdl() {

		$mysql = parent::connect();

		$resultArray = array();

		if (!isset($this->csrf_token) || $this->csrf_token !== common::getSession('csrf_token')) {
			http_response_code(403);
			
			$resultArray["msg"] = "Invalid CSRF token";
		} else {

			if(!empty($this->password)) {
				$stmt = $mysql->prepare("UPDATE users SET `role` = ?, `name` = ?, `email` = ?, `password` = ? WHERE id = ?");
				$password = password_hash($this->password, PASSWORD_BCRYPT);
				$stmt->bind_param('ssssi', $this->role, $this->name, $this->email, $password, $this->id);
			} else {
				$stmt = $mysql->prepare("UPDATE users SET `role` = ?, `name` = ?, `email` = ? WHERE id = ?");
				$stmt->bind_param('sssi', $this->role, $this->name, $this->email, $this->id);
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

	protected function user_delete_mdl() {

		$mysql = parent::connect();

		$resultArray = array();

		$stmt = $mysql->prepare("SELECT `id` FROM `posts` WHERE user_id = ?");

		$stmt->bind_param('i', $this->id);

		$stmt->execute();

		$stmt->store_result();

		if ($stmt->num_rows == 0) {

			$stmt = $mysql->prepare("DELETE FROM users WHERE id = ?");

			$stmt->bind_param('i', $this->id);

			$stmt->execute();

			$stmt->close();

			$resultArray["isSuccess"] = 1;
		} else {
			$resultArray["msg"] = "User have one post so it cannot be deleted";
		}

		parent::disconnect($mysql);
		
		/* Ajax Select Example Start */
		common::sendJson($resultArray);
		/* Ajax Select Example End */

		/* Page Load Select Example Start */
		return $resultArray;
		/* Page Load Select Example End */
	}

	protected function user_inactive_mdl() {

		$mysql = parent::connect();

		$resultArray = array();

		$stmt = $mysql->prepare("UPDATE users SET `status` = ? WHERE id = ?");

		$stmt->bind_param('ii', $this->status, $this->id);

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
