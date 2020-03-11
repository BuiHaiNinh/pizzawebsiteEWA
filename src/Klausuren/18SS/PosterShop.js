function start() {
	"use strict";
	document.getElementById('allPosters').options[0].selected = true;
	show(0);
}

function nextPoster() {
	"use strict";
	var i;
	var myselect = document.getElementById('allPosters');
	var options = myselect.options;
	i=myselect.selectedIndex;
	i = i + 1;
	if (i >= options.length)
		i = 0;
	options[i].selected = true;
	show(i);
}

function show(nr) {
	"use strict";
	var sel = document.getElementById('allPosters');
	var pict = sel.options[nr].text;
	document.getElementById('poster').src = 'Images/' + pict;
}
