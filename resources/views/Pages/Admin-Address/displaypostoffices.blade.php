<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Post Offices</title>
</head>
<body>
    @if(session('alertMessage'))
    <script>alert("{{session('alertMessage')}}")</script>
    @endif
<script src="{{ asset('/js/application.js') }}"></script>
<link rel="stylesheet" href="{{ asset('/css/application.css') }}">
    @include('Pages.templates.sidebar')
    <div id="edit-box">
    <form action="" method="POST">
    @csrf
    @method('post')
        <button  id="update-btn" formaction="{{route('toPostOfficeActions',['actionType' => 'Edit'])}}" hidden>Update All Data</button>
    <table>
        <tr>
            <th>ID</th>
            <th>Post Office Name</th>
            <th>Actions</th>
        </tr>
        @foreach($postoffices as $postoffice)
        <tr id = "{{$postoffice->id}}">
        <td><input class="column-data" type="text" name="{{'postofficeID'.$rowCount}}" value={{$postoffice->id}} readonly></td>
        <td><input class="column-data" type="text" name="{{'postofficeName'.$rowCount}}" value={{$postoffice->name}} readonly></td>
        <td>
            <button class="column-data" type="button" onclick="postOfficeEdit(event)">Edit</button><button class="column-data" type="button" hidden onclick="cancelpostOfficeEdit(event)">Cancel Edit</button>
            <br><button name="deletebtn" class="column-data" value="{{$postoffice->id}}" formaction="{{route('toPostOfficeActions',['actionType' => 'Delete'])}}">Delete</button>
    
        </td>
        </tr>
        <input hidden type="text" name="rowcount" value="{{$rowCount}}">
        @php
        $rowCount++;
        @endphp
        @endforeach

    </table>
    </form>
    </div>
    <button type="button" onclick="showAddBox()">New Post Office</button>
    <div id="add-box" hidden>
    <form id="addressTypeForm" method="POST" action="" enctype="multipart/form-data">
    @include('Pages.Admin-Address.addpostoffices')
    <button type="submit" formaction="{{route('toPostOfficeActions',['actionType' => 'Add'])}}">Add New Post Office</button>
    </form>
    <button type="button" onclick="closeAddBox()">Cancel</button>
    </div>
</body>
</html>