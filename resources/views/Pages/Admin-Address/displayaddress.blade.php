@extends('Pages.tablelayout')
@section('title', 'Admin | Address')
@section('updatecontent')
@section('pagename','Address')

<form id="userForm" action="" method="POST">
@csrf
    @method('post')
<div class="text-right mb-3"> <!-- Added mb-3 class for margin-bottom -->
    <button id="update-btn" onclick="removeData()"
        formaction="{{route('toAddressActions',['actionType' => 'Edit'])}}" hidden><i class="fa-solid fa-floppy-disk"></i> Update All Entries</button>
</div>
<table id="example1" class="table table-bordered table-striped" width="100%">
<thead>
    <tr>
    <th>ID</th>
    <th>User Email</th>
    <th>Address Type</th>
    <th>Address Line 1</th>
    <th>Street</th>
    <th>State</th>
    <th>Post Office Name</th>
    <th>Post Code</th>
    <th>Country</th>
    <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <input hidden type="text" id="rowcount" name="rowcount" value="">
    @foreach($addresses as $address)
    
    <tr id = "{{ $address->id }}" >
    <td>
        <label hidden for="">{{$address -> id ?? 'N/A'}}</label hidden>
        <input  type="text" class="form-control" name="{{'addressid'.$rowcount}}"  id="adress-id" value={{$address -> id ?? 'N/A'}} readonly>
    </td>
    <td>
    <label hidden for="">{{$address -> user->email ?? 'N/A'}}</label hidden>
        <input type="text" class="form-control" name="useremail" id="user-email" value={{$address -> user->email ?? 'N/A'}} readonly>
        <select class="form-control" name="{{'selecteduseremail'.$rowcount}}" id="userOptions" hidden>
    @foreach($users as $user)
        <option value="{{$user -> email}}" id="postofficename2">{{$user -> email}}</option>
    @endforeach
    </select>
    </td>
    <td>
    <label hidden for="">{{$address -> addressType -> name ?? 'N/A'}}</label hidden>
        <input type="text" class="form-control" id="address-type" name="addressTypeID" value="{{trim($address -> addressType -> name) ?? 'N/A'}}" readonly>
        <select class="form-control" name="{{'addressType'.$rowcount}}" id="addressTypeOptions" hidden>
    @foreach($addressTypes as $addressType)
        <option value="{{$addressType -> name}}" id="">{{$addressType -> name}}</option>
    @endforeach
    </select>
    </td>
    <td>
    <label hidden for="">{{$address -> addressLine1 ?? 'N/A'}}</label hidden>
        <input  type="text" class="form-control" name="{{'addressline'.$rowcount}}"  id="address-line" value="{{trim($address -> addressLine1) ?? 'N/A'}}" readonly>
    </td>
    <td>
    <label hidden for="">{{$address -> street ?? 'N/A'}}</label hidden>
        <input  type="text" class="form-control" name="{{'street'.$rowcount}}"  id="address-street" value="{{trim($address -> street) ?? 'N/A'}}" readonly>
    </td>
    <td>
    <label hidden for="">{{$address -> postcode -> statecode -> name ?? 'N/A'}}</label hidden>
        <input type="text" class="form-control" id="{{'stateCode'.$rowcount}}" name="stateCode" value="{{$address -> postcode -> statecode -> name ?? 'N/A'}}" readonly>
        <select  class="form-control" name="{{'stateCode'.$rowcount}}" id="{{'stateCode'.$rowcount}}" onchange="handleStateCodeChange(this,event)" hidden>
        @foreach($stateCodes as $statecode)
        <option  value="{{$statecode -> name}}" id="">{{$statecode -> name}}</option>
        @endforeach
        </select>
    </td>
    <td>
    <label hidden for="">{{$address -> postcode -> postoffice -> name ?? 'N/A'}}</label hidden>
        <input type="text" class="form-control" id="{{'postOfficeName'.$rowcount}}" name="postOffice" value="{{$address -> postcode -> postoffice -> name ?? 'N/A'}}" readonly>
        <select class="form-control" name="{{'postOfficeOption'.$rowcount}}" id="{{'postOfficeOption'.$rowcount}}" onchange="handlepostOfficeChange(this,'2')" hidden>
        
        <option  value="" id="">Auto-Generated</option>
        
    </select></td>
    <td>
    <label hidden for="">{{$address -> postcode -> postcode ?? 'N/A'}}</label hidden>
        <input type="text" class="form-control" id="{{'postcode'.$rowcount}}" name="{{'postcode'.$rowcount}}" value={{$address -> postcode -> postcode ?? 'N/A'}} readonly>
    </td>
    <td> 
    <label hidden for="">Malaysia</label hidden>
        <input type="text" class="form-control" id="country" name="{{'country'.$rowcount}}" value="Malaysia" readonly>
    </td>

    <td>
    <button class="form-control edit-btn" type="button"  id="edit-btn" onclick="addressEdit(event,{{$rowcount}})"><i class="fa-solid fa-pen-to-square"></i></button>
    <button class="form-control delete-btn" formaction="{{route('toAddressActions',['actionType' => 'Delete'])}}" name="deletebtn" value="{{ $address -> id }}"><i class="fa-solid fa-trash-can"></i></button>
    <button class="form-control cancel-btn" onclick="cancelAddressEdit(event,{{$rowcount}})" value="{{$address -> id}}" id="cancel-btn" type="button" hidden><i class="fa-solid fa-ban"></i></button> 
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
@section('buttonText','New Address')
@section('addBoxContent')
@section('addBoxType','showAddBoxForAjax("new-address-state")')
    @include('Pages.Admin-Address.addaddress')
    
@endsection
