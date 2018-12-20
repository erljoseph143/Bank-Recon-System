
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Date Posted</th>
                <th>Check No.</th>
                <th>Withdrawals</th>
                <th>Deposits</th>
                <th>Balance</th>
                <th>Error info</th>
                <th>Line #</th>
            </tr>
            </thead>
            <tbody>
                {{--foreach ($upload_errors as $key => $error)--}}
                @foreach($upload_errors as $key => $error)
                    <tr>
                        <td>{{ $error[0] }}</td>
                        <td>{{ $error[1] }}</td>
                        <td>{{ $error[2] }}</td>
                        <td>{{ $error[3] }}</td>
                        <td>{{ $error[4] }}</td>
                        <td>
                            @if( $error[5] == "Blank cell balance" )
                                <span data-toggle="tooltip" title="Tip: The Line # next to this column specifies the line number of the excel file so that you can easily trace the error!" class="badge bg-red">{{ $error[5] }}</span>
                            @elseif( $error[5] == "Blank cell deposit and withdrawals" )
                                <span data-toggle="tooltip" title="Tip: The Line # next to this column specifies the line number of the excel file so that you can easily trace the error!" class="badge bg-red">{{ $error[5] }}</span>
                            @elseif( $error[5] == "Check # is not numeric" )
                                <span data-toggle="tooltip" title="Tip: If you have seen no errors in a cell try double clicking the cell press enter and upload it again!" class="badge bg-red">{{ $error[5] }}</span>
                            @elseif( $error[5] == "Withdrawal is not numeric" )
                                <span data-toggle="tooltip" title="Tip: If you have seen no errors in a cell try double clicking the cell press enter and upload it again!" class="badge bg-red">{{ $error[5] }}</span>
                            @elseif( $error[5] == "Deposit is not numeric" )
                                <span data-toggle="tooltip" title="Tip: If you have seen no errors in a cell try double clicking the cell press enter and upload it again!" class="badge bg-red">{{ $error[5] }}</span>
                            @elseif( $error[5] == "Balance is not numeric" )
                                <span data-toggle="tooltip" title="Tip: If you have seen no errors in a cell try double clicking the cell press enter and upload it again!" class="badge bg-red">{{ $error[5] }}</span>
                            @elseif( $error[5] == "Invalid effective date" )
                                <span data-toggle="tooltip" title="Tip: If you have seen no errors in a cell try double clicking the cell press enter and upload it again!" class="badge bg-red">{{ $error[5] }}</span>
                            @elseif( $error[5] == "Invalid date posted" )
                                <span data-toggle="tooltip" title="Tip: If you have seen no errors in a cell try double clicking the cell press enter and upload it again!" class="badge bg-red">{{ $error[5] }}</span>
                            @elseif( $error[5] == "Deposit and withdrawals Occupied" )
                                <span data-toggle="tooltip" title="Tip: The Line # next to this column specifies the line number of the excel file so that you can easily trace the error!" class="badge bg-red">{{ $error[5] }}</span>
                            @elseif( $error[5] == "OK" )
                                <span data-toggle="tooltip" title="Tip: The Line # next to this column specifies the line number of the excel file so that you can easily trace the error!" class="badge bg-green">{{ $error[5] }}</span>
                            @else
                            @endif
                        </td>
                        <td>{{ $error[6] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>