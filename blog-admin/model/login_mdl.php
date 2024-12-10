<?php
include_once '../model/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class login_mdl extends config
{
	protected $id = null;
	protected $email = null;
	protected $password = null;
	protected $token = null;
	protected $csrf_token = null;

	protected function login_select_mdl()
	{
		$id = null;
		$username = null;
		$email = null;
		$password = null;
		$status = null;
		$role = null;
		$mysql = parent::connect();

		$resultArray = array();
		
		if (!isset($this->csrf_token) || $this->csrf_token !== common::getSession('csrf_token')) {
			http_response_code(403);
			
			$resultArray["msg"] = "Invalid CSRF token";
		} else {
			$stmt = $mysql->prepare("SELECT `id`, `name`, `email`, `password`, `status`, `role` FROM `users` WHERE email = ?");

			$stmt->bind_param('s', $this->email);

			$stmt->execute();

			$stmt->store_result();

			if ($stmt->num_rows > 0) {
				$stmt->bind_result($id, $name, $email, $password, $status, $role);

				while ($stmt->fetch()) {

					if (password_verify($this->password, $password)) {
						$resultArray["isSuccess"] = 1;
						$resultArray["msg"] = "Login successful";
						$dataArray["id"] = $id;
						$dataArray["name"] = $name;
						$dataArray["email"] = $email;
						$dataArray["status"] = $status;
						$dataArray["role"] = $role;
						common::setSession(common::ADMIN_SESSION, json_encode($dataArray));
					} else {
						$resultArray["msg"] = "Invalid credentials";
					}
				}
				$stmt->free_result();
			} else {
				$resultArray["msg"] = "Invalid credentials";
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

	protected function forgot_password_mdl()
	{
		$id = null;
		$username = null;
		$email = null;
		$status = null;
		$role = null;
		$mysql = parent::connect();

		$resultArray = array();
		
		if (!isset($this->csrf_token) || $this->csrf_token !== common::getSession('csrf_token')) {
			http_response_code(403);
			
			$resultArray["msg"] = "Invalid CSRF token";
		} else {
			$stmt = $mysql->prepare("SELECT `id`, `name`, `email`, `password`, `status`, `role` FROM `users` WHERE email = ?");

			$stmt->bind_param('s', $this->email);

			$stmt->execute();

			$stmt->store_result();

			if ($stmt->num_rows > 0) {
				$stmt->bind_result($id, $name, $email, $password, $status, $role);

				while ($stmt->fetch()) {

					$forgot_pass = md5($this->email.time());

					require '../vendor/autoload.php';

					$mail = new PHPMailer(true);

					try {
						$mail->isSMTP();
						$mail->Host = 'sandbox.smtp.mailtrap.io'; 
						$mail->SMTPAuth = true;
						$mail->Username = '604aa1cfe2373c'; 
						$mail->Password = '28ededac7efc0b';
						$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
						$mail->Port = 587;
					
						$mail->setFrom('info@sahajcreativezone.com', 'Sahaj Creative Zone');
						$mail->addAddress($email, $name);
					
						$mail->isHTML(true);
						$mail->Subject = 'Password Reset Link';
						$mail->Body    = "
						<html>
						<head>
							<title>Password Reset Link</title>
						</head>
						<body>
							<h1>Reset Password</h1>
							<p>Please reset password click on below link</p>
							<a href='".common::ADMIN_SITE_URL."resetpassword.php?token=".$forgot_pass."'>Click Here</a>
						</body>
						</html>
						";
					
						$mail->send();

						$resultArray["isSuccess"] = 1;
						$resultArray["msg"] = "A password reset link has been sent to your email. Please check your inbox.";
						$stmt1 = $mysql->prepare("UPDATE users SET `forgot_pass` = ? WHERE id = ?");

						$stmt1->bind_param('si', $forgot_pass,  $id);

						$stmt1->execute();

					} catch (Exception $e) {
						echo "Failed to send email. Mailer Error: {$mail->ErrorInfo}";
						$resultArray["msg"] = "Problem in mail sending please try again";
					}
				}
				$stmt->free_result();
			} else {
				$resultArray["msg"] = "User not registered with us";
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

	protected function reset_password_mdl()
	{
		$id = null;
		$username = null;
		$email = null;
		$status = null;
		$role = null;
		$mysql = parent::connect();

		$resultArray = array();
		
		if (!isset($this->csrf_token) || $this->csrf_token !== common::getSession('csrf_token')) {
			http_response_code(403);
			
			$resultArray["msg"] = "Invalid CSRF token";
		} else {
			$stmt = $mysql->prepare("SELECT `id`, `name`, `email`, `password`, `status`, `role` FROM `users` WHERE forgot_pass = ?");

			$stmt->bind_param('s', $this->token);

			$stmt->execute();

			$stmt->store_result();

			if ($stmt->num_rows > 0) {
				$stmt->bind_result($id, $name, $email, $password, $status, $role);

				while ($stmt->fetch()) {

					$password = password_hash($this->password, PASSWORD_BCRYPT);
					$stmt1 = $mysql->prepare("UPDATE users SET `password` = ?, `forgot_pass` = '' WHERE id = ?");

					$stmt1->bind_param('si', $password, $id);

					$stmt1->execute();
				}
				$stmt->free_result();
			} else {
				$resultArray["msg"] = "Token mismatch please try again";
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
}
