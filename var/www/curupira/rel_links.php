<?php
Session_start();
include('inc/valida_session.php');
include('inc/conn.php');
include('inc/head.php');
$tabela ="tb_link";
?>

<html>
	<head>
		<title>Rel&oacute;torio de Links para outros curupiras</title>
		<script language="JavaScript" src="script/general_functions.js"></script>
		<script language="JavaScript" src="script/visual.js"></script>
	</head>
	<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
		<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td>
					<table style="MARGIN-BOTTOM:5px" border="0" width="100%" cellpadding="0" cellspacing="1" align="center" bgColor="black" />
						<tr><td class="Cab" align="center" colspan="5">Outros Servidores do Curupira</td></tr>
					</table>
				</td>
			</tr>
			<tr>
				<td height="100%" valign="top" width="100%">
					<br>
					<table border="0" width="100%">
						<tr>
							<td valign="top" height="100%" colspan="2">
								<table border="0" width="100%">
									<tr>
										<td class="titTable" colspan="7">Listagem de Unidades</td>
									</tr>
									<tr>
										<td width="5%" bgcolor="#e9e9e9"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<b>Descri&ccedil;&atilde;o</b>&nbsp;</font></td>
										<td width="5%" bgcolor="#e9e9e9"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<b>Link</b>&nbsp;</font></td>
										<td width="5%" bgcolor="#e9e9e9"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<b>Endere&ccedil;o</b>&nbsp;</font></td>
										<td width="5%" bgcolor="#e9e9e9"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<b>Resp</b>&nbsp;</font></td>
										<td width="5%" bgcolor="#e9e9e9"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<b>Nome Servidor</b>&nbsp;</font></td>
										<td width="5%" bgcolor="#e9e9e9"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<b>Ip Servidor</b>&nbsp;</font></td>
									</tr>
<?php
	$SQL = pg_query("SELECT * FROM $tabela ORDER BY Ds_Link");
	$Cor = "";
	while ($rs = pg_fetch_array($SQL))
	{
		if ($Cor == "#ffffff") $Cor = "#ECF2F8"; else $Cor = "#ffffff";
		echo'									<tr style="cursor:pointer" onMouseOver="javascript: trOver(this);" onMouseOut="javascript: trOut(this);"  onclick="window.open(\'http://'.$rs["link"].'\')" >
										<td width="5%" bgcolor="'.$Cor.'" valign="top" align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$rs["ds_link"].'</font></td>
										<td width="5%" bgcolor="'.$Cor.'" valign="top" align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$rs["link"].'</font></td>
										<td width="5%" bgcolor="'.$Cor.'" valign="top" align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$rs["end_predio"].'</font></td>
										<td width="5%" bgcolor="'.$Cor.'" valign="top" align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$rs["co_filial"].'</font></td>
										<td width="5%" bgcolor="'.$Cor.'" valign="top" align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$rs["no_servidor"].'</font></td>
										<td width="5%" bgcolor="'.$Cor.'" valign="top" align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$rs["ip_servidor"].'</font></td>
									</tr>';
	}
	$total=pg_num_rows($SQL);
	echo '									<tr class="titTable"><td  colspan="6" align="center">' .$total .' Unidades(s)</td></tr>';
?>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr><?php include('inc/foot.php') ?></tr>
		</table>
	</body>
</html>