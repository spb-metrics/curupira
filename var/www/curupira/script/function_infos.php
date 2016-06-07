<?php

# separa a string do login por exemplo DOMINIO\matricula em duas strings, matricula e domínio
function trata_login($login,$info){
	$aux = explode('\\', $login);

	if(isset($aux[1])){
    $matricula=$aux[1];
  }else{
    $matricula = NULL;
  }
	# caso o usuario nao tenha passado o nome do domínio atual, busca esse nome no arquivo do samba
 	if(!isset($aux[1]) || $aux[1] == ""){
 	#  $dominio=exec("sed -n '1,/login.php/d;s/^NTLMDomain //p' /etc/apache/conf.d/ntlm.conf");
 	   $dominio=exec("sed -n 's/^.*workgroup.*=[ ]*//p' /etc/samba/smb.conf");

	   # Em último caso, se não tiver encontrado valor nenhum no smb.conf, coloca o domínio como BELOHORIZONTE
	   if ( $dominio == ""){
	   	$dominio = "BELOHORIZONTE";
	   }
	   $matricula=$aux[0];
 	}else{
	   $dominio=$aux[0];
 	}

	$comando=exec("./script/script.sh $matricula $dominio $info");
	return $comando;
}
?>
