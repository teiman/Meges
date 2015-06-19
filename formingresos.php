<?php

require("tool.php");
require("ingresos.php");

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
	<tabs id="tabingresos">
		<tab image="img/altagasto.gif" 	id="tab-alta" 		label="ALTA INGRESO"/>
		<tab image="img/buscagasto.gif" id="tab-busca" 		label="BUSCA INGRESO"/>
		<tab image="img/modgasto.gif" 	id="tab-modificar" 	label="MODIFICAR INGRESO"/>
	</tabs>
<tabpanels flex="1" id="panelingresos">
<tabpanel flex="1" style="overflow: auto"   align="center" pack="center">
<groupbox id="FormaDeIngresos">
<caption label="Alta de ingresos"/>
<hbox>
<grid>
<rows> 
 <columns></columns>
 <row><description>Concepto</description><textbox value="" id="Concepto"/></row>
 <row><description>Serie</description>
<menulist editable='true' class='media' id='Serie'>
<menupopup>
<menuitem value='1'  label='A'/>
<menuitem value='2'  label='B'/>
<menuitem value='3'  label='C'/>
</menupopup></menulist>
</row>
<row>
	<description>Num Fac</description>
	<hbox>	
	<textbox class="media" id="NumFac" value="0" onchange="FiltraEntero(this);"/>
	</hbox>
</row>
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
<row><description>IVA</description>
	<hbox>
	<toolbarbutton label="=" tooltiptext="Calcula el IVA suponiendo un 16%" oncommand="document.getElementById('IVA').value = (document.getElementById('ImporteBase').value * 0.16).toFixed(2)"/>
	<textbox onchange="onChangeImporte()" value="" id="IVA" flex="1"/>
	</hbox>
</row>
<row><description>Importe Total</description><label class="media" id="ImporteTotal" value="0"/></row>
<row><description>Importe Cobrado</description><label class="media" id="ImporteCobrado" value="0"/></row>
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
<caption label="Gestion Cobros"/>
 <box align="right">
 <toolbarbutton image="skin/calendar-up.gif" label="" onmousedown="EnviaCalendario('oe-date-picker-popup','newFecha')" popup="oe-date-picker-popup" position="after_start" />
 <textbox id="newFecha" value="DD-MM-AAAA" style="width: 8em"/>
 <textbox id="newCobro" value="0" style="width: 8em"/>
 <button label="Agregar cobro" oncommand="onAgregarCobro()"/>

 </box>
 <listbox rows="3" id="gestionCobros-list" contextmenu="accionesDinero">
 </listbox>
 </groupbox>
</vbox>
<!-- XXXXXXXXXXXXXXXXXX  -->
</vbox>
</hbox>
<hbox>
	<button image="img/button_ok.png" flex="1" class="media" oncommand="onEnvioDatos()" label="Alta"/>
	<button image="img/button_cancel.png" flex="1" class="media"  label="Cancelar" oncommand="CancelarAlta()"/>
	<spacer flex='1' id="progAlta1" mode="determined" value="0" />
</hbox>
</groupbox>
</tabpanel>


<!-- XXXXXXX  BUSCADOR  XXXXXXXXX  -->

<tabpanel flex="1">
<vbox>
<caption style="font-size:105%;font-weight: bold;border-bottom: 1px solid gray" label="Buscar"/>
<spacer style="height: 8px"/>
<grid>
<rows>
<row><description>Serie</description>
<menulist editable='true' class='media' id='sSerie'>
<menupopup>
<menuitem value='0'  label=''/>
<menuitem value='1'  label='A'/>
<menuitem value='2'  label='B'/>
<menuitem value='3'  label='C'/>
</menupopup></menulist>
</row>
<row><description>Num Fac</description><textbox class="media" id="sNumFac" value=""/></row>
<row><description>Desde</description>
	<hbox>
	<toolbarbutton image="skin/calendar-up.gif" label="" onmousedown="EnviaCalendario('oe-date-picker-popup','sFechaDesde')" popup="oe-date-picker-popup" position="after_start" />		
	<textbox class="media" id="sFechaDesde" value="DD-MM-AAAA"/>
	</hbox>	
</row>
<row><description>Hasta</description>
	<hbox>
	<toolbarbutton image="skin/calendar-up.gif" label="" onmousedown="EnviaCalendario('oe-date-picker-popup','sFechaHasta')" popup="oe-date-picker-popup" position="after_start" />		
	<textbox class="media" id="sFechaHasta" value="DD-MM-AAAA"/>
	</hbox>	
</row>
<row><description>Centro fiscal</description>
<?php
	genMenulistCentros("sCentroFiscal",true);
?>
</row>
<row><box/><checkbox checked="false" id="sImpagos" label="Solo pendientes"/></row>
<row><description>Importe</description><textbox value="" id="sImporteBase"/></row>
<row><description>IVA</description><textbox value="" id="sIVA"/></row>
<row><description>Importe Total</description><textbox class="media" id="sImporteTotal" value=""/></row>
<row><description>Importe Cobrado</description><textbox class="media" id="sImporteCobrado" value=""/></row>
<row>
	<button  image="img/find16.png" label="Buscar" flex="1" oncommand="onBuscar()"/>		
	<box flex='1' id="progAlta" mode="determined" value="0" />
</row>
</rows>
</grid>
</vbox>

    <listbox flex="1" id="lista-ingresos" rows="5" contextmenu="acciones-ingresos" class="listado">
     <listcols flex="1">
		<listcol/>
		<splitter  class="tree-splitter"/>		
		<listcol/>
		<splitter class="tree-splitter"/>
		<listcol flex="1"/>
		<splitter class="tree-splitter" />
		<listcol flex="1"/>
		<splitter class="tree-splitter" />
		<listcol flex="1" collapsed="true"/>
		<splitter class="tree-splitter" />
		<listcol/>		
		<listcol/>				
		<splitter class="tree-splitter" />
		<listcol/>	
     </listcols>
     <listhead>
		<listheader label="Serie"/>
		<listheader label="N.Fact"/>
		<listheader label="Fecha"/>
		<listheader label="Importe Total"/>
		<listheader label="E/Cobro"  collapsed="true"/>
		<listheader label="E/Ingreso"/>
		<listheader label="Usuario"/>		
     </listhead>


    </listbox>
</tabpanel>
<!-- XXXXXXX  TERMINA BUSCADOR  XXXXXXXXX  -->

<tabpanel flex="1"><iframe id="framemodingreso" src="about:blank" flex="1"/></tabpanel>

</tabpanels>
</tabbox>
<textbox id="IdIngresoActual" value="<?php echo CleanID($IdIngresoActual) ?>" collapsed="true"/>
<script type="application/x-javascript" src="comun.js?ver=1"/>	
<script type="application/x-javascript" src="ilumina.js?ver=1"/>
<script type="application/x-javascript" src="ingresos.js?ver=1"/>	
</window>