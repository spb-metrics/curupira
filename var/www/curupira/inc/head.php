<?php
if(!isset($_SESSION))
  Session_start();
include('inc/valida_session.php');
include('inc/conn.php');
include('inc/menu.php');

$SQL = pg_query("SELECT * FROM users, groupsmembers, groups WHERE users.id = groupsmembers.userid AND groupsmembers.groupid = groups.id
AND users.username = '".$_SESSION["matricula"]."'");
$rs = pg_fetch_array($SQL);
$NomeGrupo = $rs["groupname"];

$head = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Caixa Econ&ocirc;mica Federal</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="estilo/estiloCaixa.css" rel="stylesheet">
<script language="javascript" src="script/visual.js" type="text/javascript"></script>
<script language="JavaScript" src="script/general_functions.js"></script>
</head>
<body>
';

$body='<table cellSpacing="0" cellPadding="0" width="100%" border="0">
	<tbody>
		<tr>
			<td>
				<!-- INICIO: Cabecalho -->
				<table border="0" cellSpacing="0" cellPadding="0" width="100%">
					<tbody>
						<tr>
							<td bgcolor="#ecf2f8"><IMG src="imagens/LogoSL.gif"></td>
							<td align="center" bgcolor="#ecf2f8" valign="bottom"><IMG src="imagens/logoCurupira_novo.jpg" ></td>
							<td nowrap="true" bgcolor="#ecf2f8">
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr>
										<td align="right">
											<table border="1" cellpadding="0" cellspacing="0" bgcolor="#ECF2F8" bordercolorlight="#000000" bordercolordark="#ffffff">
												<tr>
													<td align="left">
														&nbsp;<b>Usu&aacute;rio:&nbsp;</b>'.$_SESSION["matricula"].'<br />
														&nbsp;<b>Nome:&nbsp;</b>'.$_SESSION["nome"].'<br />
														&nbsp;<b>Grupo:&nbsp;</b>'.$NomeGrupo.'<br />
														&nbsp;<b>Vers&atilde;o Curupira:</b> '.exec("dpkg -l curupira |grep curupira | awk '{ print $3}'|cut -d '-' -f 1").'<br />
														&nbsp;<b>Vers&atilde;o do pacote:</b> '.exec("dpkg -l curupira |grep curupira | awk '{ print $3}'|cut -d '-' -f 2").'
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				<!-- FIM: Cabecalho -->
			</td>
		</tr>
		<tr>
			<td>
			<!-- INICIO: Menu -->';
		if(!isset($printing) || !$printing) $body.= $menu;
		$body.='<!-- FIM: Menu -->
			</td>
		</tr>
		<tr>
			<td>
				<!-- INICIO: Corpo -->
				<table align="center" width="98%" cellSpacing="0" cellPadding="0" border="0">
					<tr>
						<td>
';

		function geraHead($head,$body,$opcao=0){
			if(!$opcao)
				echo $head.$body;
		}
		if(isset($opcaoParaPaginasQueNaoImprimem))
      geraHead($head,$body,$opcaoParaPaginasQueNaoImprimem);
    else
      geraHead($head,$body,NULL);
?>
