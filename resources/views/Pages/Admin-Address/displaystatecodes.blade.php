<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | State Codes</title>
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
        <button  id="update-btn" formaction="{{route('toStateCodeActions',['actionType' => 'Edit'])}}" hidden>Update All Data</button>
    <table>
        <tr>
            <th>ID</th>
            <th>State Name</th>
            <th>Actions</th>
        </tr>
        @foreach($statecodes as $statecode)
        <tr id = "{{$statecode->id}}">
        <td><input class="column-data" type="text" name="{{'stateCodeID'.$rowCount}}" value={{$statecode->id}} readonly></td>
        <td><input class="column-data" type="text" name="{{'stateName'.$rowCount}}" value={{$statecode->name}} readonly></td>
        <td>
            <button class="column-data" type="button" onclick="stateCodeEdit(event)">Edit</button><button class="column-data" type="button" hidden onclick="cancelstateCodeEdit(event)">Cancel Edit</button>
            <br><button name="deletebtn" class="column-data" value="{{$statecode->id}}" formaction="{{route('toStateCodeActions',['actionType' => 'Delete'])}}">Delete</button>
    
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
    <button type="button" onclick="showAddBox()">New State Code</button>
    <div id="add-box" hidden>
    <form id="addressTypeForm" method="POST" action="" enctype="multipart/form-data">
    @include('Pages.Admin-Address.addstatecodes')
    <button type="submit" formaction="{{route('toStateCodeActions',['actionType' => 'Add'])}}">Add New State Code</button>
    </form>
    <button type="button" onclick="closeAddBox()">Cancel</button>
    </div>
</body>
</html>