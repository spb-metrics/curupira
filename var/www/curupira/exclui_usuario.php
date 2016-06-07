<?php
Session_start();
include('inc/conn.php');
include('inc/valida_session.php');

//Dados do formulario
		$CodUsuario = "";
if ($_GET["id"] != "")
	$CodUsuario = $_GET["id"];

if ($CodUsuario != ""){
	$resultado = pg_query("SELECT * from jobhistory where userid=".$CodUsuario);
	if(pg_num_rows($resultado) == 0 ){
		$UserQuotaSQL = "DELETE FROM userpquota WHERE userid = ".$CodUsuario;
		$PayamentsSQL = "DELETE FROM payments WHERE userid = ".$CodUsuario;
		$GroupsSQL = "DELETE FROM groupsmembers WHERE userid = ".$CodUsuario;
		$UsersSQL = "DELETE FROM users WHERE id = ".$CodUsuario;

		$ExecSQL3 = pg_query($UserQuotaSQL) or die ("Erro ao tentar excluir dados do Usu&aacute;rio");
		$ExecSQL2 = pg_query($PayamentsSQL) or die ("Erro ao tentar excluir dados do Usu&aacute;rio");
		$ExecSQL1 = pg_query($GroupsSQL) or die ("Erro ao tentar excluir um Usu&aacute;rio.");
		$ExecSQL = pg_query($UsersSQL) or die ("Erro ao tentar excluir um Usu&aacute;rio.");
		?>
				<script language="JavaScript">
				alert('Usuário excluido com sucesso.');
		window.location.href = 'cad_usuario.php';
		</script>
				<?php
	}else{
			?>

					<script language="JavaScript">
					alert('Um usuário que possui impressões não pode ser excluído. Este usuário possui <?php echo pg_num_rows($resultado);?> trabalhos impressos.');
			window.location.href = 'cad_usuario.php';
			</script>
					<?php
	}
}
?>
