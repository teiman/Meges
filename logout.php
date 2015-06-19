<?php
include ("tool.php");

setcookie("auth","el usuario ha salido",time()-1);  /* expire in 1 hour */

foreach ($_SESSION as $key => $value) {
	$_SESSION[$key] = false;
	session_unregister($key);	
}

session_unset();//Borrado propiamente dicho

setcookie("auth","el usuario ha salido",time()-1);  /* expire in 1 hour */

$cookiesSet = array_keys($_COOKIE);
for ($x = 0; $x < count($cookiesSet); $x++) {
   if (is_array($_COOKIE[$cookiesSet[$x]])) {
       $cookiesSetA = array_keys($_COOKIE[$cookiesSet[$x]]);
       for ($c = 0; $c < count($cookiesSetA); $c++) {
           $aCookie = $cookiesSet[$x].'['.$cookiesSetA[$c].']';
           setcookie($aCookie,"",time()-1);
       }
   }
   setcookie($cookiesSet[$x],"",time()-1);
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD>
<script>
document.location="index.php";
</script>
</HEAD>
<BODY></BODY></HTML>
