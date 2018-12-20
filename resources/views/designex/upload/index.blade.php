@extends('designex.layouts.snoopy')
@section('content')
<div class="col-sm-12">
    <div class="panel panel-default card-view">
        <div class="panel-heading">
            <div class="pull-left">
                <h6 class="panel-title txt-dark">upload files (CV, SL, LEDGER, ACCOUNT)</h6>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="panel-wrapper collapse in">
            <div class="panel-body">
                <p class="text-muted"><code>NOTE:</code> Maximum file upload size is only <code>{{ $max_file_size }}</code>! Only <code>text files (TXT)</code> are allowed.</p>
                <div class="mt-40">
                    <form class="file-upload-form" action="{{ route('xuploadtask') }}">
                        {{ csrf_field() }}
                        <input type="file" id="drag_n_drop_txt" class="dropify" name="files[]" accept=".txt" multiple=""/>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="progress-32321" class="page" style="display: none">
    <div class="page__demo">
        <div class="page__container">
            <div class="demo">
                <div class="js-progressbar">
                    <span class="js-progressbar__value">0%</span>
                    <div id="load-html-3242">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('styles')
    <!--alerts CSS -->
    <link href="{{ asset('snoopy/vendors/bower_components/sweetalert/dist/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <!-- Bootstrap Dropify CSS -->
    <link href="{{ asset('snoopy/vendors/bower_components/dropify/dist/css/dropify.min.css') }}" rel="stylesheet" type="text/css"/>
@endsection
@section('endstyles')
    <link rel="stylesheet" href="{{ url('designex/css/main.css') }}">
@endsection
@section('scripts')
    <!-- Bootstrap Daterangepicker JavaScript -->
    <script src="{{ asset('snoopy/vendors/bower_components/dropify/dist/js/dropify.min.js') }}"></script>
    <!-- Sweet-Alert  -->
    <script src="{{ asset('snoopy/vendors/bower_components/sweetalert/dist/sweetalert.min.js') }}"></script>
@endsection
@section('endscripts')
    <script>
        $(document).ready(function () {
            $('.dropify').dropify();

            var txt = $('#drag_n_drop_txt'),
                form = $('.file-upload-form');
            txt.on('change', function (e) {
                e.preventDefault();
                droppedFiles = e.target.files;
                console.log(droppedFiles);

                form.trigger('submit');
            });

            form.submit(function (e) {
                e.preventDefault();
                var fdata = new FormData(),
                    url = $(this).attr('action'),
                    token = $('input[name="_token"]').val(),
                    html = "";
                for (var i = 0; i < droppedFiles.length; i++) {
                    var progressClass = [['color-1','bg-color-1'],['color-2','bg-color-2'],['color-3','bg-color-3'],['color-4','bg-color-4'],['color-5','bg-color-5']],
                        rand = progressClass[Math.floor(Math.random() * progressClass.length)];
                    file_id = droppedFiles[i].name.replace(/\./ig, "");
                    file_id = file_id.replace(/\s/ig, "");
                    html+='<div id="'+file_id+'" class="prog-cont">' +
                        '<label for="" id="">'+droppedFiles[i].name+'</label>' +
                        '<div class="progress progress-xs '+rand[1]+'">' +
                        '<div id="prog'+file_id+'" class="progress-bar '+rand[0]+'" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">' +
                        '<span class="sr-only">50%</span>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                }
                $('#load-html-3242').html(html);
                $('#progress-32321').show();
                if (droppedFiles) {
                    $.each( droppedFiles, function(i, file) {
                        fdata.append('files[]', file);
                    });
                }
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': token } });
                uploadxhr = $.ajax({
                    type: 'POST', url: url, data: fdata, dataType: 'text',
                    success: function(data) {
                        setTimeout(function (e) {
                            $('#progress-32321').fadeOut(500);
                        }, 1000);
                        data = JSON.parse(data.match(/{[^}]+}$/gi)[0]);
                        if (data.b=='error') { swal("Error",data.a,"error"); }
                        txt.val("");
                    },
                    error: function (error, error2, error3) {
                        setTimeout(function (e) {
                            $('#progress-32321').fadeOut(500);
                        }, 1000);
                        swal("Error",error+error2+error3,"error");
                        txt.val("");
                    },
                    cache: false, processData: false, contentType: false,
                    beforeSend: function (jqXHR, settings) {
                        var xhr = settings.xhr;
                        settings.xhr = function () {
                            var output = xhr();
                            output.onreadystatechange = function () {
                                if (output.readyState == 3) {
                                    try {
                                        var result = JSON.parse( output.responseText.match(/{[^}]+}$/gi) );
//                                        console.log(output.responseText);
                                        $('.js-progressbar__value').text(result.progress+'%');
                                        $('#prog'+result.filename).css({'width':result.progress+'%'}).attr('aria-valuenow', result.progress);
                                        if (result.progress > 95) {
                                            $('#'+result.filename).fadeOut(1000);
                                        }
                                    }catch(e){ console.log("[XHR STATECHANGE] Exception: " + e); }
                                }
                            };
                            return output;
                        }
                    }
                });
            });

        });
    </script>
@endsection