<?
require("tool.php");
require("gastos.inc.php");

header("Content-Type: application/vnd.mozilla.xul+xml");
header("Content-languaje: es");


if (!$_SESSION["IdOperador"]){
	MiniPaginaMensaje("Este modulo requiere un usuario");	
	exit();
}


echo $CabeceraXUL;
echo str_replace("@","?","<@xul-overlay href='datepicker-overlay.php' type='application/vnd.mozilla.xul+xml'@>");

?>
<window id="login-meges" title="meges"
        xmlns:html="http://www.w3.org/1999/xhtml"        
        xmlns="http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul">       

<script type="application/x-javascript" src="calendario.js"/>		
<popup  id="oe-date-picker-popup" position="after_start" oncommand="RecibeCalendario( this )" value=""/>		


<popup id="accionesItemActividad" class="media">  
   <menuitem class="menuitem-iconic" image="img/remove16.gif" label="Eliminar" oncommand="EliminarElementoSeleccionadoActividad()" />
</popup>
<popup id="accionesItemCentros" class="media">  
   <menuitem class="menuitem-iconic" image="img/remove16.gif" label="Eliminar" oncommand="EliminarElementoSeleccionadoCentros()" />
</popup>  
<popup id="accionesDinero" class="media">  
   <menuitem class="menuitem-iconic" image="img/remove16.gif" label="Eliminar" oncommand="EliminarElementoSeleccionadoDinero()" />
   <menuitem class="menuitem-iconic" label="Marcar pagado" oncommand="MarcarElementoSeleccionadoDinero(1)" />
   <menuitem class="menuitem-iconic" label="Marcar no pagado" oncommand="MarcarElementoSeleccionadoDinero(0)" />   
</popup>  


<box flex="1" style="overflow: auto"   align="center" pack="center">
<groupbox id="FormaDeGastos">
<caption label="Mod de un gasto"/>
<hbox>
<grid>
<rows> 
 <columns></columns>
<row><caption class="media" label="Concepto"/><textbox value="<?php echo addslashes(CleanParaXul($Concepto)) ?>" id="Concepto"/></row>
<row><caption class="media" label="Proveedor"/>
<?php
	genMenulistProveedores("Proveedor",false,$IdProveedor);
?>
</row>
<row>
	<caption class="media" label="Fac. Prov."/>
	<textbox value="<?php echo CleanParaXul($FacProv) ?>" id="FacProv"/>
</row>
<row>
	<caption class="media" label="Fecha"/>
	<hbox>
	<toolbarbutton image="skin/calendar-up.gif" label="" onmousedown="EnviaCalendario('oe-date-picker-popup','Fecha')" popup="oe-date-picker-popup" position="after_start" />		
	<textbox class="media" id="Fecha" value="<?php echo CleanParaXul($Fecha) ?>"/>
	</hbox>	
</row>
<row><caption class="media" label="Centro fiscal"/>
<?php
	genMenulistCentros("CentroFiscal",false,$IdCentroFiscal);
?>
</row>
<row>
	<caption class="media" label="Importe"/>
	<textbox onchange="onChangeImporte()" value="<?php echo $Importe ?>" id="ImporteBase"/>
</row>
<row><caption class="media" label="IVA"/>

	<hbox>
	<toolbarbutton label="=" tooltiptext="Calcula el IVA suponiendo un 16%" oncommand="document.getElementById('IVA').value = (document.getElementById('ImporteBase').value * 0.16).toFixed(2)"/>	
	<textbox onchange="onChangeImporte()" value="<?php echo $IVA  ?>" id="IVA"/>
	</hbox>

</row>
<row><caption class="media" label="Importe Total"/><label class="media" id="ImporteTotal" value="<?php echo $ImporteTotal ?>"/></row>
<row><caption class="media" label="Importe Pagado"/><label class="media" id="ImportePagado" value="<?php echo $ImportePagado ?>"/></row>
<row><caption class="media" label="Observaciones"/>
	<textbox multiline="true" class="media" value="<?php echo CleanParaXul($Observaciones) ?>"  id="Comentarios"/></row>
</rows>
</grid>
<vbox>
<!-- XXXXXXXXXXXXXXXXXX  -->
<vbox>
<groupbox>
<caption class="media" label="Atribucion Actividad"/>
 <box align="right">
 <textbox id="cuantoContribActividad-resto" value="0" style="width: 4em;color: gray" readonly="true"/>
 <textbox id="cuantoContribActividad" value="0" style="width: 4em"/>
  <?	
	genMenulistActividad("attribActividad");
 ?>
 <spacer flex="1"/>
 <button label="Agregar %" oncommand="onAtribActividad();cambioActiv=1"/>
 </box>
 <listbox rows="3"  id="attribActividad-list" contextmenu="accionesItemActividad">
 </listbox>
 </groupbox>
</vbox>
<!-- XXXXXXXXXXXXXXXXXX  -->
<vbox>
<groupbox>
<caption class="media" label="Atribucion Centros"/>
 <box align="right">
<textbox id="cuantoContribCentros-resto" value="0" style="width: 4em;color: gray" readonly="true"/>
<textbox id="cuantoContribCentros" value="0" style="width: 4em"/>
<?php	
	genMenulistCentros("attribCentros");
 ?>
<spacer flex="1"/>
<button label="Agregar %" oncommand="onAtribCentros();cambioCentros=1"/>
 </box>
 <listbox rows="3" id="attribCentros-list" contextmenu="accionesItemCentros">
 </listbox>
 </groupbox>
</vbox>
<!-- XXXXXXXXXXXXXXXXXX  -->
<vbox>
<groupbox>
<caption class="media" label="Gestion Pagos"/>
 <box align="right">
 <toolbarbutton image="skin/calendar-up.gif" label="" onmousedown="EnviaCalendario('oe-date-picker-popup','newFecha')" popup="oe-date-picker-popup" position="after_start" />
 <textbox id="newFecha" value="DD-MM-AAAA" style="width: 8em"/>
 <textbox id="newPago" value="0" style="width: 8em"/>
 <button label="Agregar pago" oncommand="onAgregarPago();cambioPagos=1;"/>

 </box>
 <listbox rows="3" id="gestionPagos-list" contextmenu="accionesDinero">
 </listbox>
 </groupbox>
</vbox>
<!-- XXXXXXXXXXXXXXXXXX  -->
</vbox>
</hbox>
<box>
 <button image="img/button_ok.png" flex="1" class="media" oncommand="onModificarDatos()" label="Modificar"/>
 <box flex='1' id="progMod" mode="determined" value="0" />
 <button image="img/del.gif" flex="1" class="media"  label="Eliminar" oncommand="EliminarRegistro()"/>
</box>
</groupbox>
</box>

<textbox id="IdGastoActual" value="<?php 

 echo $IdGastoActual;
 
 //error(__FILE__ . __LINE__ ,"Info: idghere is $IdGastoActual ");

?>" collapsed="true"/>
<script type="application/x-javascript" src="comun.js?ver=1"/>
<script type="application/x-javascript" src="ilumina.js?ver=1"/>
<script type="application/x-javascript" src="gastos.js?ver=1"/>
<script>//<![CDATA[

var cambioPagos = 0;
var cambioActiv = 0;
var cambioCentros = 0;

<?php
//IdGastoActual
	echo $AutoRepartoAct;
	echo $AutoRepartoCentros;
	echo $AutoRepartoGastos;	

?>

//]]></script>

</window>