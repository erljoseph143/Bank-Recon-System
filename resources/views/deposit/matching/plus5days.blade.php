<table class="my-table" border="1" style="border-collapse: collapse;width:100%">
    <thead>
    <tr>
        <th class="my-table-th">Bank Date</th>
        <th class="my-table-th">Bank Description</th>
        <th class="my-table-th">Bank Amount</th>
        <th class="my-table-th"></th>
        <th class="my-table-th">Entry No</th>
        <th class="my-table-th">Book Date</th>
        <th class="my-table-th">Doc No</th>
        <th class="my-table-th">Ext Doc No</th>
        <th class="my-table-th">Book Amount</th>
        <th class="my-table-th">User ID</th>
        <th class="my-table-th">Description</th>
    </tr>
    </thead>
    <tbody>
    @foreach($plus5days as $key => $plus5)
        <tr>
            @foreach($plus5 as $key2 => $days)
                <td>{{$days}}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>