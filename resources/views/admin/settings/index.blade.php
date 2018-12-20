@extends('admin.layouts.main')

@section('content')
    <div class="row">
        <div class="col-sm-4">
            <div class="card m-b-20">
                <h5 class="card-header">Bank Statement</h5>
                <div class="card-body">
                    <p>Scan bank statement duplicate entries.</p>
                    <ul>
                        <li>
                            <a id="scan-duplicate-bs" href="{{ route("adminscanningbs") }}" data-toggle="modal" data-target=".bs-example-modal-sm">Duplicate Entries</a>
                            <span id="bs-dup-count-label" class="badge badge-info" data-url="{{ route('admin.bsdupcount.index') }}">0</span>
                        </li>
                        <li>
                            <a id="scan-bs-no-bu" href="{{ route('admin.bsnobu.index') }}">Bank statement with no business unit</a>
                            <span id="bs-no-bu-count-label" class="badge badge-danger" data-url="{{ route('admin.bsnobucount.index') }}">0</span>
                        </li>
                        <li>
                            <a id="scan-bs-no-bu" href="{{ route('admin.bsnobu.index') }}">Deleted bank statements</a>
                            <span id="bs-no-bu-count-label" class="badge badge-danger" data-url="">0</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="mySmallModalLabel">Scanning duplicate BS</h4>
                        </div>
                        <div class="modal-body">
                            <div class="progress m-b-20">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="100" aria-valuemax="100" style="width:100%"></div>
                            </div>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- circliful Chart -->
    <script type="text/javascript">

        window.onload = function () {
            $('.spinner-wrapper').fadeOut();

            getBSduplicateCount();
            getBSnoBUCount();

            
        }
        
        function getBSduplicateCount() {

            var el = $('#bs-dup-count-label'),
                url = el.data('url');

            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') } });

            $.post(url, function (data) {
                console.log(data);
                el.text(data);
            });
            
        }
        
        function getBSnoBUCount() {

            var el = $('#bs-no-bu-count-label'),
                url = el.data('url');

            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') } });

            $.post(url, function (data) {
                console.log(data);
                el.text(data);
            });
            
        }

        $('#scan-duplicate-bs').on('click', function (e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') } });

            $.ajax({
                type: 'POST', url: url, dataType: 'text',
                success: function(data) {
                    console.log(data);
                    var result = JSON.parse( data.match(/{[^}]+}$/gi) );
                    window.location.href = result.url;
                },
                error: function (error, error2, error3) {
                    console.log(error);
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
                                    console.log(output.responseText);
                                }catch(e){ console.log("[XHR STATECHANGE] Exception: " + e); }
                            }
                        };
                        return output;
                    }
                }
            });
        });

    </script>
@endpush