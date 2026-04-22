
//graph-1
const graphTab1 = $("#graph-btn-1");
const tableTab1 = $("#table-btn-1");
const downloadTab1 = $("#download-btn-1>img");

	const graph1 = $("#graph-1");
	const table1 = $("#table-1");

	graphTab1.on("click", () => {
	    graphTab1.addClass("active");
	    tableTab1.removeClass("active");
	    graph1.show();
	    table1.hide();
	    graphTab1.find('img').prop('src', 'img/Pie-selected.svg');
	    tableTab1.find('img').prop('src', 'img/Table.svg');
	});

	tableTab1.on("click", () => {
	    tableTab1.addClass("active");
	    graphTab1.removeClass("active");
	    table1.show();
	    graph1.hide();
	    graphTab1.find('img').prop('src', 'img/Pie.svg');
	    tableTab1.find('img').prop('src', 'img/Table-selected.svg')
	});

	downloadTab1.on("click", () => {
	if(downloadTab1.attr('src')=='img/download.svg'){
		downloadTab1.prop('src', 'img/Download-selected.svg')
	} else if(downloadTab1.attr('src')=='img/Download-selected.svg'){
		downloadTab1.prop('src', 'img/download.svg')
	}
	});





//graph-2
	const graphTab2 = $("#graph-btn-2");
	const tableTab2 = $("#table-btn-2");
	const downloadTab2 = $("#download-btn-2>img");

	const graph2 = $("#graph-2");
	const table2 = $("#table-2");

	graphTab2.on("click", () => {
	    graphTab2.addClass("active");
	    tableTab2.removeClass("active");
	    graph2.show();
	    table2.hide();
	    graphTab2.find('img').prop('src', 'img/Bar-selected.svg');
	    tableTab2.find('img').prop('src', 'img/Table.svg');
	});

	tableTab2.on("click", () => {
	    tableTab2.addClass("active");
	    graphTab2.removeClass("active");
	    table2.show();
	    graph2.hide();
	    graphTab2.find('img').prop('src', 'img/Bar.svg');
	    tableTab2.find('img').prop('src', 'img/Table-selected.svg')
	});

	downloadTab2.on("click", () => {
	if(downloadTab2.attr('src')=='img/download.svg'){
		downloadTab2.prop('src', 'img/Download-selected.svg')
	} else if(downloadTab2.attr('src')=='img/Download-selected.svg'){
		downloadTab2.prop('src', 'img/download.svg')
	}
	});
	