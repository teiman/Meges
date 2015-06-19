<?

include("tool.php");

header("Content-Type: application/vnd.mozilla.xul+xml");
header("Content-languaje: es");

echo $CabeceraXUL;

?>
<window id="yourwindow" xmlns="http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul">


<vbox flex="1">
<hbox  flex="1">
    <tree id="tree1"  hidecolumnpicker="true" seltype="single"
                     style="min-width: 300px">
      <treecols>
	<treecol id="name1"  flex="1"  label="Carpeta" />
	<treecol id="sex1"   label="Elementos" />
      </treecols>

      <treechildren>
       <treeitem>
        <treerow>
 	 <treecell label="Listas de precios" />
	 <treecell label="3" />
        </treerow>
       </treeitem>
       <treeitem>
        <treerow>
	 <treecell label="Modelos de cartas" />
	 <treecell label="2" />
        </treerow>
       </treeitem>
       <treeitem>
        <treerow>
	 <treecell label="Modelos de presupuesto" />
	 <treecell label="1" />
        </treerow>
       </treeitem>
       <treeitem>
        <treerow>
	 <treecell label="Logotipos e imagen" />
	 <treecell label="10" />
        </treerow>
       </treeitem>
       <treeitem>
        <treerow>
	 <treecell label="Varios" />
	 <treecell label="3" />
        </treerow>
       </treeitem>
      </treechildren>
    </tree>
  <splitter collapse="before" ><grippy /></splitter>

<vbox flex="1">
<hbox style="" >
<description style="font-size: 14px">Carpeta:</description>
<description style="font-size: 18px">Varios</description>
</hbox>
    <tree id="tree1" flex="1" hidecolumnpicker="true" seltype="single"
                     onselect="setText('tree1','value1');" >
      <treecols>
	<treecol id="name1"  flex="1" label="Nombre" />
	<treecol id="sex1"   flex="1" label="Autor" />
	<treecol id="color1" flex="1" label="Version" />
	<treecol id="attr1"  flex="1" label="Fecha modificación" />
      </treecols>

      <treechildren>
       <treeitem>
        <treerow>
 	 <treecell label="agenda de direcciones.xls" />
	 <treecell label="Romero" />
	 <treecell label="1.0.32" />
	 <treecell label="9/1/07" />
        </treerow>
       </treeitem>
   
       <treeitem>
        <treerow>
 	 <treecell label="beneficios año pasado.xls" />
	 <treecell label="Romero" />
	 <treecell label="2.0.1" />
	 <treecell label="12/1/07" />
        </treerow>
       </treeitem>

       <treeitem>
        <treerow>
 	 <treecell label="invitacion general.eml" />
	 <treecell label="Romero" />
	 <treecell label="1.0" />
	 <treecell label="9/1/07" />
        </treerow>
       </treeitem>

       <treeitem>
        <treerow>
 	 <treecell label="recordatorio.txt" />
	 <treecell label="Francisca" />
	 <treecell label="1.0" />
	 <treecell label="14/1/07" />
        </treerow>
       </treeitem>

      </treechildren>
    </tree>
<box style="background-color: #ccc"  flex="1">
<vbox flex="1">
<groupbox flex="1">
<description style="font-size: 14px">Documento: </description>
<description style="font-size: 18px">invitacion general.eml</description>
<description style="font-size: 12px">Tipo: Correo electronico</description>
<description style="font-size: 12px">Fecha ultima modificación: 9/01/2007</description>
<description style="font-size: 12px">Comentario:</description>

<groupbox>
<box style="background-color: white" flex="1">
<textbox  flex="1" multiline="true" style="font-size: 12px; margin: 3px" value="Mensaje de invitación, a la reunión de federaciones de centros de Zaragoza. Tiene las fechas del evento, areas asignadas, etc"/>
</box>
</groupbox>

<spacer flex="1"/>
<hbox>
<button label="Descargar fichero"/><button label="Actualizar"/><button label="Borrar"/>
<button label="Mostrar versiones"/>

</hbox>
</groupbox>
</vbox>
</box>
</vbox>

</hbox>


</vbox>




</window>