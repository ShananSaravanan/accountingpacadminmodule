@extends('Pages.tablelayout')
@section('title', 'Admin | Honorific Codes')
@section('pagename','Honorific Codes')
@section('updatecontent')
    <form id="userForm" action="" method="POST">
        @csrf
        @method('post')
        <div class="text-right mb-3">
            <button id="update-btn" onclick="removeData()" formaction="{{route('toHcodeActions',['actionType' => 'Edit'])}}" hidden><i class="fa-solid fa-floppy-disk"></i> Update All Data</button>
        </div>

        <table id="example1" class="table table-bordered table-striped" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Honorific Code Name</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <input hidden type="text" id="rowcount" name="rowcount" value="">
                @foreach($hcodes as $hcode)
                    <tr id="{{ $hcode->id }}">
                        <td>
                            <label hidden>{{ $hcode->id ?? 'N/A' }}</label>
                            <input type="text" class="form-control" name="{{ 'hcodeID' . $rowcount }}" value="{{ $hcode->id ?? 'N/A' }}" readonly>
                        </td>
                        <td>
                            <label hidden>{{ $hcode->CodeName ?? 'N/A' }}</label>
                            <input type="text" class="form-control" name="{{ 'hCodeName' . $rowcount }}" value="{{ $hcode->CodeName ?? 'N/A' }}" readonly>
                        </td>
                        <td>
                            <button class="form-control edit-btn" type="button" onclick="HcodeEdit(event,{{ $rowcount }})"><i class="fa-solid fa-pen-to-square"></i> </button>
                            <button class="form-control cancel-btn" type="button" onclick="cancelHcodeEdit(event,{{ $rowcount }})" hidden><i class="fa-solid fa-ban"></i></button>
                            <br>
                            <button class="form-control delete-btn" name="deletebtn" value="{{ $hcode->id }}" formaction="{{ route('toHcodeActions', ['actionType' => 'Delete']) }}"><i class="fa-solid fa-trash-can"></i> </button>
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

@section('buttonText','New Honorific Code')
@section('addBoxContent')
@section('addBoxType','showAddBox()')
    @include('Pages.Admin-Hcode.addhcode')
    
@endsection
