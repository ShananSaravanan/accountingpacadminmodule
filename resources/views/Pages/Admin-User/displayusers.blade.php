<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token()}}">
    <title>Document</title>
</head>

<body>
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
<label for="">Display Filters</label>
<select name="" id="" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);"> 
    <option value="/Pages/Admin-User/displayusers/allusers">All Users</option>
    <option value="/Pages/Admin-User/displayusers/onlineusers">Currently Online</option>
    <option value="/Pages/Admin-User/displayusers/offlineusers">Inactive Users</option>
</select>

<table>
    <tr>
    <th>ID</th>
    <th>First Name</th>
    <th>Last Name</th>
    <th>Honorific Code</th>
    <th>Role</th>
    <th>Contact Number</th>
    <th>Registered Email Address</th>
    <th>Registered Password</th>
    <th>Status</th>
    <th>Actions</th>
    </tr>
    <form action="" method="POST">
    @csrf
    @method('post')
    <button id="update-btn" onclick="removeGlobalData()" formaction="{{route('toUserActions',['actionType' => 'Edit'])}}" hidden>Update All Data</button>
    @foreach($usersData as $userData)
    <input name="rowcount" value={{$rowcount}} hidden>
    <tr id = "{{ $userData->id }}" value="1">
    <td ><input  type="text" class="column-data" name="{{'id'.$rowcount}}"  id="user-id" value={{$userData -> id}} readonly></td>
    <td ><input type="text" class="column-data" name="{{'fname'.$rowcount}}" id="user-fname" value={{$userData -> FirstName}} readonly></td>
    <td ><input type="text" class="column-data" name="{{'lname'.$rowcount}}" id="user-lname" value={{$userData -> LastName}} readonly></td>
    <td >
        <input type="text" class="column-data" id="user-hcode" name="{{$userData -> honorific -> id}}" value={{$userData -> honorific->CodeName}} readonly>
        <select class="column-data" name="{{'hCode'.$rowcount}}" id="hCodeOptions" hidden>
    @foreach($hCodeNames as $hCodeName)
        <option value="{{$hCodeName -> CodeName}}" id="user-hcode2">{{$hCodeName -> CodeName}}</option>
    @endforeach
    </select>
    </td>
    <td >
        <input type="text" class="column-data" id="user-rname" name="{{$userData -> roles -> id}}" value={{$userData -> roles->name}} readonly>
        <select class="column-data" name="{{'role'.$rowcount}}" id="roleOptions" hidden>
        @foreach($roleNames as $roleName)
        <option value="{{$roleName -> name}}" id="user-rname2">{{$roleName -> name}}</option>
        @endforeach
    </select></td>
    <td ><input type="text" class="column-data" name="{{'contact'.$rowcount}}" id="user-contact" value={{$userData -> contactNo}} readonly></td>
    <td ><input type="text" class="column-data" name="{{'email'.$rowcount}}" id="user-email" value={{$userData -> email}} readonly></td>
    <td ><input type="text" class="column-data" name="{{'password'.$rowcount}}" id="user-password" value={{$userData -> password}} readonly></td>
    <td ><input type="text" class="column-data" name="{{'status'.$rowcount}}" id="user-status"  value={{$userData -> Status}} readonly></td>
    <td >
    <button class="column-data" type="button"  id="edit-btn" onclick="userEditMode(event)">Edit</button>
    <button class="column-data" formaction="{{route('toUserActions',['actionType' => 'Delete'])}}" name="deletebtn" value="{{ $userData->id }}">Delete</button>
    <button class="column-data" onclick="cancelUserEdit(event)" value={{$userData -> id}} id="cancel-btn" type="button" hidden>Cancel Edit</button> 
</td> 
    </tr>
    @php
    $rowcount++; 
    @endphp
    @endforeach
    </form>
</table>
</div>
<button type="button" onclick="showAddBox()">New User</button>
<div id="add-box" hidden>
<form action="" method="POST" >
    @include('Pages.Admin-User.adduser')
    <button formaction="{{route('toUserActions',['actionType' => 'Add'])}}"  id="addUserBtn">Add User</button>
    </form>
<button type="button" onclick="closeAddBox()"> Cancel</button>
</div>
</body>
</html>
