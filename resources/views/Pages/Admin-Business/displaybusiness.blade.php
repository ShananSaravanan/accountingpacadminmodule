@extends('Pages.tablelayout')
@section('title', 'Admin | Businesses')
@section('updatecontent')
<form id="userForm" action="" method="POST">

@csrf
@method('post')
<div class="text-right mb-3"> <!-- Added mb-3 class for margin-bottom -->
    <button id="update-btn" onclick="removeData()" formaction="{{route('toBusinessActions',['actionType' => 'Edit'])}}"
        hidden><i class="fa-solid fa-floppy-disk"></i> Update All Entries</button>
</div>

<table id="example1" class="table table-bordered table-striped" width="100%">
    <thead>
   
        <tr>
        <th><label for="">Business ID</label></th>
        <th><label for="">Business Name</label></th>
        <th><label for="">Business Type</label></th>
        <th><label for="">Business Contact</label></th>
        <th><label for="">Business Email</label></th>
        <th><label for="">Business Logo</label></th>
        <th><label for="">Actions</label></th>
        </tr>
</thead>
        <tbody>
        <input hidden type="text" id="rowcount" name="rowcount" value="">
        @foreach($businessData as $business)
        <tr id = "{{$business->id}}">
        <td>
        <label hidden>{{$business->id?? 'N/A'}}</label>   
        <input class="form-control" type="text" name="{{'business-id'.$rowcount}}" value="{{$business->id?? 'N/A'}}" readonly></td>
        <td>
        <label hidden>{{$business->businessName?? 'N/A'}}</label>  
            <input class="form-control" type="text" name="{{'business-name'.$rowcount}}" value="{{$business->businessName?? 'N/A'}}" readonly></td>
        <td>
        <label hidden>{{$business->bType->businessTypeName?? 'N/A'}}</label>  
            <input class="form-control" type="text" name="{{'business-type'.$rowcount}}" value="{{$business->bType->businessTypeName?? 'N/A'}}" readonly>
            <select class="form-control" name="{{'businessTypes'.$rowcount}}" id="businessTypes" hidden>
                @foreach($businessTypes as $businessType)
                <option value="{{$businessType->businessTypeName}}">{{$businessType->businessTypeName}}</option>
                @endforeach
            </select>
        </td>
        <td>
        <label hidden>{{$business->Contact?? 'N/A'}}</label>     
        <input class="form-control" type="text" name="{{'business-contact'.$rowcount}}" value="{{$business->Contact?? 'N/A'}}" readonly></td>
        <td>
        <label hidden>{{$business->email?? 'N/A'}}</label>     
        <input class="form-control" type="text" name="{{'business-email'.$rowcount}}" value="{{$business->email?? 'N/A'}}" readonly></td>
        <td>
            <input name="{{'oldlogosrc'.$rowcount}}" type="text" value="{{asset('storage/logos' . $business->logo)}}" hidden>
            <img class="form-control" src="{{asset('storage/logos' . $business->logo)}}" name="{{'business-logo'.$rowcount}}" alt="Business-Logo" height="100">
            <img  class="form-control" id="imagePreview" alt="Preview" hidden>
            <input class="form-control" id="imageInput" type="file" name="{{'edited-logo'.$rowcount}}" accept=".png, .jpg" onchange="previewImage(event,'edit')" height="100" hidden>
        </td>
        <td>
            <button class="form-control edit-btn"  type="button" onclick="businessEdit(event,{{$rowcount}})"><i class="fa-solid fa-pen-to-square"></i></button>
            <button value="{{$business->id}}"  name="deletebtn" formaction="{{route('toBusinessActions',['actionType' => 'Delete'])}}" class="form-control delete-btn"><i class="fa-solid fa-trash-can"></i></button>
            <button class="form-control cancel-btn" type="button" onclick="cancelBusinessEdit(event,{{$rowcount}})" hidden><i class="fa-solid fa-ban"></i></button>
        </td>
        
        @php
        $rowcount++;
        @endphp

        @endforeach
        </tbody>
        </tr>
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

    @section('buttonText','New Business')
@section('addBoxContent')
@section('addBoxType','showAddBox()')
   @include('Pages.Admin-Business.addbusiness')
@endsection     