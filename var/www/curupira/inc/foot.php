<?php
$footer='';

function imprimeFooter($foot,$opcao){
	if(!$opcao)
	echo $foot;
}
if(isset($opcaoParaPaginasQueNaoImprimem))
  imprimeFooter($footer, $opcaoParaPaginasQueNaoImprimem);
else
  imprimeFooter($footer, NULL);
?>
