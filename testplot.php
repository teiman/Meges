<?php

include("tool.php");

// SELECT ImporteCobrado FROM men_ingresos WHERE Eliminado=0 ORDER BY Fecha LIMIT 0, 30

//Include the code
include("phplot.php");

//Define the object
$graph = &new PHPlot;

	$sql = "SELECT ImporteCobrado FROM men_ingresos WHERE Eliminado=0 ORDER BY Fecha";
	$res = query($sql);
	
$i =0;
$data = array();
$mydata = array();
array_push($mydata,"abc");

while( $row = Row($res) ){	
	//$data[$i]= array( "abcd",$row["ImporteCobrado"] );
	array_push($mydata,$row["ImporteCobrado"]);
	//$i++;		
}

$data[0] = $mydata;



$graph->SetPlotType("pie");

/*
	$graph->SetDataColors(	
		array("blue","green","yellow","red"),  //Data Colors
		array("black")							//Border Colors
	);  */
	
$graph->SetDataValues($data);

 
//Draw it
$graph->DrawGraph();
 


?>
