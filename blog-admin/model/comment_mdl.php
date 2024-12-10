<?php
include_once '../model/config.php';

class comment_mdl extends config
{
	protected $id = null;
	protected $title = null;
	protected $description = null;
	protected $image = null;
	protected $user_id = null;
	protected $category_id = null;
	protected $published = null;
	protected $csrf_token = null;

	public function comment_select_mdl() {
		$id = null;
		$post_id = null;
		$post_title = null;
		$comment_id = null;
		$user_id = null;
		$author = null;
		$comment = null;
		$status = null;
		$published = null;
		$created = null;
		$mysql = parent::connect();

		$resultArray = array();

		$stmt = $mysql->prepare("SELECT c.id, c.`post_id`, p.title as post_title, c.`comment_id`, c.`user_id`, u.name as author, c.`comment`, c.`status`, c.`published`, c.`created` FROM `comments` as c INNER JOIN posts as p on p.id = c.post_id INNER JOIN users as u on u.id = c.user_id WHERE c.`status` = 0");

		$stmt->execute();

		$stmt->store_result();

		if ($stmt->num_rows > 0) {
			$stmt->bind_result($id, $post_id, $post_title, $comment_id, $user_id, $author, $comment, $status, $published, $created);

			$resultArray["isSuccess"] = 1;

			while ($stmt->fetch()) {
				$dataArray["id"] = $id;
				$dataArray["post_id"] = $post_id;
				$dataArray["post_title"] = $post_title;
				$dataArray["comment_id"] = $comment_id;
				$dataArray["user_id"] = $user_id;
				$dataArray["author"] = $author;
				$dataArray["comment"] = $comment;
				$dataArray["status"] = !empty($status) ?  "Active" : "Inactive";
				$dataArray["published"] = !empty($published) ? date('d-m-Y', strtotime($published)) : '';
				$dataArray["created"] = !empty($created) ? date('d-m-Y H:i A', strtotime($created)) : '';
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

	protected function comment_approve_mdl() {

		$mysql = parent::connect();

		$resultArray = array();
	
		$stmt = $mysql->prepare("UPDATE comments SET `status` = ?, `published` = ? WHERE `id` = ?");

		$status = 1;
		$published = date('Y-m-d');
		$stmt->bind_param('isi', $status, $published, $this->id);

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

	protected function comment_delete_mdl() {

		$mysql = parent::connect();

		$resultArray = array();

		$stmt = $mysql->prepare("DELETE FROM comments WHERE id = ?");

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
