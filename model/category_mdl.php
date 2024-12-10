<?php
include_once 'config.php';

class category_mdl extends config
{
	protected $id = null;
	protected $category = null;
	protected $parent = null;
	protected $csrf_token = null;

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
}
