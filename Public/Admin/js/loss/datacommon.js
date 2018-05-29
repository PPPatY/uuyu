// headers = {0:{sorter:false}};
var tablePage = function (source, target, pageId, tds, sortTable, headers, fun, args) {
	var num_entries = $("#" + source + " tr[name='rows']").length;
    $("#" + pageId).pagination(num_entries, {
        num_edge_entries: 1, //边缘页数
        num_display_entries: 4, //主体页数
        prev_text: lan_pre_page,
        next_text: lan_next_page,
        callback: pageselectCallback,
        items_per_page: 10 //每页显示10项
    });
    
    function pageselectCallback(page_index, jq){
	 	$("#" + target + "").empty();
    	var items_per_page = 10;
		var max_elem = Math.min((page_index+1) * items_per_page, num_entries);
		if ($("#" + target + " thead").length < 1) {
			$("#" + target + "").append($("#" + source + " thead").clone());
		}

		if ($("#" + target + " tbody").length < 1) {
			$("#" + target + "").append("<tbody></tbody>");
		} else {
			$("#" + target + " tbody").empty();
		}

		for(var i=page_index*items_per_page;i<max_elem;i++){
			var tr = $("#" + source + " tr[name='rows']:eq("+i+")").clone();
			for(var j in tds) {
				var text = tr.find("td").eq(tds[j]).text();
				text = $.string.numberFormat(text);
				tr.find("td").eq(tds[j]).text(text);
			}
			$("#" + target + " tbody").append(tr);
		}

		if (sortTable == false) return false;
		$("#" + target + " th").bind("click", function() {
   			var index = parseInt($(this).index());

   			if (headers) {
	   			if (headers[index] && headers[index]["sorter"] == false) return false;
   			}
   			var length = $("#" + target + " th").length;
   			var sort = new Array(index, 1);
   			if ($(this).hasClass("headerSortUp")) {
   				$(this).removeClass("headerSortUp").addClass("headerSortDown");
   				sort[1] = 0;
   			} else {
   				$(this).removeClass("headerSortDown").addClass("headerSortUp");
   			}
   			
   			if (headers) {
   				var sortList = {sortList:[sort], headers:headers};
   			} else {
   				var sortList = {sortList:[sort]};
   			}
   			
   			$("#" + source).tablesorter(sortList);

   			setTimeout(function(){
   				$("#" + target + " tbody").empty();
       			// 获取加载元素
				for(var i=page_index*items_per_page;i<max_elem;i++){
					var tr = $("#" + source + " tr[name='rows']:eq("+i+")").clone();
					for(var j in tds) {
						var text = tr.find("td").eq(tds[j]).text();
						text = $.string.numberFormat(text);
						tr.find("td").eq(tds[j]).text(text);
					}
					$("#" + target + " tbody").append(tr);
				}

                if (fun) {
                    if (args) {
                        doCallback(eval(fun), args);
                    } else {
                        doCallback(eval(fun));
                    }
                }
   			}, 5);
		});

        if (fun) {
            if (args) {
                doCallback(eval(fun), args);
            } else {
                doCallback(eval(fun));
            }
        }
        return false;
    }
}

var showTable = function(label, titles, labels, dataJson, keys) {

	var labelTable = label + "Table";
	var labelContent = label + "Content";
	var labelContentTable = label + "ContentTable";
	var labelContentPagination = label + "Pagination";
	var html = '<div class="table-container"><div class="table-responsive">';
	html += '<table id="' + labelContent + '" class="table table-striped table-hover table-bordered egretTable"></table>';
	html += '<div id="' + labelContentPagination + '" class="pagination" style="display:block;"></div>';
	html += '<table class="table table-striped table-hover table-bordered egretTable" style="display:none;" id="' + labelContentTable + '">';
	html += '<thead><tr>';
	for (i in titles) {
		html += '<th>' + titles[i] + '</th>';
	}
	html += '</tr></thead><tbody>';
	var key = '';
	for (j in keys) {
		key = keys[j];
		html += '<tr name="rows">';
			html += '<td>' + key + '</td>';
			for (m in labels) {
				if (labels[m].indexOf('Rate') == '-1') {
					html += '<td>' + dataJson[labels[m]][key] + '</td>';
				} else {
					if (dataJson[labels[m]][key]) {
						html += '<td>' + dataJson[labels[m]][key] + '%</td>';
					} else {
						html += '<td>--</td>';
					}
				}
			}
		html += '</tr>';
	}
	html += '</tbody></table></div></div>';
	$("#" + labelTable).html(html);
	$("#" + labelContentTable).tablesorter();
}

var doCallback = function(fn, args) {
	fn.apply(this, args);
}

var getDate = function(time) {
	if (time) {
		var nowDate = new Date(time);
	} else {
		var nowDate = new Date();
	}
    var year = nowDate.getFullYear();
    var month = parseInt(nowDate.getMonth()) + 1;
    var day = nowDate.getDate();
    if (parseInt(month) < 10) month =  "0" + month;
    if (parseInt(day) < 10) day =  "0" + day;
    var date = year + "-" + month + "-" + day;
    return date
};

var showUserData = function(gameId, egretId, server, chanName, tag, chanId) {
	if (tag == undefined || tag == null) {
		tag = "game";
	}
	var url = "/Ajax/Stat/Games.getUserInfo?dataTag=" + tag + "&gameId=" + gameId + "&egretId=" + egretId + "&server=" + server + "&chanName=" + chanName + "&chanId=" + chanId;
	$.get(url, function(data){
		if (data.code == 0) {
			$('#modalUserInfo').modal('show');
			$("#modalUserInfoData").html(data.data);

			tablePage('detailOrder', 'dataOrder', 'PaginationOrder', [], false);

			tablePage('detailDiamondUse_' + server, 'contentDiamondUse_' + server, 'PaginationDiamondUse_' + server, [], false);
	   }
	});
}

//无serverId的处理
var showAllUserData = function(gameId, egretId,chanName,source) {
	var url = "/Ajax/Stat/Games.getUserInfo?dataTag=game&gameId=" + gameId + "&egretId=" + egretId  + "&chanName=" + chanName + "&source=" + source + "&type=service";
	$.get(url, function(data){
		//console.log(data);
		if (data.code == 0) {
			$('#modalAllUserInfo').modal('show');
			$("#modalAllUserData").html(data.data);
			tablePage('detailOrder', 'dataOrder', 'PaginationOrder', [], false);
			tablePage('detailDiamondUse_' + server, 'contentDiamondUse_' + server, 'PaginationDiamondUse_' + server, [], false);
		}else if(data.code == 20001){
			$('#showErrorModalMsg').html('该游戏未在运营中');
			$('#showErrorModal').modal('show');
		}
	});
}

//检查悬浮球功能开关
function checkATFunc(subFuncName,chanId,redirectUrl){
	var url = "/Member/ChannelOperators/Channel/AssistiveTouch.getConfigInfo?subFuncName=" + subFuncName + "&chanId=" + chanId;
	$.get(url, function(data){
		console.log(data);
		if (data == 'on') {
			window.location.href=redirectUrl;
		}else{
			alert(data);
		}
	});
}
// 获取url参数
function getUrlParam(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
    var r = window.location.search.substr(1).match(reg);  //匹配目标参数
    if (r != null) return unescape(r[2]); return null; //返回参数值
}