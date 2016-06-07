<?php
Session_start();
include('inc/conn.php');
include('inc/valida_session.php');
?>
<html>
	<head>
		<script language="JavaScript" src="script/general_functions.js"></script>
		<TITLE>Relat&oacute;rio p/Usu&aacute;rio logado</TITLE>
	</head>
	<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
		<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td valign="top"><?php include('inc/header.php'); ?></td>
				<td valign="top" height="100%" width="100%">
					<table border="0" width="100%">
						<tr>
							<?php
							echo '<td bgcolor="#6487DC" colspan="4"><font size="1" face="Verdana, Arial, Helvetica, sans-serif" color="white">&nbsp;<b>Usuario: </b></font><a href="javascript:show_window(\'detalhe_user.php?id='.$_SESSION["userid"].'\',\'Usuario\',500,400);"><font size="1" face="Verdana, Arial, Helvetica, sans-serif" color="white"><b><u>'.$_SESSION["login"].'</u></b></font></a></td>
';
							?>
						</tr>
						<tr>
							<td nowrap="true" bgcolor="#e9e9e9"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<b>Impressora</b>&nbsp;</font></td>
							<td nowrap="true" bgcolor="#e9e9e9"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<b>P&aacute;g.: Impressas</b>&nbsp;</font></td>
							<td nowrap="true" bgcolor="#e9e9e9"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<b>Pre&ccedil;o</b>&nbsp;</font></td>
						</tr>
						<?php
							$strSQL = "SELECT users.id, users.username, printers.printername, userpquota.pagecounter, SUM(jobhistory.jobprice), printers.id FROM users, userpquota, printers, jobhistory WHERE users.id = userpquota.userid AND userpquota.printerid = printers.id AND userpquota.userid = jobhistory.userid AND userpquota.printerid = jobhistory.printerid AND users.username = '".$_SESSION["login"]."' GROUP BY users.id, users.username, printers.printername, userpquota.pagecounter, userpquota.hardlimit, userpquota.softlimit, printers.id ORDER BY users.username";
							$SQL = pg_query($strSQL);

							$Cor = "";
							$aux = 0;
							while ($rs = pg_fetch_row($SQL))
							{
								if ($Cor == "#ffffff") $Cor = "#ECF2F8"; else $Cor = "#ffffff";
								echo '<tr>
<td bgcolor="'.$Cor.'" width="100%" valign="top" align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="javascript:show_window(\'detalhe_impressora.php?id='.$rs[5].'\',\'Impressora\',500,400);">'.$rs[2].'</font></td>
<td bgcolor="'.$Cor.'" valign="top" align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$rs[3].'</font></td>
<td bgcolor="'.$Cor.'" nowrap valign="top" align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;R$&nbsp;'.$rs[5].'&nbsp;</font></td>
</tr>
';
							}
						?>
					</table>
				</td>
			</tr>
		</table>
				</body>
			</td>
		</tr>
		<tr><?php include('inc/rodape.php');?></tr>
	</table> <!-- Fecha tabela do header.php -->
</html>
