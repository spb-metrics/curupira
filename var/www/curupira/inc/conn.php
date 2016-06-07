<?php
$File = "./conf/curupira.conf";
$DataFile = fopen($File,"r");
$Dados = fread($DataFile,filesize($File));

// Separando variavei do arquivo de configuração
list($H,$B,$U,$S) = split("[;]",$Dados);
list($NameH,$Host) = split("[=]",$H);
list($NameB,$Banco) = split("[=]",$B);
list($NameU,$Usuario) = split("[=]",$U);
list($NameS,$Senha) = split("[=]",$S);
fclose($DataFile);

$conn = pg_connect("host=".$Host." dbname=".$Banco." user=".$Usuario." password=".$Senha."") or die ('Erro ao conectar ao Bando de Dados');
	pg_set_client_encoding($conn, 'ISO-8859-1');
?>
