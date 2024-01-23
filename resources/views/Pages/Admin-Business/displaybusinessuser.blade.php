@extends('Pages.tablelayout')
@section('title', 'Admin | Business Users')
@section('pagename','Business Users')
@section('updatecontent')

<form id="userForm" action="" method="POST">
    @csrf
    @method('post')
    <div class="text-right mb-3">
        <button id="update-btn" onclick="removeBusinessUserData()" formaction="{{route('toBusinessUserActions',['actionType' => 'Edit'])}}" hidden><i class="fa-solid fa-floppy-disk"></i> Update All Data</button>
    </div>

    <table id="example1" class="table table-bordered table-striped" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Business Name</th>
                <th>User Email</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <input hidden type="text" id="rowcount" name="rowcount" value="">
            @foreach($busers as $buser)
            <tr id="{{ $buser->id }}">
                <td>
                    <label hidden>{{ $buser->id ?? 'N/A' }}</label>
                    <input type="text" class="form-control" name="{{ 'businessuserid' . $rowcount }}" value="{{ $buser->id ?? 'N/A' }}" readonly>
                </td>
                <td>
                    <label hidden>{{ $buser->business->businessName ?? 'N/A' }}</label>
                    <input type="text" class="form-control" name="{{ 'businessname' . $rowcount }}" value="{{ $buser->business->businessName ?? 'N/A' }}" readonly>
                    <select class="form-control" name="{{ 'businessname' . $rowcount }}" id="businessOptions" hidden>
                        @foreach($businesses as $business)
                            <option value="{{ $business->businessName }}" id="business-name">{{ $business->businessName }} ({{ $business->btype->businessTypeName }})</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <label hidden>{{ $buser->user->email ?? 'N/A' }}</label>
                    <input type="text" class="form-control" name="{{ 'useremail' . $rowcount }}" value="{{ $buser->user->email ?? 'N/A' }}" readonly>
                    <select class="form-control" name="{{ 'useremail' . $rowcount }}" id="userOptions" hidden>
                        @foreach($users as $user)
                            <option value="{{ $user->email }}" id="user-email">{{ $user->email }} ({{ $user->FirstName }} {{ $user->LastName }})</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <button class="form-control edit-btn" type="button" onclick="bUserEdit(event,{{ $rowcount }})"><i class="fa-solid fa-pen-to-square"></i></button>
                    <button class="form-control cancel-btn" type="button" onclick="cancelbUserEdit(event,{{ $rowcount }})" hidden><i
                            class="fa-solid fa-ban"></i></button>
                    <br>
                    <button class="form-control delete-btn" name="deletebtn" value="{{ $buser->id }}" formaction="{{route('toBusinessUserActions',['actionType' => 'Delete'])}}"><i class="fa-solid fa-trash-can"></i></button>
                </td>
            </tr>
            @php
            $rowcount++;
            @endphp
            @endforeach
        </tbody>
    </table>
</form>
<script>
    $(document).ready(function () {

        $('.edit-btn').each(function () {

            // Your logic to add classes or perform other actions based on rowcount and userid
            $(this).addClass('btn btn-outline-primary btn-block');
        });
        $('.delete-btn').each(function () {

            // Your logic to add classes or perform other actions based on rowcount and userid
            $(this).addClass('btn btn-outline-danger btn-block btn-sm');
        });
        $('.cancel-btn').each(function () {

            // Your logic to add classes or perform other actions based on rowcount and userid
            $(this).addClass('btn btn-outline-danger btn-block btn-sm');
        });
        $('#update-btn').each(function () {

            // Your logic to add classes or perform other actions based on rowcount and userid
            $(this).addClass('btn btn-success btn-block');
        });


    });
    document.addEventListener('DOMContentLoaded', function () {
        var userForm = document.getElementById('userForm');

        // Initialize DataTables
        var example1Table = $('#example1').DataTable({
            scrollX: true,
            scrollY: 400,
            // Other DataTable options as needed
        });

        // Your other JavaScript code...

        // Example: Submitting form with DataTables

    });
</script>
@endsection

@section('buttonText','New Business User')
@section('addBoxContent')
@section('addBoxType','showAddBox()')
    @include('Pages.Admin-Business.addbusinessuser')
@endsection
