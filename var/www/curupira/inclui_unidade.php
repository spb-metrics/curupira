<?php
include('inc/conn.php');
//include('inc/valida_session.php');

//Dados do formulário
		if (isset($_POST["codunidade"]) && $_POST["codunidade"] != "")
		$CodUnidade = $_POST["codunidade"];
else
	$CodUnidade = "null";

if (isset($_POST["ilha"]) && $_POST["ilha"] != "")
	list($CodIlha,$NomeIlha) = split("[|]",$_POST["ilha"]);
else
	$CodIlha = "null";

$CGCUnidade = (isset($_POST["cgcunidade"]))?$_POST["cgcunidade"]:NULL;
$NomeUnidade = $_POST["nomeunidade"];
$Endereco = $_POST["endereco"];

if ($CodUnidade == "null")
	$SQL = "INSERT INTO unidades (codunidade, nomeunidade, endereco, codilha) VALUES (".$CGCUnidade.", '".$NomeUnidade."', '".$Endereco."', ".$CodIlha.")";
else
	$SQL = "UPDATE unidades SET nomeunidade = '".$NomeUnidade."', endereco = '".$Endereco."', codilha = ".$CodIlha." WHERE codunidade = ".$CodUnidade;

$SQLUser = pg_query($SQL);
 if ($SQLUser){
	 echo '<script language="JavaScript">
			 alert(\'Unidade incluída/alterada com sucesso.\');';
 }else {
	 echo '<script language="JavaScript">
			 alert(\'Unidade não incluída/alterada com sucesso.\');';
 }
 echo '
		 window.location.href = \'cad_unidade.php\';
 </script>';
 ?>
