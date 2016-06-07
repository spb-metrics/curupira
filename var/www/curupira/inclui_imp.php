<?php
Session_start();
include('inc/conn.php');
include('inc/valida_session.php');

//Dados do formulario
		if ($_POST["codusuario"] != "")
		$CodUsuario = $_POST["codusuario"];
else
	$CodUsuario = "null";

if ($CodUsuario == "null")
{
	echo '<script language="JavaScript">
			alert(\'Nenhum Usuário selecionado.\');';
}else{
	$SQLT= "SELECT printers.id from printers ";
	$SQLI = pg_query($SQLT) or die ("Erro ao tentar incluir/alterar um Usu&aacute;rio.".$SQLT);
	while ($rs=pg_fetch_array($SQLI)){
		if(isset($_POST[$rs["id"]])){
			if(isset($_POST[$rs["id"]])){
				$quota = $_POST["i".$rs["id"]];
				if($quota == ""){
					$quota ="NULL";
				}
			}else $quota="NULL";
			$SQL = "UPDATE userpquota SET softlimit=$quota  , hardlimit=$quota, temporarydenied='false' WHERE userid = ".$CodUsuario." and printerid=".$rs["id"];
			$SQLUser = pg_query($SQL) or die ("Erro ao tentar incluir/alterar um Usu&aacute;rio.".$SQL);
		}
	}
	echo '<script language="JavaScript">
			alert(\'Permissões do Usuário incluído/alterado com sucesso.\');';
}
 echo 'window.location.href = \'cad_usuario.php\';
 </script>';
 ?>
