<?php
	function Busca_Ldap($src){
		$ad_server = "IP";
		$ad_port   = 3268;
		$dominio   = "DOMINIO";
		$user      = "cn=DOUSUARIO";
		$pass      = "SENHA_AQUI";
		$base_dn   = "BASE";
		$ldap_conn = ldap_connect($ad_server, $ad_port) or die ("Erro ao conectar ao ldap");
		if ($ldap_conn) {
			$bind = ldap_bind($ldap_conn, $user, $pass) or die ("Nao autenticou");
			$src = ldap_search($ldap_conn, $base_dn, "sAMAccountName=$src");
			if ($src) {
				$info = ldap_get_entries($ldap_conn, $src);
				if ($info["count"]) return $info; else return false;
			}
		}
		return false;
	}
	function Atualiza_Informacoes_Usuario($usr){
		if(! isset ($_SESSION) ) Session_start();
		if($_SESSION["perfil"] == 2 || $_SESSION["perfil"] == 3 || ($_SESSION["matricula"] == $usr) ){
			include_once('inc/conn.php');
			$info = Busca_Ldap($usr);
			if(isset($info[0]['cn'][0]) && isset($info[0]['extensionattribute1'][0]) && isset($info[0]['description'][0]) && isset($info[0]['company'][0]) )
			{
				$SQL = 'UPDATE users SET ';
				$a_testar = htmlentities($info[0]['cn'][0]);
				if($a_testar != ''){
					$SQL .= 'nome=\'' . $a_testar . '\'';

					$a_testar = htmlentities(trim(substr($info[0]['extensionattribute1'][0], strrpos($info[0]['extensionattribute1'][0], ' ') ) ));
					if($a_testar != ''){
						$SQL .= ', codunidade=\'' . $a_testar. '\'';
						/* Algum erro nas informações do ldap, tente buscar no banco de dados local o codigo da unidade */
					}else if( isset($info[0]['department'][0]) && trim($info[0]['department'][0]) != '' && trim($info[0]['department'][0]) != '-') {
							if($rs_dep = pg_query('select codunidade from unidades where nomeunidade=\''.$info[0]['department'][0].'\';'))
								$ln_dep = pg_fetch_row($rs_dep);
							if(isset($ln_dep[0]) && $ln_dep[0] != '')
								$SQL .= ', codunidade=\'' . $ln_dep[0]. '\'';
					}
					if(isset($info[0]['description'][0]) && $info[0]['description'][0] != ''){
						if(strtolower($info[0]['description'][0]) == 'prestador'){
							$SQL .= ', description=\'' . htmlentities($info[0]['description'][0]) . '\n' . htmlentities($info[0]['company'][0]). '\'';
						}else{
							$SQL .= ', description=\'' . htmlentities($info[0]['description'][0]) . '\'';
						}
					}
					$SQL .= ' WHERE username=\'' . $usr . '\';';
					pg_query($SQL);
					return true;
				}
			}
		}
		return false;
	}
?>
