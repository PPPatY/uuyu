;(function($,window,document,undefined){
    'use strict'; //严格模式，提高效率

    $.fn.area = function(options){
        $(this).click(function(){
          _init(options);          
        });
        var defaults = {
          place:'body',
          speed:2,
          data:'',
          datapath:'/Public/Media/js/',
        };
        var that = this;
        var _init = function(options){
          that.options = $.extend({_list:'',_lh:''},defaults,options);
          var script = $('script[src*="area.lwx"]');
          if (script.parent().find('[src="'+that.options.datapath+'areacity.lwx.js"]').length<1)
            script.before('<script src="'+that.options.datapath+'areacity.lwx.js"></script>');          
          that.options._data = that.options.data?that.options.data:dataJson;
          that.options._type = _browser.versions.mobile?{evt1:'touchstart',evt2:'touchmove',evt3:'touchend'}:{evt1:'mousedown',evt2:'mousemove',evt3:'mouseup'};
          
          _render();
        };
        var _browser = {
          versions:function(){
            var u = navigator.userAgent,app=navigator.appVersion;
            return {
              trident:u.indexOf('Trident')>-1,/*IE*/
              presto:u.indexOf('Presto')>-1,/*opera*/
              webkit:u.indexOf('AppleWebKit')>-1,/*apple google*/
              gecko:u.indexOf('Gecko')>-1 && u.indexOf('KHTML')==-1,/*firefox*/
              mobile:!!u.match(/AppleWebKit.*Mobile.*/)||!u.match(/AppleWebKit/),/*移动*/
              ios:!!u.match(/\(i[^;]+;( U;)?CPU.+Mac OS X/),/*ios*/
              android:u.indexOf('Android')>-1 || u.indexOf('Linux')>-1,/*android uc*/
              iPhone:u.indexOf('iPhone')>-1|| u.indexOf('Mac')>-1,/*iPhone QQHD*/
              iPad:u.indexOf('iPad')>-1,/*iPad*/
              webApp:u.indexOf('Safari')==-1,
            };
          }(),
          language:(navigator.browserLanguage||navigator.language).toLowerCase(),
        };
        var _render = function() {
          var str = '<div class="lwx-area-container touches">'+
                '<div class="lwx-area-row lwx-area-mg0 lwx-area-butn">'+
                '<div class="lwx-area-col s3">'+
                '<a href="javascript:void(0)" class="lwx-area-cancel">取消</a>'+
                '</div>'+
                '<div class="lwx-area-col s6 lwx-area-title">设置地区</div>'+
                '<div class="lwx-area-col s3">'+
                '<a href="javascript:void(0)" class="lwx-area-sure">确定</a>'+
                '</div>'+
                '</div>'+
                '<div class="lwx-area-row lwx-area-mg0 lwx-area-main">'+
                '<div class="lwx-area-col s4 lwx-area-timer">'+
                    '<div class="lwx-area-bgTop"></div>'+
                    '<ul class="lwx-area-list lwx-area-province">'+
                    '</ul>'+
                    '<div class="current current-date-right"></div>'+
                    '<div class="lwx-area-bgBottom"></div>'+
                '</div>'+
                '<div class="lwx-area-col s4 lwx-area-timer">'+
                    '<div class="lwx-area-bgTop"></div>'+
                    '<ul class="lwx-area-list lwx-area-city">'+
                    '</ul>'+
                    '<div class="current current-date-left current-date-right"></div>'+
                    '<div class="lwx-area-bgBottom"></div>'+
                '</div>'+
                '<div class="lwx-area-col s4 lwx-area-timer">'+
                    '<div class="lwx-area-bgTop"></div>'+
                    '<ul class="lwx-area-list lwx-area-county">'+
                    '</ul>'+
                    '<div class="current current-date-left"></div>'+
                    '<div class="lwx-area-bgBottom"></div>'+
                '</div>'+
                '</div>'+
                '</div>'+
                '<div class="lwx-area-layer"></div>';
                
          $(that.options.place).append(str);
          that.options._list = $('.lwx-area-list');
          var place = that.options.place;
          var type = that.options._type;
          $(".lwx-area-cancel,.lwx-area-layer").on("click",function(){
            $(".touches,.lwx-area-layer").remove();
            $(place).unbind(type); //恢复了body的拖动事件
          });
          
          $(".lwx-area-sure").on("click",function(){
            var val = '',val2 = '';
            $(".lwx-area-active").each(function(){
                val += $(this).text()+'';
                val2 += $(this).attr('data-code')+',';
            });
            
            that.find('.area').html(val).removeClass('default');
            that.find('input').val(val2);
            $(".touches,.lwx-area-layer").remove();
            $(place).unbind(type); //恢复了body的拖动事件
          });
          $(place).on(type,function (e){
            e.preventDefault();
          });
          
          that.options._lh = $('.lwx-area-main .current ').outerHeight();
          _province();
          _handle(); //绑定事件
          return this;
        };
        var _province = function(){
            var list = that.options._list.eq(0);
            var data = that.options._data;
            for(var i=0;i<data.length;i++){
                list.append('<li data-code="'+data[i].code+'" data-index="'+i+'">' +data[i].name+'</li>'); //data[i].code省code  data[i].name省name
            }
            list.find("li").eq(0).addClass("lwx-area-active");//一开始默认第三行选中
            list.css("top",2*that.options._lh);
            _city();
        };
        var _city = function(){
            var list = that.options._list.eq(1);
            var data = that.options._data;
            list.html('');
            var scode = that.options._list.eq(0).find("li.lwx-area-active").attr("data-code");
            var cityarr = '';
            for(var i=0;i<data.length;i++){
                if(data[i].code == scode){
                    cityarr = data[i].cities;
                }
            }
            for(var j=0;j<cityarr.length;j++){
                list.append('<li data-code="'+cityarr[j].code+'" data-index="'+j+'">' +cityarr[j].name+'</li>');
            }
            list.find("li").eq(0).addClass("lwx-area-active");//一开始默认第三行选中
            list.css("top",2*that.options._lh);
            _county();
        };
        var _county = function(){
            var list = that.options._list.eq(2);
            var data = that.options._data;
            list.html('');
            var provinceindex = that.options._list.eq(0).find("li.lwx-area-active").attr("data-index");
            var cityindex = that.options._list.eq(1).find("li.lwx-area-active").attr("data-index");
            var countyarr = data[provinceindex].cities[cityindex].district;

            for(var j=0;j<countyarr.length;j++){
                list.append('<li data-code="'+countyarr[j].code+'">' +countyarr[j].name+'</li>');
            }
            list.find("li").eq(0).addClass("lwx-area-active");//一开始默认第三行选中
            list.css("top",2*that.options._lh);
        };
        var _handle = function(){ //函数绑定
            that.options._list.each(function(){
                var startY = null,//开始的pageY
                    endY = null,//结束的pageY
                    distY = null,//endY - startY
                    cTop = null,//currentTop
                    _top = null,//ul.list的top值
                    timeS = null,//滚动的开始时间
                    distT = null,//每一次滚动的时间差
                    speed = null;//速度
                var SE = null;
                var ME = null;
                function startCallBack(e){
                    //这里的this指向当前滑动的$list
                    if (_browser.versions.mobile) {
                      if(e.originalEvent.touches){
                          SE=e.originalEvent.targetTouches[0];
                          //console.log(SE)
                      }
                      startY = SE.pageY;
                    } else {
                      startY = e.clientY;
                    }
                    
                    cTop = $(this).position().top;
                    timeS = new Date();
                }
                function moveCallBack(e){
                    //这里的this指向当前滑动的$list]
                    if (_browser.versions.mobile) {
                      if(e.originalEvent.touches){
                          ME=e.originalEvent.targetTouches[0];
                          //console.log(ME)
                      }
                      endY = ME.pageY;
                    } else {
                      endY = e.clientY;
                    }
                    var scrollSpeed = that.options.speed || 2;
                    distY = scrollSpeed*(endY - startY);
                    //console.log(distY);//往下滑动是正直，往上是负值
                    if($(this).find("li").length==2){
                        if(cTop+distY>2*that.options._lh){//从顶部往下滑动
                            _top = 2*that.options._lh;
                        } else if(cTop+distY<$(this).parent().height()-$(this).height()-that.options._lh){//从底部往上滑动
                            _top = $(this).parent().height() - $(this).height()-that.options._lh;
                        } else {//中间地方滑动
                            _top = cTop+distY;
                        }
                    } else if($(this).find("li").length==1){
                        return;
                    } else {
                        if(cTop+distY>2*that.options._lh){//从顶部往下滑动
                            _top = 2*that.options._lh;
                        } else if(cTop+distY<$(this).parent().height()-$(this).height()-2*that.options._lh){//从底部往上滑动
                            _top = $(this).parent().height() - $(this).height()-2*that.options._lh;
                        } else {//中间地方滑动
                            _top = cTop+distY;
                        }
                    }
                    _top = _top - _top % that.options._lh;//取整
                    $(this).css('top',_top);
                    if(_top==that.options._lh){
                        $(this).find("li").eq(1).addClass("lwx-area-active").siblings().removeClass("lwx-area-active");
                    } else if(_top==2*that.options._lh){
                        $(this).find("li").eq(0).addClass("lwx-area-active").siblings().removeClass("lwx-area-active");
                    } else {
                        $(this).find("li").eq(Math.abs(_top/that.options._lh)+2).addClass("lwx-area-active").siblings().removeClass("lwx-area-active");
                    }
                }
                function endCallBack(e){
                    //这里的this指向当前滑动的$list
                    var $this = $(this);
                    var list = that.options._list;
                    //console.log()
                    var dir = distY < 0 ? 1 : -1;//方向 上移为1，下移为-1
                    distT = new Date() - timeS;
                    speed = Math.abs(distY / distT);//单位px/ms
                    if($(this).find("li").length==1){
                        return;
                    } else {
                        if(speed>0.6) {
                            /*alert(1)*/
                            if (dir == 1 && Math.abs(_top / that.options._lh) + 3 == $(this).find('li').length) { //手指向上滑动
                                if($this.attr('class')==list.eq(0).attr("class")){
                                    _city();
                                } else if($this.attr('class')==list.eq(1).attr("class")){
                                    _county();
                                }

                                return;//到底了，不能滑了
                            } else if(dir==-1 && _top==2*that.options._lh){ //手指向下滑动
                                if($this.attr('class')==list.eq(0).attr("class")){
                                    _city();
                                } else if($this.attr('class')==list.eq(1).attr("class")){
                                    _county();
                                }
                                return;//到顶了，不能滑了
                            }
                        }
                        setTimeout(function(){
                            $this.css("top",_top);
                            if(_top==that.options._lh){
                                $(this).find("li").eq(1).addClass("lwx-area-active").siblings().removeClass("lwx-area-active");
                            } else if(_top==2*that.options._lh){
                                $(this).find("li").eq(0).addClass("lwx-area-active").siblings().removeClass("lwx-area-active");
                            } else {
                                $(this).find("li").eq(Math.abs(_top/that.options._lh)+2).addClass("lwx-area-active").siblings().removeClass("lwx-area-active");
                            }

                            if($this.attr('class')==list.eq(0).attr("class")){
                                _city();
                            } else if($this.attr('class')==list.eq(1).attr("class")){
                                _county();
                            }
                        },50);
                    }
                }
                
                $(this).off(that.options._type.evt1).on(that.options._type.evt1,startCallBack).click(function(){var ethis=$(this);if(ethis.hasClass('disabled')){ethis.removeClass('disabled').on(that.options._type.evt2,moveCallBack)}else{ethis.addClass('disabled').off(that.options._type.evt2)}}); //下滑开始 
                $(this).off(that.options._type.evt2).on(that.options._type.evt2,moveCallBack).click(function(){var ethis=$(this);if(ethis.hasClass('disabled')){ethis.removeClass('disabled').on(that.options._type.evt2,moveCallBack)}else{ethis.addClass('disabled').off(that.options._type.evt2)}}); //滑动的时候 
                $(this).off(that.options._type.evt3).on(that.options._type.evt3,endCallBack).click(function(){var ethis=$(this);if(ethis.hasClass('disabled')){ethis.removeClass('disabled').on(that.options._type.evt2,moveCallBack)}else{ethis.addClass('disabled').off(that.options._type.evt2)}}); //滑动完了

            });
        };
        
        return this;//返回调用插件的对象，以便支持链式调用
    }
    
})(jQuery,window,document);


