<?php
Session_start();
include('inc/conn.php');
include('inc/valida_session.php');

if ($_POST['codimpressora'] != '')
	$CodImpressora = $_POST['codimpressora'];
else
	$CodImpressora = 'null';

$NomeImpressora = $_POST['impressora'];
list($CodGrupo,$NomeGrupo) = split('[|]',$_POST['grupo']);
$CodUnidade = $_POST['unidade'];
$Custo = $_POST['custo'];
$Recurso = $_POST['recurso'];
$Cor = $_POST['cor'];
$nSerie = $_POST['nserie'];
$nPPM = $_POST['nppm'];
$NomeServidor = $_POST['nomeservidor'];
/* adicionado para pegar a localizacao da impressora */
$LocalizacaoImp = $_POST['localizacaoimp'];
$Descricao = $_POST['descricao'];

if ($CodImpressora == 'null') {
	$CorD = ( $Cor == 2 )?'-k':'-m';
	$RecursoD = ($Recurso == 2)?'-l':'-t';
	$saida = NULL;
	$erro = NULL;
	$linha = exec('/etc/pykota/insere-impressora -c ' . $Custo . ' -i ' . $NomeImpressora . ' -u ' . $CodUnidade . ' ' . $CorD . ' ' . $RecursoD . ' -s \'' . $NomeServidor . '\' -p ' .  $nPPM . ' -n \'' . $nSerie . '\' -g \'' . $NomeGrupo . '\' -x \'' . $LocalizacaoImp . '\' -d \'' . $Descricao . '\'', $saida, $erro);

	if($erro){
		echo $erro . '<br />';
		echo '/etc/pykota/insere-impressora -c ' . $Custo . ' -i ' . $NomeImpressora . ' -u ' . $CodUnidade . ' ' . $CorD . ' ' . $RecursoD . ' -s \'' . $NomeServidor . '\' -p ' .  $nPPM . ' -n \'' . $nSerie . '\' -g \'' . $NomeGrupo . '\' -x \'' . $LocalizacaoImp . '\' -d \'' . $Descricao . '\'';
	}else if( ($saida != NULL)&& ($saida != "") ) {
		foreach($saida as $key => $value)
			echo '[' . $key . '] = '. $a . "<br />\n";
	}
}
else
{
	if (!empty($_POST['descricao']))
		$Descricao = '\''.$_POST['descricao'].'\'';
	else
		$Descricao = 'null';
	if (!empty($_POST['localizacaoimp']))
		$LocalizacaoImp = '\''.$_POST['localizacaoimp'].'\'';
	else
		$LocalizacaoImp = 'null';


	$SQL = pg_query('UPDATE printers SET printername = \''.$NomeImpressora.'\', description = '.$Descricao.', priceperpage = \''.$Custo.'\', codunidade = '.$CodUnidade.', recurso = '.$Recurso.', cor = '.$Cor.', nserie = \''.$nSerie.'\', PPM = '.$nPPM.', nomeservidor = \''.$NomeServidor.'\', localizacao = '.$LocalizacaoImp.'  WHERE id = '.$CodImpressora);
	$strSQL = pg_query('UPDATE printergroupsmembers SET groupid = '.$CodGrupo.' WHERE printerid = '.$CodImpressora);

}
?>

<script language='JavaScript'>
	window.location.href = 'cad_impressora.php';
</script>
