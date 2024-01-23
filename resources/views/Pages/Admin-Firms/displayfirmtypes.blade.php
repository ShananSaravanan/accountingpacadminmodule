@extends('Pages.tablelayout')
@section('title', 'Admin | Firm Types')
@section('pagename', 'Firm Types')
@section('updatecontent')
    <form id="userForm" action="" method="POST">
        @csrf
        @method('post')
        <div class="text-right mb-3">
            <button id="update-btn" onclick="removeData()" formaction="{{route('toFirmTypeActions',['actionType' => 'Edit'])}}" hidden><i class="fa-solid fa-floppy-disk"></i> Update All Data</button>
        </div>

        <table id="example1" class="table table-bordered table-striped" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Firm Type Name</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <input hidden type="text" id="rowcount" name="rowcount" value="">
                @foreach($FirmTypes as $firmType)
                    <tr id="{{ $firmType->id }}">
                        <td>
                            <label hidden>{{ $firmType->id ?? 'N/A' }}</label>
                            <input type="text" class="form-control" name="{{ 'firmTypeID' . $rowcount }}" value="{{ $firmType->id ?? 'N/A' }}" readonly>
                        </td>
                        <td>
                            <label hidden>{{ $firmType->name ?? 'N/A' }}</label>
                            <input type="text" class="form-control" name="{{ 'firmTypeName' . $rowcount }}" value="{{ $firmType->name ?? 'N/A' }}" readonly>
                        </td>
                        <td>
                            <button class="form-control edit-btn" type="button" onclick="firmTypeEdit(event,{{ $rowcount }})"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="form-control cancel-btn" type="button" onclick="cancelfirmTypeEdit(event,{{ $rowcount }})" hidden><i class="fa-solid fa-ban"></i> </button>
                            <br>
                            <button class="form-control delete-btn" name="deletebtn" value="{{ $firmType->id }}" formaction="{{ route('toFirmTypeActions', ['actionType' => 'Delete']) }}"><i class="fa-solid fa-trash-can"></i> </button>
                        </td>
                    </tr>
                    @php
                        $rowcount++;
                    @endphp
                @endforeach
            </tbody>
        </table>
    </form>
    <script>
    $(document).ready(function () {

        $('.edit-btn').each(function () {

            // Your logic to add classes or perform other actions based on rowcount and userid
            $(this).addClass('btn btn-outline-primary btn-block');
        });
        $('.delete-btn').each(function () {

            // Your logic to add classes or perform other actions based on rowcount and userid
            $(this).addClass('btn btn-outline-danger btn-block btn-sm');
        });
        $('.cancel-btn').each(function () {

            // Your logic to add classes or perform other actions based on rowcount and userid
            $(this).addClass('btn btn-outline-danger btn-block btn-sm');
        });
        $('#update-btn').each(function () {

            // Your logic to add classes or perform other actions based on rowcount and userid
            $(this).addClass('btn btn-success btn-block');
        });


    });
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
@endsection

@section('buttonText','New Firm Type')
@section('addBoxContent')
@section('addBoxType','showAddBox()')
    @include('Pages.Admin-Firms.addfirmtypes')
@endsection
