<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Roles</title>
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
        <button  id="update-btn" formaction="{{route('toRoleActions',['actionType' => 'Edit'])}}" hidden>Update All Data</button>
    <table>
        <tr>
            <th>ID</th>
            <th>Role Name</th>
            <th>Actions</th>
        </tr>
        @foreach($roles as $role)
        <tr id = "{{$role->id}}">
        <td><input class="column-data" type="text" name="{{'roleid'.$rowCount}}" value={{$role->id}} readonly></td>
        <td><input class="column-data" type="text" name="{{'rolename'.$rowCount}}" value={{$role->name}} readonly></td>
        <td>
            <button class="column-data" type="button" onclick="roleEdit(event)">Edit</button><button class="column-data" type="button" hidden onclick="cancelRoleEdit(event)">Cancel Edit</button>
            <br><button name="deletebtn" class="column-data" value="{{$role->id}}" formaction="{{route('toRoleActions',['actionType' => 'Delete'])}}">Delete</button>
    
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
    <button type="button" onclick="showAddBox()">New Role</button>
    <div id="add-box" hidden>
    <form id="roleForm" method="POST" action="" enctype="multipart/form-data">
    @include('Pages.Admin-Roles.addroles')
    <button type="submit" formaction="{{route('toRoleActions',['actionType' => 'Add'])}}">Add New Role</button>
</form>
    <button type="button" onclick="closeAddBox()">Cancel</button>
    </div>
</body>
</html>