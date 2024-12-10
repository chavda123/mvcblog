<?php
include_once '../model/config.php';

class category_mdl extends config
{
	protected $id = null;
	protected $category = null;
	protected $parent = null;
	protected $csrf_token = null;

	protected function category_insert_mdl()
	{
		$mysql = parent::connect();

		$resultArray = array();

		if (!isset($this->csrf_token) || $this->csrf_token !== common::getSession('csrf_token')) {
			http_response_code(403);
			
			$resultArray["msg"] = "Invalid CSRF token";
		} else {

			$stmt = $mysql->prepare("SELECT `id`, `name` FROM `categories` WHERE `name` = ?");

			$stmt->bind_param('s', $this->category);

			$stmt->execute();

			$stmt->store_result();	

			if ($stmt->num_rows == 0) {
				
				$stmt = $mysql->prepare("INSERT INTO categories (`name`, `parent`) VALUES(?, ?)");

				$stmt->bind_param('si', $this->category, $this->parent);

				$stmt->execute();

				$resultArray["isSuccess"] = 1;
				$resultArray["msg"] = "Category added successfully";
						
				$stmt->free_result();
			} else {
				$resultArray["msg"] = "Category already exists";
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

	public function category_select_mdl() {
		$id = null;
		$name = null;
		$parent = null;
		$mysql = parent::connect();

		$resultArray = array();
		

		$stmt = $mysql->prepare("SELECT ca.`id`, ca.`name`, cat.name as parent FROM `categories` as ca LEFT JOIN `categories` as cat on cat.id = ca.parent");

		$stmt->execute();

		$stmt->store_result();

		if ($stmt->num_rows > 0) {
			$stmt->bind_result($id, $name, $parent);

			$resultArray["isSuccess"] = 1;

			while ($stmt->fetch()) {
				$dataArray["id"] = $id;
				$dataArray["name"] = $name;
				$dataArray["parent"] = $parent;
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

	public function category_select_id_mdl() {
		$id = null;
		$name = null;
		$parent = null;
		$mysql = parent::connect();

		$dataArray = array();
		
		$stmt = $mysql->prepare("SELECT `id`, `name`, parent  FROM `categories` WHERE id = ?");

		$stmt->bind_param('i', $this->id);

		$stmt->execute();

		$stmt->store_result();

		if ($stmt->num_rows > 0) {
			$stmt->bind_result($id, $name, $parent);

			while ($stmt->fetch()) {
				$dataArray["id"] = $id;
				$dataArray["name"] = $name;
				$dataArray["parent"] = $parent;
			}
			$stmt->free_result();
		}

		$stmt->close();

		parent::disconnect($mysql);

		/* Page Load Select Example Start */
		return $dataArray;
		/* Page Load Select Example End */
	}

	protected function category_update_mdl() {

		$mysql = parent::connect();

		$resultArray = array();

		if (!isset($this->csrf_token) || $this->csrf_token !== common::getSession('csrf_token')) {
			http_response_code(403);
			
			$resultArray["msg"] = "Invalid CSRF token";
		} else {

			$stmt = $mysql->prepare("UPDATE categories SET `name` = ?, parent = ? WHERE id = ?");

			$stmt->bind_param('ssi', $this->category, $this->parent, $this->id);

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

	protected function category_delete_mdl() {

		$mysql = parent::connect();

		$resultArray = array();

		$stmt = $mysql->prepare("SELECT `id` FROM `posts` WHERE category_id = ?");

		$stmt->bind_param('i', $this->id);

		$stmt->execute();

		$stmt->store_result();

		if ($stmt->num_rows == 0) {

			$stmt = $mysql->prepare("DELETE FROM categories WHERE id = ?");

			$stmt->bind_param('i', $this->id);

			$stmt->execute();

			$stmt->close();

			$resultArray["isSuccess"] = 1;
		} else {
			$resultArray["msg"] = "Category have one post so it cannot be deleted";
		}

		parent::disconnect($mysql);
		
		/* Ajax Select Example Start */
		common::sendJson($resultArray);
		/* Ajax Select Example End */

		/* Page Load Select Example Start */
		return $resultArray;
		/* Page Load Select Example End */
	}
}
