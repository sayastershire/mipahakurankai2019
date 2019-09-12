function openForm(evt, subeventName) {
	var i, tabcontent, tablinks;

	tabcontent = document.getElementsByClassName("registration-tab-content");
	for (i = 0; i < tabcontent.length; i++){
		tabcontent[i].style.display = "none";
	}

	tablinks = document.getElementsByClassName("registration-events");
	for (i = 0; i < tablinks.length; i++){
		tablinks[i].className = tablinks[i].className.replace(" active", "");
	}

	document.getElementById(subeventName).style.display = "block";
	evt.currentTarget.className += " active";
}