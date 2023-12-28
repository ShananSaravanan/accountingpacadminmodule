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
        <button  id="update-btn" formaction="{{route('toAddressTypeActions',['actionType' => 'Edit'])}}" hidden>Update All Data</button>
    <table>
        <tr>
            <th>ID</th>
            <th>Address Type Name</th>
            <th>Actions</th>
        </tr>
        @foreach($AddressTypes as $addressType)
        <tr id = "{{$addressType->id}}">
        <td><input class="column-data" type="text" name="{{'addressTypeID'.$rowCount}}" value={{$addressType->id}} readonly></td>
        <td><input class="column-data" type="text" name="{{'addressTypeName'.$rowCount}}" value={{$addressType->name}} readonly></td>
        <td>
            <button class="column-data" type="button" onclick="addressTypeEdit(event)">Edit</button><button class="column-data" type="button" hidden onclick="canceladdressTypeEdit(event)">Cancel Edit</button>
            <br><button name="deletebtn" class="column-data" value="{{$addressType->id}}" formaction="{{route('toAddressTypeActions',['actionType' => 'Delete'])}}">Delete</button>
    
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
    <button type="button" onclick="showAddBox()">New Address Type</button>
    <div id="add-box" hidden>
    <form id="addressTypeForm" method="POST" action="" enctype="multipart/form-data">
    @include('Pages.Admin-Address.addaddresstypes')
    <button type="submit" formaction="{{route('toAddressTypeActions',['actionType' => 'Add'])}}">Add New Address Type</button>
    </form>
    <button type="button" onclick="closeAddBox()">Cancel</button>
    </div>
</body>
</html>