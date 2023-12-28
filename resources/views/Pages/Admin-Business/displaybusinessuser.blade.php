<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Business User</title>
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
        <button  id="update-btn" formaction="{{route('toBusinessUserActions',['actionType' => 'Edit'])}}" hidden>Update All Data</button>
    <table>
        <tr>
            <th>ID</th>
            <th>Business Name</th>
            <th>User Email</th>
            <th>Actions</th>
        </tr>
        @foreach($busers as $buser)
        <tr id = "{{$buser->id}}">
        <td><input class="column-data" type="text" name="{{'businessuserid'.$rowCount}}" value={{$buser->id}} readonly></td>
        <td><input class="column-data" type="text" name="{{$buser-> business-> businessName}}" value={{$buser-> business-> businessName}} readonly>
            <select class="column-data" name="{{'businessname'.$rowCount}}" id="businessOptions" hidden>
        @foreach($businesses as $business)
        <option value="{{$business -> businessName}}" id="business-name">{{$business -> businessName}} ({{$business->btype->businessTypeName}})</option>
        @endforeach
    </select></td>
    <td><input class="column-data" type="text" name="{{$buser-> user-> email}}" value={{$buser-> user-> email}} readonly>
        <select class="column-data" name="{{'useremail'.$rowCount}}" id="userOptions" hidden>
        @foreach($users as $user)
        <option value="{{$user -> email}}" id="user-email">{{$user -> email}} ({{$user->FirstName}} {{$user->LastName}})</option>
        @endforeach
    </select></td>
        <td>
            <button class="column-data" type="button" onclick="bUserEdit(event)">Edit</button><button class="column-data" type="button" hidden onclick="cancelbUserEdit(event)">Cancel Edit</button>
            <br><button name="deletebtn" class="column-data" value="{{$buser->id}}" formaction="{{route('toBusinessUserActions',['actionType' => 'Delete'])}}">Delete</button>
    
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
    <button type="button" onclick="showAddBox()">New Business User</button>
    <div id="add-box" hidden>
    <form id="addpackageForm" method="POST" action="" enctype="multipart/form-data">
    @include('Pages.Admin-Business.addbusinessuser')
    <button type="submit" formaction="{{route('toBusinessUserActions',['actionType' => 'Add'])}}">Add New Business User</button>
    </form>
    <button type="button" onclick="closeAddBox()">Cancel</button>
    </div>
</body>
</html>