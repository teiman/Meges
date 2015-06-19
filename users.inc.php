<?php


function GenerarJSUsers(){
	$sql = "SELECT IdUsuario,Nombre FROM men_usuarios WHERE Eliminado=0";
	$res = query($sql);
	if ($res) {
		while($row = Row($res)){
			$id = CleanID($row["IdUsuario"]);
			$nombre = addslashes(CleanParaXul(CleanNombre($row["Nombre"])));
			echo "xAddUser($id,'$nombre');\n";
		}
	}
}	
	
function CrearUsuario($nombre) {
	global $UltimaInsercion;
	//Comprobamos si es correcto crear usuario con este nombre
	$sql = "SELECT IdUsuario FROM men_usuarios WHERE (Nombre='$nombre') AND (Eliminado=0)";
	$row = queryrow($sql);
	if ($row and $row["IdUsuario"]>0){
		return false;//Ya existe usuario
	}
	
	$sql = "INSERT INTO men_usuarios ( Nombre) VALUES ('$nombre')";
	
	if(query($sql , "alta usuario")) {
		return $UltimaInsercion;
	}
	
	return 0;

}



function ModificarUsuario($IdUsuario, $NuevoNombre, $usuario, $pass,$esActivo,$esAdmin){	
	if (!$IdUsuario)	
		return false;
	
	$NuevoNombre = CleanNombre($NuevoNombre);	
	
	$sql = "UPDATE men_usuarios SET Nombre ='$NuevoNombre', usuario = '$usuario', pass = '$pass', esActivo='$esActivo', esAdmin='$esAdmin' WHERE IdUsuario = '$IdUsuario' ";
	
	return query($sql, "cambiando datos usuario");
}



function DatosUsuario($IdUsuario){
	$IdUsuario	= CleanID($IdUsuario);
	$sql 		= "SELECT * FROM men_usuarios WHERE IdUsuario ='$IdUsuario'";	
	return queryrow($sql);
}

function BorrarUsuario($id){
	$id = CleanID($id);
	$sql = "UPDATE men_usuarios SET Eliminado=1 WHERE IdUsuario='$id'";
	return query($sql);
}



switch($modo){
	case "borrar":
		$id = CleanID($_GET["id"]);
		
		if (!$id)
			echo "ERROR";
		else {
			BorrarUsuario($id);
			echo "OK=ok";
		}		
		exit();
		break;
	case "alta":
		$nombre = CleanNombre($_POST["nombre"]);

		if ($id = CrearUsuario($nombre))
			echo "OK=$id";
		else
			echo "ERROR";
		exit();
		break;
		
	case "update":
		$nombre	 	= CleanNombre($_POST["nombre"]);
		$usuario	= CleanNombre($_POST["usuario"]);
		$pass	 	= CleanNombre($_POST["pass"]);
		$esActivo 	= CleanInt($_POST["activo"]);
		$esAdmin	= CleanInt($_POST["admin"]);
		
		$IdUsuario 	= CleanID($_POST["IdUsuario"]);		
		if ( ModificarUsuario( $IdUsuario, $nombre, $usuario, $pass,$esActivo, $esAdmin) ){
			echo "OK=$Idusuario";
		} else {
			echo "ERROR";
		}
		exit();
		break;
	
	case "CargarUsuario":
		$id 		= CleanID($_GET["IdUsuario"]);
		$sep = "#";
		if ($row = DatosUsuario($id)){
			echo $row["Nombre"] . $sep . $row["usuario"] . $sep . $row["pass"] . $sep . $row["esActivo"] . $sep . $row["esAdmin"];
		} else{
			echo "ERROR";		
		}		
	
		exit();
		break;						
}



?>