
function show_window(url,name,x,y){
	var showpop;
	showpop = window.open(url,name,'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,menubar=no,width='+ x +',height='+ y +',screenX=0,screenY=0,top=0,left='+(window.screen.width - screen.availWidth));
	showpop.resizeTo(x,y);
	return;
}

/* Esta função recebe o nome de um formulário e um action e o submete caso a solicitação seja confirmada */
function confirmExcluir(formname, formaction){
	if (confirm("Tem certeza que deseja excluir o(s) item(s) selecionado(s)?")){
		document.forms[formname].action = formaction;
		document.forms[formname].submit();
	}
  //return true;
}

function FormataData(formname, elementname, teclapres){
//chamada: <input name="dtnasc" onKeyUp="FormataData('formname', 'dtnasc', event);">
	if (document.forms[formname])
	{
		var num = "0123456789";
		var tecla = teclapres.keyCode;
		var vr = document.forms[formname].elements[elementname].value;
		vr = vr.substr(0, 10);
		if ( tecla != 9 && tecla != 8 ){
			for (var i = 0; i< vr.length; i++)
			{
				if (num.indexOf(vr.charAt(i)) == -1){vr = vr.replace(vr.charAt(i), ""); i--;}
			}
			document.forms[formname].elements[elementname].value = vr;
			var tam = vr.length + 1;

			if ( tam > 2 && tam < 5 )
			document.forms[formname].elements[elementname].value = vr.substr( 0, 2 ) + '/' + vr.substr( 2, 2 );
			if ( tam >= 5)
			document.forms[formname].elements[elementname].value = vr.substr( 0, 2 ) + '/' + vr.substr( 2, 2 ) + '/' + vr.substr( 4, 4 );
		}
	}
}

function FormataFloat(formname,elementname,decimal,separador,maximo,teclapres)
{
  //chamanda: <input name="float" onKeyUp="FormataFloat('formname', 'float', 2, ',', 5, event);"
	if (document.forms[formname])
	{
		var num = "0123456789";
		var tecla = teclapres.keyCode;
		var vr = document.forms[formname].elements[elementname].value;
		vr = vr.substr(0,maximo);
		if ( tecla != 9 && tecla != 8 ){
			for (var i = 0; i< vr.length; i++)
			if (num.indexOf(vr.charAt(i)) == -1){vr = vr.replace(vr.charAt(i), ""); i--;}
			document.forms[formname].elements[elementname].value = vr;
			var tam = vr.length;

			if ((tam > decimal) && (decimal > 0))
			document.forms[formname].elements[elementname].value = vr.substr( 0, (vr.length-decimal) ) + separador + vr.substr( (vr.length-decimal), decimal);
		}
	}
}

function FormataHora(formname,elementname,teclapres)
{
  //chamada: <input name="dtnasc" onKeyUp="FormataData('formname', 'dtnasc', event);">
	if (document.forms[formname])
	{
		var num = "0123456789:";
		var tecla = teclapres.keyCode;
		var vr = document.forms[formname].elements[elementname].value;
		vr = vr.substr(0,5);
		if ( tecla != 9 && tecla != 8 )
		{
			for (var i = 0; i< vr.length; i++)
			if (num.indexOf(vr.charAt(i)) == -1){vr = vr.replace(vr.charAt(i), ""); i--;}
			document.forms[formname].elements[elementname].value = vr;
			var tam = vr.length + 1;

			if (tam > 2 && tam < 5)
			document.forms[formname].elements[elementname].value = vr.substr(0,2) + ':' + vr.substr(2,2);
		}
	}
}

function CountMaxChar(formname, elementname, name, maxcharname, maxchar) {
//chamada: <textarea name="Mensagem1" cols="15" rows="3" onChange="javascript:CountMaxChar('formname', 'Fielname', 'Descricao', 'maxcaracteres', 200);" onKeyUp="javascript:CountMaxChar('formname', 'Fielname', 'Descricao', maxcaracteres, 200););"></textarea>
	var Texto = document.forms[formname].elements[elementname].value;
	document.forms[formname].elements[maxcharname].value = maxchar - Texto.length;

	if (document.forms[formname].elements[maxcharname].value < 0) {
		document.forms[formname].elements[maxcharname].value = 0;
		document.forms[formname].elements[elementname].value = Texto.substring(0, maxchar);
		alert('O campo "' + name + '" não deve ter mais que "' + maxchar + '" caracteres.');
	}
}

function numDaysFeb(ano)
{
	// Fevereiro tem 29 dias em anos divisiveis por 4, exceto se divisíveis por 100 e não por 400
	return (((ano % 4 == 0) && ( (!(ano % 100 == 0)) || (ano % 400 == 0))) ? 29 : 28 );
}

//Função que gera mensagens para uma data de entrada invalida. Não considera a DATA obrigatória
function ValidaDataMsg(dtStr, name)
{
	var minYear=1900;
	var maxYear=2100;
	var MonthDays = new Array(12)
			for(var i=0;i<12;i++)
			MonthDays[i] = new Array(2)
					MonthDays[0][0] = "Janeiro"
					MonthDays[0][1] = 31
					MonthDays[1][0] = "Fevereiro"
					MonthDays[1][1] = 29
					MonthDays[2][0] = "Marco"
					MonthDays[2][1] = 31
					MonthDays[3][0] = "Abril"
					MonthDays[3][1] = 30
					MonthDays[4][0] = "Maio"
					MonthDays[4][1] = 31
					MonthDays[5][0] = "Junho"
					MonthDays[5][1] = 30
					MonthDays[6][0] = "Julho"
					MonthDays[6][1] = 31
					MonthDays[7][0] = "Agosto"
					MonthDays[7][1] = 31
					MonthDays[8][0] = "Setembro"
					MonthDays[8][1] = 30
					MonthDays[9][0] = "Outubro"
					MonthDays[9][1] = 31
					MonthDays[10][0] = "Novembro"
					MonthDays[10][1] = 30
					MonthDays[11][0] = "Dezembro"
					MonthDays[11][1] = 31

					var valid = true;

			var num = "0123456789/";
			for (var i = 0; i< dtStr.length; i++)
		//caso caracter inválido
			if (num.indexOf(dtStr.charAt(i)) == -1) valid = false;

			var pos1=dtStr.indexOf("/");
			var pos2=dtStr.indexOf("/",pos1+1);
			var strDay=dtStr.substring(0,pos1);
			var strMonth=dtStr.substring(pos1+1,pos2);
			var strYear=dtStr.substring(pos2+1);
			if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1)
			if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1)
	//tira zeros a esquerda
			for (var i = 1; i <= 3; i++) {
				if (strYear.charAt(0)=="0" && strYear.length>1) strYear=strYear.substring(1)
			}
			month=parseInt(strMonth)
					day=parseInt(strDay)
					year=parseInt(strYear)

					if (pos1==-1 || pos2==-1 || dtStr.indexOf("/",pos2+1)!=-1 || !valid ){
				alert('O formato correto para o campo "' + name + '" deve ser : dd/mm/aaaa');
				valid = false;
					}
					else if (strYear.length != 4 || year==0 || year<minYear || year>maxYear){
						alert('O ano no campo "' + name + '" deve conter 4 digitos entre ' + minYear + ' e ' + maxYear);
						valid =  false;
					}
					else if (month<1 || month>12){
						alert('O mês no campo "' + name + '" deve estar entre 01 e 12.');
						valid = false;
					}
					else if ((day<1 || day>31) || (month==2 && day>numDaysFeb(year)) || (day > MonthDays[month-1][1])){
						if (month==2)
						alert('O dia no campo "' + name + '" deve conter 2 digitos. \nPara o mês '+ MonthDays[month-1][0] + ' deve estar entre 01 e ' + numDaysFeb(year));
						else
							alert('O dia no campo "' + name + '" deve conter 2 digitos. \nPara o mês '+ MonthDays[month-1][0] + ' deve estar entre 01 e ' + MonthDays[month-1][1]);
						valid = false;
					}

					return valid
}

//Função que gera mensagens para uma data de entrada invalida e dá o foco no campo do formulario.
function ValidaDt(formname, elementname, name)
{
	if(document.forms[formname].elements[elementname].value != '')
	{
		if (!ValidaDataMsg(document.forms[formname].elements[elementname].value, name))
		{
			document.forms[formname].elements[elementname].select();
			document.forms[formname].elements[elementname].focus();
			return false;
		}
	}
	return true;
}

function modificaPagina(valor1){
	document.frm_data.page.value=valor1;
	document.frm_data.submit();
}

function modificaSalvar(valor1){
	tmp=document.frm_data.page.value;
	document.frm_data.salvar.value=valor1;
	document.frm_data.page.value=0;
	document.frm_data.submit();
	document.frm_data.salvar.value=0;
	document.frm_data.page.value=tmp;
}
/*Modifica os valores da ordenação*/
function modifica(valor1, valor2){
	document.frm_data.flg.value=valor1;
	document.frm_data.tord.value=valor2;
	document.frm_data.submit();
}

/*Verifica os campos de data: dtinicial, dtfinal; antes do submit.*/
function RelData(formulario)
{
	if(
	(formulario.codunidade.value !=0) &&
	(formulario.dtinicial.value == '') &&
	(formulario.dtfinal.value == '')
		){
		formulario.action = window.location.href;
		formulario.submit();
		return true;
		}
		if (formulario.dtinicial.value == '')
		{

			alert('O Campo "Data Inicial" deve ser preenchido.');
			formulario.dtinicial.focus();
			return false;
		}
		if(!ValidaDt(formulario.name,'dtinicial','Data Inicial')){
			return false;
		}

		if (formulario.dtfinal.value == '')
		{
			alert('O Campo "Data Final" deve ser preenchido.');
			formulario.dtfinal.focus();
			return false;
		}

		if(!ValidaDt(formulario.name,'dtfinal','Data Final')){
			return false;
		}

		formulario.action = window.location.href;
		formulario.submit();
}


	/*Verifica os campos de data: dtinicial, dtfinal; antes do submit.*/
function RelGrafico(formulario)
{
	if (formulario.dtinicial.value == '')
	{

		alert('O Campo "Data Inicial" deve ser preenchido.');
		formulario.dtinicial.focus();
		return false;
	}
	if(!ValidaDt(formulario.name,'dtinicial','Data Inicial')){
		return false;
	}

	if (formulario.dtfinal.value == '')
	{
		alert('O Campo "Data Final" deve ser preenchido.');
		formulario.dtfinal.focus();
		return false;
	}

	if(!ValidaDt(formulario.name,'dtfinal','Data Final')){
		return false;
	}

	formulario.action = window.location.href;
	formulario.submit();
}

function ValidaCampo(formname, elementname, name)
//chamada: <input name="nome" onKeyUp="ValidaCampo('formname', 'nome', 'Descricao');">
{
	if (document.forms[formname])
	if (document.forms[formname].elements[elementname])
	{
		var invalidchar = "'";
		var Texto = document.forms[formname].elements[elementname].value;
		for (var i = 0; i< Texto.length; i++)
		{
			if (invalidchar.indexOf(Texto.charAt(i)) != -1){Texto = Texto.replace(Texto.charAt(i), ""); i--;}
		}
		document.forms[formname].elements[elementname].value = Texto;
	}
}
