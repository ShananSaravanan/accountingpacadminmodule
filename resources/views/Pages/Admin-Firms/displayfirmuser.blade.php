@extends('Pages.tablelayout')
@section('title', 'Admin | Firm Users')
@section('pagename', 'Firm Users')
@section('updatecontent')
    <form id="userForm" action="" method="POST">
        @csrf
        @method('post')
        <div class="text-right mb-3">
            <button id="update-btn" onclick="removeData()" formaction="{{route('toFirmUserActions',['actionType' => 'Edit'])}}" hidden><i class="fa-solid fa-floppy-disk"></i> Update All Data</button>
        </div>

        <table id="example1" class="table table-bordered table-striped" width="100%">
            <thead>
                <tr>
                    <th>Firm User ID</th>
                    <th>Firm Name</th>
                    <th>User</th>
                    <th>MIA No</th>
                    <th>PC No</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <input hidden type="text" id="rowcount" name="rowcount" value="">
                @foreach($firmusers as $firmuser)
                    <tr id="{{ $firmuser->id }}">
                        <td>
                            <label hidden>{{ $firmuser->id ?? 'N/A' }}</label>
                            <input type="text" class="form-control" name="{{ 'firmuserid' . $rowcount }}" value="{{ $firmuser->id ?? 'N/A' }}" readonly>
                        </td>
                        <td>
                            <label hidden>{{ $firmuser->firm->firmName ?? 'No firm' }}</label>
                            <input type="text" class="form-control" name="{{ 'firmname' . $rowcount }}" value="{{ $firmuser->firm->firmName ?? 'No firm' }}" readonly>
                            <select class="form-control" name="{{ 'firmname' . $rowcount }}" id="firmOptions" hidden>
                                <option value="">No firm</option>
                                @foreach($firms as $firm)
                                    <option value="{{ $firm->id }}" id="firm-name">{{ $firm->firmName }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <label hidden>{{ $firmuser->user->email ?? 'N/A' }}</label>
                            <input type="text" class="form-control" name="{{ 'user' . $rowcount }}" value="{{ $firmuser->user->email ?? 'N/A' }} ({{ $firmuser->user->FirstName }} {{ $firmuser->user->LastName }})" readonly>
                            <select class="form-control" name="{{ 'user' . $rowcount }}" id="userOptions" hidden>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" id="package-name">{{ $user->email }} ({{ $user->FirstName }} {{ $user->LastName }})</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <label hidden>{{ $firmuser->MIA_NO ?? 'N/A' }}</label>
                            <input type="text" class="form-control" name="{{ 'miano' . $rowcount }}" value="{{ $firmuser->MIA_NO ?? 'N/A' }}" readonly>
                        </td>
                        <td>
                            <label hidden>{{ $firmuser->PC_NO ?? 'N/A' }}</label>
                            <input type="text" class="form-control" name="{{ 'pcno' . $rowcount }}" value="{{ $firmuser->PC_NO ?? 'N/A' }}" readonly>
                        </td>
                        <td>
                            <button class="form-control edit-btn" type="button" onclick="firmUserEdit(event,{{ $rowcount }})"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="form-control cancel-btn" type="button" onclick="cancelfirmUserEdit(event,{{ $rowcount }})" hidden><i class="fa-solid fa-ban"></i> </button>
                            <br>
                            <button class="form-control delete-btn" name="deletebtn" value="{{ $firmuser->id }}" formaction="{{ route('toFirmUserActions', ['actionType' => 'Delete']) }}"><i class="fa-solid fa-trash-can"></i></button>
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

@section('buttonText','New Firm User')
@section('addBoxContent')
@section('addBoxType','showAddBox()')
    @include('Pages.Admin-Firms.addfirmuser')

@endsection
