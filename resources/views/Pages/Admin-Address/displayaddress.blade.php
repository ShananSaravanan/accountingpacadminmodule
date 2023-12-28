<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token()}}">
    <title>Admin | Registered Addresses</title>
</head>

<body>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="{{ asset('/js/application.js') }}"></script>
<link rel="stylesheet" href="{{ asset('/css/application.css') }}">

@if (session('alertMessage'))
<script>alert('{{session('alertMessage')}}')</script>
@endif
@if(session('updateError'))
<script>alert('{{session('updateError')}}')</script>
@endif

@include('Pages.templates.sidebar') 
<div id="edit-box">

<table>
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
    </tr>
    <form action="" method="POST">
    @csrf
    @method('post')
    <button id="update-btn" formaction="{{route('toAddressActions',['actionType' => 'Edit'])}}" hidden>Update All Data</button>
    @foreach($addresses as $address)
    <input name="rowcount" value={{$rowcount}} hidden>
    <tr id = "{{ $address->id }}" >
    <td><input  type="text" class="column-data" name="{{'addressid'.$rowcount}}"  id="adress-id" value={{$address -> id}} readonly></td>
    <td>
        <input type="text" class="column-data" name="useremail" id="user-email" value={{$address -> user->email}} readonly>
        <select class="column-data" name="{{'selecteduseremail'.$rowcount}}" id="userOptions" hidden>
    @foreach($users as $user)
        <option value="{{$user -> email}}" id="postofficename2">{{$user -> email}}</option>
    @endforeach
    </select>
    </td>
    <td>
        <input type="text" class="column-data" id="address-type" name="addressTypeID" value={{$address -> addressType -> name}} readonly>
        <select class="column-data" name="{{'addressType'.$rowcount}}" id="addressTypeOptions" hidden>
    @foreach($addressTypes as $addressType)
        <option value="{{$addressType -> name}}" id="">{{$addressType -> name}}</option>
    @endforeach
    </select>
    </td>
    <td><input  type="text" class="column-data" name="{{'addressline'.$rowcount}}"  id="address-line" value={{$address -> addressLine1}} readonly></td>
    <td><input  type="text" class="column-data" name="{{'street'.$rowcount}}"  id="address-street" value={{$address -> street}} readonly></td>
    <td>
        <input type="text" class="column-data" id="{{'stateCode'.$rowcount}}" name="stateCode" value={{$address -> postcode -> statecode -> name}} readonly>
        <select  class="column-data" name="{{'stateCode'.$rowcount}}" id="{{'stateCode'.$rowcount}}" onchange="handleStateCodeChange(this,event)" hidden>
        @foreach($stateCodes as $statecode)
        <option  value="{{$statecode -> name}}" id="">{{$statecode -> name}}</option>
        @endforeach
        </select>
    </td>
    <td>
        <input type="text" class="column-data" id="{{'postOfficeName'.$rowcount}}" name="postOffice" value="{{$address -> postcode -> postoffice -> name}}" readonly>
        <select class="column-data" name="{{'postOfficeOption'.$rowcount}}" id="{{'postOfficeOption'.$rowcount}}" onchange="handlepostOfficeChange(this,'2')" hidden>
        
        <option  value="" id="">Auto-Generated</option>
        
    </select></td>
    <td>
        <input type="text" class="column-data" id="{{'postcode'.$rowcount}}" name="{{'postcode'.$rowcount}}" value={{$address -> postcode -> postcode}} readonly>
    </td>
    <td> <input type="text" class="column-data" id="country" name="{{'country'.$rowcount}}" value="Malaysia" readonly></td>

    <td>
    <button class="column-data" type="button"  id="edit-btn" onclick="addressEdit(event)">Edit</button>
    <button class="column-data" formaction="{{route('toAddressActions',['actionType' => 'Delete'])}}" name="deletebtn" value="{{ $address -> id }}">Delete</button>
    <button class="column-data" onclick="cancelAddressEdit(event)" value="{{$address -> id}}" id="cancel-btn" type="button" hidden>Cancel Edit</button> 
</td> 
    </tr>
    @php
    $rowcount++; 
    @endphp
    @endforeach
    </form>
</table>
</div>
<button id="newBtn" type="button" onclick="showAddBox('new-address-state')">New Address</button>
<div id="add-box" hidden>
    <form id="addressTypeForm" method="POST" action="" enctype="multipart/form-data">
    @include('Pages.Admin-Address.addaddress')
    <button type="submit" formaction="{{route('toAddressActions',['actionType' => 'Add'])}}">Add New Address </button>
    </form>
    <button type="button" onclick="closeAddBox()">Cancel</button>
</div>
</body>
</html>
