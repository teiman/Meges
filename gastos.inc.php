<?php



function AltaGasto($Fecha,$IdProveedor,$facprov ,$cenfis,$imppagado,$impbase,$iva,$observ,$Concepto){
	global $UltimaInsercion;
	
	$operador = getSesionDato("IdOperador");
	
	$imptotal 		= $impbase + $iva;
	$Concepto 		= CleanParaMysql($Concepto);
	$operador		= CleanID($operador);
	$Fecha			= CleanParaMysql($Fecha);
	$IdProveedor 	= CleanID($IdProveedor);
	$facprov		= CleanParaMysql($facprov);
	$cenfis			= CleanID($cenfis);
	$impbase		= CleanFloat($impbase);
	$observ			= CleanParaMysql($observ);
	$iva			= CleanFloat($iva);	 	
	
	$sql = "INSERT INTO men_gastos ( Concepto,UltimoOperador, Fecha,IdProveedor," .
			" NumeroFacturaProv,IdCentroFiscal,ImportePagado,ImporteTotal,ImporteBase," .
			" Comentarios, ImporteIVA) VALUES ('$Concepto','$operador','$Fecha','$IdProveedor'," .
			"'$facprov' ,'$cenfis','$imppagado','$imptotal','$impbase','$observ','$iva')";
	$res = query($sql,"Nuevo Gasto");
	if (!$res)	return false;
			
	return $UltimaInsercion;
}

function ModGasto($IdGasto, $Fecha,$IdProveedor,$facprov ,$cenfis,$imppagado,$impbase,$iva,$observ,$Concepto){

	$operador = getSesionDato("IdOperador");
	
	$imptotal = $impbase + $iva;	
	$Concepto 		= CleanParaMysql($Concepto);
	$operador		= CleanID($operador);
	$Fecha			= CleanParaMysql($Fecha);
	$IdProveedor 	= CleanID($IdProveedor);
	$facprov		= CleanParaMysql($facprov);
	$cenfis			= CleanID($cenfis);
	$impbase		= CleanFloat($impbase);
	$observ			= CleanParaMysql($observ);
	$iva			= CleanFloat($iva);
	$imppagado		= CleanFloat($imppagado);	 
		
	$sql = "UPDATE men_gastos
      SET Concepto='$Concepto',UltimoOperador='$operador',Fecha='$Fecha',IdProveedor='$IdProveedor',
	  NumeroFacturaProv='$facprov',IdCentroFiscal='$cenfis',ImporteIVA='$iva',
	  ImportePagado='$imppagado',ImporteTotal='$imptotal',ImporteBase='$impbase', Comentarios ='$observ' WHERE IdGasto='$IdGasto'";
	$res = query($sql,"Modificando gasto");
	if (!$res)	return false;
			
	return $IdGasto;
}

function TotalPagos($IdGestionPagos){
	$IdGestionPagos = CleanID($IdGestionPagos);
	if (!$IdGestionPagos)
		return 0;
	
	$sql = "SELECT SUM(Cuanto) as Total FROM men_gestiongastos WHERE IdGestionPago = '$IdGestionPagos'";
	$row = queryrow( $sql );
	
	if ($row) return $row["Total"];
	
	return 0;
}

function EliminarRegistro($IdGasto){
	$operador = getSesionDato("IdOperador");

	$sql = sprintf("UPDATE men_gastos SET UltimoOperador='%d', Eliminado=1 WHERE IdGasto='%d'",$operador,$IdGasto);
	query($sql);
}



switch($modo){
	case "eliminarregistro":
		$IdGasto = CleanID($_GET["id"]);
		EliminarRegistro($IdGasto);
		MiniPaginaMensaje("Registro eliminado");
		exit();
		break;
	case "abrirmodificar":
		$IdGasto = CleanID($_GET["id"]); 
		$IdGastoActual = $IdGasto;		
		//error(__FILE__ . __LINE__ , "Info: idgac: $IdGastoActual ");
		
		$row = queryrow("SELECT * FROM men_gastos WHERE (IdGasto='$IdGasto') AND Eliminado=0","abrir para modificar");
		if ($row){
			$IdProveedor 	= CleanID($row["IdProveedor"]);
			$FacProv 		= $row["NumeroFacturaProv"];
			$Fecha			= CleanFechaFromDB($row["Fecha"]);
			$IdCentroFiscal = CleanID($row["IdCentroFiscal"]);
			$Importe		= CleanFloat($row["ImporteBase"]);
			$ImporteTotal 	= CleanFloat($row["ImporteTotal"]);
			$ImportePagado 	= CleanFloat($row["ImportePagado"]);
			$Observaciones	= CleanText(CleanXSS($row["Comentarios"]));	
			$IdAtribAct		= CleanID($row["IdRepartoActividad"]);
			
			$repartoact 	= getRepartoActiv($IdAtribAct);
			$repartocen 	= getRepartoCentros($row["IdRepartoCentros"]);
			$repartogastos 	= getRepartoGastos($row["IdGestionPagos"]);
			$Concepto 		= CleanText(CleanXSS($row["Concepto"])); 
			
			$IVA 			= CleanFloat($row["ImporteIVA"]);			
					
			$out = "";
			foreach ($repartoact as $key => $value){
				//AddAttribLine("attribActividad-list", IdCentro , NombreCentro, requerido,"cuantoContribActividad-resto","cuantoContribActividad" );
				list( $id, $nombreActividad) = split("#",$key);
				$nombreActividad = addslashes($nombreActividad);
				$out .= "AddAttribLine('attribActividad-list', '$id' , '$nombreActividad', '$value','cuantoContribActividad-resto','cuantoContribActividad' );";
			}
			$AutoRepartoAct = $out;
			
			$out = "";
			foreach ($repartocen as $key => $value){				
				list( $id, $nombreActividad) = split("#",$key);	
				$nombreActividad = addslashes($nombreActividad);			
				$out .= "AddAttribLine('attribCentros-list', '$id' , '$nombreActividad', '$value','cuantoContribCentros-resto','cuantoContribCentros' );";
			}
			$AutoRepartoCentros = $out;
			
			$out = "";
			error(__LINE__ , "infoe:". var_export($repartogastos,true));
			foreach ($repartogastos as $codereparto => $fecha ){							
				list( $valor, $esPagado ) = split("#",$codereparto);
				$out .="xAddPago('gestionPagos-list','$fecha','$valor','$esPagado');";
			}
			$AutoRepartoGastos = $out;									
		} else {
			MiniPaginaMensaje("El registro no existe");
			exit();
			break;
		}
		break;
	case "buscar":
		$cr = "\n";
		$prov 		= CleanNombre($_POST["Proveedor"]);
		$facprov 	= CleanNombre($_POST["FacProv"]);
		$centrofis 	= CleanID($_POST["CentroFis"]);
		$fechadesde = CleanFechaES($_POST["FechaDesde"]); 
		$fechahasta = CleanFechaES($_POST["FechaHasta"]);
		
		$condiciones = Array();
		$i = 0;
		if (strlen($prov)>0) {
			$IdProveedor = getIdProvFromName($prov);
			$condiciones[$i++] = "IdProveedor = '$IdProveedor'";
		}
		if (strlen($facprov)>0){
			$condiciones[$i++] = "NumeroFacturaProv LIKE '%".$facprov."%'";
		}
		if ($centrofis > 0){
			$condiciones[$i++] = "IdCentroFiscal = '$centrofis'";
		}
			
		if (strlen($fechadesde) > 0){
			$condiciones[$i++] = "Fecha >= '$fechadesde'";
		} 
		
		if (strlen($fechahasta) > 0){
			$condiciones[$i++] = "Fecha <= '$fechahasta'";
		} 		
		
		
		
		if( !$i ){
			echo "ERROR=no datos busca";
			exit();
			break;
		}
		
		$condiciones[$i++] = "Eliminado = 0";
		
		
		$campos = "IdGasto, Concepto,IdProveedor,Fecha,IdGestionPagos,ImporteTotal,NumeroFacturaProv,UltimoOperador";
		$res = BuscaCompleja("men_gastos",$campos, $condiciones, "ORDER BY NumeroFacturaProv ASC, Fecha DESC");
		if ($res) {
			while( $row = Row($res)){
				echo "Gasto:\n" . $row["IdGasto"] . $cr;
				echo getNombreProvID($row["IdProveedor"]) . $cr; 
				echo $row["NumeroFacturaProv"] . $cr;
				echo $row["Fecha"] . $cr;
				echo $row["ImporteTotal"] . $cr;
				echo "0" . $cr;//Cobro
				echo CleanFloat(TotalPagos($row["IdGestionPagos"])) . $cr; 
				echo getNombreOperador($row["UltimoOperador"]) . $cr;
				echo CleanParaXul($row["Concepto"]) . $cr;				
			}
		}
				
		echo "OK";
		exit();	
		break;
	case "gastos":
	/*
	 * ejemplo:
	 undefined
IdGasto=132
AtribNumGastos=2
AtribGastos_Nombre_0=3-1-2006
AtribGastos_Cantidad_0=32%20
AtribGastos_Pagado_0=true
AtribGastos_Nombre_1=23-1-2006
AtribGastos_Cantidad_1=33%20
AtribGastos_Pagado_1=false 
	 * */
		$IdGasto = CleanID($_POST["IdGasto"]);
		$fechas = array();
		$gastos = array();
		$gastospagados = array();
		$numgastos = CleanInt($_POST["AtribNumGastos"]);
		
		for($t=0;$t<$numgastos;$t++){
			$fechaGasto 	= CleanFechaES($_POST["AtribGastos_Nombre_$t"]);
			$cantidadGasto 	= CleanFloat($_POST["AtribGastos_Cantidad_$t"]);
			//$esPagado 		= ($_POST["AtribGastos_Pagado_$t"]=="true")?"true":"false";
			$esPagado 		= $_POST["AtribGastos_Pagado_$t"];
			//error(__FILE__ . __LINE__ ,"info: ep:$esPagado, t:$t");
			
			$gastospagados[$t]  	= "$esPagado";
			$fechas[$t] 	= $fechaGasto;
			$gastos[$t]   	= $cantidadGasto;						
		}	

		$row = queryrow("SELECT IdGestionPagos FROM men_gastos WHERE IdGasto = '$IdGasto'");
		if ($row and isset($row["IdGestionPagos"]) and $row["IdGestionPagos"]>0){
			query("UPDATE men_gestiongastos SET Eliminado=1 WHERE IdGestionPago=" . $row["IdGestionPagos"]);
			query("UPDATE men_gastos SET IdGestionPagos = '0' WHERE (IdGasto = '$IdIngreso')");
			if (!$numgastos or !($numgastos>0)){
				//Simplemente no hay datos de pago. Se marca sin datos y se sale
				error(__FILE__.__LINE__,"Info: numgastos = '$numgastos', es cero");
				echo "OK";
				exit();								
			}
		}

		/*Id  IdRepartoCentros  IdCentro  Cuanto */
		$IdMax = intval( 1  + intval(getMaxIdGestionPago()) );
		//error(__FILE__ . __LINE__ ,"err: " . var_export($gastospagados,true));
		

		for($t=0;$t<$numgastos;$t++){
			$gasto 		= $gastos[$t];	
			$fecha 		= $fechas[$t];
			$esPagado 	= ($gastospagados[$t]=="true")?"1":"0";
				
			$sql = "INSERT INTO men_gestiongastos (IdGestionPago,Fecha,Cuanto,esPagado) VALUES ('$IdMax','$fecha','$gasto','$esPagado')";
			$res = query($sql,"Nuevos gastos");	
			$hayDatos = true;				
		}
		//Reajustar padre 
		if ($hayDatos){
			$sql = "UPDATE men_gastos SET IdGestionPagos = '$IdMax' WHERE IdGasto = '$IdGasto'";
			query($sql);
		}
	 
		//query("select 'Prueba de attrib'");
		echo "OK";
		exit();	
		break;
	case "atrib":
		$IdGasto = CleanID($_POST["IdGasto"]);
		$centros = array();
		$idcentros = array();
		$numcentros = CleanInt($_POST["AtribNumCentros"]);
		
		if (!$numcentros){
			echo "ERROR";
			exit();				
		}		
		
		for($t=0;$t<$numcentros;$t++){
			$nombreCentro 	= Clean($_POST["AtribCentro_Nombre_$t"]);
			$centajeCentro 	= Clean($_POST["AtribCentro_Centaje_$t"]);
			$id	 			= Clean($_POST["AtribCentro_IdCentro_$t"]);
			$centros[$nombreCentro] = $centajeCentro;			
			$idcentros[$nombreCentro] = $id;
		}		

		/*Id  IdRepartoCentros  IdCentro  Cuanto */
		$IdMax = intval( 1  + intval(getMaxIdRepartoCentros()) );
		//error(__FILE__ . __LINE__ ,"IdMax: $IdMax");

		foreach( $centros as $nombre=>$porcen){
			if (!isset($idcentros[$nombre]) and !$idcentros[$nombre])
				$IdCentro = getIdCentroFromNombre($nombre);
			else
				$IdCentro = $idcentros[$nombre];							
			
			$sql = "INSERT INTO men_repartocentros (IdRepartoCentros,IdCentro,Cuanto) VALUES ('$IdMax','$IdCentro','$porcen')";
			$res = query($sql,"Nuevo reparto de centros");					
		}
	 
	 	//Reajustar padre 
		$sql = "UPDATE men_gastos SET IdRepartoCentros = '$IdMax' WHERE IdGasto = '$IdGasto'";
		query($sql);
		
		//query("select 'Prueba de attrib'");
		echo "OK";
		exit();	
		break;
	case "activ":
		$IdGasto = CleanID($_POST["IdGasto"]);
		$activ = array();
		$idactiv = array();		
		$numcentros = CleanInt($_POST["AtribNumActiv"]);
		
		if (!$numcentros){
			echo "ERROR";
			exit();				
		}						 		
		
		for($t=0;$t<$numcentros;$t++){
			$nombreCentro 	= Clean($_POST["AtribActiv_Nombre_$t"]);
			$centajeCentro 	= CleanFloat($_POST["AtribActiv_Centaje_$t"]);
			$id 			= Clean($_POST["AtribActiv_IdCentro_$t"]);			
			$activ[$nombreCentro] 	= $centajeCentro;
			$idactiv[$nombreCentro] = $id; 
		}	

		/*Id  IdRepartoCentros  IdCentro  Cuanto */
		$IdMax = intval( 1  + intval(getMaxIdRepartoActiv()) );
		//error(__FILE__ . __LINE__ ,"IdMax: $IdMax");

		foreach( $activ as $nombre=>$porcen){
			if (!isset($idactiv[$nombre]) and !$idactiv[$nombre])
				$IdActividad = getIdActividadFromNombre($nombre);
			else
				$IdActividad = $idactiv[$nombre];
							
			$sql = "INSERT INTO men_repartoactiv (IdRepartoActiv,IdActividad,Cuanto) VALUES ('$IdMax','$IdActividad','$porcen')";
			$res = query($sql,"Nuevo reparto de activ");					
		}
	 	
		//Reajustar padre 
		$sql = "UPDATE men_gastos SET IdRepartoActividad = '$IdMax' WHERE IdGasto = '$IdGasto'";
		query($sql);
		
	 
		//query("select 'Prueba de attrib'");
		echo "OK";
		exit();	
		break;		
	case "alta_mod":
		$esMod = 1;//es una modificacion, no una alta

	case "alta":
		$Fecha		= CleanFechaES($_POST["Fecha"]);	
		$Proveedor 	= CleanNombre($_POST["Prov"]);
		$facprov 	= CleanText($_POST["FacProv"]);
		$cenfis 	= CleanId($_POST["CenFis"]);
		$impbase 	= CleanFloat($_POST["ImporteBase"]);
		$IVA 		= CleanFloat($_POST["IVA"]);
		$imppagado 	= CleanFloat($_POST["ImpPagado"]);
		$observ 	= CleanText($_POST["Observaciones"]);
		$Proveedor 	= CleanNombre($_POST["Prov"]);
		$Concepto 	= CleanText($_POST["Concepto"]);
		
		
		$IdProveedor = getIdProvFromName($Proveedor);
		if (!$IdProveedor){
			$IdProveedor = AltaProv($Proveedor);
		}
		error(__FILE__.__LINE__ , "Info: $IdGasto, $Fecha,$IdProveedor,$facprov,$cenfis,$imppagado,$impbase,$IVA,$observ ");
		if (!$esMod) {
			if ($id = AltaGasto($Fecha,$IdProveedor,$facprov ,$cenfis,$imppagado,$impbase,$IVA,$observ,$Concepto)){
				echo "OK=$id";			
			} else {
				echo "error=error";
			}
		} else {
			$IdGasto = CleanID($_POST["IdGasto"]);
			$imptotal = CleanFloat($_POST["ImpTotal"]);
			//error( __FILE__ . __LINE__ ,"Idgasto: idgasto '$IdGasto'"); 
			//if ($id = ModGasto($IdGasto, $Fecha,$IdProveedor,$facprov ,$cenfis,$imppagado,$impbase,$IVA,$observ)){
			if ($id = ModGasto($IdGasto, $Fecha,$IdProveedor,$facprov ,$cenfis,$imppagado,$impbase,$IVA,$observ,$Concepto)){
				echo "OK=$id";			
			} else {
				echo "error=error";
			}				
		}
		exit();
		break;
}
		
		
		
		
		
?>
