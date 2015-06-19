<?php

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
<window id="login-meges" title="MeGes"
        xmlns:html="http://www.w3.org/1999/xhtml"        
        xmlns="http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul">       

<!-- Calendar popup overlay -->
<script type="application/x-javascript" src="calendario.js" />		
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


<tabbox flex="1">
	<tabs id="tabGastos">
		<tab image="img/altagasto.gif" 	id="tab-alta" 		label="ALTA GASTO"/>
		<tab image="img/buscagasto.gif" id="tab-busca" 		label="BUSCA GASTO"/>
		<tab image="img/modgasto.gif" 	id="tab-modificar" 	label="MODIFICAR GASTO"/>
	</tabs>
<tabpanels flex="1" id="panelGastos">
<tabpanel flex="1" style="overflow: auto"   align="center" pack="center">
<groupbox id="FormaDeGastos">
<caption label="Alta de un gasto"/>
<hbox>
<grid>
<rows> 
 <columns></columns>
<row><description>Concepto</description><textbox value="" id="Concepto"/></row>
<row><description>Proveedor</description>
<?php
	genMenulistProveedores("Proveedor");
?>
</row>
<row><description>Fac. Prov.</description><textbox value="" id="FacProv"/></row>
<row>
	<description>Fecha</description>
	<hbox>
	<toolbarbutton image="skin/calendar-up.gif" label="" onmousedown="EnviaCalendario('oe-date-picker-popup','Fecha')" popup="oe-date-picker-popup" position="after_start" />		
	<textbox class="media" id="Fecha" value="DD-MM-AAAA"/>
	</hbox>	
</row>
<row><description>Centro fiscal</description>
<?php
	genMenulistCentros("CentroFiscal");
?>
</row>
<row><description>Importe</description><textbox onchange="onChangeImporte()" value="" id="ImporteBase"/></row>
<row>
	<description>IVA</description>
	<hbox>
	<toolbarbutton label="=" tooltiptext="Calcula el IVA suponiendo un 16%" oncommand="document.getElementById('IVA').value = (document.getElementById('ImporteBase').value * 0.16).toFixed(2)"/>
	<textbox onchange="onChangeImporte()" value="" id="IVA" flex="1"/>
	</hbox>
</row>
<row><description>Importe Total</description><label class="media" id="ImporteTotal" value="0"/></row>
<row><description>Importe Pagado</description><label class="media" id="ImportePagado" value="0"/></row>
<row><description>Observaciones</description><textbox multiline="true" class="media" value=""  id="Comentarios"/></row>
</rows>
</grid>
<vbox>
<!-- XXXXXXXXXXXXXXXXXX  -->
<vbox>
<groupbox>
<caption label="Atribucion Actividad"/>
 <box align="right">
 <textbox id="cuantoContribActividad-resto" value="100" style="width: 4em;color: gray" readonly="true"/>
 <textbox id="cuantoContribActividad" value="100" style="width: 4em"/>
  <?	
	genMenulistActividad("attribActividad");
 ?>
 <spacer flex="1"/>
 <button label="Agregar %" oncommand="onAtribActividad()"/>
 </box>
 <listbox rows="3"  id="attribActividad-list" contextmenu="accionesItemActividad">
 </listbox>
 </groupbox>
</vbox>
<!-- XXXXXXXXXXXXXXXXXX  -->
<vbox>
<groupbox>
<caption label="Atribucion Centros"/>
 <box align="right">
<textbox id="cuantoContribCentros-resto" value="100" style="width: 4em;color: gray" readonly="true"/>
<textbox id="cuantoContribCentros" value="100" style="width: 4em"/>
<?php	
	genMenulistCentros("attribCentros");
 ?>
<spacer flex="1"/>
<button label="Agregar %" oncommand="onAtribCentros()"/>
 </box>
 <listbox rows="3" id="attribCentros-list" contextmenu="accionesItemCentros"> 
 </listbox>
 </groupbox>
</vbox>
<!-- XXXXXXXXXXXXXXXXXX  -->
<vbox>
<groupbox>
<caption label="Gestion Pagos"/>
 <box align="right">
 <toolbarbutton image="skin/calendar-up.gif" label="" onmousedown="EnviaCalendario('oe-date-picker-popup','newFecha')" popup="oe-date-picker-popup" position="after_start" />
 <textbox id="newFecha" value="DD-MM-AAAA" style="width: 8em"/>
 <textbox id="newPago" value="0" style="width: 8em"/>
 <button label="Agregar pago" oncommand="onAgregarPago()"/>

 </box>
 <listbox rows="3" id="gestionPagos-list" contextmenu="accionesDinero">
 
 
 </listbox>
 </groupbox>
</vbox>
<!-- XXXXXXXXXXXXXXXXXX  -->
</vbox>
</hbox>
<hbox>
	<button image="img/button_ok.png" flex="1" class="media" oncommand="onEnvioDatos()" label="Alta"/>
	<button image="img/button_cancel.png" flex="1" class="media"  label="Cancelar" oncommand="CancelarAlta()"/>
	<spacer flex='1' id="progAlta" mode="determined" value="0" />
</hbox>
</groupbox>
</tabpanel>
<tabpanel flex="1">

<vbox>
<caption style="font-size:105%;font-weight: bold;border-bottom: 1px solid gray" label="Buscar"/>
<spacer style="height: 8px"/>
<grid>
<rows>
<row><description>Proveedor</description>
<?php
	genMenulistProveedores("sProveedor",true);
?>
</row>
<row><description>Fac. Prov</description><textbox class="media" id="sFacProv"/></row>
<row><description>Centro fiscal</description>	
<?php
	genMenulistCentros("sCentroFis",true);
?>
</row>
<row><box/><checkbox checked="false" id="sImpagos" label="Solo pendientes"/></row>
<row>
	<description>Desde</description>
	<hbox>
	<toolbarbutton image="skin/calendar-up.gif" label="" onmousedown="EnviaCalendario('oe-date-picker-popup','sFechaDesde')" popup="oe-date-picker-popup" position="after_start" />		
	<textbox class="media" id="sFechaDesde" value="DD-MM-AAAA"/>
	</hbox>	
</row>
<row>
	<description>Hasta</description>
	<hbox>
	<toolbarbutton image="skin/calendar-up.gif" label="" onmousedown="EnviaCalendario('oe-date-picker-popup','sFechaHasta')" popup="oe-date-picker-popup" position="after_start" />		
	<textbox class="media" id="sFechaHasta" value="DD-MM-AAAA"/>
	</hbox>	
</row>
<row>
	<hbox>
	<button image="img/find16.png" label="Buscar" flex="1" oncommand="onBuscar()"/>
	</hbox>
	<box mode="undetermined" flex="1"/>
</row>
</rows>
</grid>
</vbox>

    <listbox flex="1" id="lista-gastos" rows="5" contextmenu="acciones-gastos" class="listado">
     <listcols flex="1">
		<listcol/>
		<splitter class="tree-splitter" />
		<listcol flex="2"/>
		<splitter class="tree-splitter" />
		<listcol  style="width: 90px"/>
		<splitter class="tree-splitter" />
		<listcol  flex="2"/>
		<splitter class="tree-splitter" />
		<listcol flex="2" collapsed="true"/>
		<splitter class="tree-splitter" />
		<listcol/>		
		<listcol/>				
		<splitter class="tree-splitter" />
		<listcol/>	
     </listcols>
     <listhead>
		<listheader label="Proveedor"/>
		<listheader label="N.Fact"/>
		<listheader label="Fecha"/>
		<listheader label="Importe Total"/>
		<listheader label="E/Cobro" collapsed="true"/>
		<listheader label="Pagado"/>
		<listheader label="Usuario"/>		
     </listhead>


    </listbox>
</tabpanel>
<tabpanel flex="1">
<iframe id="framemodgasto" src="about:blank" flex="1"/>
</tabpanel>
</tabpanels>
</tabbox>
<textbox id="IdGastoActual" value="<?php echo CleanID($IdGastoActual) ?>" collapsed="true"/>

<script type="application/x-javascript" src="comun.js?ver=1"/>	
<script type="application/x-javascript" src="ilumina.js?ver=1"/>
<script type="application/x-javascript" src="gastos.js?ver=1"/>	
</window>