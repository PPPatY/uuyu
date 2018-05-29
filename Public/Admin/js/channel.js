/**
 * Created by Administrator on 15-3-18.
 * 肖骏涛
 */


$(function () {
    re_bind();

})

var re_bind = function () {

    //change_select();
    //change_module();
    //fix_form();
    add_one();
    add_two();
    remove_li();
    add_child();
    //bind_color();
    add_flag();
    //target_change()
    //bind_chose_icon()
}

var target_change = function(){
    $('.target').change(function(){
        $(this).closest('.new-blank').find('.target_input').val($(this).is(':checked')?1:0);
    })
}


var change_module = function () {
    $('.module').unbind('change')
    $('.module').change(function () {
        var obj = $(this);
        var text = obj.find("option:selected").text();
        var value = obj.val();
        obj.closest('li>div').children('input.title').val(text);
        obj.closest('li>div').children('input.url').val(value);

        obj.closest('li>div').next().children('select.chosen-icons').attr('data-value','icon-'+obj.find("option:selected").data('icon'));
        re_bind()
    })

}


var fix_form = function () {
    $('.channel-ul').sortable({trigger: '.sort-handle-1', selector: 'li', dragCssClass: '',finish:function(){
        re_bind()
    }
    });
    $('.channel-ul .ul-2').sortable({trigger: '.sort-handle-2', selector: 'li', dragCssClass: '',finish:function(){
        re_bind()
    }});

}


var add_one = function () {
    $('.add-one').unbind('click');
    $('.add-one').click(function () {
        if($(this).parents('form').find('.pLi').length > 2){
            updateAlert('一级导航最多支持三个','tip_error');
            setTimeout(function(){
                $('#tip').find('.tipclose').click();
            },1500);
        }else{
            $(this).closest('.pLi').after($('#one-nav').html());
            re_bind()
        }
        
    })
}

var add_two = function () {
    $('.add-two').unbind('click');
    $('.add-two').click(function () {
        if($(this).parents('.pLi').find('.cLi').length > 4){
            updateAlert('二级导航最多支持五个','tip_error');
            setTimeout(function(){
                $('#tip').find('.tipclose').click();
            },1500);
        }else{
            $(this).closest('.cLi').after($('#two-nav').html());
            re_bind()
        }
        
    })
}

var add_child = function () {
    $('.add-child').unbind('click');
    $('.add-child').click(function () {
        if($(this).parents('.pLi').find('.cLi').length > 4){
            updateAlert('二级导航最多支持五个','tip_error');
            setTimeout(function(){
                $('#tip').find('.tipclose').click();
            },1500);
        }else{
            $(this).closest('.controls').after($('#two-nav').html());
            re_bind()
        }
    })
}


var remove_li = function () {
    $('.remove-li').unbind('click');
    $('.remove-li').click(function () {
        if( $(this).parents('form').find('.pLi').length > 1 || $(this).parents('form').find('.cLi').length > 0){
            $(this).closest('.channel').remove()
            re_bind()
        }else{
            updateAlert('不能再减了~','tip_error');
            setTimeout(function(){
                $('#tip').find('.tipclose').click();
            },1500);
        }

    })
}





var add_flag = function () {
    $('#tab3 .pLi').each(function (index, element) {
        $(this).attr('data-id', index);
        //$(this).find('.sort').val($(this).attr('data-order'));
    })
    $('.cLi').each(function (index, element) {
        $(this).find('.pid').val($(this).parents('.pLi').attr('data-id'));
        //$(this).find('.sort').val($(this).attr('data-order'));
    })
}

