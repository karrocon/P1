<?php
	// 0. PRELIMINARY SETTINGS

	// 0.1 Load auxiliary PHP Packages
	require("php/lib/Helper.php");

	// 0.2 Start session
	session_start();

	// 0.3 Set desired language
	$langs = array(
		'es',
		'es-ES',
		'en',
		'en-US',
	    'fr',
	    'fr-FR',
	    'de',
	    'de-DE',
	    'de-AT',
	    'de-CH',
	);
	$_SESSION["lang"] = isset($_SESSION["lang"]) ? $_SESSION["lang"] : Helper::select_prefered_language($langs);

	// 1. CREATE HTML DOCUMENT

	// 1.1 Set document type
	echo("<!DOCTYPE html>\n");

	echo("\n");

	// 1.2 Set <html> with user-desired language
	echo("<html lang=\"" . $_SESSION["lang"] . "\">\n");

	// 1.3 Set <head>
	echo("\t<head>\n");

	// 1.4 Load metadata
	echo("\t\t<meta charset=\"utf-8\">\n");
	echo("\t\t<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n");
	echo("\t\t<meta name=\"description\" content=\"\">\n");
	echo("\t\t<meta name=\"author\" content=\"\">\n");

	echo("\n");

	// 1.5 Load favicons
	echo("\t\t<link rel=\"shortcut icon\" href=\"../assets/ico/favicon.ico\">\n");
	echo("\t\t<link rel=\"apple-touch-icon-precomposed\" sizes=\"144x144\" href=\"../assets/ico/apple-touch-icon-144-precomposed.png\">\n");
	echo("\t\t<link rel=\"apple-touch-icon-precomposed\" sizes=\"114x114\" href=\"../assets/ico/apple-touch-icon-114-precomposed.png\">\n");
	echo("\t\t<link rel=\"apple-touch-icon-precomposed\" sizes=\"72x72\" href=\"../assets/ico/apple-touch-icon-72-precomposed.png\">\n");
	echo("\t\t<link rel=\"apple-touch-icon-precomposed\" href=\"../assets/ico/apple-touch-icon-57-precomposed.png\">\n");
	
	echo("\n");

	// 1.6 Load shared style sheets (CSS)
	echo("\t\t<link href='css/lib/bootstrap.min.css' rel='stylesheet'>\n");
	echo("\t\t<link href='css/lib/bootstrap-responsive.css' rel='stylesheet'>\n");
	
	echo("\n");

	// 1.7 Load shared scripts (JS)
	echo("\t\t<script src='js/lib/jquery-1.8.2.min.js'></script>\n");
	echo("\t\t<script src='js/lib/bootstrap.min.js'></script>\n");
	echo("\t\t<script src='js/lib/jquery-ui-1.9.1.custom.min.js'></script>\n");
	echo("\t\t<!--[if lte IE 8]><script src='js/lib/excanvas.min.js'></script><![endif]-->\n");
	echo("\t\t<script src='js/lib/jquery.flot.js'></script>\n");

	echo("\n");

	// 1.8 Check desired action
	$action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : "main";
	
	// 1.9 Check if session is started
	$action = isset($_SESSION["user"]) ? ($action == "access" ? "main" : $action) : "access";

	// 1.10 Check valid action
	switch($action){
		case "access":
			$view = "access";
			break;
		case "preferences":
			$view = "preferences";
			break;
		case "main":
		default:
			$view = "main";
	}

	// 1.11 Set web title
	echo("\t\t<title>Simple Online OS</title>\n");
	
	echo("\n");

	// 1.12 Load custom style sheet (CSS) and script (JS) for chosen view
	echo("\t\t<link href='css/" . $view . ".css' rel='stylesheet'>\n");
	echo("\t\t<script>$.getScript('js/" . $view . ".js');</script>\n");

	// 1.13 Close <head>
	echo("\t</head>\n");

	// 1.14 Begin <body>
	echo("\t<body>\n");

	// 1.15 Load view structure (HTML)
	require("html/" . $view . ".html");

	// 1.16 Close <body>
	echo("\n\t</body>\n");

	// 1.17 Close <html>
	echo("</html>");