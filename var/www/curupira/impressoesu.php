<?php
session_start();

include ("inc/pagina.php");

if($_SESSION["perfil"] != 2 && $_SESSION["perfil"]!=3 ){
	$_POST["codunidade"]=$_SESSION["uni"];
	$CodUnit="AND unidades.codunidade= ".$_SESSION["uni"];
}

class Pagina1 extends Pagina{
	function Pagina1(){
	}
	function camposBusca($POST=NULL,$GET=NULL){
		$retval;
		$this->post= $POST;
		$this->get = $GET;
		if($this->impressao ||$this->salvar){
			return "";
		}
		$this->campos .= '<form name="frm_data" method="post" action="'.$this->end.'">
<table border="0" width="100%" cellpadding="0" cellspacing="0">';
		$retval= 'Unidade:&nbsp;';
		$SQLU = pg_query("SELECT codunidade, nomeunidade FROM unidades ORDER BY nomeunidade");
		if($_SESSION["perfil"]==2 || $_SESSION["perfil"]==3 ){
		$retval .='<select name="codunidade" style="font-size:8pt" onchange="document.forms.frm_data.usuario.value=\'-1\';document.forms.frm_data.submit()">';
		$retval .='<option value="-1">Todas</option>';
    if(isset($this->post["codunidade"])){
      while ($rs = pg_fetch_row($SQLU)){
        if($rs[0] == $this->post["codunidade"])
          $retval .= "<option value=\"".$rs[0]."\" selected>";
        else
          $retval .= "<option value=\"".$rs[0]."\">";
        $retval .= $rs[1]."</option>";
      }
    }else{
      while ($rs = pg_fetch_row($SQLU)){
        $retval .= "<option value=\"".$rs[0]."\">".$rs[1]."</option>";
      }
    }
		$retval .='</select>
';
		}
		$retval.= '&nbsp;Usu&aacute;rio:&nbsp;';
    if(isset($this->post["codunidade"]) && $this->post["codunidade"] >0)
			$string="SELECT id, nome FROM users WHERE codunidade=" . $this->post["codunidade"]." ORDER BY nome";
		else $string="SELECT id, nome FROM users ORDER BY nome";

		$SQLU = pg_query($string) or die("Erro ".$string);
		$retval .='<select name="usuario" style="font-size:8pt">';
		$retval .='<option value="-1"> Todos </option>';
    if(isset($this->post["usuario"])){
      while ($rs = pg_fetch_row($SQLU)){
        if($rs[0] == $this->post["usuario"])
          $retval .= "<option value=\"".$rs[0]."\" selected>";
        else
          $retval .= "<option value=\"".$rs[0]."\">";
        $retval .= $rs[1]."</option>";
      }
    }else{
      while ($rs = pg_fetch_row($SQLU)){
        $retval .= "<option value=\"".$rs[0]."\">";
        $retval .= $rs[1]."</option>";
      }
    }
		$retval .='</select><br>
<input type="checkbox" name="matricula" '.(isset($this->post["matricula"])?"checked":'').'>&nbsp;Matr&iacute;cula&nbsp;
<input type="checkbox" name="nome" '.(isset($this->post["nome"])?"checked":'').'>&nbsp; Nome &nbsp;
<input type="checkbox" name="impressora" '.(isset($this->post["impressora"])?"checked":'').'>&nbsp; Impressora &nbsp;
<input type="checkbox" name="documento" '.(isset($this->post["documento"])?"checked":'').'>&nbsp; Documento &nbsp;<br>
<input type="checkbox" name="estacao" '.(isset($this->post["estacao"])?"checked":'').'>&nbsp; Esta&ccedil;&atilde;o &nbsp;
<input type="checkbox" name="data" '.(isset($this->post["data"])?"checked":'').'>&nbsp; Data &nbsp;
<input type="checkbox" name="paginas" '.(isset($this->post["paginas"])?"checked":'').'>&nbsp; P&aacute;ginas&nbsp;
<input type="checkbox" name="custo" '.(isset($this->post["custo"])?"checked":'').'>&nbsp; Custo&nbsp;
<input type="submit" class="btnMenu" name="ok" value=" OK " ></td>
</tr>
<input type="hidden" name="page" value="'.((isset($this->post["page"]) && ($this->post["page"] == 0))? (int)0: 1).'\>
<input type="hidden" name="flg" value="'.$this->post["flg"].'">
<input type="hidden" name="tord" value="'.$this->post["tord"].'">
<input type="hidden" name="impressao" value="0">
<input type="hidden" name="salvar" value="0">';
		$this->campos.=$retval;
		$this->campos .=  "</table></form>";
	}

	/* Gera a consulta final atraves das informações passadas no formulario*/
	function geraSQL($sql){
		/* Datasq e unitsq serão utilizados na filtragem dos resultados por data e unidade.
	 	* */
		$opcoes;
		$indice=0;

		if (isset($this->post["codunidade"])  && $this->post["codunidade"]>=0){
				$opcoes[$indice] = " users.codunidade = ".$this->post["codunidade"]." ";
				$indice++;
		}

		if (isset($this->post["usuario"])  && $this->post["usuario"]>=0){
			$opcoes[$indice] = " users.id = ".$this->post["usuario"]." ";
			$indice++;
		}
		if(isset($opcoes))
      $this->sql= $this->adicionaAConsulta($sql,$opcoes);
    else
    $this->sql= $this->adicionaAConsulta($sql, NULL);
	}
}


include ("inc/valida_session.php");
include ("inc/conn.php");

/*Setando a opção de ordenação padrao*/
if(!isset($_POST["tord"])) $_POST["tord"] = "DESC";
/*Criando uma nova pagina*/
$pagina = new Pagina1();

/*Setando as colunas*/
$colspan=0;
if(isset($_POST["matricula"])){
	$params[strtolower("login")] = new EstruturaPF("Usu&aacute;rio", "U");
	$colspan++;
	$flag="U";
}
if(isset($_POST["nome"])){
$params[strtolower("username")] = new EstruturaPF("Nome", "N");
$colspan++;
$flag="N";
}
if(isset($_POST["impressora"])){
$params[strtolower("printername")] = new InfoCol("Impressora", "I");
$colspan++;
$flag="I";
}
if(isset($_POST["documento"])){
$params[strtolower("title")] = new InfoCol("Documento", "D");
$colspan++;
$flag="D";
}
if(isset($_POST["estacao"])){
$params[strtolower("hostname")] = new StringCol("Esta&ccedil;&atilde;o", "E",100);
$colspan++;
$flag="E";
}
if(isset($_POST["data"])){
$params[strtolower("data")] = new DataCol("Data", "DA");
$colspan++;
$flag="DA";
}
if(isset($_POST["paginas"])){
$params[strtolower("jobsize")] = new InfoCol("P&aacute;ginas", "P");
$flag="P";
}
if(isset($_POST["custo"])){
$params[strtolower("jobprice")] = new InfoCol("Custo", "R");
$flag="R";
}


if(!isset($_POST["flg"]) || $_POST["flg"]=="") $_POST["flg"] = (isset($flag))?$flag:NULL;

$pagina->comeca("Impress&otilde;es", $_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"],$_POST, $_GET);
$pagina->camposBusca($_POST, $_GET);

$sql = "SELECT users.username as login, users.nome as username,jobhistory.title, jobhistory.jobsize, jobhistory.jobprice, jobhistory.hostname, date(jobhistory.jobdate) ||'/'|| to_char(jobhistory.jobdate, 'HH24:MI:SS')AS data, unidades.nomeunidade, groups.groupname, printers.printername FROM printers, users, jobhistory, unidades, groups, printergroupsmembers  WHERE printers.id = jobhistory.printerid AND users.id = jobhistory.userid AND printers.codunidade = unidades.codunidade AND jobhistory.jobsize > 0 AND printers.id = printergroupsmembers.printerid AND groups.id = printergroupsmembers.groupid ";
if(isset($codimpresq))
  $sql .= $codimpresq;

if (isset($params)){
/*Iniciando a tabela*/
$pagina->geraSQL($sql);
$pagina->tabela($params);
$pagina->adicionarVariaveisPaginacao($params);

/*O que estara na linha inicial*/
$linhaInicial = "Detalhes das Impress&otilde;es";
/*O que sera mandado para a proxima pagina. em forma de link.*/
//$links["id"] = 0;
if(isset($links)) $link = new InfoLink($links,strtolower("title"),"");
else $link = new InfoLink(NULL, strtolower("title"),"");
/**/
$indice=0;

$linhaFinal[$indice++] = new InfoLinhaFinal('registros Registros',$colspan,1,"center");
if(isset($_POST["paginas"]))
$linhaFinal[$indice++] = new InfoLinhaFinal('jobsize',1,1);
if(isset($_POST["custo"]))
$linhaFinal[$indice++] = new InfoLinhaFinal('jobprice',1,1);
//?codunidade=$rs[cgcunidade]'";
/*Faz a tabela*/
$pagina->fazTabela($params, $linhaInicial, $linhaFinal, $link);

$pagina->varPaginacao();
}
$pagina->head();

echo $pagina->header; // Imprime header.
echo $pagina->body; // Imprime cabeçalho.
echo $pagina->cab; // Imprime cabeçalho.
echo $pagina->campos; //Imprime campos de consulta
echo $pagina->tabela; //Imprime tabela
echo $pagina->barraPagina;//Imprime paginação
echo "<br />";
$pagina->foot();
echo $pagina->footer;//Imprime paginação
//echo $pagina->sql;
?>
