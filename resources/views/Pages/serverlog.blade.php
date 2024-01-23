<!-- @extends('Pages.tablelayout')
@section('title', 'Admin | Server Log')
@section('pagename','Server Logs')
@section('updatecontent')
<table id="example1" class="table table-bordered table-striped" width="100%">
        <thead>
            <tr>
                <th>Time</th>
                <th>Level</th>
                <th>Message</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logContent as $entry)
                <tr>
                    <td>{{$entry['time']}}</td>
                    <td>{{$entry['level']}}</td>
                    <td>{{$entry['message']}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var userForm = document.getElementById('userForm');

        // Initialize DataTables
        var example1Table = $('#example1').DataTable({
            scrollX: true,
            scrollY: 400,
            // Other DataTable options as needed
        });

        // Your other JavaScript code...

        // Example: Submitting form with DataTables

    });
</script>
@endsection -->