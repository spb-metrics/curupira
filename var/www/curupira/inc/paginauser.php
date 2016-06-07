<?php
include ("inc/pagina.php");
class PaginaComUsuario extends Pagina{
	function PaginaComUsuario(){
	}
	function camposBusca($POST=NULL,$GET=NULL){
		$retval;
		if($this->impressao ||$this->salvar){
			return "";
		}
		$this->campos .= '<form name="frm_data" method="post" action="".$this->end."">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
';
    if(isset($retval))
      $retval .= "<tr>
<td>Login: ";
    else
      $retval = "<tr>
<td>Login: ";
		$retval .= '<input type="text" size="10" maxlength="10" name="usuario" value="';
    if(isset($this->post["usuario"]))
      $retval .= $this->post["usuario"];
    $retval .= '" >';
		$retval .= '&nbsp;Nome: <input type="text" size="40" maxlength="40" name="nomeusuario" value="';
    if(isset($this->post["nomeusuario"]))
      $retval .= $this->post["nomeusuario"];
    $retval .= '" >
';

		if($_SESSION["perfil"]!=6 && $_SESSION["perfil"]!=4){
		  $retval .='&nbsp;Unidade:&nbsp;';
		  $SQLU = pg_query("SELECT codunidade, nomeunidade FROM unidades ORDER BY nomeunidade");

		  $retval .='<select name="codunidade" style="font-size:8pt">
<option value="-1">:: Unidade ::</option>
';
      if(isset($this->post["codunidade"])){
					while ($rs = pg_fetch_row($SQLU)){
						if($rs[0] == $this->post["codunidade"])
						  $retval .= '<option value="'.$rs[0].'" selected>';
						else
						  $retval .= '<option value="'.$rs[0].'">';
						$retval .= $rs[1]."</option>
";
					}
      }else{
        while ($rs = pg_fetch_row($SQLU)){
          $retval .= '<option value="'.$rs[0].'">';
          $retval .= $rs[1]."</option>
";
        }
      }
		$retval .='</select>
';
		}
		$retval .= '<input type="submit" class="btnMenu" name="ok" value=" OK " ></td>
</tr>
<input type="hidden" name="page" value="'.((isset($this->post["page"]) && ($this->post["page"] == 0))? (int)0: 1).'">
<input type="hidden" name="flg" value="'.$this->post["flg"].'">
<input type="hidden" name="tord" value="'.$this->post["tord"].'">
<input type="hidden" name="impressao" value="0">
<input type="hidden" name="salvar" value="0">
';
		$this->campos.=$retval;
		$this->campos .=  "</table>
</form>
";
	}

	/* Gera a consulta final atraves das informações passadas no formulario*/
	function geraSQL($sql){
		/* Datasq e unitsq serão utilizados na filtragem dos resultados por data e unidade.
	 	* */
		$opcoes;
		$indice=0;
    if (isset($this->post["usuario"]) && $this->post["usuario"] != ""){
				$opcoes[$indice] = " upper(users.username) like '%".strtoupper($this->post['usuario'])."%' ";
				$indice++;
		}

    if (isset($this->post["nomeusuario"]) && $this->post["nomeusuario"] != ""){
				$opcoes[$indice] = " upper(users.nome) like '%".strtoupper($this->post['nomeusuario'])."%' ";
				$indice++;
		}

		if (isset($this->post["codunidade"])  && $this->post["codunidade"]>=0){
				$opcoes[$indice] = " users.codunidade = ".$this->post["codunidade"]." ";
				$indice++;
		}
		if(isset($opcoes)){
      $this->sql= $this->adicionaAConsulta($sql,$opcoes);
    }else{
      $this->sql= $this->adicionaAConsulta($sql, NULL);
    }
	}
}
?>
