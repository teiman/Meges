<?php


function getSesionDato($nombre){
	global $_SESSION;
	
	switch($nombre){
		default:	
			return $_SESSION[$nombre];		
	
	}	
}


function setSesionDato($slot,$valor) {	
	//global $_SESSION; < --- descomentar esta linea causa un bug, porque $_SESSION nunca es actualizado
	
	
	if (is_object($valor)){
	 	$_SESSION[$slot] = serialize($valor);
	 	return;		
	}	
	
	$_SESSION[$slot] = $valor;
}

function invalidarSesion($clase) {
	switch($clase){
		default:
			$_SESSION[$clase] =false;	
	} 
}






?>