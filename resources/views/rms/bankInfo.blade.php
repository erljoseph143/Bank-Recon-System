
        <table id="datatable_fixed_column" class="table table-bordered" style="border-collapse:collapse;border:1px solid black" width="100%">
            <thead>
            <tr>
                <th>Year</th>
                <th>Pecentage</th>
                <th>Status</th>

            </tr>
            </thead>
            <tbody>
            @foreach($data as $key => $bs)

                @if($data[$key][0]!=0)
                    <tr>
                        <td>{{$data[$key][1]}}</td>
                        <td>

                            <div class="easy-pie-chart txt-color-{{$data[$key][3]==100?'green':'red'}} easyPieChart" data-percent="{{$data[$key][3]}}" data-size="120" data-pie-size="100">
                                <span class="percent percent-sign txt-color-{{$data[$key][3]==100?'green':'red'}} font-xl semi-bold">49</span>
                            </div>
                        </td>
                        <td>{{$data[$key][2]}}</td>

                    </tr>
                @else
                    <tr>
                        <td colspan="3" style="text-align:center">{{$data[$key][1]}}</td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>

<script type="text/javascript">

    /* DO NOT REMOVE : GLOBAL FUNCTIONS!
     *
     * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
     *
     * // activate tooltips
     * $("[rel=tooltip]").tooltip();
     *
     * // activate popovers
     * $("[rel=popover]").popover();
     *
     * // activate popovers with hover states
     * $("[rel=popover-hover]").popover({ trigger: "hover" });
     *
     * // activate inline charts
     * runAllCharts();
     *
     * // setup widgets
     * setup_widgets_desktop();
     *
     * // run form elements
     * runAllForms();
     *
     ********************************
     *
     * pageSetUp() is needed whenever you load a page.
     * It initializes and checks for all basic elements of the page
     * and makes rendering easier.
     *
     */

    pageSetUp();

    /*
     * ALL PAGE RELATED SCRIPTS CAN GO BELOW HERE
     * eg alert("my home function");
     *
     * var pagefunction = function() {
     *   ...
     * }
     * loadScript("js/plugin/_PLUGIN_NAME_.js", pagefunction);
     *
     */

    // PAGE RELATED SCRIPTS

    // pagefunction
    var pagefunction = function() {


    };

    // load related plugins

    loadScript("js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js");


</script>

