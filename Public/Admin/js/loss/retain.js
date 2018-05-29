var retainChart = function() {
	EgretChart.init('');
	EgretChart.setYAxis([{name : lan_sa_retention, axisLabel:{formatter: '{value} %'}}]);
	EgretChart.setData('line', lan_sa_retention_one, dataJson["1"]);
	EgretChart.setData('line', lan_sa_retention_three, dataJson["3"]);
	EgretChart.setData('line', lan_sa_retention_seven, dataJson["7"]);
	EgretChart.show('retainChart');
};