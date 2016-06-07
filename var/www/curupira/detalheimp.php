<?php
include ("inc/pagina.php");
$pagina = new Pagina();
$pagina->head();

if(isset($_GET["id"]) && $_GET["id"]!=""){
	$consulta = pg_query
	("SELECT printername, printers.description, priceperpage, priceperjob, nomeunidade, recurso, cor, nserie, ppm, nomeservidor, groupname FROM unidades, printers , printergroupsmembers, groups" .
			" WHERE printers.id = printergroupsmembers.printerid AND printergroupsmembers.groupid = groups.id AND printers.codunidade = unidades.codunidade AND printers.id=".$_GET["id"]) or die("Consulta invalida");
	$tupla = pg_fetch_object($consulta);
}

$pagina->comeca("Detalhes da impressora ".$tupla->printername, $_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"],$_POST, $_GET);
$tabela = '<div class="tddetalhes">
<table border="0" width="100%">
<tr><td class="negrito" nowrap="true">Nome</td>
<td width="100%">' . $tupla->printername . '</td>
</tr>
<tr>
<td class="negrito" nowrap="true">Pre&ccedil;o por p&aaute;gina</td>
<td > R$&nbsp;' . number_format($tupla->priceperpage,2,",",".") . '</td>
</tr>
<tr>
<td class="negrito" nowrap="true">Unidade</td>
<td>'.$tupla->nomeunidade.'</td>
</tr>
<tr>
<td  class="negrito" nowrap="true">Grupo</td>
<td>'.$tabela .= $tupla->groupname.'</td>
</tr>
<tr>
<td class="negrito" nowrap="true">Recurso</td>
<td>';
$tabela .= (($tupla->recurso==2)?"Laser":"Jato de Tinta");
$tabela .= '</td></tr>
<tr><td class="negrito" nowrap="true">Cor</td>
<td>';
$tabela .= (($tupla->cor ==1)? "Colorida":"Monocrom&aacute;tica");
$tabela .= '</td>
</tr>
<tr>
<td class="negrito" nowrap="true">Nr S&eacute;rie</td>
<td>'.$tupla->nserie.'</td>
</tr>
<tr>
<td class="negrito" nowrap="true">PPM</td>
<td>' . $tupla->ppm . '</td>
</tr>
<tr>
<td class="negrito" nowrap="true">Servidor</td>
<td>' . $tupla->nomeservidor . '</td>
</tr>
<tr>
<td class="negrito" nowrap="true">Descri&ccedil;&atilde;o</td>
<td>' .$tupla->description . '</td>
</tr>
</table>
</div>';
$pagina->foot();
echo $pagina->header . $pagina->cab . $tabela . $pagina->footer;
?>
