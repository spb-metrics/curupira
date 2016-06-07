<?php
if(!isset($_SESSION)) session_start();
include ("inc/paginacao2.php");

class InfoLink{
	var $link;
	var $nome;
	var $pagina;
	function InfoLink($link, $nome, $pagina){
		$this->link = $link;
		$this->nome = $nome;
		$this->pagina = $pagina;
	}
	function toLink(){
		if($this->pagina =="")
		return "";
		$retval="location.href='$this->pagina";
		$i=0;
		if(ereg("\?",$this->pagina)){
			$retval.="&";
		}else $retval.="?";
		foreach($this->link as $key =>$value){
			$retval.="$key=$value";
			$retval.="&";
		}
		$retval.="';";
		return $retval;
	}
}
class InfoCol{
	var $nome;
	var $idord;
	function InfoCol($name, $id){
		$this->nome = $name;
		$this->idord = $id;
	}
	function getString($val){
		return $val;
	}
}
class DataCol extends InfoCol{
	var $tam;
	function DataCol($name, $id){
		parent::InfoCol($name, $id);
	}
	function getString($string){
			list($Data,$Hora) = split("[/]",$string);
			list($Ano,$Mes,$Dia) = split("[-]",$Data);
			$Data =$Dia."/".$Mes."/".$Ano;
			$Data .="-";
			$Data .=$Hora;
			return $Data;
	}
}

class EstruturaPF extends InfoCol{
	function EstruturaPF($name, $id){
		InfoCol::InfoCol($name, $id);
	}
	var $meustring;
	function imprime(){
		echo "Imprime ".$this->meustring."<br/>";
	}
	function getString($val){
		return $val;
	}
	function modifica($val){
		echo "Antes ".$this->meustring."<br/>";
		$retval= (strcasecmp(($this->meustring),$val) !=0) ? $val: " ";
		$this->meustring=$val;
		echo "Depois ".$this->meustring."<br/>";
		return $retval;
	}
}

class StringPF extends StringCol{
	function StringPF($name, $id,$tam){
		StringCol::StringCol($name, $id,$tam);
	}
}

class StringCol extends InfoCol{
	var $tam;
	function StringCol($name, $id,$tamanho){
		parent::InfoCol($name, $id);
		$this->tam=$tamanho;
	}
	function getString($string){
/*		$a=strlen($string)/$this->tam;
		$retval = "";
		if(strlen($string)<$this->tam){
			return $string;
		}
		else{
			for($i=0; $i<$a; $i++){
				$k = $i*$this->tam;
				$retval.=substr($string,$k,$this->tam)."
";
			}
		}
		return $retval;
	}*/
		return $string;
	}
}

class InfoLinhaFinal{
	var $nome;
	var $tam;
	var $tipo;
	var $align;
	function InfoLinhaFinal($name, $tam, $valido, $align = "left"){
		$this->nome = $name;
		$this->tam = $tam;
		$this->valido = $valido;
		$this->align = $align;
	}
}

class Pagina{
	var $header;
	var $body;
	var $cab;
	var $campos;
	var $tabela;
	var $footer;
	var $barraPagina;
	var $paginacao;
	var $end;
	var $Cabecalho;
	var $post;
	var $get;
	var $sql;
	var $consulta;
	var $tuplas;
	var $registros;
	var $impressao;
	var $salvar;
	var $camposInput;
	function Pagina(){
	}

	function comeca($titulo, $endereco, $POST, $GET){
		if(!isset($_SESSION))
      session_start();
		session_register("postit");
		session_register("primeiro");

		session_register("datai");
		session_register("dataf");
		session_register("firsttime");

		if ( isset($POST["ok"])){
			$_SESSION['datai'] = (isset($POST["dtinicial"]))?$POST["dtinicial"]:NULL;
			$_SESSION['dataf'] = (isset($POST["dtfinal"]))?$POST["dtfinal"]:NULL;
			$_SESSION['firsttime'] = 1;
		#	print "a";
		}else if ($_SESSION['firsttime'] == ""){
			$_SESSION['datai'] = "01/".date("m/Y",time());
			$_SESSION['dataf'] = date("d/m/Y",time());

		}
		$POST["dtinicial"] = $_SESSION['datai'];
		$POST["dtfinal"] = $_SESSION['dataf'];
		$this->end = $endereco;
		$this->get = $GET;
		$this->salvar=(isset($POST["salvar"]))?$POST["salvar"]:NULL;
		$this->impressao=(isset($GET["impressao"]))?$GET["impressao"]:NULL;
		$this->post = $POST;
		if($this->impressao==0){
			$_SESSION["postit"] = serialize($POST);
			//echo $_SESSION["postit"];
		}elseif($this->impressao>0){
			//if(!isset($this->post))
			if(isset($_SESSION["postit"])){
				$this->post = unserialize($_SESSION["postit"]);
			}
		}
		if($this->salvar || $this->impressao){
			return;
		}
		$this->cab = '<table style="MARGIN-BOTTOM:5px" border="0" width="100%" cellpadding="0" cellspacing="1" align="center" bgColor="black" ><tr><td class="Cab" align="center" colspan="5">'.$titulo.' </td></tr></table></td></tr><tr><td>
';
	}

	function innerForm(){
	}

	/*Nesta função define-se os padrões para procura dos formularios*/
	function camposBusca($POST=NULL, $GET=NULL){
		if($this->salvar){
			return;
		}
		$this->campos .= '<form name="frm_data" method="post" action="'.$this->end.'">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
';
		$retval;
		if($this->impressao){
			return "";
		}
		$dataI = $this->post["dtinicial"];
		$dataF = $this->post["dtfinal"];
    $codunidade = (isset($this->post["codunidade"]))?$this->post["codunidade"]:NULL;
		if(!isset($retval)){
      $retval = "<tr>
";
    }else{
      $retval .= "<tr>
";
    }
		$retval .= '<td align="left">&nbsp;Data Inicial:&nbsp;
<input type="text" size="10" maxlength="10" name="dtinicial" onblur="javascript:ValidaCampo(this.form.name,this.name, \'Data Inicial\');" onchange="javascript:FormataData(this.form.name,this.name,event);" value="'.$dataI.'" />
&nbsp;Data Final:&nbsp;
<input type="text" size="10" maxlength="10" name="dtfinal" onblur="javascript:ValidaCampo(this.form.name,this.name,\'Data Final\');" onchange="javascript:FormataData(this.form.name,this.name,event);" value="'.$dataF.'" />
';

		if($_SESSION["perfil"]==2 || $_SESSION["perfil"]==3){
			$SQLU = pg_query("SELECT distinct unidades.codunidade, nomeunidade FROM unidades, printers where printers.codunidade = unidades.codunidade ORDER BY nomeunidade");
			$retval .='&nbsp;Unidade:&nbsp;
<select name="codunidade" style="font-size:8pt">
<option value="-1">:: Unidade ::</option>
';
					while ($rs = pg_fetch_row($SQLU)){
						if($rs[0] == $codunidade)
							$retval .= '<option value="'.$rs[0].'" selected>';
						else
							$retval .= '<option value="'.$rs[0].'">';
						$retval .= $rs[1]."</option>
";
					}
		}

		$retval .='
</select>&nbsp;<input type="submit" class="btnMenu" name="ok" value=" OK " onclick="javascript:return RelData(this.form);">
</td>
</tr>
<input type="hidden" name="page" value="'.((isset($this->post["page"]) && ($this->post["page"] == 0))? (int)0: 1).'">
<input type="hidden" name="flg" value="'.$this->post["flg"].'">
<input type="hidden" name="tord" value="'.$this->post["tord"].'">
<input type="hidden" name="impressao" value="0">
<input type="hidden" name="salvar" value="0">
';
		$this->campos.= $retval . '</table></form></td></tr><tr><td>
';
	}

	// Função para incluir cabeçalho de dados do usuario logado
	function CabecalhoImpressao($Matricula,$NomeUsuario,$Unidade)
	{
		$SQL = pg_query("SELECT groups.groupname as nomegrupo FROM users, groupsmembers, groups WHERE users.id = groupsmembers.userid AND groupsmembers.groupid = groups.id AND users.username = '".$Matricula."'");
		$rs = pg_fetch_array($SQL) or die ("Error: Grupo não encontrado");
		$Grupo = $rs["nomegrupo"];

		$this->Cabecalho = '<table border="0" width="100%" cellpadding="2" cellspacing="2">
<tr>
<td>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr><td class=\"titulo\">Relat&oacute;rio gerado pelo Curupira</td></tr>
<tr><td><b>Usu&aacute;rio:</b> '.$Matricula.'</td></tr>
<tr><td><b>Nome:</b> '.$NomeUsuario.'</td></tr>
<tr><td><b>Unidade:</b> '.$Unidade.'</td></tr>
<tr><td><b>Grupo:</b> '.$Grupo.'</td></tr>
</table>
</td></tr>
</table><br>
';
		echo $this->Cabecalho;
	}
	function adicionarVariaveisPaginacao($params){
		$paginaAtual;
		$opcoes;
		$indice = 0;
    $paginaAtual = (!isset($this->post["page"]) || $this->post["page"]=="")? 1:$this->post["page"];
		$this->paginacao = new Paginar($this->registros, $paginaAtual);
		if(!isset($opcoes[$indice]))
      $opcoes[$indice] = "";
    foreach($params as $key => $value){
			if(!strcasecmp($this->post["flg"], $value->idord)){
          $opcoes[$indice] .= " ORDER BY ".$key." ";
			}
		}
		if(!strcmp($this->post['tord'],'DESC')){
			$opcoes[$indice++] .= "DESC";
		}
		else $opcoes[$indice++] .= "ASC";
		$opcoes[$indice++] = $this->paginacao->retornaLimites();
		$this->sql = $this->adicionaLimites($this->sql,$opcoes);
	}
	function geraSQL($sql){
		$opcoes;
		$indice=0;
		if ($this->post["dtinicial"] != "" && $this->post["dtfinal"] != ""){
				list($DiaI,$MesI,$AnoI) = split("[/]",$this->post["dtinicial"]);
				list($DiaF,$MesF,$AnoF) = split("[/]",$this->post["dtfinal"]);
				$datainicial = $AnoI."-".$MesI."-".$DiaI;
				$datafinal = $AnoF."-".$MesF."-".$DiaF;
				$opcoes[$indice] = " date(jobdate) between  '".$datainicial."' AND '".$datafinal."'";
				$indice++;
		}
    if($_SESSION['perfil']==4 && $this->post["codunidade"] >0  ){
			$opcoes[$indice]=" users.codunidade=".(int)$this->post["codunidade"]." ";
			$indice++;
    }else if(isset($this->post["codunidade"]) && $this->post["codunidade"] >0){
			$opcoes[$indice]=" printers.codunidade=".(int)$this->post["codunidade"]." ";
			$indice++;
		}

		if(isset($opcoes))
      $this->sql= $this->adicionaAConsulta($sql,$opcoes);
    else
      $this->sql= $this->adicionaAConsulta($sql, NULL);
		return $this->sql;
	}
	function adicionaLimites($sql, $limite){
		$retval = $sql;
		foreach($limite as $value){
			$retval .= " ".$value;
		}
		return $retval;
	}

	function adicionaAConsulta($sql, $stringsAdicionar){
		if(count($stringsAdicionar) > 0){
			$arranjo = preg_split('/(where)/i', $sql);

			if(count($arranjo) != 2 && count($arranjo)!=1)
				die("Erro where encontrado duas vezes");
				$arranjo[0] .=" WHERE ";
				$fim = count($stringsAdicionar);
				for($i=0; $i<$fim ;$i++){
					$arranjo[0] .= " ".$stringsAdicionar[$i]." ";
					$arranjo[0] .= " AND ";
				}
				return $arranjo[0].$arranjo[1];
			}
			return $sql;
	}

	function tabela($params,$sql="null"){
		include('inc/conn.php');
		if(!strcasecmp($sql,"null"))
			$consulta = pg_query($this->sql)or die ('Erro consulta inv&aacute;lida '.$this->sql);
		else
		  $consulta = pg_query($sql)or die ('Erro consulta inv&aacute;lida '.$this->sql);
		$i=0;
		$this->registros = 0;
		foreach ($params as $key => $value){
			$i  = pg_field_num($consulta,$key);
			if($i != -1){
			//echo $i." ".pg_field_type($consulta,$i);
			if(ereg("float", pg_field_type($consulta,$i)) || ereg("int", pg_field_type($consulta,$i)) || ereg("numeric", pg_field_type($consulta,$i))){
				$this->tuplas[$key] = 0;
				}
			}
		}
		$i=0;
		$this->registros=pg_num_rows($consulta);
		while($array = pg_fetch_assoc($consulta)){
      if(isset($this->tuplas)){
        foreach ($this->tuplas as $key => $value){
					$this->tuplas[$key] += $array[$key];
        }
			}
			$i=0;
		}
	}

	/*Cria a tabela principal*/
	function fazTabela($params, $linhaInicial, $linhaFinal, $link){
		$sqlfinal = $this->sql;
    //echo $sqlfinal;
		if($this->salvar)
			$this->tabela ='<table border="0" width="100%">
';
		else
			$this->tabela ='<table border="0" width="100%">
';
		$this->tabela .='<tr><td class="titTable" colspan="'.count($params).'">'.$linhaInicial.'</td></tr>
';
		$consulta=pg_query($sqlfinal) or die("Erro ".$sqlfinal);
		foreach($params as $key => $value){
			if($this->salvar) $this->tabela .="<th style='overflow: hidden'>".$value->nome."</th>
";
			else{
				$this->tabela .="<th ";
				if(!strcmp($this->post['flg'],$value->idord)){
					if(!strcmp($this->post['tord'],'DESC')){
						$this->tabela .=' class="ordenadesc"><a href="#" onclick="modifica(\''.$value->idord."','ASC')\"";
					}
					else{
						$this->tabela .=' class="ordena"><a href="#" onclick="modifica(\''.$value->idord."','DESC')\"";
					}
				}else $this->tabela .= '><a href="#" onclick="modifica(\''.$value->idord."','ASC');\"";
			$this->tabela .='>'.$value->nome.'</a></th>
';
			}
		}
		/*Construindo agora os dados da tabela.*/
		$Cor = "";
		$aux = "";
		$aux1 = "";
		$arranjoRepeticao=array();

		while ($rs = pg_fetch_assoc($consulta)){
			if ($Cor == "#ffffff") $Cor = "#ECF2F8"; else $Cor = "#ffffff";
			/*Pegando os links nescessarios*/
      if(isset($link->link)){
        foreach($link->link as $key => $value){
          $link->link[$key] = $rs[$key];
        }
      }
			if($this->salvar || $this->impressao)
			$this->tabela .="<tr bgcolor=\"".$Cor."\" title=\"Detalhar ".iconv('UTF-8', 'ISO-8859-1', $rs[$link->nome])."\" onMouseOver=\"javascript: trOver(this);\" onMouseOut=\"javascript: trOut(this);\" style=\"cursor: pointer\">
";
			else
				$this->tabela .="<tr title=\"".iconv('UTF-8', 'ISO-8859-1', $rs[$link->nome])."\"bgcolor=\"".$Cor."\" onMouseOver=\"javascript: trOver(this);\" onMouseOut=\"javascript: trOut(this);\" style=\"cursor: pointer\" onClick=\"".$link->toLink()."\">
";
			foreach($params as $key => $value){
				$i = pg_field_num($consulta, $key);
				$arranjoPF;
				if(ereg("float", pg_field_type($consulta,$i))){
					if(eregi("price", $key) || eregi("preco", $key) || eregi("custo", $key))
						$this->tabela .='<td>R$ ';
					$this->tabela .="".number_format($rs[$key],2,",",".")."</td>";
				}
				elseif(ereg("int", pg_field_type($consulta,$i))){
					$this->tabela .="<td>".htmlentities($rs[$key])."</td>
";
				}
				else{
					if(strcmp(get_Class($value),"infocol")){
						if($this->salvar || $this->impressao){
							if(!strcmp(get_Class($value),"datacol"))
								$this->tabela .='<td nowrap="true">&nbsp;'.htmlentities($value->getString($rs[$key])).'&nbsp;</td>
';
							else if(!strcasecmp($value->nome,"nome"))
								$this->tabela .='<td nowrap="true">&nbsp;'.htmlentities($rs[$key]).'&nbsp;</td>
';
							else $this->tabela .='<td>&nbsp;'.htmlentities($rs[$key]).'&nbsp;</td>
';
						}else{
							if(!strcmp(get_Class($value),"estruturapf")){
								$truevalue=(strcasecmp($arranjoPF[$key],$rs[$key])) ? $rs[$key]: "";
								$arranjoPF[$key] = $rs[$key];
								$this->tabela .="<td>&nbsp;".$truevalue."&nbsp;</td>
";
							}elseif(!strcmp(get_Class($value),"stringpf")){
								$truevalue=(strcasecmp($arranjoPF[$key],$rs[$key])) ? $rs[$key]: "";
								$arranjoPF[$key] = $rs[$key];
								$this->tabela .="<td>&nbsp;".htmlentities($value->getString($truevalue))."&nbsp;</td>
";
							}
							else $this->tabela .="<td>&nbsp;".htmlentities($value->getString(iconv("utf-8", "iso-8859-1",$rs[$key])))."&nbsp;</td>
";
						}
					}
					else
							$this->tabela .="<td>&nbsp;" . htmlentities(
								($_SERVER['HTTP_REFERER'] == 'https://'.$_SERVER['HTTP_HOST'].'/relimpressoras.php')?$rs[$key]:iconv("utf-8", "iso-8859-1",$rs[$key]))."&nbsp;</td>
";
				}
			}
			$this->tabela .= "</tr>
";
		}
		$this->tabela .= '<tr class="titTable">';
		/*Agora vem a ultima linha da tabela.*/
		foreach ($linhaFinal as $key => $value){
			$this->tabela .='<td colspan="'.$value->tam.'" align="'.$value->align.'">';
			if($value->valido){
				if(ereg("registros", $value->nome)){
					$arranjo = preg_split('/(registros)/', $value->nome);
					$this->tabela .="".number_format($this->registros,0,",",".").$arranjo[1]."";
				}else{
					$i = pg_field_num($consulta, $value->nome);
					if(ereg("float", pg_field_type($consulta,$i))){
						if(eregi("price", $value->nome) || eregi("preco", $value->nome) || eregi("custo", $value->nome))
							$this->tabela .="R$ ";
						$this->tabela .="".number_format($this->tuplas[$value->nome],2,",",".")."</td>";
					}
					elseif(ereg("int", pg_field_type($consulta,$i)) || ereg("numeric", pg_field_type($consulta,$i))){
						$this->tabela .="".number_format($this->tuplas[$value->nome],0,",",".")."";
					}
				}
			}
			else $this->tabela .="&nbsp;".$value->nome."&nbsp;";
		}
		$this->tabela .= "</tr>
</table>
";
	}
	function varPaginacao(){
		if($this->impressao || $this->salvar){
			return;
		}
			$this->barraPagina = '<div align="center">'.$this->paginacao->retornaBarraPaginas()."</div>
";
	}

	function head(){
		if(!isset($_SESSION))
      session_start();
		include('inc/valida_session.php');
		include('inc/conn.php');
		$opcaoParaPaginasQueNaoImprimem =1;
		$printing = $this->impressao;
		$enderecoclique = $this->end;
		include('inc/head.php');

		if($this->salvar){
			header("Content-type: application/vnd.ms-excel");//x-download
			header("Expires: 0");
			header("Cache-Control: post-check=0, pre-check=0");
			header("Content-Disposition: attachment; filename=arq.xls");
			header('Pragma: public');
			$this->body='		<script language="javascript" src="script/visual.js" type="text/javascript"></script>
		<script language="JavaScript" src="script/general_functions.js"></script>
		<script language="JavaScript" src="script/funcoes.js"></script>
';
			return;
		}
		$this->header=$head;
		$this->body=$body;
	}

	function foot(){
		$opcaoParaPaginasQueNaoImprimem =1;
		include("inc/foot.php");
		if($this->salvar) return;
		if($this->impressao){
			$this->footer='<div align="left"><input type="button" class="btnMenu" value="Imprimir" onclick="window.print()"></div><br/></body></html>';
			return;
		}
		$this->footer=$footer;
	}
}

