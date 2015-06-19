<?php
include("tool.php");


$num = intval($_GET["num"]);

if (!$num)
	$num = 40;


////////////////////////////////////////////////////////
// COSTANTES


	//PageStart();
	
	echo "<a href='$action'>Reload</a> - <a href='#Sesion'>Sesion</a>";
	
	$sql = "SELECT * FROM men_logsql ORDER BY FechaCreacion DESC, Idlogsql DESC  LIMIT $num";
	
	$res = query($sql,"------");
	
	echo "<a name='Log'>";
	echo "<table border=1 width=100%>";
	if ($res){
		while($row = Row($res)){
			$sql = base64_decode($row["Sql"]); 
			if ($row["Exito"]==0)
				$sql = gColor("red",$sql);
			$nick = $row["TipoProceso"];
			
			echo g("tr",g("td class=fact",$row["FechaCreacion"]).g("td",$nick).g("td",$sql));			
		}			
	} else{
		echo g(br,q($sql) . " no mola");	
	}
		
	echo "</table>";
	
	echo "<a name='Sesion'>";
	if ($_GET["sesion"]!="no"){
		echo "<table border=1 width=100%>";
		foreach($_SESSION as $key=>$value){	
			if (is_array($value)){
				$value = var_export($value,true);
			}
			echo g("tr",g("td class=fact width=10%",$key).g("td","<xmp>".$value."</xmp>"));			
		}			
		echo "</table>";
	}
	echo "<a href='#Log'>Log</a><br>";
	
//	PageEnd();
	
?>
