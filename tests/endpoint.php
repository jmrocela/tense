<?php

if (isset($_GET['controller']) && isset($_GET['action'])) {
	switch ($_GET['controller']) {
		case "working":
			header("HTTP/1.0 200 OK");
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
				case "notjson":
					echo 'This is not a JSON response';
				break;
				case "withstatuscode403":
					header("HTTP/1.0 403 Forbidden");
					die();
				break;
				case "withstatuscode401":
					header("HTTP/1.0 404 Unauthorized");
					die();
				break;
				case "withstatuscode405":
					header("HTTP/1.0 405 Method Not Allowed");
					die();
				break;
			}
		break;
	}
}

?>