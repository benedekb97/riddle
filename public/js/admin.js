var show = false;
var left = "<i class='fa fa-arrow-left'></i>";
var right = "<i class='fa fa-arrow-right'></i>";
$('#sidebar-show').click(function(){
    show = !show;
    if(show){
        $('#sidebar').css('left','0');
        $('#sidebar-show').html(left);
    }else{
        $('#sidebar').css('left','-140px');
        $('#sidebar-show').html(right);
    }
});
