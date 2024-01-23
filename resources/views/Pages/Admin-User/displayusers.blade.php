@extends('Pages.tablelayout')
@section('title', 'Admin | Users')
@section('pagename','Users')
@section('updatecontent')



<form id="userForm" action="" method="POST">

    @csrf
    @method('post')
    <div class="text-right mb-3"> <!-- Added mb-3 class for margin-bottom -->
        <button id="update-btn" onclick="removeData()" formaction="{{route('toUserActions',['actionType' => 'Edit'])}}"
            hidden><i class="fa-solid fa-floppy-disk"></i> Update All Entries</button>
    </div>

    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Honorific Code</th>
                <th>Role</th>
                <th>Contact</th>
                <th>Email Address</th>
                <th>Password</th>
                <th>Status</th>
                <th class="no-sort">Actions</th>
            </tr>
        </thead>

        <tbody>

            <input hidden type="text" id="rowcount" name="rowcount" value="">
            @foreach($usersData as $userData)

            <tr id="{{ $userData->id ?? 'N/A'}}" value="{{ $userData->id ?? 'N/A'}}">

                <td> <label hidden>{{$userData -> id?? 'N/A'}}</label>

                    <input type="text" class="form-control" name="{{'id'.$rowcount}}" id="user-id"
                        value="{{$userData -> id?? 'N/A'}}" readonly>

                </td>
                <td> <label hidden>{{$userData -> FirstName?? 'N/A'}}</label>
                    <div class="form-group">
                        <input type="text" class="form-control" name="{{'fname'.$rowcount}}" id="user-fname"
                            value={{$userData -> FirstName?? 'N/A'}} readonly>
                    </div>
                </td>
                <td> <label hidden>{{$userData -> LastName?? 'N/A'}}</label>
                    <div class="form-group">
                        <input type="text" class="form-control" name="{{'lname'.$rowcount}}" id="user-lname"
                            value={{$userData -> LastName?? 'N/A'}} readonly>
                    </div>
                </td>
                <td>
                    <label hidden>{{$userData->honorific->CodeName?? 'N/A'}}</label>
                    <div class="form-group text-center">
                        <input type="text" class="form-control" id="user-hcode"
                            name="{{$userData->honorific->id?? 'N/A'}}"
                            value="{{$userData->honorific->CodeName?? 'N/A'}}" readonly>
                    </div>
                    <div class="form-group text-center">
                        <select class="form-control" name="{{'hCode'.$rowcount}}" id="hCodeOptions" hidden>
                            @foreach($hCodeNames as $hCodeName)
                            <option value="{{$hCodeName->CodeName?? 'N/A'}}" id="user-hcode2">{{$hCodeName->CodeName??
                                'N/A'}}</option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td> <label hidden>{{$userData -> roles->name?? 'N/A'}}</label>
                    <div class="form-group">
                        <input type="text" class="form-control" id="user-rname"
                            name="{{$userData -> roles -> id?? 'N/A'}}" value={{$userData -> roles->name?? 'N/A'}}
                        readonly>
                    </div>
                    <select class="form-control" name="{{'role'.$rowcount}}" id="roleOptions" hidden>
                        @foreach($roleNames as $roleName)
                        <option value="{{$roleName -> name?? 'N/A'}}" id="user-rname2">{{$roleName -> name}}</option>
                        @endforeach
                    </select>
                </td>
                <td> <label hidden>{{$userData -> contactNo?? 'N/A'}}</label>
                    <div class="form-group">
                        <input type="text" class="form-control" name="{{'contact'.$rowcount}}" id="user-contact"
                            value={{$userData -> contactNo?? 'N/A'}} readonly>
                    </div>
                </td>
                <td> <label hidden>{{$userData -> email?? 'N/A'}}</label>
                    <div class="form-group">
                        <input type="text" class="form-control" name="{{'email'.$rowcount}}" id="user-email"
                            value={{$userData -> email?? 'N/A'}} readonly>
                    </div>
                </td>
                <td> <label hidden>{{$userData -> password?? 'N/A'}}</label>
                    <div class="form-group">
                        <input type="text" class="form-control" name="{{'password'.$rowcount}}" id="user-password"
                            value={{$userData -> password?? 'N/A'}} readonly>
                    </div>
                </td>
                <td> <label hidden>{{$userData -> Status?? 'N/A'}}</label>
                    <div class="form-group">
                        <input type="text" class="form-control" name="{{'status'.$rowcount}}" id="user-status"
                            value={{$userData -> Status?? 'N/A'}} readonly>
                    </div>
                </td>
                <td>
                    <button class="form-control edit-btn" type="button" id="edit-btn"
                        onclick="userEditMode(event,{{$rowcount}})"><i class="fa-solid fa-pen-to-square"></i></button>
                    <button id="delete-btn" class="form-control delete-btn"
                        formaction="{{route('toUserActions',['actionType' => 'Delete'])}}" name="deletebtn"
                        value="{{ $userData->id ?? 'N/A'}}"><i class="fa-solid fa-trash-can"></i></button>
                    <button class="form-control cancel-btn" onclick="cancelUserEdit(event,{{$rowcount}})"
                        value="{{$userData -> id}}" id="cancel-btn" type="button" hidden><i
                            class="fa-solid fa-ban"></i></button>
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


@section('buttonText','New User')
@section('addBoxContent')
@section('addBoxType','showAddBox()')
@include('Pages.Admin-User.adduser')

@endsection