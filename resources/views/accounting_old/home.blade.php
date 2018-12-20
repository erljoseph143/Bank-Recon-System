@extends('layouts.app')

@section('content')
    <section id="widget-grid" class="">

        <!-- row -->
        <div class="row">
            <article class="col-sm-12">
                <!-- new widget -->
                <div class="jarviswidget" id="wid-id-0" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
                    <!-- widget options:
                    usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

                    data-widget-colorbutton="false"
                    data-widget-editbutton="false"
                    data-widget-togglebutton="false"
                    data-widget-deletebutton="false"
                    data-widget-fullscreenbutton="false"
                    data-widget-custombutton="false"
                    data-widget-collapsed="true"
                    data-widget-sortable="false"

                    -->
                    <header>
                        <span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
                        <h2> </h2>



                    </header>

                    <!-- widget div-->
                    <div class="no-padding" style="min-height:30em;">
                        <!-- widget edit box -->

                        <!-- end widget edit box -->

                        <div class="widget-body">
                            <!-- content -->

                            <img src="{{asset('bg-images/home-bg.png')}}" class="img-responsive" style="height:26em;margin-left:auto;margin-right: auto" alt="">
                            <div class="col-md-12" style="color: #f70000;font-family: monospace;font-size: 14px;font-style: italic;text-align: center;position: inherit;top: 10px;bottom: 10px; background: oldlace;padding: 10px;">
                                *Note: Please be inform that this system can only be use for matching disbursement of book and bank. Thank You!
                            </div>
                            <!-- end content -->
                        </div>

                    </div>
                    <!-- end widget div -->
                </div>
                <!-- end widget -->

            </article>
        </div>

        <!-- end row -->

        <!-- row -->



        <!-- end row -->

    </section>
    <!-- end widget grid -->

@endsection
