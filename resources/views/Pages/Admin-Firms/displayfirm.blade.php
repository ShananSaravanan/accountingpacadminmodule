@extends('Pages.tablelayout')
@section('title', 'Admin | Firms')
@section('pagename','Firms')
@section('updatecontent')

<form id="userForm" action="" method="POST">
@csrf
    @method('post')
<div class="text-right mb-3"> <!-- Added mb-3 class for margin-bottom -->
    <button id="update-btn" onclick="removeData()"
        formaction="{{route('toFirmActions',['actionType' => 'Edit'])}}" hidden><i class="fa-solid fa-floppy-disk"></i> Update All Entries</button>
</div>

<table id="example1" class="table table-bordered table-striped" width="100%" >
    <thead>
        <tr>
        <th><label for="">Firm ID</label></th>
        <th><label for="">Name</label></th>
        <th><label for="">Firm Owner</label></th>
        <th><label for="">Firm Type</label></th>
        <th><label for="">AF No</label></th>
        <th><label for="">SSM No</label></th>
        <th><label for="">Contact</label></th>
        <th><label for="">Email</label></th>
        <th><label for="">Address</label></th>
        <th><label for="">User Limit</label></th>
        <th><label for="">Status</label></th>
        <th><label for="">Firm Logo</label></th>
        <th><label for="">Actions</label></th>
        </tr>
        </thead>
        
        
        <tbody>
    
        <input hidden type="text" id="rowcount" name="rowcount" value="">
        @foreach($firms as $firm)
        <tr id = "{{$firm->id}}">
        <td>
            <label hidden>{{$firm->id ?? 'N/A'}}</label>
            <input class="form-control" type="text" name="{{'firm-id'.$rowcount}}" value="{{$firm->id ?? 'N/A'}}" readonly>
        </td>
        <td>
        <label hidden>{{$firm->firmName ?? 'N/A'}}</label>
            <input class="form-control" type="text" name="{{'firm-name'.$rowcount}}" value="{{$firm->firmName  ?? 'N/A'}}" readonly>
        </td>
        <td>
        <label hidden>{{$firm->firmowner->id??'N/A'}}</label>
            <input class="form-control" type="text" name="firmowner" value="{{$firm->firmowner->id??'N/A'}}" readonly>
            <select class="form-control" name="{{'firmOwner'.$rowcount}}" id="firmuserTypes" hidden>
                @foreach($firmusers as $firmuser)
                <option value="{{$firmuser->user->email}}">{{$firmuser->user->email}} ({{$firmuser->user->FirstName}} {{$firmuser->user->LastName}})</option>
                @endforeach
            </select>
        </td>
        <td>
        <label hidden>{{$firm->firmType->name??'N/A'}}</label>
            <input class="form-control" type="text" name="firmtype" value="{{$firm->firmType->name??'N/A'}}" readonly>
            <select class="form-control" name="{{'firmTypes'.$rowcount}}" id="firmTypes" hidden>
                @foreach($firmTypes as $firmType)
                <option value="{{$firmType->name}}">{{$firmType->name}}</option>
                @endforeach
            </select>
        </td>
        <td>
        <label hidden>{{$firm->AF_NO??'N/A'}}</label>
            <input class="form-control" type="text" name="{{'afno'.$rowcount}}" value="{{$firm->AF_NO??'N/A'}}" readonly>
        </td>
        <td>
        <label hidden>{{$firm->SSM_NO??'N/A'}}</label>
            <input class="form-control" type="text" name="{{'ssmno'.$rowcount}}" value="{{$firm->SSM_NO??'N/A'}}" readonly>
        </td>
        <td>
        <label hidden>{{$firm->contactNo??'N/A'}}</label>
            <input class="form-control" type="text"  name="{{'firm-contact'.$rowcount}}" value="{{$firm->contactNo??'N/A'}}" readonly>
        </td>
        <td>
        <label hidden>{{$firm->emailAddress??'N/A'}}</label>
            <input class="form-control" type="text" name="{{'firm-email'.$rowcount}}" value="{{$firm->emailAddress??'N/A'}}" readonly>
        </td>
        <td>
        <label hidden>{{$firm->address->addressLine1??'N/A'}},{{$firm->address->street??'N/A'}},{{$firm->address->postcode->postcode??'N/A'}},{{$firm->address->postcode->postoffice->name??'N/A'}},{{$firm->address->postcode->statecode->name??'N/A'}},{{$firm->address->country??'N/A'}}</label>
            <input class="form-control" type="text" name="address" value="{{$firm->address->addressLine1??'N/A'}},{{$firm->address->street??'N/A'}},{{$firm->address->postcode->postcode??'N/A'}},{{$firm->address->postcode->postoffice->name??'N/A'}},{{$firm->address->postcode->statecode->name??'N/A'}},{{$firm->address->country??'N/A'}}" readonly>
            <select class="form-control" name="{{'firmAddress'.$rowcount}}" id="firmTypes" hidden>
                @foreach($addresses as $address)
                <option value="{{$address->id}}">{{$address->addressLine1}},{{$address->street}},{{$address->postcode->postcode}},{{$address->postcode->postoffice->name}},{{$address->postcode->statecode->name}},{{$address->country}}</option>
                @endforeach
            </select>
        </td>
        <td>
        <label hidden>{{$firm->userLimit??'N/A'}}</label>
            <input class="form-control" type="number" name="{{'userlimit'.$rowcount}}" value="{{$firm->userLimit??'N/A'}}" readonly>
        </td>
        <td>
        <label hidden>{{$firm->status??'N/A'}}</label>
            <input class="form-control" type="text" name="{{'status'.$rowcount}}" value="{{$firm->status??'N/A'}}" readonly>
        </td>

        <td>
            <input name="{{'oldlogosrc'.$rowcount}}" type="text" value="{{asset('storage/logos' . $firm->logo)}}" hidden>
            <img class="form-control" src="{{asset('storage/logos' . $firm->logo)}}" name="{{'firm-logo'.$rowcount}}" alt="firm-Logo" height="100">
            <img  class="form-control" id="imagePreview" alt="Preview" hidden  height="100">
            <input class="form-control" id="imageInput" type="file" name="{{'edited-logo'.$rowcount}}" accept=".png, .jpg" onchange="previewImage(event,'firm')" hidden>
        </td>
        <td>
            <button class="form-control edit-btn"  type="button" onclick="firmEdit(event,{{$rowcount}})"><i class="fa-solid fa-pen-to-square"></i></button>
            <button value="{{$firm->id}}"  name="deletebtn" formaction="{{route('toFirmActions',['actionType' => 'Delete'])}}" class="form-control delete-btn"><i class="fa-solid fa-trash-can"></i></button>
            <button class="form-control cancel-btn" type="button" onclick="cancelFirmEdit(event,{{$rowcount}})" hidden><i class="fa-solid fa-ban"></i></button>
        </td>
        
        @php
        $rowcount++;
        @endphp

        @endforeach
        </tr>
        </tbody>
        </table>
        </form>
        <script>
    $(document).ready(function() {
    
        $('.edit-btn').each(function() {
        
        // Your logic to add classes or perform other actions based on rowcount and userid
        $(this).addClass('btn btn-outline-primary btn-block');
    });
    $('.delete-btn').each(function() {
        
        // Your logic to add classes or perform other actions based on rowcount and userid
        $(this).addClass('btn btn-outline-danger btn-block btn-sm');
    });
    $('.cancel-btn').each(function() {
        
        // Your logic to add classes or perform other actions based on rowcount and userid
        $(this).addClass('btn btn-outline-danger btn-block btn-sm');
    });
    $('#update-btn').each(function() {
        
        // Your logic to add classes or perform other actions based on rowcount and userid
        $(this).addClass('btn btn-success btn-block');
    });
    
  
});
    document.addEventListener('DOMContentLoaded', function () {
        var userForm = document.getElementById('userForm');
        
        // Initialize DataTables
        $('#example1').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });

        // Your other JavaScript code...

        // Example: Submitting form with DataTables
        
    });
</script>
 @endsection
        
@section('buttonText','New Firm')
@section('addBoxContent')
@section('addBoxType','showAddBox()')
@include('Pages.Admin-Firms.addfirm')

@endsection 