@extends('designex.layouts.metronic2')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="tabbable-custom tabbable-noborder">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#tab_1" data-toggle="tab">Data Transmit</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet light">
                                    <div class="portlet-title">
                                        <div class="caption font-purple-plum">
                                            <i class="icon-cloud-upload font-purple-plum"></i>
                                            <span class="caption-subject bold uppercase"> CVS, SL, LEDGER, ACCOUNT</span>
                                        </div>
                                        <div class="actions">
                                            <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;" data-original-title="" title="">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="portlet-body flip-scroll">
                                        <form action="{{ url('designex/accounting/upload') }}" class="file-upload-form" id="" method="POST">
                                            <div class="drag-drop-area">
                                                {{ csrf_field() }}
                                                <div class="box__input">
                                                    <input type="hidden" id="prog-url" value="">
                                                    <input type="hidden" id="prog-status" value="">
                                                    <p class="drag-drop-info">Drop files here</p>
                                                    <p>or</p>
                                                    <p class="drag-drop-buttons">
                                                        <input type="file" class="box__file open" id="drag_n_drop_txt" data-ses="" name="files[]" accept=".txt" multiple="" data-multiple-caption="{count} files selected">
                                                        <label for="drag_n_drop_txt" class="btn btn-sm bg-olive btn-flat">Select Files</label>
                                                    </p>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="note note-danger note-bordered">
                                            <p>
                                                NOTE: Maximum file upload size is only {{ $max_file_size }}! Only text files (TXT) are allowed.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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

@section('crumb')
    <li>
        <a href="{{ url('designex/accounting') }}">Home</a>
        <i class="fa fa-circle"></i>
    </li>
    <li class="active">
        Upload
    </li>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ url('designex/css/main.css') }}">
@endpush
@push('scripts')
    <script type="text/javascript" src="{{ url('designex/larry-scripts/upload.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/plugin/swal/sweetalert.min.js') }}"></script>
@endpush
@push('scriptinit')
    <script>
        jQuery(document).ready(function() {
            Metronic.init(); // init metronic core componets
            Layout.init(); // init layout
            ComponentsPickers.init();
            Index.init();
        });

    </script>
@endpush