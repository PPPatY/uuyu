
var lossPlayerChart = function() {
    EgretChart.init('');
	EgretChart.setYAxis([{name : '流失', axisLabel:{formatter: '{value} '}},{name : '流失率', axisLabel:{formatter: '{value} %'}}]);
    EgretChart.setData('line','每日流失',dataJson.lossplayer.loss);
    EgretChart.setData('line','每日流失率',dataJson.lossplayer.lossrate,false,1);
    
	EgretChart.show('lossPlayerChart');
}
var lossMoneyChart = function() {

    EgretChart.init('');
	EgretChart.setYAxis([{name : '流失', axisLabel:{formatter: '{value} 人'}}]);
    EgretChart.setData('line','',dataJson.lossmoney);
    
	EgretChart.show('lossMoneyChart');
}
var lossTimesChart = function() {

    EgretChart.init('');
	EgretChart.setYAxis([{name : '流失', axisLabel:{formatter: '{value} 人'}}]);
    EgretChart.setData('line','',dataJson.losstimes);
    
	EgretChart.show('lossTimesChart');
}

$("#generalTabs a").click(function(event){
    $("#generalTabs a").removeClass("active");
    $(this).addClass("active");
    var value = $(this).attr("data");

    var labels = $("#generalTabs a");

    labels.each(function(){
    	var label = $(this).attr("data")
    	$("#" + label).hide();
    });

    $("#" + value).show();


    if (value == "hourData") {
        $("#hourDataDiv").show();
        var fun = "showHourData";
    } else {
        $("#hourDataDiv").hide();
        var fun = value + "Chart";
    }
    doCallback(eval(fun));

    event.preventDefault();
});