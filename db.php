<?php

//DETECCION DE VERSION

list($dummy,$esDir,$next) = split("/",$PHP_SELF);


/////////////////////////////////
// Constantes

$link = false;
$UltimaInsercion = false;
$FilasAfectadas = false;
$debug_sesion = false;	

$modo_verbose = false;


switch($esDir){
	default:
		$ges_database = "meges";	
		$global_host_db = "localhost";
		$global_user_db = "root";
		$global_pass_db = "";
		error(__FILE__ . __LINE__ ,"Info, usando conexion desconocida");	
		break;
}


function Row($res) {

	if(!$res){
		error(__FILE__ . __LINE__ ,"ERROR requiriendo datos");
		return false;	
	}
	
	$data = mysql_fetch_array($res);
	if (!is_array($data)) {
		$data = mysql_fetch_row($res);
	}

	return $data;
}

function LogSQLErroneo ($sql) {
	$sqlguardar = base64_encode($sql);			
	$llaves 	= "Sql, CreadoPor, IdCreador, Exito, FechaCreacion";
	$valores 	= "'$sqlguardar','web',0,0,NOW()"; 		
		
	$sqlSalvar = "INSERT men_logsql ( $llaves ) VALUES ( $valores )";
	
	mysql_query($sqlSalvar);
}


function forzarconexion(){
	global $link;
	global $global_host_db;
	global $global_user_db ;
	global $global_pass_db;
	global $ges_database;
	
	$database = $ges_database;
	
	if (!$link) {
		//Si no se conecto antes, conecta ahora.
		$host = $global_host_db;
		$user = $global_user_db ;
		$pass = $global_pass_db;
		
		$link = mysql_connect($host, $user, $pass);
		if (!$link)
			error(__FILE__. __LINE__, "Fatal: No puedo conectar a la base de datos");
		else
			mysql_select_db($database,$link);
	}
	
}


function query($sql=false,$nick="") {
	global $link;
	global $UltimaInsercion,$FilasAfectadas, $debug_sesion;		
	global $ges_database;
	global $sqlTimeSuma;
	global $global_host_db;
	global $global_user_db ;
	global $global_pass_db;
	
	$lastime = microtime(true);
	
	if (!isset($sql)) {
		error(__FILE__ . __LINE__ , "Fatal: se paso un sql vacio!");
		return false;
	}
		
	$database = $ges_database;	
	$result = false;

	if (!$link) {
		forzarconexion();
	}

	if ($link) 
		//$result = mysql_db_query($database, $sql,$link) 
	 	$result = 	mysql_query($sql) or LogSQLErroneo($sql);
	
	if (!$result)
		die("Fallo de conexion en $sql o $link");
	
	$ahora = microtime(true);
		
	$sqlTimeSuma = $sqlTimeSuma + ($ahora - $lastime);

	$UltimaInsercion  = mysql_insert_id($link);
	$FilasAfectadas   = mysql_affected_rows($link);
	
	if($result)	$fueExito = 1;
	else $fueExito = 0;
		
	$sqlguardar = base64_encode($sql);			
	$llaves 	= "Sql, CreadoPor, IdCreador, Exito, FechaCreacion,TipoProceso";
	$valores 	= "'$sqlguardar','web',0,'$fueExito',NOW(),'$nick'"; 		
		
	$sqlSalvar = "INSERT men_logsql ( $llaves ) VALUES ( $valores )";
	
	if (!mysql_db_query($database, $sqlSalvar,$link)){
			error(__FILE__ . __LINE__ , "Fatal: fallo log '$sqlSalvar'");	
	}			
		 
	return $result;
}

function CreaInsercion($soloEstos,$data,$nombreTabla) {
	$coma = false;
	
	$todos = true;
	if (is_array($soloEstos))
		$todos = false;
	
	$listaKeys = "";
	$listaValues = "";
				
	foreach ($data as $key=>$value){
		
		if ($todos)
			$vale = true;			
		else
			$vale = in_array($key,$soloEstos);
		
		if ($key =="0" or !$key)
			$vale = false;
		if (intval($key)>0)
			$vale = false;							

		
		//error(__LINE__ , "Info: key '$key' val '$value' vale '$vale' lkeys: '$listaKeys'");
							
		if ($vale) {
			if ($coma) {
				$listaKeys .= ", ";
				$listaValues .= ", ";
			}
			
			$listaKeys .= " $key";
			$listaValues .= " '$value'";
			$coma = true;						
		}									
	}

	return "INSERT INTO $nombreTabla ( $listaKeys ) VALUES ( $listaValues )";
}
		

function CreaUpdate ($soloEstos, $data,$nombreTabla, $nombreID,$idvalue ) {
		$coma = false;
	
		foreach ($data as $key => $value) {
			if ( in_array($key,$soloEstos) and $key != "0" ) {
				if ($coma)
					$str .= ",";

				$value = mysql_escape_string($value);

				$str .= " $key = '$value'";
				$coma = true;
			}
		}

		return "UPDATE $nombreTabla SET $str WHERE $nombreID = '$idvalue'";
}
	
function CreaUpdateSimple ($data,$nombreTabla, $nombreID,$idvalue ) {
		$coma = false;
	
		foreach ($data as $key => $value) {
			if (  $key != "0" and intval($key)==0 ) {
				if ($coma)
					$str .= ",";

				$value = mysql_escape_string($value);

				$str .= " $key = '$value'";
				$coma = true;
			}
		}

		return "UPDATE $nombreTabla SET $str WHERE $nombreID = '$idvalue'";
}
  
function queryrow($sql,$nick=false) {
	$res = query($sql,$nick);
	if (!$res){
		return false;	
	}
	$row = Row($res);
	if (!is_array($row)){
		return false;	
	}
	return $row;
} 


function BuscaCompleja($tabla, $campos, $condiciones,$extra="" ){	
	$sqlcon = "";
	$visto = 0;
	foreach ($condiciones as $condicion){
		if ($visto)
		  $sqlcon = $sqlcon . " AND ";
		$sqlcon = $sqlcon . "( " . $condicion . " )"; 
		$visto = 1;
	}
	
	
	$sql = "SELECT $campos FROM $tabla WHERE $sqlcon $extra";
	return query($sql, "Busqueda");	
}



?>