<?php

function data_page($dbc, $id) {
	
	$q = "SELECT * FROM pages WHERE id = $id";
	$r = mysqli_query($dbc, $q);
	
	$page = mysqli_fetch_assoc($r);

	return $page;	
}

function data_team($dbc) {
	
	$q = "SELECT * FROM team";
	$r = mysqli_query($dbc, $q);
	
	$page = mysqli_fetch_assoc($r);

	return $page;	
}

?>