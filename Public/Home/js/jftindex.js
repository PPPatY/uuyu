$(document).ready(function() {
						   
    $("#nav>li").click(function () {
        $(this).siblings('li').removeClass('current');
        $(this).addClass('current');  //下一个元素显示
        $(this).siblings('li').removeClass('current');

        var url = $(this).children(".J_menuItem").attr('href');
        $("#J_iframe").attr('src', url);
        return false; //阻止默认事件

    });

});