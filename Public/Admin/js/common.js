//dom加载完成后执行的js
;$(function(){

	//全选的实现
	$(".check-all").click(function(){
		$(".ids").prop("checked", this.checked);
	});
	$(".ids").click(function(){
		var option = $(".ids");
		option.each(function(i){
			if(!this.checked){
				$(".check-all").prop("checked", false);
				return false;
			}else{
				$(".check-all").prop("checked", true);
			}
		});
	});

    // 删除
    //$('body').append('<div id="delnotic" class="delwrap"><div class="delbg"></div><div class="delnotic"><div class="deltitle">提示信息<a href="javascript:;" class="delclose ">x</a></div><div class="delcontent"><span class="delimg"></span><span>确认要执行该操作吗?</span></div><div class="delbutton"><a href="javascript:;" id="delconfirm">确 定</a><a href="javascript:;" id="delcancel">取 消</a></div></div></div>');
    $('body').append('<div class="laybox" id="laybox"><div class="delnotic" id="delnotic" ><div class="deltitle"><span>提示信息</span><a class="delclose "></a></div><div class="delcontent"><span class="delimg"><img src="/Public/Admin/images/edit.png"></span><div class="deltext"><p>确认要执行该操作吗？</p><cite>如果“是”请点击确定按钮 ，如果“否”请点取消</cite></div></div><div class="delbutton"><input name="" type="button" class="delconfirm" value="确定">&nbsp;<input name="" type="button" class="delcancel" value="取消"></div></div></div>');
    $('body').append('<div class="laybox" id="laybox2"><div class="delnotic" id="post_delnotic" ><div class="deltitle"><span>提示信息</span><a class="delclose "></a></div><div class="delcontent"><span class="delimg"><img src="/Public/Admin/images/edit.png"></span><div class="deltext"><p>确认要执行该操作吗？</p><cite>如果是请点击确定按钮 ，否则请点取消。</cite></div></div><div class="delbutton"><input name="" type="button" class="delconfirm" value="确定">&nbsp;<input name="" type="button" class="delcancel" value="取消"></div></div></div>');
    
    
    
    var delnotic = function(obj) {
        var delthat = $('#delnotic');
        var laybox = $('#laybox');
        delthat.fadeIn(800);
        laybox.fadeIn();
        delthat.find('.delconfirm').focus();
        delthat.find('.delcancel').on('click',function(){
        	laybox.fadeOut();
            delthat.fadeOut(800);
        });
        delthat.find('.delconfirm').unbind('click').bind('click',function(){
        	laybox.fadeOut();
            delthat.fadeOut(800);
            obj && del(obj);
        });
        $('.delclose').on('click',function(){
        	laybox.fadeOut();
            delthat.fadeOut(800);
        });
    }

    var post_delnotic = function(obj) {
        var delthat = $('#post_delnotic');
        var laybox2 = $('#laybox2');
        delthat.fadeIn(800);
        laybox2.fadeIn();
        delthat.find('.delconfirm').focus();
        delthat.find('.delcancel').on('click',function(){
        	laybox2.fadeOut();
            delthat.fadeOut(800);
            // return false;
        });
        delthat.find('.delconfirm').unbind('click').bind('click',function(){
            delthat.fadeOut(800);
            laybox2.fadeOut();
            post_submit(obj)
        });
        $('.delclose').on('click',function(){
        	laybox2.fadeOut();
            delthat.fadeOut(800);
        });
    }
    //ajax get请求    
    $('.ajax-get').click(function(){
        var target;
        var that = this;
        if ( $(this).hasClass('confirm') ) {
            delnotic(that);
        } else {
            del(that);           
        }        
        return false;
    });
    
    function del(that) {
        if ( (target = $(that).attr('href')) || (target = $(that).attr('url')) ) {
            $.get(target).success(function(data){
                if (data.status==1) {
                    if (data.url) {
                        updateAlert(data.info + ' 页面即将自动跳转~');
                    }else{
                        updateAlert(data.info);
                    }
                    setTimeout(function(){
                        if (data.url) {
                            location.href=data.url;
                        }else if( $(that).hasClass('no-refresh')){
                            $('#tip').find('.tipclose').click();
                        }else{
                            location.reload();
                        }
                    },1500);
                }else{
                    updateAlert(data.info,'tip_error');
                    setTimeout(function(){
                        if (data.url) {
                            location.href=data.url;
                        }else{
                            $('#tip').find('.tipclose').click();
                        }
                    },1500);
                }
            });

        }
    }

    //ajax post submit请求
    $('.ajax-post').click(function(){
        var that = this;
        if ( $(this).hasClass('confirm') ) {
            post_delnotic($(this));
        } else {
            post_submit($(this));
        }
        return false;
    });

    function post_submit(obj){
        var target,query,form;
        var target_form = obj.attr('target-form');
        var that = obj;
        var nead_confirm=false;
        if( (obj.attr('type')=='submit') || (target = obj.attr('href')) || (target = obj.attr('url')) ){

            form = $('.'+target_form);
            if (obj.attr('hide-data') === 'true'){//无数据时也可以使用的功能
                form = $('.hide-data');
                query = form.serialize();
            }else if (form.get(0)==undefined){
                return false;
            }else if ( form.get(0).nodeName=='FORM' ){
                if(obj.attr('url') !== undefined){
                    target = obj.attr('url');
                }else{
                    target = form.get(0).action;
                }
                query = form.serialize();
            }else if( form.get(0).nodeName=='INPUT' || form.get(0).nodeName=='SELECT' || form.get(0).nodeName=='TEXTAREA') {
                form.each(function(k,v){
                    if(v.type=='checkbox' && v.checked==true){
                        nead_confirm = true;
                    }
                })
                query = form.serialize();
            }else{
                query = form.find('input,select,textarea').serialize();
            }
            $(that).addClass('disabled').attr('autocomplete','off').prop('disabled',true);
            $.post(target,query).success(function(data){
                if (data.status==1) {
                    if (data.url) {
                        updateAlert(data.info + ' 页面即将自动跳转~');
                    }else{
                        updateAlert(data.info);
                    }
                    setTimeout(function(){
                        $(that).removeClass('disabled').prop('disabled',false);
                        if (data.url) {
                            location.href=data.url;
                        }else if( $(that).hasClass('no-refresh')){
                            $('#tip').find('.tipclose').click();
                        }else{
                            location.reload();
                        }
                    },1500);
                }else{
                    updateAlert(data.info,'tip_error');
                    setTimeout(function(){
                        $(that).removeClass('disabled').prop('disabled',false);
                        if (data.url) {
                            location.href=data.url;
                        }else{
                            $('#tip').find('.tipclose').click();
                        }
                    },1500);
                }
            });
        }
        return false;
    }

	/**顶部警告栏*/
	var content = $('#main');
	var top_alert = $('#tip');
    //$('body').append('<div class="notice"><div><i></i>成功</div></div>');
	
    top_alert.find('.tipclose').on('click', function () {
		top_alert.removeClass('block').slideUp(200);
		// content.animate({paddingTop:'-=55'},200);
	});

    window.updateAlert = function (text,c) {
		text = text||'default';
        c = c||false;
		if ( text!='default' ) {
            top_alert.find('.tipinfo').text(text);
			if (top_alert.hasClass('block')) {
			} else {
				top_alert.addClass('block').slideDown(200);
				// content.animate({paddingTop:'+=55'},200);
			}
		} else {
			if (top_alert.hasClass('block')) {
				top_alert.removeClass('block').slideUp(200);
				// content.animate({paddingTop:'-=55'},200);
			}
		}
        if ( c!=false ) {
            top_alert.removeClass('tip_error tip_right').addClass(c);
		}else{
            top_alert.removeClass('tip_error tip_right').addClass(' tip_right');
        }
	};

    //按钮组
    (function(){
        //按钮组(鼠标悬浮显示)
        $(".btn-group").mouseenter(function(){
            var userMenu = $(this).children(".dropdown ");
            var icon = $(this).find(".btn i");
            icon.addClass("btn-arrowup").removeClass("btn-arrowdown");
            userMenu.show();
            clearTimeout(userMenu.data("timeout"));
        }).mouseleave(function(){
            var userMenu = $(this).children(".dropdown");
            var icon = $(this).find(".btn i");
            icon.removeClass("btn-arrowup").addClass("btn-arrowdown");
            userMenu.data("timeout") && clearTimeout(userMenu.data("timeout"));
            userMenu.data("timeout", setTimeout(function(){userMenu.hide()}, 100));
        });

        //按钮组(鼠标点击显示)
        // $(".btn-group-click .btn").click(function(){
        //     var userMenu = $(this).next(".dropdown ");
        //     var icon = $(this).find("i");
        //     icon.toggleClass("btn-arrowup");
        //     userMenu.toggleClass("block");
        // });
        $(".btn-group-click .btn").click(function(e){
            if ($(this).next(".dropdown").is(":hidden")) {
                $(this).next(".dropdown").show();
                $(this).find("i").addClass("btn-arrowup");
                e.stopPropagation();
            }else{
                $(this).find("i").removeClass("btn-arrowup");
            }
        })
        $(".dropdown").click(function(e) {
            e.stopPropagation();
        });
        $(document).click(function() {
            $(".dropdown").hide();
            $(".btn-group-click .btn").find("i").removeClass("btn-arrowup");
        });
    })();

    // 独立域表单获取焦点样式
    $(".text").focus(function(){
        $(this).addClass("focus");
    }).blur(function(){
        $(this).removeClass('focus');
    });
    $("textarea").focus(function(){
        $(this).closest(".textarea").addClass("focus");
    }).blur(function(){
        $(this).closest(".textarea").removeClass("focus");
    });
});

/* 上传图片预览弹出层 */

//标签页切换(无下一步) lwx
function showTab() {
    $(".jstabnav li").click(function(){
        var self = $(this), target = self.data("tab");
        self.addClass("current").siblings(".current").removeClass("current");
        window.location.hash = "#" + target.substr(3);
        $(".tab-pane.in").removeClass("in");
        $("." + target).addClass("in");
    }).filter("[data-tab=tab" + window.location.hash.substr(1) + "]").click();
}
function oldshowTab() {
    $(".tab-nav li").click(function(){
        var self = $(this), target = self.data("tab");
        self.addClass("current").siblings(".current").removeClass("current");
        window.location.hash = "#" + target.substr(3);
        $(".tab-pane.in").removeClass("in");
        $("." + target).addClass("in");
    }).filter("[data-tab=tab" + window.location.hash.substr(1) + "]").click();
}

//标签页切换(有下一步)
function nextTab() {
     $(".tab-nav li").click(function(){
        var self = $(this), target = self.data("tab");
        self.addClass("current").siblings(".current").removeClass("current");
        window.location.hash = "#" + target.substr(3);
        $(".tab-pane.in").removeClass("in");
        $("." + target).addClass("in");
        showBtn();
    }).filter("[data-tab=tab" + window.location.hash.substr(1) + "]").click();

    $("#submit-next").click(function(){
        $(".tab-nav li.current").next().click();
        showBtn();
    });
}

// 下一步按钮切换
function showBtn() {
    var lastTabItem = $(".tab-nav li:last");
    if( lastTabItem.hasClass("current") ) {
        $("#submit").removeClass("hidden");
        $("#submit-next").addClass("hidden");
    } else {
        $("#submit").addClass("hidden");
        $("#submit-next").removeClass("hidden");
    }
}

//导航高亮
function highlight_subnav(url){
    $('.side-sub-menu').find('a[href="'+url+'"]').closest('li').addClass('current');
    /*显示选中的菜单*/
    $('.side-sub-menu').find("a[href='" + url + "']").parent().parent().prev("h3").find("i").removeClass("icon-fold");
    $('.side-sub-menu').find("a[href='" + url + "']").parent().parent().show()
}
