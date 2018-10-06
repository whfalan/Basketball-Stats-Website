<?php
// Setup file

#Database conection :
$dbc = mysqli_connect('localhost', 'dev', 'dev', 'bball') 
OR die('ERROR: '.mysqli_connect_error());


$bbal_dbc = mysqli_connect('localhost', 'root', 'root', 'nbastats') 
OR die('ERROR: '.mysqli_connect_error());



# Constants:
DEFINE('D_TEMPLATE', 'template');

#FUNCTIONS :
include('functions/data.php');

$site_title = 'BBall';


if (isset($_GET['page'])) {
	$pageid = $_GET['page']; // set $pageid to equal the value given in the url
}
else {
	$pageid = 1; //sets $pageid to 1 or the home page
}

if (isset($_GET['asthetic'])) {
	$asthetic = $_GET['asthetic']; 
}
else {
	$asthetic = 1; //sets to true default
}
#page setup

$page = data_page($dbc, $pageid);


?>