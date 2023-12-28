<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Package Base</title>
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
        <button  id="update-btn" formaction="{{route('toPackageBaseActions',['actionType' => 'Edit'])}}" hidden>Update All Data</button>
    <table>
        <tr>
            <th>ID</th>
            <th>Package Name</th>
            <th>Package Duration (in months)</th>
            <th>Base Price (in RM)</th>
            <th>Actions</th>
        </tr>
        @foreach($basepackages as $basepackage)
        <tr id = "{{$basepackage->id}}">
        <td><input class="column-data" type="text" name="{{'packagebaseid'.$rowCount}}" value={{$basepackage->id}} readonly></td>
        <td><input class="column-data" type="text" name="{{$basepackage->package->name}}" value={{$basepackage->package->name}} readonly>
        <select class="column-data" name="{{'packagename'.$rowCount}}" id="packageOptions" hidden>
        @foreach($packageNames as $packageName)
        <option value="{{$packageName -> name}}" id="package-name">{{$packageName -> name}}</option>
        @endforeach
    </select></td>
        <td><input class="column-data" type="text" name="{{'duration'.$rowCount}}" value={{$basepackage->duration}} readonly></td>
        <td><input class="column-data" type="text" name="{{'baseprice'.$rowCount}}" value={{$basepackage->baseprice}} readonly></td>
        <td>
            <button class="column-data" type="button" onclick="packageBaseEdit(event)">Edit</button><button class="column-data" type="button" hidden onclick="cancelPackageBaseEdit(event)">Cancel Edit</button>
            <br><button name="deletebtn" class="column-data" value="{{$basepackage->id}}" formaction="{{route('toPackageBaseActions',['actionType' => 'Delete'])}}">Delete</button>
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
    <button type="button" onclick="showAddBox()">New Package Base</button>
    <div id="add-box" hidden>
    <form id="addpackagebaseForm" method="POST" action="" enctype="multipart/form-data">
    @include('Pages.Admin-Package.addpackagebase')
    <button type="submit" formaction="{{route('toPackageBaseActions',['actionType' => 'Add'])}}">Add New Package Base</button>
    </form>
    <button type="button" onclick="closeAddBox()">Cancel</button>
    </div>
</body>
</html>