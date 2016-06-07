<?php
class Paginar{
	var $NumTotalRegistros;
	var $NumRegistrosPagina;
	var $PaginaCorrente;
	var $NumPaginas;

	function Paginar($registros,$pagina){
		$this->NumRegistrosPagina=15;
		$this->NumTotalRegistros=$registros;
		if($this->NumTotalRegistros==false)/*Caso em que a pesquisa retorna zero tuplas.*/{
			$this->NumTotalRegistros=1;
		}
		$this->PaginaCorrente = ($pagina<0)? 1: $pagina;
		$this->NumPaginas=ceil($this->NumTotalRegistros/$this->NumRegistrosPagina);
		if($this->PaginaCorrente ==0){
			$this->NumPaginas=1;
		}
	}

	function retornaLimites(){
		$retval = 'LIMIT ';
		if($this->PaginaCorrente ==0){
			$retval .= $this->NumTotalRegistros."  ";
			$LimInf = " OFFSET 0";
		}
		else{
			$retval .= "$this->NumRegistrosPagina  ";
			$LimInf = " OFFSET ".($this->NumRegistrosPagina*($this->PaginaCorrente-1));
		}
		$retval .= $LimInf;
		return $retval;
	}

	function setaPaginaAtual($pagina){
		if(isset($pagina))
			$this->PaginaCorrente = ($pagina<0)? 1: $pagina;
		else $this->PaginaCorrente = 1;
		if($this->PaginaCorrente ==0){
			$this->NumPaginas=1;
		}
	}

	function retornaBarraPaginas(){
		$retval=" ";
		if($this->PaginaCorrente == 0){
			$retval.='<div align="right"><input type="button" class="btnMenu" value="Incluir Pagina&ccedil;&atilde;o" onclick="modificaPagina(1);"></div>
';
			return $retval;
		}

		if($this->NumPaginas == 1){
			return $retval;
		}
		$retval.='<span class ="LinkPaginacao">
<div align="right"><input type="button" class="btnMenu" value="Excluir Pagina&ccedil;&atilde;o" onclick="modificaPagina(0);"></div>
';
		if($this->PaginaCorrente > 1){
			$pageprev = $this->PaginaCorrente-1;
			$retval.='<a href="#" onclick="modificaPagina('.$pageprev.');"><< Anterior</a>&nbsp;
';
		}

		if($this->PaginaCorrente >6){
				$retval .= '...';
		}

		for($i = $this->PaginaCorrente-5 ;($i<=$this->NumPaginas)&&($i <= $this->PaginaCorrente+5); $i++){
			if($i<=0) $i=1;

			if($this->PaginaCorrente == $i){
					$retval.=$i."&nbsp;";
			}else {
				$retval.='<a href="#" onclick="modificaPagina('.$i.');">'.$i.'</a>&nbsp;
';
			}
			}

			if($this->PaginaCorrente < $this->NumPaginas-5){
				$retval .= '...';
			}


		if($this->PaginaCorrente < $this->NumPaginas){
			$pagenext = ($this->PaginaCorrente + 1);
			$retval.= '<a href="#" onclick="modificaPagina('.$pagenext.');">Pr&oacute;xima >></a>
';
		}

		$retval.='<br />
<br /><a href="#" onclick=" modificaPagina(1);">Primeira</a>&nbsp;
<a href="#" onclick="modificaPagina('.$this->NumPaginas.');">&Uacute;ltima</a>
<br />
</span>';
		return $retval;
	}
}
?>
