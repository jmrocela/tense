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