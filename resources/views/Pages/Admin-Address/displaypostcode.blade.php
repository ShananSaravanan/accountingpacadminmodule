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
<table>
    <tr>
    <th>ID</th>
    <th>Post Code</th>
    <th>Location</th>
    <th>Post Office Name</th>
    <th>State Name</th>
    <th>Actions</th>
    </tr>
    <form action="" method="POST">
    @csrf
    @method('post')
    <button id="update-btn" onclick="removeGlobalData()" formaction="{{route('toPostCodeActions',['actionType' => 'Edit'])}}" hidden>Update All Data</button>
    @foreach($postcodes as $postcode)
    <input name="rowcount" value={{$rowcount}} hidden>
    <tr id = "{{ $postcode->id }}" >
    <td ><input  type="text" class="column-data" name="{{'postcodeid'.$rowcount}}"  id="user-id" value={{$postcode -> id}} readonly></td>
    <td ><input type="number" class="column-data" name="{{'postcode'.$rowcount}}" id="user-fname" value={{$postcode -> postcode}} readonly></td>
    <td ><input type="text" class="column-data" name="{{'location'.$rowcount}}" id="user-lname" value={{$postcode -> location}} readonly></td>
    <td >
        <input type="text" class="column-data" id="postofficename" name="{{$postcode -> postoffice -> name}}" value={{$postcode -> postoffice -> name}} readonly>
        <select class="column-data" name="{{'officename'.$rowcount}}" id="postOfficeOptions" hidden>
    @foreach($postoffices as $postoffice)
        <option value="{{$postoffice -> name}}" id="postofficename2">{{$postoffice -> name}}</option>
    @endforeach
    </select>
    </td>
    <td >
        <input type="text" class="column-data" id="state-name" name="{{$postcode -> statecode -> name}}" value={{$postcode -> statecode -> name}} readonly>
        <select class="column-data" name="{{'statename'.$rowcount}}" id="stateOptions" hidden>
        @foreach($statecodes as $statecode)
        <option value="{{$statecode -> name}}" id="state-name2">{{$statecode -> name}}</option>
        @endforeach
    </select></td>
    <td >
    <button class="column-data" type="button"  id="edit-btn" onclick="postCodeEdit(event)">Edit</button>
    <button class="column-data" formaction="{{route('toPostCodeActions',['actionType' => 'Delete'])}}" name="deletebtn" value="{{ $postcode -> id }}">Delete</button>
    <button class="column-data" onclick="cancelpostCodeEdit(event)" value={{$postcode -> id}} id="cancel-btn" type="button" hidden>Cancel Edit</button> 
</td> 
    </tr>
    @php
    $rowcount++; 
    @endphp
    @endforeach
    </form>
</table>
</div>
<button type="button" onclick="showAddBox()">New Post Code</button>
<div id="add-box" hidden>
<form action="" method="POST" >
    @include('Pages.Admin-Address.addpostcode')
    <button formaction="{{route('toPostCodeActions',['actionType' => 'Add'])}}"  id="addUserBtn">Add Post Code</button>
    </form>
<button type="button" onclick="closeAddBox()"> Cancel</button>
</div>
</body>
</html>
