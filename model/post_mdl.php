<?php
include_once 'config.php';

class post_mdl extends config
{
	protected $id = null;
	protected $page_no = null;
	protected $search = null;
	protected $title = null;
	protected $description = null;
	protected $image = null;
	protected $user_id = null;
	protected $category_id = null;
	protected $published = null;
	protected $commentid = null;
	protected $userid = null;
	protected $postid = null;
	protected $name = null;
	protected $email = null;
	protected $comment = null;
	protected $is_admin = null;
	protected $post_id = null;
	protected $csrf_token = null;

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
		$resultArray["total_pages"] = 1;

		$result_per_page = common::POST_LIST_LIMIT;
		$page_no = 1;
		if(!empty($this->page_no)) {
			$page_no = $this->page_no;
		}
		$resultArray["result_per_page"] = $result_per_page;
		$current_page_result = ($page_no - 1) * $result_per_page;

		$serch_where = ' 1 = 1 ';
		if (!empty($this->search)) {
			$serch_where .= " AND (p.`title` LIKE CONCAT('%', ?, '%') OR p.`description` LIKE CONCAT('%', ?, '%'))";
		}
		$resultArray["search"] = $this->search;

		$stmt = $mysql->prepare("SELECT p.id, p.`user_id`, u.name as author, p.`category_id`, p.`title`, p.`description`, p.`image`, p.`status`, p.`published`, p.`created`, p.`updated` FROM `posts` as p INNER JOIN users as u ON u.id = p.user_id WHERE (p.published IS NULL || p.published <= NOW()) AND " . $serch_where."");

		if (!empty($this->search)) {
			$stmt->bind_param('ss', $this->search, $this->search);
		}

		$stmt->execute();

		$stmt->store_result();

		if ($stmt->num_rows > 0) {
			$resultArray["total_pages"] = ceil($stmt->num_rows / $result_per_page);
		}
		$stmt->close();

		$stmt = $mysql->prepare("SELECT p.id, p.`user_id`, u.name as author, p.`category_id`, p.`title`, p.`description`, p.`image`, p.`status`, p.`published`, p.`created`, p.`updated` FROM `posts` as p INNER JOIN users as u ON u.id = p.user_id WHERE (p.published IS NULL || p.published <= NOW()) AND " . $serch_where." LIMIT ?, ?");

		if (!empty($this->search)) {
			$stmt->bind_param('ssii', $this->search, $this->search, $current_page_result, $result_per_page);
		} else {	
			$stmt->bind_param('ii', $current_page_result, $result_per_page);
		}
		
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

		/* Page Load Select Example Start */
		return $resultArray;
		/* Page Load Select Example End */
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

	public function post_get_mdl() {
		$id = null;
		$user_id = null;
		$category_id = null;
		$author = null;
		$title = null;
		$description = null;
		$image = null;
		$status = null;
		$published = null;
		$mysql = parent::connect();

		$dataArray = array();
		
		$stmt = $mysql->prepare("SELECT p.id, p.`user_id`, u.name as author, p.`category_id`, p.`title`, p.`description`, p.`image`, p.`status`, p.`published` FROM `posts` as p INNER JOIN users as u ON u.id = p.user_id WHERE p.id = ?");

		$stmt->bind_param('i', $this->id);

		$stmt->execute();

		$stmt->store_result();

		if ($stmt->num_rows > 0) {
			$stmt->bind_result($id, $user_id, $author, $category_id, $title, $description, $image, $status, $published);

			while ($stmt->fetch()) {
				$catearr = $this->getCategoryHierarchy($id, $mysql);
				$catstr = implode(" . ", $catearr);
				$this->post_id = $id;
				$comments = $this->get_post_comments();
				$dataArray["id"] = $id;
				$dataArray["user_id"] = $user_id;
				$dataArray["author"] = $author;
				$dataArray["category_id"] = $category_id;
				$dataArray["category_list"] = $catstr;
				$dataArray["title"] = $title;
				$dataArray["description"] = $description;
				$dataArray["image"] = $image;
				$dataArray["status"] = !empty($status) ?  "Active" : "Inactive";
				$dataArray["published"] = $published;
				$dataArray["comments"] = $comments;
			}
			$stmt->free_result();
		}

		$stmt->close();

		parent::disconnect($mysql);

		/* Page Load Select Example Start */
		return $dataArray;
		/* Page Load Select Example End */
	}

	private function get_post_comments() {
		$id = null;
		$post_id = null;
		$comment_id = null;
		$user_id = null;
		$author = null;
		$comment = null;
		$published = null;
		$created = null;
		$mysql = parent::connect();

		$resultArray = array();
		
		$stmt = $mysql->prepare("SELECT c.id, c.`post_id`, c.`comment_id`, c.`user_id`, u.name as author, c.`comment`, c.published, c.created FROM `comments` as c INNER JOIN users as u on u.id = c.user_id WHERE c.post_id = ?");

		$stmt->bind_param('i', $this->post_id);

		$stmt->execute();

		$stmt->store_result();

		if ($stmt->num_rows > 0) {
			$stmt->bind_result($id, $post_id, $comment_id, $user_id, $author, $comment, $published, $created);

			while ($stmt->fetch()) {
				$dataArray["id"] = $id;
				$dataArray["post_id"] = $post_id;
				$dataArray["comment_id"] = $comment_id;
				$dataArray["user_id"] = $user_id;
				$dataArray["author"] = $author;
				$dataArray["comment"] = $comment;
				if(!empty($published)) {
					$dates = date('F j, Y', strtotime($published)); 
				} else {
					$dates = date('F j, Y', strtotime($created));
				}
				$dataArray["published"] = $dates;
				$resultArray[] = $dataArray;
			}
			$stmt->free_result();
		}

		$stmt->close();

		parent::disconnect($mysql);

		/* Page Load Select Example Start */
		return $resultArray;
		/* Page Load Select Example End */
	}

	public function post_categories_mdl() {
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
		$resultArray["total_pages"] = 1;

		$result_per_page = common::POST_LIST_LIMIT;
		$page_no = 1;
		if(!empty($this->page_no)) {
			$page_no = $this->page_no;
		}
		
		$resultArray["result_per_page"] = $result_per_page;
		$current_page_result = ($page_no - 1) * $result_per_page;

		$stmt = $mysql->prepare("SELECT p.id, p.`user_id`, u.name as author, p.`category_id`, p.`title`, p.`description`, p.`image`, p.`status`, p.`published`, p.`created`, p.`updated` FROM `posts` as p INNER JOIN users as u ON u.id = p.user_id WHERE (p.published IS NULL || p.published <= NOW()) AND p.category_id = ?");

		$stmt->bind_param('i', $this->category_id);

		$stmt->execute();

		$stmt->store_result();

		if ($stmt->num_rows > 0) {
			$resultArray["total_pages"] = ceil($stmt->num_rows / $result_per_page);
		}
		$stmt->close();

		$stmt = $mysql->prepare("SELECT p.id, p.`user_id`, u.name as author, p.`category_id`, p.`title`, p.`description`, p.`image`, p.`status`, p.`published`, p.`created`, p.`updated` FROM `posts` as p INNER JOIN users as u ON u.id = p.user_id WHERE (p.published IS NULL || p.published <= NOW()) AND p.category_id = ? LIMIT ?, ?");
	
		$stmt->bind_param('iii', $this->category_id, $current_page_result, $result_per_page);
		
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

		/* Page Load Select Example Start */
		return $resultArray;
		/* Page Load Select Example End */
	}

	protected function post_add_comment_mdl()
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
				
				$stmt = $mysql->prepare("INSERT INTO users (`role`, `name`, `email`, `password`, `status`) VALUES(?, ?, ?, ?, ?)");
				$role = 3;
				$status = 0;
				$password = password_hash("123456", PASSWORD_BCRYPT);
				$stmt->bind_param('ssssi', $role, $this->name, $this->email, $password, $status);

				$stmt->execute();

				$this->userid = $mysql->insert_id;
						
				$stmt->free_result();
			} else {
				$stmt->bind_result($id, $email);

				$stmt->fetch();

				$this->userid = $id;
			}
			if(!empty($this->userid)) {
				$stmt = $mysql->prepare("INSERT INTO comments (`post_id`, `comment_id`, `user_id`, `comment`) VALUES(?, ?, ?, ?)");

				$stmt->bind_param('iiis', $this->postid, $this->commentid, $this->userid, $this->comment);

				$stmt->execute();

				$stmt->close();

				$resultArray["isSuccess"] = 1;
				$resultArray["msg"] = "Comment added successfully after review it will be displayed";

			}
			
			parent::disconnect($mysql);
		}

		/* Ajax Select Example Start */
		common::sendJson($resultArray);
		/* Ajax Select Example End */

		/* Page Load Select Example Start */
		return $resultArray;
		/* Page Load Select Example End */
	}
}
