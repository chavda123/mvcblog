<?php
include_once '../model/config.php';

class posts extends config
{
    public function post_latest_mdl() {
		$id = null;
		$user_id = null;
		$author = null;
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
		$resultArray["rows"] = array();

		$stmt = $mysql->prepare("SELECT p.id, p.`user_id`, u.name as author, p.`category_id`, p.`title`, p.`description`, p.`image`, p.`status`, p.`published`, p.`created`, p.`updated` FROM `posts` as p INNER JOIN users as u ON u.id = p.user_id WHERE (p.published IS NULL || p.published <= NOW())");
		
		$stmt->execute();

		$stmt->store_result();
		
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($id, $user_id, $author, $category_id, $title, $description, $image, $status, $published, $created, $updated);

			$resultArray["isSuccess"] = 1;

			while ($stmt->fetch()) {
				$catearr = $this->getCategoryHierarchy($category_id, $mysql);
				$catstr = implode(" . ", $catearr);
				$dataArray["id"] = $id;
				$dataArray["user_id"] = $user_id;
				$dataArray["author"] = $author;
				$dataArray["category_id"] = $category_id;
				$dataArray["category_list"] = $catstr;
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

		/* Ajax Select Example Start */
		common::sendJson($resultArray);
		/* Ajax Select Example End */
	}

	public function getCategoryHierarchy($categoryId, $mysqli) {
		$hierarchy = [];
	
		while ($categoryId) {
			$sql = "SELECT name, parent FROM categories WHERE id = ?";
			$stmt = $mysqli->prepare($sql);
			$stmt->bind_param("i", $categoryId);
			$stmt->execute();
			$result = $stmt->get_result();
	
			if ($row = $result->fetch_assoc()) {
				array_unshift($hierarchy, $row['name']); // Prepend category name to the hierarchy
				$categoryId = $row['parent']; // Move to the parent category
			} else {
				break; // Stop if no more parent category exists
			}
	
			$stmt->close();
		}
	
		return $hierarchy;
	}
}