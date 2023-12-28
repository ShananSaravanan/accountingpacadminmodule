<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Business Type</title>
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
        <button  id="update-btn" formaction="{{route('toBusinessTypeActions',['actionType' => 'Edit'])}}" hidden>Update All Data</button>
    <table>
        <tr>
            <th>ID</th>
            <th>Business Type Name</th>
            <th>Actions</th>
        </tr>
        @foreach($bTypes as $bType)
        <tr id = "{{$bType->id}}">
        <td><input class="column-data" type="text" name="{{'bTypeID'.$rowCount}}" value={{$bType->id}} readonly></td>
        <td><input class="column-data" type="text" name="{{'bTypeName'.$rowCount}}" value={{$bType->businessTypeName}} readonly></td>
        <td>
            <button class="column-data" type="button" onclick="bTypeEdit(event)">Edit</button><button class="column-data" type="button" hidden onclick="cancelbTypeEdit(event)">Cancel Edit</button>
            <br><button name="deletebtn" class="column-data" value="{{$bType->id}}" formaction="{{route('toBusinessTypeActions',['actionType' => 'Delete'])}}">Delete</button>
    
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
    <button type="button" onclick="showAddBox()">New Business Type</button>
    <div id="add-box" hidden>
    <form id="BusinessTypeForm" method="POST" action="" enctype="multipart/form-data">
    @include('Pages.Admin-Business.addbtype')
    <button type="submit" formaction="{{route('toBusinessTypeActions',['actionType' => 'Add'])}}">Add New Business Type</button>
</form>
    <button type="button" onclick="closeAddBox()">Cancel</button>
    </div>
</body>
</html>