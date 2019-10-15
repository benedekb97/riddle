var input = $('#name_search');
var url = $('#post_url').val();
var token = $('#token').val();
var current_data = null;

$('#name_search').on("change paste keyup",function(){
    var value = input.val();

    var data = {
        'search': value,
        '_token': token
    };
    $('#autocomplete').css('display','block');
    $('#submit_button').attr('disabled','true');

    if(value!=="") {
        $.ajax({
            url: url,
            type: "POST",
            data: data,
            dataType: "json",
            success: function (data) {
                current_data = data;
                var addToHtml = "";

                data.forEach(function (value) {
                    addToHtml += "<div class=\'autocomplete-row\' onClick=\'addtotext(" + value.id + ")\'>" + value.name + "</div>";
                });

                $('#autocomplete').html(addToHtml);
            },
            error: function (error) {
                console.log(error);
            }
        });
    }else{
        $('#autocomplete').html('');
    }
});

function addtotext(id){
    current_data.forEach(function(value){
        if(value.id == id) {
            $('#name_search').val(value.name);
            $('#autocomplete').css('display','none');
            $('#user_id').val(value.id);
            $('#submit_button').removeAttr('disabled');
        }
    });
}
