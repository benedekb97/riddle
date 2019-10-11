$('#submit').click(function(){
    var data = {
        "answer": $('#answer').val(),
        "_token": $('#csrf_token').val()
    };
    var url = $('#post_url').val();
    console.log(data);

    $.ajax({
        url: url,
        type: "POST",
        data: data,
        dataType: 'json',
        success:function(d){
            if(d.guess === "correct") {
                $('#success_modal').modal('show');
            }

        },
        error:function(e){
            console.log(e);
        }

    });


    $('#submit').attr('disabled','true');
    setTimeout(function(){
        $('#submit').removeAttr('disabled');
        $('#answer').val("");
    },3000);
});
$('#answer').keypress(function (e) {
    if (e.which === 13) {
        var data = {
            "answer": $('#answer').val(),
            "_token": $('#csrf_token').val()
        };
        var url = $('#post_url').val();
        console.log(data);

        $.ajax({
            url: url,
            type: "POST",
            data: data,
            dataType: 'json',
            success:function(d){
                if(d.guess === "correct") {
                    $('#success_modal').modal('show');
                }
            },
            error:function(e){
                console.log(e);
            }

        });
        $('#submit').attr('disabled','true');
        setTimeout(function(){
            $('#submit').removeAttr('disabled');
            $('#answer').val("");
        },3000);
        return false;

    }

});

$('#success_modal').on('hidden.bs.modal', function (e) {
    $('#next_riddle').submit();
});
