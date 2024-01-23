@extends('Pages.tablelayout')
@section('title', 'Admin | Post Codes')
@section('updatecontent')

<form id="userForm" action="" method="POST">
@csrf
    @method('post')
<div class="text-right mb-3"> <!-- Added mb-3 class for margin-bottom -->
        <button id="update-btn" onclick="removeData()" formaction="{{route('toPostCodeActions',['actionType' => 'Edit'])}}"
            hidden><i class="fa-solid fa-floppy-disk"></i> Update All Entries</button>
    </div>
<table id="example1" class="table table-bordered table-striped" width="100%">
    <thead>
    <tr>
    <th>ID</th>
    <th>Post Code</th>
    <th>Location</th>
    <th>Post Office Name</th>
    <th>State Name</th>
    <th>Actions</th>
    </tr>
    </thead>
 
    <tbody>
    <input hidden type="text" id="rowcount" name="rowcount" value="">
    @foreach($postcodes as $postcode)
   
    <tr id = "{{ $postcode->id }}" >
    <td >
    <label for="" hidden>{{$postcode -> id ?? 'N/A'}}</label>    
    <input  type="text" class="form-control" name="{{'postcodeid'.$rowcount}}"  id="user-id" value={{$postcode -> id ?? 'N/A'}} readonly></td>
    <td >
    <label for="" hidden>{{$postcode -> postcode ?? 'N/A'}}</label> 
        <input type="number" class="form-control" name="{{'postcode'.$rowcount}}" id="user-fname" value={{$postcode -> postcode ?? 'N/A'}} readonly></td>
    <td >
    <label for="" hidden>{{$postcode -> location ?? 'N/A'}}</label> 
        <input type="text" class="form-control" name="{{'location'.$rowcount}}" id="user-lname" value="{{trim($postcode -> location )?? 'N/A'}}" readonly></td>
    <td >
    <label for="" hidden>{{$postcode -> postoffice -> name ?? 'N/A'}}</label> 
        <input type="text" class="form-control" id="postofficename" name="{{$postcode -> postoffice -> name}}" value="{{trim($postcode -> postoffice -> name) ?? 'N/A'}}" readonly>
        <select class="form-control" name="{{'officename'.$rowcount}}" id="postOfficeOptions" hidden>
    @foreach($postoffices as $postoffice)
        <option value="{{$postoffice -> name}}" id="postofficename2">{{$postoffice -> name}}</option>
    @endforeach
    </select>
    </td>
    <td >
    <label for="" hidden>{{$postcode -> statecode -> name ?? 'N/A'}}</label> 
        <input type="text" class="form-control" id="state-name" name="{{$postcode -> statecode -> name}}" value={{$postcode -> statecode -> name ?? 'N/A'}} readonly>
        <select class="form-control" name="{{'statename'.$rowcount}}" id="stateOptions" hidden>
        @foreach($statecodes as $statecode)
        <option value="{{$statecode -> name}}" id="state-name2">{{$statecode -> name}}</option>
        @endforeach
    </select></td>
    <td >
    <button class="form-control edit-btn" type="button"  id="edit-btn" onclick="postCodeEdit(event,{{$rowcount}})"><i class="fa-solid fa-pen-to-square"></i></button>
    <button class="form-control delete-btn" formaction="{{route('toPostCodeActions',['actionType' => 'Delete'])}}" name="deletebtn" value="{{ $postcode -> id }}"><i class="fa-solid fa-trash-can"></i></button>
    <button class="form-control cancel-btn" onclick="cancelpostCodeEdit(event,{{$rowcount}})" value={{$postcode -> id}} id="cancel-btn" type="button" hidden><i
                            class="fa-solid fa-ban"></i></button> 
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
@section('buttonText','New Address Type')
@section('addBoxContent')
@section('addBoxType','showAddBox()')
    @include('Pages.Admin-Address.addpostcode')
@endsection
