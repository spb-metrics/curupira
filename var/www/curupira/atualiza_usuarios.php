<?php
	if(! isset($_SESSION)) Session_start();
	if($_SESSION["perfil"] == 2 || $_SESSION["perfil"] == 3){
		include_once('inc/conn.php');
		include_once('pega_info_usuario.php');
		$res = pg_query('SELECT username FROM users WHERE nome=\'Desconhecido\';');
		$ok = 0;
		$notok = 0;
		while ($linha = pg_fetch_row($res) ){
			if( Atualiza_Informacoes_Usuario($linha[0]) ) $ok++; else $notok++;
		}
		echo '<script language="javascript"> alert(\'' . $ok . ' usuários atualizados e\\n' . $notok . ' usuários não encontrados\'); window.location=\'cad_usuario.php\'</script>';
		flush();
	} else {
		header("location: valida_login.php");
	}
	?>
