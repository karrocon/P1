<?php
	$do = isset($_REQUEST["do"]) ? $_REQUEST["do"] : "init";
	
	switch($do){
		case "lang":
			changeLang();
			break;
		case "login":
			login();
			break;
		case "register":
			register();
			break;
		case "verify":
			verify();
			break;
		case "init":
		default:
			init();
			break;
	}
	
	function changeLang(){
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Content-type: application/json; charset=iso-8859-1');
		
		require("../json/dicts.json");
	}
	
	function login(){
		/* 
		 * @DESCRIPTION Se encarga de comprobar si el acceso a la web es válido comprobando las credenciales en la base de datos.
		 * 
		 * @ECHO 1 si el acceso fue satisfactorio y un número negativo indicando si hubo algún problema (-1000: Error al conectar con la base de datos; -1001: Error inesperado en consultas SQL; -1: Credenciales erróneas; -2: Cuenta inactiva; -3: Usuario no registrado).
		 *
		 */
		require("lib/PasswordHash.php");
		require_once("../config.php");
	
		$email = $_POST["email"];
		$password = $_POST["password"];
		$remember = isset($_POST["remember"]) ? $_POST["remember"] : 'off';
		
		$pwdHasher = new PasswordHash(8, FALSE);
		
		$db = new mysqli($db_host, $db_user, $db_pass, $db_name) or die("-1000");
		
		$result = $db->query("SELECT id, email, hash, active FROM soos_users WHERE email='" . $email . "';");
		
		if($result){
			$row = $result->fetch_array();
			
			$output = (isset($row[0]) ? ($pwdHasher->CheckPassword($password, $row["hash"]) ? ($row["active"] ? "1" : "-2") : "-1") : "-3");
			
			if($output == "1"){
				session_start();
				
				$_SESSION["user"] = $row["id"];
				//$_SESSION["hash"] = md5($row["hash"]);
			}
			
			echo $output;
			
			$result->close();
		}else{
			echo "-1001";
		}

		$db->close();
	}
	
	function register(){
		require("lib/PasswordHash.php");
		require_once("../config.php");
		
		$password = $_POST["password"];
		$confirm_password = $_POST["confirm_password"];
		$email = $_POST["email"];
		
		$pwdHasher = new PasswordHash(8, FALSE);
		
		$db = new mysqli($db_host, $db_user, $db_pass, $db_name);
		
		if ($db->connect_errno){
			die('Could not connect: ' . $db->connect_errno);
		}
		
		$db->autocommit(FALSE);
		
		$hash = $pwdHasher->HashPassword($password);
		
		if (!$db->query("INSERT INTO soos_users(hash, email) VALUES('" . $hash . "', '" . $email . "');")){
			die("failed (unexpected insert() error");
		}
		
		// TODO. Generalizar el link para un servidor mirando la ruta con PHP
		$activation_link = "http://www.soos.net76.net/simplify/verify.php?email=" . $email . "&code=" . md5($email);
		
		$subject = "Account confirmation";
		$body = "
		<html>
			<body>
				<p>
					Dear " . $email . ",
				</p>
				<p>
					thanks for signing up to <a href=http://soos.net76.net>SooS.net76.net</a>!
				</p>
				<p>
					To activate your account please click the following link:
					<br />
					<a href=" . $activation_link . ">" . $activation_link . "</a>
				</p>
				<p>
					Feel free to use the support ticket system to contact us if you have any questions.
				</P>
				<p>
					Sincerely,
					<br />
					The SooS Team.
				</p>
			</body>
		</html>
		";
		
		// TODO. Comprobar lo del charset y mirar si se puede hacer algo para aquellos clientes que no dispongan de html en su correo
		$headers = "MIME-Version: 1.0" . "\r\n" . "Content-type: text/html; charset=iso-8859-1" . "\r\n" . "From: <SooS Team> no-reply@soos.net76.net" . "\r\n" . "X-Mailer: PHP/" . phpversion();
		
		if (!mail($email, $subject, $body, $headers)){
			die("failed (unexpected mail() error)");
		}else{
			$db->commit();
			echo "success";
		}
		
		$db->close();
	}
	
	function verify(){
		require_once("../config.php");
		
		$db = new mysqli($db_host, $db_user, $db_pass, $db_name) or die($db->connect_errno);
		
		if(isset($_GET["email"]) && !empty($_GET["email"]) AND isset($_GET["code"]) && !empty($_GET["code"])){
			echo ("\n\n\t\t<input id='hidden_verify_msg' type='hidden' tag='");
			
			$result = $db->query("SELECT email, active FROM soos_users WHERE email='" . $_GET["email"] . "';") or die($db->error);
			
			if($result->num_rows == 0){
				// TODO. Error inesperado ya que ese correo no existe
				echo ("Activation failed (wrong email).");
			}else{
				$row = $result->fetch_array();
				
				$result->close();
				
				if($_GET["code"] == md5($row["email"])){
					if($db->query("UPDATE soos_users SET active='1' WHERE email='" . $row["email"] . "';")){
						// TODO. Mostrar mensaje de cuenta activada
						echo ("Account was activated succesfully.");
					}else{
						// TODO. Error inesperado al hacer insert
						echo ("Activation failed (unexpected error).");
					}
				}else{
					// TODO. Código de activación erróneo
					echo ("Activation failed (wrong code).");
				}
			}
			
			echo("'>");
		}
		
		$db->close();
	}
	
	function init(){
		
	}
?>