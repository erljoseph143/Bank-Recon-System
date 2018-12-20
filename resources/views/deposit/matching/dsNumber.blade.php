<table class="my-table" border="1" style="border-collapse: collapse;width: 100%;">
    <thead>
    <tr>
        <th class="my-table-th">Entry No</th>
        <th class="my-table-th">Book Date</th>
        <th class="my-table-th">Doc No</th>
        <th class="my-table-th">Ext Doc No</th>
        <th class="my-table-th">Book Amount</th>
        <th class="my-table-th">Users</th>
        <th class="my-table-th">Description</th>
        <th class="my-table-th">Total</th>
        <th class="my-table-th">Bank Date</th>
        <th class="my-table-th">Bank Description</th>
        <th class="my-table-th">Bank Amount</th>

    </tr>
    </thead>
    <tbody>
        @foreach($batchDS as $key => $batch)
            <tr>
                @foreach($batch as $key2 => $ds)
                   <td>{{$ds}}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>