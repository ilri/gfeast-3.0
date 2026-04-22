
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
	    graphTab1.find('img').prop('src', 'img/Bar-selected.svg');
	    tableTab1.find('img').prop('src', 'img/Table.svg');
	});

	tableTab1.on("click", () => {
	    tableTab1.addClass("active");
	    graphTab1.removeClass("active");
	    table1.show();
	    graph1.hide();
	    graphTab1.find('img').prop('src', 'img/Bar.svg');
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
	    graphTab2.find('img').prop('src', 'img/Map-selected.svg');
	    tableTab2.find('img').prop('src', 'img/Table.svg');
	});

	tableTab2.on("click", () => {
	    tableTab2.addClass("active");
	    graphTab2.removeClass("active");
	    table2.show();
	    graph2.hide();
	    graphTab2.find('img').prop('src', 'img/Map.svg');
	    tableTab2.find('img').prop('src', 'img/Table-selected.svg');
	});

	downloadTab2.on("click", () => {
	if(downloadTab2.attr('src')=='img/download.svg'){
		downloadTab2.prop('src', 'img/Download-selected.svg')
	} else if(downloadTab2.attr('src')=='img/Download-selected.svg'){
		downloadTab2.prop('src', 'img/download.svg')
	}
	});



//graph-3
	const graphTab3 = $("#graph-btn-3");
	const tableTab3 = $("#table-btn-3");
	const downloadTab3 = $("#download-btn-3>img");

	const graph3 = $("#graph-3");
	const table3 = $("#table-3");

	graphTab3.on("click", () => {
	    graphTab3.addClass("active");
	    tableTab3.removeClass("active");
	    graph3.show();
	    table3.hide();
	     graphTab3.find('img').prop('src', 'img/Sankey-selected.svg');
	    tableTab3.find('img').prop('src', 'img/Table.svg');
	});

	tableTab3.on("click", () => {
	    tableTab3.addClass("active");
	    graphTab3.removeClass("active");
	    table3.show();
	    graph3.hide();
	    graphTab3.find('img').prop('src', 'img/Sankey.svg');
	    tableTab3.find('img').prop('src', 'img/Table-selected.svg')
	});
	downloadTab3.on("click", () => {
	if(downloadTab3.attr('src')=='img/download.svg'){
		downloadTab3.prop('src', 'img/Download-selected.svg')
	} else if(downloadTab3.attr('src')=='img/Download-selected.svg'){
		downloadTab3.prop('src', 'img/download.svg')
	}
	});





	//graph-4
	const graphTab4 = $("#graph-btn-4");
	const tableTab4 = $("#table-btn-4");
	const downloadTab4 = $("#download-btn-4>img");

	const graph4 = $("#graph-4");
	const table4 = $("#table-4");

	graphTab4.on("click", () => {
	    graphTab4.addClass("active");
	    tableTab4.removeClass("active");
	    graph4.show();
	    table4.hide();
	    graphTab4.find('img').prop('src', 'img/Bar-selected.svg');
	    tableTab4.find('img').prop('src', 'img/Table.svg');
	});

	tableTab4.on("click", () => {
	    tableTab4.addClass("active");
	    graphTab4.removeClass("active");
	    table4.show();
	    graph4.hide();
	    graphTab4.find('img').prop('src', 'img/Bar.svg');
	    tableTab4.find('img').prop('src', 'img/Table-selected.svg')
	});
	downloadTab4.on("click", () => {
	if(downloadTab4.attr('src')=='img/download.svg'){
		downloadTab4.prop('src', 'img/Download-selected.svg')
	} else if(downloadTab4.attr('src')=='img/Download-selected.svg'){
		downloadTab4.prop('src', 'img/download.svg')
	}
	});


	//graph-5
	const graphTab5 = $("#graph-btn-5");
	const tableTab5 = $("#table-btn-5");
	const downloadTab5 = $("#download-btn-5>img");

	const graph5 = $("#graph-5");
	const table5 = $("#table-5");

	graphTab5.on("click", () => {
	    graphTab5.addClass("active");
	    tableTab5.removeClass("active");
	    graph5.show();
	    table5.hide();
	    graphTab5.find('img').prop('src', 'img/Bar-selected.svg');
	    tableTab5.find('img').prop('src', 'img/Table.svg');
	});

	tableTab5.on("click", () => {
	    tableTab5.addClass("active");
	    graphTab5.removeClass("active");
	    table5.show();
	    graph5.hide();
	    graphTab5.find('img').prop('src', 'img/Bar.svg');
	    tableTab5.find('img').prop('src', 'img/Table-selected.svg');
	});
	downloadTab5.on("click", () => {
	if(downloadTab5.attr('src')=='img/download.svg'){
		downloadTab5.prop('src', 'img/Download-selected.svg')
	} else if(downloadTab5.attr('src')=='img/Download-selected.svg'){
		downloadTab5.prop('src', 'img/download.svg')
	}
	});



	//graph-6
	const graphTab6 = $("#graph-btn-6");
	const tableTab6 = $("#table-btn-6");
	const downloadTab6 = $("#download-btn-6>img");

	const graph6 = $("#graph-6");
	const table6 = $("#table-6");

	graphTab6.on("click", () => {
	    graphTab6.addClass("active");
	    tableTab6.removeClass("active");
	    graph6.show();
	    table6.hide();
	    graphTab6.find('img').prop('src', 'img/Pie-selected.svg');
	    tableTab6.find('img').prop('src', 'img/Table.svg');
	});

	tableTab6.on("click", () => {
	    tableTab6.addClass("active");
	    graphTab6.removeClass("active");
	    table6.show();
	    graph6.hide();
	    graphTab6.find('img').prop('src', 'img/Pie.svg');
	    tableTab6.find('img').prop('src', 'img/Table-selected.svg');
	});
	downloadTab6.on("click", () => {
	if(downloadTab6.attr('src')=='img/download.svg'){
		downloadTab6.prop('src', 'img/Download-selected.svg')
	} else if(downloadTab6.attr('src')=='img/Download-selected.svg'){
		downloadTab6.prop('src', 'img/download.svg')
	}
	});

	