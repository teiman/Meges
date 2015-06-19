<?php

include("tool.php");

header("Content-Type: application/vnd.mozilla.xul+xml");
header("Content-languaje: es");

echo $CabeceraXUL;

?>
<window id="ventanaprincipal" title="off"
        xmlns:html="http://www.w3.org/1999/xhtml"        
        xmlns="http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul" debug="false">
                
        		<description>Función no disponible</description>
        
<?php

echo "</window>";

exit;

?>        
        		       
<tabbox flex="1">
<hbox flex="1">
	<spacer flex="1"/>
	<vbox flex="1">   
	<spacer flex="1"/>
	<groupbox style="width: 400px;height: 200px;background-color: #ECE8DE">
	 	<spacer flex="1"/>		
		<groupbox flex="1">
			<caption label="Aviso" id="mensajebienvenida"/>			
			<description>Función no disponible</description>
		</groupbox>
		<spacer flex="1"/>		
	</groupbox>
	<spacer flex="1"/>	 
	</vbox>
    <spacer flex="1"/>
</hbox>    

</window>