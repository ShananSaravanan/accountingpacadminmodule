@extends('Pages.tablelayout')
@section('title', 'Admin | Purchasable Packages')
@section('pagename','Purchasable Packages')
@section('updatecontent')
    <form id="userForm" action="" method="POST">
        @csrf
        @method('post')
        <div class="text-right mb-3">
            <button id="update-btn" onclick="removeData()" formaction="{{route('toPackageBaseActions',['actionType' => 'Edit'])}}" hidden><i class="fa-solid fa-floppy-disk"></i> Update All Data</button>
            <!-- <button type="button" onclick="exportToExcel()"><i class="fa-solid fa-file-excel"></i> Export to Excel</button> -->
        </div>

        <table id="example1" class="table table-bordered table-striped" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Package Name</th>
                    <th>Package Duration (in months)</th>
                    <th>Base Price (in RM)</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <input hidden type="text" id="rowcount" name="rowcount" value="">
                @foreach($basepackages as $basepackage)
                    <tr id="{{ $basepackage->id }}">
                        <td>
                            <label hidden>{{ $basepackage->id ?? 'N/A' }}</label>
                            <input class="form-control" type="text" name="{{ 'packagebaseid' . $rowcount }}" value="{{ $basepackage->id ?? 'N/A' }}" readonly>
                        </td>
                        <td>
                            <label hidden>{{ $basepackage->package->name ?? 'N/A' }}</label>
                            <input class="form-control" type="text" name="{{ 'packagename' . $rowcount }}" value="{{ $basepackage->package->name ?? 'N/A' }}" readonly>
                            <select class="form-control" name="{{'firmOwner'.$rowcount}}" id="firmuserTypes" hidden>
                @foreach($packageNames as $packageName)
                <option value="{{$packageName -> name}}">{{$packageName -> name}}</option>
                @endforeach
            </select>
                        </td>
                        <td>
                            <label hidden>{{ $basepackage->duration ?? 'N/A' }}</label>
                            <input class="form-control" type="text" name="{{ 'duration' . $rowcount }}" value="{{ $basepackage->duration ?? 'N/A' }}" readonly>
                        </td>
                        <td>
                            <label hidden>{{ number_format($basepackage->baseprice, 2) ?? 'N/A' }}</label>
                            <input class="form-control" type="text" name="{{ 'baseprice' . $rowcount }}" value="{{ number_format($basepackage->baseprice, 2) ?? 'N/A' }}" readonly pattern="\d+(\.\d{1,2})?" oninput="validateAmount(this)">
                        </td>
                        <td>
                            <button class="form-control edit-btn" type="button" onclick="BaseEdit(event,{{ $rowcount }})"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="form-control cancel-btn" type="button" onclick="cancelPackageBaseEdit(event,{{ $rowcount }})" hidden><i class="fa-solid fa-ban"></i></button>
                            <button name="deletebtn" class="form-control delete-btn" value="{{ $basepackage->id }}" formaction="{{ route('toPackageBaseActions', ['actionType' => 'Delete']) }}"><i class="fa-solid fa-trash-can"></i></button>
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

@section('buttonText','New Package Price')
@section('addBoxContent')
@section('addBoxType','showAddBox()')
    @include('Pages.Admin-Package.addpackagebase')
  
@endsection
