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
                $('#submit').attr('class','btn btn-success');
                $('#submit').val('Siker!');
                $('#submit').attr('disabled','true');
            }else{
                $('#submit').attr('disabled','true');
                $('#submit').attr('class','btn btn-danger');
                $('#submit').val('Közel volt, próbálkozz még!')
                $('#answer').attr('readonly','true');
                setTimeout(function(){
                    $('#submit').removeAttr('disabled');
                    $('#submit').attr('class','btn btn-primary');
                    $('#submit').val('Próba');
                    $('#answer').val("");
                    $('#answer').removeAttr('readonly');
                },1000);
            }

        },
        error:function(e){
            $('#submit').attr('disabled','true');
            $('#submit').attr('class','btn btn-danger');
            $('#submit').val('Közel volt, próbálkozz még!')
            $('#answer').attr('readonly','true');
            setTimeout(function(){
                $('#submit').removeAttr('disabled');
                $('#submit').attr('class','btn btn-primary');
                $('#submit').val('Próba');
                $('#answer').val("");
                $('#answer').removeAttr('readonly');
            },1000);
        }

    });
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
                    $('#submit').attr('class','btn btn-success');
                    $('#submit').val('Siker!');
                    $('#submit').attr('disabled','true');
                }else{
                    $('#submit').attr('disabled','true');
                    $('#submit').attr('class','btn btn-danger');
                    $('#submit').val('Közel volt, próbálkozz még!');
                    $('#answer').attr('readonly','true');
                    setTimeout(function(){
                        $('#submit').removeAttr('disabled');
                        $('#submit').attr('class','btn btn-primary');
                        $('#submit').val('Próba');
                        $('#answer').val("");
                        $('#answer').removeAttr('readonly');
                    },1000);
                    if(d.guesses>5){
                        $('#help_form').css('display','inline');
                    }
                }
            },
            error:function(e){
                $('#submit').attr('disabled','true');
                $('#submit').attr('class','btn btn-danger');
                $('#submit').val('Közel volt, próbálkozz még!');
                $('#answer').attr('readonly','true');
                setTimeout(function(){
                    $('#submit').removeAttr('disabled');
                    $('#submit').attr('class','btn btn-primary');
                    $('#submit').val('Próba');
                    $('#answer').val("");
                    $('#answer').removeAttr('readonly');
                },1000);
            }

        });
        return false;

    }

});

$('#success_modal').on('hidden.bs.modal', function (e) {
    $('#duplicate').on('hidden.bs.modal',function(e){
        $('#next_riddle').submit();
    });
});
