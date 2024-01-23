@extends('Pages.tablelayout')
@section('title', 'Admin | Business Types')
@section('pagename','Business Types')
@section('updatecontent')
<form id="userForm" action="" method="POST">
@csrf
    @method('post')
    <div class="text-right mb-3"> <!-- Added mb-3 class for margin-bottom -->
        <button id="update-btn" onclick="removeData()" formaction="{{route('toBusinessTypeActions',['actionType' => 'Edit'])}}"
            hidden><i class="fa-solid fa-floppy-disk"></i> Update All Entries</button>
    </div>

    <table id="example1" class="table table-bordered table-striped" width="100%">
        <thead>
        <tr>
            <th>ID</th>
            <th>Business Type Name</th>
            <th>Actions</th>
        </tr>
</thead>
        <tbody>
        <input hidden type="text" id="rowcount" name="rowcount" value="">
        @foreach($bTypes as $bType)
        <tr id = "{{$bType->id}}">
        <td>
        <label hidden>{{$bType->id?? 'N/A'}}</label>
        <input class="form-control" type="text" name="{{'bTypeID'.$rowcount}}" value={{$bType->id?? 'N/A'}} readonly></td>
        <td>
        <label hidden>{{$bType->businessTypeName?? 'N/A'}}</label>    
        <input class="form-control" type="text" name="{{'bTypeName'.$rowcount}}" value={{$bType->businessTypeName?? 'N/A'}} readonly></td>
        <td>
            <button class="form-control edit-btn" type="button" onclick="bTypeEdit(event,{{$rowcount}})"><i class="fa-solid fa-pen-to-square"></i></button>
            <button class="form-control cancel-btn" type="button" hidden onclick="cancelbTypeEdit(event,{{$rowcount}})"><i
                class="fa-solid fa-ban"></i></button>
            <br><button name="deletebtn " class="form-control delete-btn" value="{{$bType->id}}" formaction="{{route('toBusinessTypeActions',['actionType' => 'Delete'])}}"><i class="fa-solid fa-trash-can"></i></button>
    
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
    @section('buttonText','New Business Type')
@section('addBoxContent')
@section('addBoxType','showAddBox()')
    @include('Pages.Admin-Business.addbtype')

@endsection     