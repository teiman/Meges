<?

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

<popup id="accionesItemActividad" >  
   <menuitem class="menuitem-iconic" image="img/remove16.gif" label="Eliminar" oncommand="EliminarElementoSeleccionadoActividad()" />
</popup>
<popup id="accionesItemCentros" >  
   <menuitem class="menuitem-iconic" image="img/remove16.gif" label="Eliminar" oncommand="EliminarElementoSeleccionadoCentros()" />
</popup>  
<popup id="accionesDinero" >  
   <menuitem class="menuitem-iconic" image="img/remove16.gif" label="Eliminar" oncommand="EliminarElementoSeleccionadoDinero()" />
   <menuitem class="menuitem-iconic" label="Marcar pagado" oncommand="MarcarElementoSeleccionadoDinero(1)" />
   <menuitem class="menuitem-iconic" label="Marcar no pagado" oncommand="MarcarElementoSeleccionadoDinero(0)" />   
</popup>  

<box flex="1" style="overflow: auto"   align="center" pack="center">
<groupbox id="FormaDeIngresos">
<caption label="Modificación de un ingreso"/>
<hbox>
<grid>
<rows> 
 <columns></columns>
 <row><description>Concepto</description><textbox value="<?php echo addslashes(CleanParaXul($Concepto)) ?>" id="Concepto"/></row>
 <row><description>Serie</description>
<menulist editable='true' class='media' id='Serie' label="<?php echo CleanParaXul($Serie) ?>">
	<menupopup>
	<menuitem value='0' selected="true" label='<?php echo CleanParaXul($Serie) ?>'/>
	<menuitem value='1'  label='A'/>
	<menuitem value='2'  label='B'/>
	<menuitem value='3'  label='C'/>
	<menuitem value='4'  label='D'/>
	</menupopup>
</menulist>
</row>
<row>
	<description>Num Fac</description>
	<textbox  id="NumFac" onchange="FiltraEntero(this);" value="<?php echo $NumFac ?>"/>
</row>
<row>
	<description>Fecha</description>
	<hbox>
	<toolbarbutton image="skin/calendar-up.gif" label="" onmousedown="EnviaCalendario('oe-date-picker-popup','Fecha')" popup="oe-date-picker-popup" position="after_start" />		
	<textbox  id="Fecha" value="<?php echo $Fecha ?>"/>
	</hbox>	
</row>
<row><description>Centro fiscal</description>
<?php
	genMenulistCentros("CentroFiscal",false,$IdCentroFiscal);
?>
</row>
<row><description>Importe</description><textbox onchange="onChangeImporte()" value="<?php echo $Importe ?>" id="ImporteBase"/></row>
<row><description>IVA</description>
	<hbox>
	<toolbarbutton label="=" tooltiptext="Calcula el IVA suponiendo un 16%" oncommand="document.getElementById('IVA').value = (document.getElementById('ImporteBase').value * 0.16).toFixed(2)"/>
	<textbox onchange="onChangeImporte()" value="<?php echo CleanParaXul($IVA)  ?>" id="IVA"/>
	</hbox>		
</row>
<row><description>Importe Total</description><label  id="ImporteTotal" value="<?php echo $ImporteTotal ?>"/></row>
<row>
	<description>Importe Cobrado</description>
	<label  id="ImporteCobrado" value="<?php echo $ImporteCobrado ?>"/>
</row>
<row>
	<description>Observaciones</description>
	<textbox multiline="true"  value="<?php echo CleanParaXul($Observaciones) ?>"  id="Comentarios"/>
</row>
</rows>
</grid>
<vbox>
<!-- XXXXXXXXXXXXXXXXXX  -->
<vbox>
<groupbox>
<caption  label="Atribucion Actividad"/>
 <box align="right">
 <textbox id="cuantoContribActividad-resto" value="100" style="width: 4em;color: gray" readonly="true"/>
 <textbox id="cuantoContribActividad" value="100" style="width: 4em"/>
  <?	
	genMenulistActividad("attribActividad");
 ?>
 <spacer flex="1"/>
 <button label="Agregar %" oncommand="onAtribActividad();cambioActiv=1;"/>
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
<button label="Agregar %" oncommand="onAtribCentros();cambioCentros=1;"/>
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
 <button label="Agregar cobro" oncommand="onAgregarCobro();cambioCobros=1;"/>

 </box>
 <listbox rows="3" id="gestionCobros-list" contextmenu="accionesDinero">
 </listbox>
 </groupbox>
</vbox>
<!-- XXXXXXXXXXXXXXXXXX  -->
</vbox>
</hbox>
<hbox>
  <button image="img/button_ok.png" flex="1"  oncommand="onModificarDatos()" label="Modificar"/>
  <spacer mode="undetermined" flex="1"/>
<button image="img/del.gif" flex="1"   label="Eliminar" oncommand="EliminarRegistro()"/>  
 </hbox>
</groupbox>
</box>

<textbox id="IdIngresoActual" value="<?php 

 echo $IdIngresoActual;
 
 //error(__FILE__ . __LINE__ ,"Info: idghere is $IdIngresoActual ");

?>" collapsed="true"/>
<script type="application/x-javascript" src="comun.js?ver=1"/>
<script type="application/x-javascript" src="ilumina.js?ver=1"/>
<script type="application/x-javascript" src="ingresos.js?ver=1"/>
<script>//<![CDATA[

var cambioCobros = 0;
var cambioActiv = 0;
var cambioCentros = 0;

<?php
//IdIngresoActual
 $cr = "\n";
 echo $AutoRepartoAct . $cr;
 echo $AutoRepartoCentros . $cr;
 echo $AutoRepartoingresos . $cr;	

?>

//]]></script>

</window>
