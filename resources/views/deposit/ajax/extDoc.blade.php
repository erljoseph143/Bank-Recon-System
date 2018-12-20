<table style="border-collapse: collapse;" border="1">
    <thead>
    <tr>
        <th>Entry No</th>
        <th>Book Date</th>
        <th>Doc No</th>
        <th>Ext Doc No</th>
        <th>Book Amount</th>
        <th>Users</th>
        <th>Description</th>
        <th>Total</th>
        <th>Bank Date</th>
        <th>Bank Description</th>
        <th>Bank Amount</th>

    </tr>
    </thead>
    <tbody>
        @foreach($batchDS as $key => $batch)
            <tr>
                @foreach($batch as $key2 => $b)
                    <td>{{$b}}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>