<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Package</title>
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
        <button  id="update-btn" formaction="{{route('toPackageActions',['actionType' => 'Edit'])}}" hidden>Update All Data</button>
    <table>
        <tr>
            <th>ID</th>
            <th>Package Code</th>
            <th>Package Name</th>
            <th>User Limit</th>
            <th>Actions</th>
        </tr>
        @foreach($packages as $package)
        <tr id = "{{$package->id}}">
        <td><input class="column-data" type="text" name="{{'packageid'.$rowCount}}" value={{$package->id}} readonly></td>
        <td><input class="column-data" type="text" name="{{'packagecode'.$rowCount}}" value={{$package->PackageCode}} readonly></td>
        <td><input class="column-data" type="text" name="{{'packagename'.$rowCount}}" value={{$package->name}} readonly></td>
        <td><input class="column-data" type="number" name="{{'userlimit'.$rowCount}}" value={{$package->userlimit}} readonly></td>
        <td>
            <button class="column-data" type="button" onclick="packageEdit(event)">Edit</button><button class="column-data" type="button" hidden onclick="cancelPackageEdit(event)">Cancel Edit</button>
            <br><button name="deletebtn" class="column-data" value="{{$package->id}}" formaction="{{route('toPackageActions',['actionType' => 'Delete'])}}">Delete</button>
    
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
    <button type="button" onclick="showAddBox()">New Package</button>
    <div id="add-box" hidden>
    <form id="addpackageForm" method="POST" action="" enctype="multipart/form-data">
    @include('Pages.Admin-Package.addpackage')
    <button type="submit" formaction="{{route('toPackageActions',['actionType' => 'Add'])}}">Add New Package</button>
    </form>
    <button type="button" onclick="closeAddBox()">Cancel</button>
    </div>
</body>
</html>