function executaMenu(url){
	if(url.substring(0,11) == 'window.open')
		window.open("http://" + url.substr(11));
	else
	if(url != '-1')
		window.location.href=url;
	}
