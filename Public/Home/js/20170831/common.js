/**
 * 基本行为
 * @author 鹿文学
 */
$(function(){	
	$("#goTop").click(function(){$('html, body').animate({scrollTop: 0}, 600);});

	//全选的实现
	$(".check-all").click(function(){
		$('.ids').prop("checked", this.checked);
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
  
  // 选择框
  /* $('.jscounterfeit').click(function() {
      var that = $(this);
      that.find('ul').toggleClass('active');
      $(document).click(function(event) {
        var e = event || window.event;
        var target = $(e.target);
        if ((target.attr('class') && target.attr('class').indexOf('jscounterfeit')<0) && target.closest('.jscounterfeit').length<1) {
            $('.jscounterfeit ul').removeClass('active');
        }
      });
      return false;
  });
  
  $('.jscounterfeit ul a').click(function() {
      var that = $(this),val = that.attr('data-id'),txt = $.trim(that.text());
      var parent = that.closest('.jscounterfeit');
      parent.find('span').text(txt);
      parent.find('input[type=hidden]').val(val);
  });
  
  $('.jscounterfeit input[type=hidden]').each(function() {
    var that= $(this),val= that.val(),parent=that.closest('.jscounterfeit');
    if (val) {
        var op = parent.find('ul a[data-id='+val+']');
        op.addClass('active');
        parent.find('span').text(op.text());
    }
  }); */
  
  
  // 时间
  (function() {
      function time() {
        var d = new Date();
        $('.jstime span').text(d.getFullYear()+'年'+(parseInt(d.getMonth())+1)+'月'+d.getDate()+'日');
      }
      time();
      setInterval(function(){
        time();
      },86400000);
  })();

    //ajax get请求
    $('.ajax-get').click(function(){

        var target;
        var that = this;
        if ( $(this).hasClass('confirm') ) {
            if(!confirm('确认要执行该操作吗?')){
                return false;
            }
        }
        if ( (target = $(this).attr('href')) || (target = $(this).attr('url')) ) {
            $.get(target).success(function(data){
                if (data.status==1) {
                    if (data.url) {
                        updateAlert(data.info + ' 页面即将自动跳转~','alert-success');
                    }else{
                        updateAlert(data.info,'alert-success');
                    }
                    setTimeout(function(){
                        if (data.url) {
                            location.href=data.url;
                        }else if( $(that).hasClass('no-refresh')){
                            $('#top-alert').find('button').click();
                        }else{
                            location.reload();
                        }
                    },1500);
                }else{
                    updateAlert(data.info);
                    setTimeout(function(){
                        if (data.url) {
                            location.href=data.url;
                        }else{
                            $('#top-alert').find('button').click();
                        }
                    },1500);
                }
            });

        }
        return false;
    });

    //ajax post submit请求
    $('.ajax-post').click(function(){

        var target,query,form;
        var target_form = $(this).attr('target-form');
        var that = this;
        var nead_confirm=false;
        if( ($(this).attr('type')=='submit') || (target = $(this).attr('href')) || (target = $(this).attr('url')) ){
            form = $('.'+target_form);
            if ($(this).attr('hide-data') === 'true'){//无数据时也可以使用的功能
            	form = $('.hide-data');
            	query = form.serialize();
            }else if (form.get(0)==undefined){
            	return false;
            }else if ( form.get(0).nodeName=='FORM' ){
                if ( $(this).hasClass('confirm') ) {
                    if(!confirm('确认要执行该操作吗?')){
                        return false;
                    }
                }
                if($(this).attr('url') !== undefined){
                	target = $(this).attr('url');
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
                if ( nead_confirm && $(this).hasClass('confirm') ) {
                    if(!confirm('确认要执行该操作吗?')){
                        return false;
                    }
                }
                query = form.serialize();
            }else{
                if ( $(this).hasClass('confirm') ) {
                    if(!confirm('确认要执行该操作吗?')){
                        return false;
                    }
                }
                query = form.find('input,select,textarea').serialize();
            }

            $(that).addClass('disabled').attr('autocomplete','off').prop('disabled',true);
            $.post(target,query).success(function(data){
                if (data.status==1) {
                	if (data.url) {
                        updateAlert(data.info,1,'');
                    }else{
                        updateAlert(data.info ,1,'alert-success');
                    }
                    setTimeout(function(){
                    	$(that).removeClass('disabled').prop('disabled',false);
                        if (data.url) {
                            location.href=data.url;
                        }else if( $(that).hasClass('no-refresh')){
                            //$('#top-alert').find('button').click();
                        }else{
                            location.reload();
                        }
                    },1500);
                }else{
                    updateAlert(data.info);
                    setTimeout(function(){
                    	$(that).removeClass('disabled').prop('disabled',false);
                    },1500);
                }
            });
        }
        return false;
    });

    window.updateAlert = function (text,status,c) {
    	switch(status){
    		case 1:
    			layer.msg(text, {icon: 1});
    			break;
    		default:
    			layer.msg(text, {icon: 2});
    		break;
    	}
    }
});

function del_action(id,url){
	layer.msg('确定删除？', {
		  time: 0 //不自动关闭
		  ,btn: ['删除', '取消']
		  ,yes: function(index){
			  $.ajax({
		          type: "post",
		          url: url,
		          dataType: "json",
		          data: {id:id},
		          success: function (res) {
		              if (res.status != 0) {
		            	  $("#"+id+"").remove();
		                  layer.msg(res.msg, {icon: 1});
		              }
		              else {
		                  layer.msg(res.msg, {icon: 2});
		              }
		          },
		          error: function () {
		              layer.msg('服务器故障', {icon: 5});
		          }
		      })
		    layer.close(index);
		  }
		});
}

/* 
 * 设置表单值
 * @author lwx
 */
function setValue(name,values) {
    var first=name.substr(0,1),input,val;
    if (values === '') return ;
    if ('#'==first || '.' == first) {
        input = $(name);
    } else {
        input = $('[name="'+name+'"]');
    }
    
    if (input.eq(0).is(':radio')) {
        input.filter('[value="'+values+'"]').each(function() {this.checked=true;});
    } else if (input.eq(0).is(':checkbox')) {
        if (!$.isArray(values)) {
            val = new Array();
            val[0] = values;
        } else {
            val = values;
        } 
        
        for (i=0,len=val.length;i<len;i++) {
            input.filter('[value="'+val[i]+'"]').each(function() {this.checked=true;});
        }
    } else {
        input.val(values);
    }
}

//导航高亮
function highlight_subnav(url){
    $('.nav .menu').find('a[href="'+url+'"]').addClass('active').closest('.wrap').addClass('active');
}