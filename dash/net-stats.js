$(function(){
	//Auto-format times to local
	$("time").each(function(){
		$(this).text((new Date($(this).data('unix') * 1000)).toLocaleString());
	});

	//Highcharts
	$("#speed-container").highcharts({
		chart: {
			type: "spline",
		},
		title:{
			text: "Last <?=$limit?> readings"
		},
		data: {
			table: "speeds",
		},
	});
});