var EgretFunnelChart = (function() {

	var title = '';
	var subtext = '';
	var legend = {};
	var series = [];
	var legendData = [];

	function EgretFunnelChart() {
	}

	EgretFunnelChart.init = function(title, subtext) {
		this.title = title;
		this.subtext = subtext;
		this.series = [];
		this.legendData = [];
	}

	EgretFunnelChart.setData = function(data, width, x, itemStyle) {
		var seriesItem = {"type":"funnel", "data":[]};
		if (width != undefined && width != '' && width != null) {
			seriesItem["width"] = width;
		}

		if (x != undefined && x != '' && x != null) {
			seriesItem["x"] = x;
		}

		if (itemStyle != undefined && itemStyle != '' && itemStyle != null) {
			seriesItem["itemStyle"] = itemStyle;
		}

		for (m in data) {
			var row = {};
			row.name = m;
			row.value = data[m];
			seriesItem.data.push(row);
			if (this.legendData.indexOf(m) == -1) {
				this.legendData.push(m);
			}
		}
		this.series.push(seriesItem);
	}

	EgretFunnelChart.show = function(id) {
		var myChart = echarts.init(document.getElementById(id));
		myChart.setOption({
			title : {
				text : this.title,
				subtext : this.subtext,
				x : 'center'
			},
			tooltip : {
				trigger : 'item',
				formatter : "{b} : {c} ({d}%)",
				showDelay : 0,
				transitionDuration : 0.2
			},
			legend : {
				orient : 'vertical',
				x : 'left',
				y : 'center',
				show : true,
				data : this.legendData,
			},
			calculable : true,
			series : this.series
		});
	}

	return EgretFunnelChart;
})();

EgretFunnelChart.prototype.__class__ = "EgretFunnelChart";

var EgretPieChart = (function() {

	var title = '';
	var subtext = '';
	var legend = {};
	var series = [];
	var legendData = [];
	var noLegend = false;

	function EgretPieChart() {
	}

	EgretPieChart.init = function(title, subtext, noLegend) {
		this.title = title;
		this.subtext = subtext;
		this.series = [];
		this.legendData = [];
		this.noLegend = noLegend;
	}

	EgretPieChart.setData = function(data, radius, center, itemStyle) {
		var seriesItem = {"type":"pie", "radius":"", "center":"", "data":[], "itemStyle":""};
		if (radius != undefined && radius != '' && radius != null) {
			seriesItem["radius"] = radius;
		} else {
			seriesItem["radius"] = '70%';
		}

		if (center != undefined && center != '' && center != null) {
			seriesItem["center"] = center;
		} else {
			seriesItem["center"] = [ '50%', '50%' ];
		}

		if (itemStyle != undefined && itemStyle != '' && itemStyle != null) {
			seriesItem["itemStyle"] = itemStyle;
		} else {
			seriesItem["itemStyle"] = {};
		}

		for (m in data) {
			var row = {};
			row.name = m;
			row.value = data[m];
			seriesItem.data.push(row);
			if (this.noLegend != true) {
				if (this.legendData.indexOf(m) == -1) {
					this.legendData.push(m);
				}
			}
		}
		this.series.push(seriesItem);
	}

	EgretPieChart.show = function(id) {
		var myChart = echarts.init(document.getElementById(id), 'macarons');
		myChart.setOption({
			title : {
				text : this.title,
				subtext : this.subtext,
				x : 'center'
			},
			tooltip : {
				trigger : 'item',
				formatter : "{b} <br/> {c} ({d}%)",
				showDelay : 0,
				transitionDuration : 0.2
			},
			legend : {
				orient : 'vertical',
				x : 'left',
				y : 'center',
				show : false,
				data : this.legendData,
			},
			calculable : true,
			series : this.series,
		});

		return myChart;
	}

	return EgretPieChart;
})();

EgretPieChart.prototype.__class__ = "EgretPieChart";

var EgretChart = (function() {

	var title = '';
	var subtext = '';
	var legend = {};
	var series = [];
	var xAxis = [];
	var yAxis = [];
	var grid = {};
	var xyChange = false;
	var dataSort = true;
	function EgretChart() {
	}

	EgretChart.init = function(title, subtext, grid, xyChange, dataSort) {
		this.title = title;
		this.subtext = subtext;
		this.legend = {
			"data" : []
		};
		this.series = [];
		this.xAxis = [];
		this.yAxis = [];
		if (grid) {
			this.grid = grid;
		}
		this.xyChange = xyChange;
		if (dataSort == false) {
			this.dataSort = dataSort;
		} else {
			this.dataSort = true;
		}
	}

	EgretChart.setYAxis = function(yAxis) {
		this.yAxis = yAxis;
	}

	EgretChart.setData = function(charType, legendName, data, stack, yAxisIndex, itemStyle) {
		this.legend.data.push(legendName);
		if (stack == true) {
			var seriesData = {
				"name" : legendName,
				"type" : charType,
				"data" : [],
				"itemStyle" : {},
				"stack" : "总量"
			};
		} else {
			var seriesData = {
				"name" : legendName,
				"type" : charType,
				"itemStyle" : {},
				"data" : []
			};
		}

		if (itemStyle != undefined) {
			seriesData.itemStyle = itemStyle;
		}

		if (yAxisIndex != undefined) {
			if (this.xyChange != true) {
				seriesData.yAxisIndex = yAxisIndex;
			}
		}

		for (m in data) {
			seriesData.data.push(data[m]);
		}
		this.series.push(seriesData);

		if (this.xAxis == '') {
			var xAxisData = {
				"type" : "category",
				"boundaryGap":true,
				"data" : []
			};
			for (m in data) {
				xAxisData.data.push(m);
			}
			this.xAxis.push(xAxisData);
		}
	}

	EgretChart.show = function(id) {
		if (this.xyChange == true) {
			var yAxis = this.yAxis;
			this.yAxis = this.xAxis;
			this.xAxis = [{type : 'value',boundaryGap : [0, 0.01]}];
			if (yAxis.length > 0) {
				this.xAxis = yAxis;
			}
		}

		var tooltip = {};
		if (this.dataSort == true) {
			tooltip = {
				trigger : 'axis',
				showDelay : 0,
				transitionDuration : 0.1,
				formatter: function (params,ticket,callback) {
		            var index = {}, data = [];
		            var l = params.length;
		            for (var i = 0; i < l; i++) {
		            	data.push(params[i].value);
		            	// dataGame[] = params[i].seriesName;
		            }
		            data.sort(function(a,b){return a<b?1:-1});
		            var getName = function(v) {
		            	for (var k = 0; k < l; k ++) {
		            		if (v == params[k].value && typeof index[params[k].seriesIndex] == "undefined") {
		            			index[params[k].seriesIndex] = 1;
		            			return params[k].seriesName;
		            		}
		            	}
		            }
		            var res = params[0].name;
		            for (var j = 0; j < l; j ++) {
		            	getName();
		            	res += '<br/>' + getName(data[j]) + ' : ' + EgretChart.String.numberFormat(data[j]);
		            }
		            setTimeout(function (){
		                callback(ticket, res);
		            }, 300)
		            return 'loading...';
		        }
		    };
		} else {
			tooltip = {
				trigger : 'axis',
				showDelay : 0,
				transitionDuration : 0.2
			};
		}
		
		var myChart = echarts.init(document.getElementById(id), 'macarons');
		myChart.setOption({
			title : {
				text : this.title,
				subtext : this.subtext
			},
			tooltip : tooltip,
			legend : this.legend,
			calculable : true,
			xAxis : this.xAxis,
			yAxis : this.yAxis,
			series : this.series,
			grid : this.grid,
		});

		this.grid = {};
		return myChart;
	}

	EgretChart.String = {
	    //格式化数字
	    numberFormat:function (s,l){
	        if (!l || l < 1)l = 3;
	        s=String(s).split(".");
	        s[0]=s[0].replace(new RegExp('(\\d)(?=(\\d{'+l+'})+$)','ig'),"$1,");
	        return s.join(".");
	    }
	};

	return EgretChart;
})();

EgretChart.prototype.__class__ = "EgretChart";