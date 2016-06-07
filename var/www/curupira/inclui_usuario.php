<?php
/*
* Curupira: Sistema PHP gerenciador de impressoes para ambiente corporativo.
* Copyright (C) 2006 - Caixa Economica Federal - GISUT/BH
* Authors:
*  Bernardo Cunha Vieira
*  Bruno Marcal Lacerda Fonseca
*  Daniel Andrade Costa Silva
*  Edgard Antonio de Aguiar
*  Evando Marcio de Almeida
*  Ricardo Carlini Sperandio
*  Zeniel Chaves
*
*  This program is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  This program is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*/

Session_start();
include('inc/conn.php');
include('inc/valida_session.php');

//Dados do formulario
if (isset($_POST["codusuario"]) && $_POST["codusuario"] != ""){
		$CodUsuario = $_POST["codusuario"];
		}
else{
	$CodUsuario = "null";
	}

$Login = $_POST["login"];
$Nome = $_POST["nome"];
list($Grupo,$NomeGrupo) = split("[|]",$_POST["grupo"]);
$Email = $_POST["email"];
$CodUnidade = $_POST["unidade"];
$Descricao = $_POST["descricao"];
if ($CodUsuario == "null"){
	echo '<script language="JavaScript">
				alert(\'Inserindo novo usuario.\')
				window.location.href = \'cad_usuario.php\';
		</script>';

	exec("/etc/pykota/infile:///var/www/curupira/inclui_usuario.phpsere-usuario -u $Login -g \"$NomeGrupo\"",$output, $retcode); 

	if ($retcode != 0){
		echo '<script language="JavaScript">
				alert(\'Usuário não incluído/alterado com sucesso.\')
				window.location.href = \'cad_usuario.php\';
		</script>';
	}
}
  else{
	$SQL = "UPDATE users SET username = '".$Login."', email = '".$Email."', description = '".$Descricao."', codunidade = ".$CodUnidade.", nome = '$Nome' WHERE id = ".$CodUsuario;
	$strSQL = "UPDATE groupsmembers SET groupid = ".$Grupo." WHERE userid = ".$CodUsuario;
	$SQLUser = pg_query($SQL) or die ("Erro ao tentar incluir/alterar um Usu&aacute;rio.");
	$SQLGrupo = pg_query($strSQL) or die ("Erro ao tentar incluir/alterar um Usu&aacute;rio.");
}
echo '<script language="JavaScript">
		alert(\'Usuário incluído/alterado com sucesso.\');
	window.location.href = \'cad_usuario.php\';
	</script>';

?>




