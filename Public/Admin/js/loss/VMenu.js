﻿(function($) {
    //菜单父级id
    var parentId = "";
    //当前菜单索引id
    var activeId = "";

    //展开关闭
    function handlerToggle(element,_delay) {
        var delay = _delay>0?_delay:0;

        //当前菜单焦点样式切换
        $(element).addClass("active");
        $(element).siblings("a").removeClass("active");

        //当前菜单内部图标切换
        $(element).find(".icon-angle_right").toggleClass("icon-angle_down");
        $(element).siblings("a").find(".icon-angle_down").removeClass("icon-angle_down");

        //当前子菜单
        var currentListGroup = $(element).next(".list_group");
        //同级子菜单
        var siblingsListGroup = currentListGroup[0]?currentListGroup.siblings(".list_group"):$(element).siblings(".list_group");

        //当前的打开
        currentListGroup.slideToggle(delay);
        //同级的关闭
        siblingsListGroup.slideUp(delay);
    }

    $.VMenu = {
        // 显示菜单
        show: function(selecter) {
            
            $(selecter).find("a").click(function(e) {
                handlerToggle(this,300);
                if($(this).attr("href") == "#")e.preventDefault();
            });
        },
        //展开某一层菜单
        open: function(){
            for(var i =0;i<arguments.length;i++) {
                var arr = arguments[i].split(".");
                var m = $(document.getElementById(arguments[i]));

                switch (arr.length) {
                    case 1:
                        if(i<1)m.addClass("active");
                        m.find(".icon-angle_right").toggleClass("icon-angle_down");
                        m.siblings("a").find(".icon-angle_down").removeClass("icon-angle_down");
                        m.next(".list_group").show();
                        break;
                    case 2:
                        if(i<1)m.addClass("active");
                        m.parent().show();
                        m.next(".list_group").show();
                        var m2 = $(document.getElementById(arr[0]));
                        if(i<1)m2.addClass("active");
                        m2.find(".icon-angle_right").toggleClass("icon-angle_down");
                        m2.siblings("a").find(".icon-angle_down").removeClass("icon-angle_down");
                        break;
                    case 3:
                        if(i<1)m.addClass("active");
                        m.parent().show();
                        m.parent().parent().show();
                        var m3 = $(document.getElementById(arr[0]));
                        if(i<1)m3.addClass("active");
                        m3.find(".icon-angle_right").toggleClass("icon-angle_down");
                        m3.siblings("a").find(".icon-angle_down").removeClass("icon-angle_down");
                        var m4 = $(document.getElementById(arr[0]+"."+arr[1]));
                        if(i<1)m4.addClass("active");
                        m4.find(".icon-angle_right").toggleClass("icon-angle_down");
                        m4.siblings("a").find(".icon-angle_down").removeClass("icon-angle_down");
                        break;
                }
            }
        }
    };
}
(jQuery));