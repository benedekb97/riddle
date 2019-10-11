@extends('layouts.home')

@section('title','Új Riddle')

@section('content')
    @if($approved==false)
        <div class="row">
            <div class="col-md-4 col-md-push-4">
                <div class="alert alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <p style="font-weight:bold; font-size:13pt;">Egy kis infó:</p>
                    Amíg nem kaptad meg a <strong>certified riddle-making street nigga</strong> címet addig egy moderátornak el kell fogadnia a riddle-jeid
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        @if( isset($error_message))
            <div class="col-md-4 col-md-push-4">
                <div class="alert alert-dismissable alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ $error_message }}
                </div>
            </div>
        @endif
        <div class="col-md-12">
            <form action="{{ route('riddles.save') }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Új riddle feltöltése</h3>
                    </div>
                    <div class="panel-body">


                        <div class="form-group">
                            <div class="input-group">
                                <label class="input-group-addon" for="title">Cím</label>
                                <input class="form-control" type="text" placeholder="Cím" name="title" id="title" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <label class="input-group-addon" for="answer">Megoldás</label>
                                <input class="form-control" type="text" placeholder="Megoldás" name="answer" id="answer" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <label class="input-group-addon" for="riddle">Riddle</label>
                                <input class="form-control" type="file" name="riddle" id="riddle" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div style="width:50%;" class="input-group">
                                <label for="difficulty">Nehézség: <span style="font-style:italic;" id="diff_value">1</span></label>
                                <input class="slider" type="range" name="difficulty" id="difficulty" min="1" max ="5">
                            </div>
                        </div>

                    </div>
                    <div class="panel-footer">
                        <input type="submit" class="btn btn-primary" value="Mentés">
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection

@section('scripts')
<script>
    var slider = document.getElementById("difficulty");
    var output = document.getElementById("diff_value");

    var difficulties = ['Egy perces riddle','Easy','Elgondolkodtató','Nehéz','Kenyér'];

    console.log(slider.value);

    output.innerHTML = difficulties[slider.value-1]; // Display the default slider value

    // Update the current slider value (each time you drag the slider handle)
    slider.oninput = function() {
        output.innerHTML = difficulties[this.value-1];
    }
</script>
@endsection
