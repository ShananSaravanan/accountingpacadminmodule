<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Honorific Codes</title>
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
        <button  id="update-btn" formaction="{{route('toHcodeActions',['actionType' => 'Edit'])}}" hidden>Update All Data</button>
    <table>
        <tr>
            <th>ID</th>
            <th>Honorific Code Name</th>
            <th>Actions</th>
        </tr>
        @foreach($hcodes as $hcode)
        <tr id = "{{$hcode->id}}">
        <td><input class="column-data" type="text" name="{{'hcodeID'.$rowCount}}" value={{$hcode->id}} readonly></td>
        <td><input class="column-data" type="text" name="{{'hCodeName'.$rowCount}}" value={{$hcode->CodeName}} readonly></td>
        <td>
            <button class="column-data" type="button" onclick="HcodeEdit(event)">Edit</button><button class="column-data" type="button" hidden onclick="cancelHcodeEdit(event)">Cancel Edit</button>
            <br><button name="deletebtn" class="column-data" value="{{$hcode->id}}" formaction="{{route('toHcodeActions',['actionType' => 'Delete'])}}">Delete</button>
    
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
    <button type="button" onclick="showAddBox()">New Honorific Code</button>
    <div id="add-box" hidden>
    <form id="HcodeForm" method="POST" action="" enctype="multipart/form-data">
    @include('Pages.Admin-Hcode.addhcode')
    <button type="submit" formaction="{{route('toHcodeActions',['actionType' => 'Add'])}}">Add New Honorific Code</button>
    </form>
    <button type="button" onclick="closeAddBox()">Cancel</button>
    </div>
</body>
</html>