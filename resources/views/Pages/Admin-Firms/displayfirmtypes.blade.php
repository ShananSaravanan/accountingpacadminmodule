<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Firm Type</title>
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
        <button  id="update-btn" formaction="{{route('toFirmTypeActions',['actionType' => 'Edit'])}}" hidden>Update All Data</button>
    <table>
        <tr>
            <th>ID</th>
            <th>Firm Type Name</th>
            <th>Actions</th>
        </tr>
        @foreach($FirmTypes as $firmType)
        <tr id = "{{$firmType->id}}">
        <td><input class="column-data" type="text" name="{{'firmTypeID'.$rowCount}}" value={{$firmType->id}} readonly></td>
        <td><input class="column-data" type="text" name="{{'firmTypeName'.$rowCount}}" value={{$firmType->name}} readonly></td>
        <td>
            <button class="column-data" type="button" onclick="firmTypeEdit(event)">Edit</button><button class="column-data" type="button" hidden onclick="cancelfirmTypeEdit(event)">Cancel Edit</button>
            <br><button name="deletebtn" class="column-data" value="{{$firmType->id}}" formaction="{{route('toFirmTypeActions',['actionType' => 'Delete'])}}">Delete</button>
    
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
    <button type="button" onclick="showAddBox()">New Firm Type</button>
    <div id="add-box" hidden>
    @include('Pages.Admin-Firms.addfirmtypes')
    <button type="button" onclick="closeAddBox()">Cancel</button>
    </div>
</body>
</html>