@extends('Pages.tablelayout')
@section('title', 'Admin | Packages')
@section('pagename','Packages')
@section('updatecontent')
    <form id="userForm" action="" method="POST">
        @csrf
        @method('post')
        <div class="text-right mb-3">
            <button id="update-btn" onclick="removeData()" formaction="{{route('toPackageActions',['actionType' => 'Edit'])}}" hidden><i class="fa-solid fa-floppy-disk"></i> Update All Data</button>
        </div>

        <table id="example1" class="table table-bordered table-striped" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Package Code</th>
                    <th>Package Name</th>
                    <th>User Limit</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <input hidden type="text" id="rowcount" name="rowcount" value="">
                @foreach($packages as $package)
                    <tr id="{{ $package->id }}">
                        <td>
                            <label hidden>{{ $package->id ?? 'N/A' }}</label>
                            <input type="text" class="form-control" name="{{ 'packageid' . $rowcount }}" value="{{ $package->id ?? 'N/A' }}" readonly>
                        </td>
                        <td>
                            <label hidden>{{ $package->PackageCode ?? 'N/A' }}</label>
                            <input type="text" class="form-control" name="{{ 'packagecode' . $rowcount }}" value="{{ $package->PackageCode ?? 'N/A' }}" readonly>
                        </td>
                        <td>
                            <label hidden>{{ $package->name ?? 'N/A' }}</label>
                            <input type="text" class="form-control" name="{{ 'packagename' . $rowcount }}" value="{{ $package->name ?? 'N/A' }}" readonly>
                        </td>
                        <td>
                            <label hidden>{{ $package->userlimit ?? 'N/A' }}</label>
                            <input type="number" class="form-control" name="{{ 'userlimit' . $rowcount }}" value="{{ $package->userlimit ?? 'N/A' }}" readonly>
                        </td>
                        <td>
                            <button class="form-control edit-btn" type="button" onclick="packageEdit(event,{{ $rowcount }})"><i class="fa-solid fa-pen-to-square"></i> </button>
                            <button class="form-control cancel-btn" type="button" onclick="cancelPackageEdit(event,{{ $rowcount }})" hidden><i class="fa-solid fa-ban"></i>  </button>
                            <br>
                            <button class="form-control delete-btn" name="deletebtn" value="{{ $package->id }}" formaction="{{ route('toPackageActions', ['actionType' => 'Delete']) }}"><i class="fa-solid fa-trash-can"></i> </button>
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

@section('buttonText','New Package')
@section('addBoxContent')
@section('addBoxType','showAddBox()')
    @include('Pages.Admin-Package.addpackage')

@endsection
