<?php

session_start();

if (isset($_GET["uvas"])){
	$_SESSION["uvas"] = "Puedo Ver Esto";	
}

echo "<pre>";
var_export( $_SESSION );
echo "</pre>";


?>