<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Businesses</title>
</head>
<body>
@if(session('alertMessage'))
<script>alert("{{session('alertMessage')}}")</script>
@endif
<script src="{{ asset('/js/application.js') }}"></script>
<link rel="stylesheet" href="{{ asset('/css/application.css') }}">
    @include('Pages.templates.sidebar')
    <div id="edit-box">
    <form action="" method="POST" enctype="multipart/form-data">
        <button formaction="{{route('toBusinessActions',['actionType' => 'Edit'])}}" id="update-btn" hidden>Update All Data</button>
    @csrf
    @method('post')
    <table>
        <tr>
        <th><label for="">Business ID</label></th>
        <th><label for="">Business Name</label></th>
        <th><label for="">Business Type</label></th>
        <th><label for="">Business Contact</label></th>
        <th><label for="">Business Email</label></th>
        <th><label for="">Business Logo</label></th>
        <th><label for="">Actions</label></th>
        </tr>
        @foreach($businessData as $business)
        <tr id = "{{$business->id}}">
        <td><input class="column-data" type="text" name="{{'business-id'.$rowCount}}" value="{{$business->id}}" readonly></td>
        <td><input class="column-data" type="text" name="{{'business-name'.$rowCount}}" value="{{$business->businessName}}" readonly></td>
        <td>
            <input class="column-data" type="text" name="{{'business-type'.$rowCount}}" value="{{$business->bType->businessTypeName}}" readonly>
            <select class="column-data" name="{{'businessTypes'.$rowCount}}" id="businessTypes" hidden>
                @foreach($businessTypes as $businessType)
                <option value="{{$businessType->businessTypeName}}">{{$businessType->businessTypeName}}</option>
                @endforeach
            </select>
        </td>
        <td><input class="column-data" type="text" name="{{'business-contact'.$rowCount}}" value="{{$business->Contact}}" readonly></td>
        <td><input class="column-data" type="text" name="{{'business-email'.$rowCount}}" value="{{$business->email}}" readonly></td>
        <td>
            <input name="{{'oldlogosrc'.$rowCount}}" type="text" value="{{asset('storage/logos' . $business->logo)}}" hidden>
            <img class="column-data" src="{{asset('storage/logos' . $business->logo)}}" name="{{'business-logo'.$rowCount}}" alt="Business-Logo" height="100">
            <img  class="column-data" id="imagePreview" alt="Preview" hidden>
            <input class="column-data" id="imageInput" type="file" name="{{'edited-logo'.$rowCount}}" accept=".png, .jpg" onchange="previewImage(event,'edit')" height="100" hidden>
        </td>
        <td>
            <button class="column-data"  type="button" onclick="businessEdit(event)">Edit</button>
            <button value="{{$business->id}}"  name="deletebtn" formaction="{{route('toBusinessActions',['actionType' => 'Delete'])}}" class="column-data">Delete</button>
            <button class="column-data" type="button" onclick="cancelBusinessEdit(event)" hidden>Cancel Edit</button>
        </td>
        <input class="column-data" name="rowcount" type="text" hidden value="{{$rowCount}}">
        @php
        $rowCount++;
        @endphp

        @endforeach
        </tr>
    </table>
</form>
</div>
<button type="button" onclick="showAddBox()">New Business</button>
<div id="add-box" hidden>
<form id="businessForm" method="POST" action="" enctype="multipart/form-data">
   @include('Pages.Admin-Business.addbusiness')
   <button type="submit" formaction="{{route('toBusinessActions',['actionType' => 'Add'])}}">Add New Business</button>
   </form>
<button type="button" onclick="closeAddBox()"> Cancel</button>
</div>
</body>
</html>