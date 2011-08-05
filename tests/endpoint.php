<?php

if (isset($_GET['controller']) && isset($_GET['action'])) {
	switch ($_GET['controller']) {
		case "working":
			header("HTTP/1.1 200 OK");
			header("Content-Type: text/json");
			switch ($_GET['action']) {
				case "noparams":
					echo json_encode(array(1));
				break;
				case "withparams":
					echo json_encode($_REQUEST);
				break;
				case "customheaders":
					echo json_encode(array('HTTP_X_TENSE_HEADER' => $_SERVER['HTTP_X_TENSE_HEADER'], 'HTTP_X_FOO' => $_SERVER['HTTP_X_FOO']));
				break;
				case "withreferer":
					echo json_encode(array('REFERER' => $_SERVER['HTTP_REFERER']));
				break;
				case "requireauthentication":
					if ($_SERVER['PHP_AUTH_USER'] == 'user' && $_SERVER['PHP_AUTH_PW'] == 'password') {
						header("HTTP/1.1 200 OK");
						echo json_encode(1);
					} else {
						header('WWW-Authenticate: Basic realm="Please Provide a Username and Password for authentication"');
						header('HTTP/1.1 401 Unauthorized');
						exit;
					}
				break;
			}
		break;
		case "broken":
			switch ($_GET['action']) {
				case "badrequest":
					header("HTTP/1.1 400 Bad Request");
					header("Content-Type: text/html");
					echo 'This is not a JSON response';
					die();
				break;
			}
		break;
	}
}

?>