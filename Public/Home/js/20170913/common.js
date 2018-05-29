/**
 * 基础JS
 */
$(document).ready(function(){
	
	// 更换图片验证码
	$("img[name='changeCaptcha']").click(function(){
		flushCaptcha(this);
	});
    $("i[name='changeCaptcha']").click(function(){
        var that = $(this).siblings('img');
        flushCaptcha(that[0]);
    });

	// JS检索
	$("a[href='#filter']").click(function(event){
        event.preventDefault();
        var type = $(this).data("type");
        var value = $(this).data("value");

        var href = window.location.href;
        if (href.indexOf("?") != -1) {
        	var p = new RegExp("(&)?" + type + "=([^&]*)", "g");
            href = href.replace(p, "");
            href = href.replace(/(&)?pageId=(\d+)?/g, "");
            window.location.href = href + "&" + type + "=" + value;
        } else {
        	window.location.href = href + "?" + type + "=" + value;
        }
    });

	/* 表单验证设置全局方法 */
	if($.validator)$.validator.setDefaults({
		errorElement:"small",
		//设置错误消息的位置
		errorPlacement: function(error, element) {
			element.parents(".input-format").nextAll(".input-status").append(error);
		},
		//每个控件成功验证后的处理
		success: function(label,element) {
            label.text('').addClass('success');
			$(element).parents(".input-format").removeClass("has-error has-feedback").addClass("has-success has-feedback");
		},
		//验证错误时处理
		highlight:function(element) {
            $(element).parents(".input-format").nextAll(".input-status").text('');
			$(element).parents(".input-format").removeClass("has-success has-feedback").addClass("has-error has-feedback");
		},
		onfocusout: function(element) { $(element).valid();$(element).parents(".input-format").removeClass('current');},
        onfocusin:function(element) {$(element).parents(".input-format").addClass('current')}
	});


	//通栏菜单延时隐藏
	var timer;
	$("#navbar .dropdown-toggle").mouseenter(function(){
		handlerMouseenter(this)
	});

	$("#navbar .dropdown-menu").mouseenter(function(){
		handlerMouseenter(this)
	});

	$("#navbar .dropdown-toggle").mouseleave(function(){
		handlerMouseLeave();
	});

	$("#navbar .dropdown-menu").mouseleave(function(){
		handlerMouseLeave();
	});

	$("#navbar .dropdown-toggle").click(function(e){
		e.stopPropagation();
	});
	function handlerMouseenter(obj) {
		clearInterval(timer);
		$("#navbar .dropdown-menu").parent().removeClass("open");
		$(obj).parent().addClass("open");
	}

	function handlerMouseLeave() {
		clearInterval(timer);
		timer = setTimeout(function() {
			$("#navbar .dropdown-menu").parent().removeClass("open");
		},100)
	}
    
    // 全选
    $('.checkall').on('click',function(){
        $(this).closest('.control').find('.ids').prop('checked', this.checked);
    });
    
    $('.ids').on('click',function() {
        var control = $(this).closest('.control'),checkall = control.find('.checkall');
        control.find('.ids').each(function() {
            if (!this.checked) {
                checkall.prop('checked',false);return false;
            } else {
                checkall.prop('checked',true);
            }
        });
        
    });
    
    $(window).scroll(function(){
        if ($(this).scrollTop()>100) {
            $('.gotop').fadeIn();
        } else {
            $('.gotop').fadeOut();
        }
    });
    $('.gotop').click(function(){
        $('body,html').animate({ scrollTop: 0 },800);
        $(window).scroll();
    });

});

function highlight_subnav(url){
    $('.header .nav').find('a[href="'+url+'"]').addClass('active');
}

/* 验证码刷新 */
function flushCaptcha(that) {
    var url = that.src.replace(/((\.html)?|(\/t\/.+)?)$/g,'');
    that.src = url+'/t/'+(new Date()).getTime()+'.html'; 
}