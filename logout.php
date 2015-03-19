<?php
session_start();
session_destroy();
require_once('atil/constants.php');
// unset($_SESSION['id']);
// unset($_SESSION['name']);
// unset($_SESSION['picUrl']);

// echo $_GET['keywords'];

switch ($_GET['uri']) {
	case 'createjoke':
    	header('Location: '.DOMAIN_URL.'createjoke.php');
    	break;
    case 'hotjokes':
    	header('Location: '.DOMAIN_URL.'hotjokes.php');
    	break;
    case "index":
        header('Location: '.DOMAIN_URL);
        break;
    case 'joke':
        header('Location: '.DOMAIN_URL.'joke.php?id='.$_GET['id']);
        break;
    case 'joker':
    	header('Location: '.DOMAIN_URL.'joker.php?id='.$_GET['id']);
    	break;
    case 'search':
    	header('Location: '.DOMAIN_URL.'search.php?keywords='.preg_replace('/\s+/', '+', $_GET['keywords']));
    	break;
    case 'topjokers':
        header('Location: '.DOMAIN_URL.'topjokers.php');
        break;
}
?>