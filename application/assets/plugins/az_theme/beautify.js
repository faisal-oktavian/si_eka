var windowWidth = $(window).width();
if (windowWidth <= 768) {
    $('.btn-beautify-menu').attr('state','inactive');
}
if (windowWidth > 768) {
    $('body').on('click','.btn-beautify-menu',function(){
        if($(this).attr('state') == "active"){
            $('#beautify-left').css({
                width: '0px',
            });
            $('#beautify-right').css({
                width: '100%',
                marginLeft: 0,
            });
            $('.btn-beautify-menu').attr('state','inactive');
        }
        else{
            $('#beautify-left').css({
                width: '240px',
            });
            $('#beautify-right').css({
                width: parseInt(windowWidth)-240,
                marginLeft: 240,
            });
            $('.btn-beautify-menu').attr('state','active');
        }
    }); 
}
if (windowWidth <= 768) {
    $('body').on('click','.btn-beautify-menu',function(){
        if($(this).attr('state') == "active"){
            $('#beautify-left').css({
                width: '0px',
            });
            $('.btn-beautify-menu').attr('state','inactive');
            $('.az-menu-trigger-mobile').hide();
        }
        else{
            $('#beautify-left').css({
                width: '240px',
            });
            $('.btn-beautify-menu').attr('state','active');
            $('.az-menu-trigger-mobile').show();
        }
    }); 

    $('body').on('click','.az-menu-trigger-mobile',function(){
        if($('.btn-beautify-menu').attr('state') == "active"){
            $('#beautify-left').css({
                width: '0px',
            });
            $('.btn-beautify-menu').attr('state','inactive');
            $('.az-menu-trigger-mobile').hide();
        }
        else{
            $('#beautify-left').css({
                width: '240px',
            });
            $('.btn-beautify-menu').attr('state','active');
            $('.az-menu-trigger-mobile').show();
        }
    });

}
$('body').on('click','.az-header-toolbar-user', function(){
    console.log('test');
    $('.az-header-toolbar').find('.account-detail').toggle();
});



// back to top
	var btn=$("#backToTop");
    var circle=document.querySelector('.progress-ring-circle');
    var radius=circle.r.baseVal.value;
    var circumference=radius*2*Math.PI;
    circle.style.strokeDasharray=circumference;
    circle.style.strokeDashoffset=circumference;

    function setProgress(percent){
        var offset=circumference-(percent/100)*circumference;
        circle.style.strokeDashoffset=offset;
    }

    $(window).scroll(function(){
        var scrollTop=$(this).scrollTop();
        var docHeight=$(document).height()-$(window).height();
        var percent=(scrollTop/docHeight)*100;
        setProgress(percent);
        if(scrollTop>300){
            btn.addClass("show");
        }
		else{
            btn.removeClass("show");
        }
    });

    $("#btnTop").click(function(){
        $("html,body").animate({
            scrollTop:0
        },700);
    });
// end back to top