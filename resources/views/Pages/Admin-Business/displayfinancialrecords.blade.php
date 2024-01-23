@extends('Pages.tablelayout')
@section('title', 'Admin | Financial Records')
@section('pagename', 'Financial Records')
@section('updatecontent')
    <form id="frecordform" action="" method="POST">
        @csrf
        @method('post')
        <div class="text-right mb-3">
        <button id="update-btn" onclick="removeBusinessUserData()" formaction="{{route('toFinancialRecordsActions',['actionType' => 'Edit'])}}" hidden><i class="fa-solid fa-floppy-disk"></i> Update All Data</button>
    </div>
    <table id="example1" class="table table-bordered table-striped" width="100%">
        <thead>
        <th>ID</th>
    <th>Business Name</th>
    <th>Record Category</th>
    <th>Amount(in RM)</th>
    <th>Description</th>
    <th>Time of Record</th>
    <th>Actions</th>
    </thead>
<tbody>
        <input hidden type="text" id="rowcount" name="rowcount" value="">
        @foreach($frecords as $frecord)
        <tr id="{{ $frecord->id }}">
            <td>
                <label hidden>{{ $frecord->id ?? 'N/A' }}</label>
                <input type="text" class="form-control" name="{{'frecordid'.$rowcount}}"  id="frecord-id" value="{{ $frecord -> id ?? 'N/A' }}" readonly>
            </td>
            <td>
                <label hidden>{{ $frecord->business->businessName ?? 'N/A' }}</label>
                <input type="text" class="form-control" id="frecordbusinessid" name="{{ $frecord -> business -> businessName ?? 'N/A' }}" value="{{ $frecord -> business -> businessName ?? 'N/A' }}" readonly>
                <select class="form-control" name="{{'businessname'.$rowcount}}" id="businessOptions" hidden>
                    @foreach($businesses as $business)
                        <option value="{{ $business -> businessName }}" id="businessname">{{ $business -> businessName }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <label hidden>{{ $frecord->recordCategory ?? 'N/A' }}</label>
                <input type="text" class="form-control" name="{{ $frecord -> recordCategory ?? 'N/A' }}"  id="recordcategory" value="{{ $frecord -> recordCategory ?? 'N/A' }}" readonly>
                <select class="form-control" name="{{'recordcategory'.$rowcount}}" id="categoryOptions" hidden>
                    <option value="Income" id="">Income</option>
                    <option value="Expense" id="">Expense</option>
                </select>
            </td>
            <td>
                <label hidden>{{ number_format($frecord->amount, 2) ?? 'N/A' }}</label>
                <input type="text" class="form-control" name="{{ 'amount' . $rowcount }}" id="amount" value="{{ number_format($frecord->amount, 2) ?? 'N/A' }}" readonly pattern="\d+(\.\d{1,2})?" oninput="validateAmount(this)">
            </td>
            <td>
                <label hidden>{{ $frecord->description ?? 'N/A' }}</label>
                <input type="text" class="form-control" name="{{'description'.$rowcount}}"  id="description" value="{{ $frecord -> description ?? 'N/A' }}" readonly>
            </td>
            <td>
                <label hidden>{{ trim($frecord->recordedtime) ?? 'N/A' }}</label>
                <input type="text" class="form-control" name="{{trim($frecord->recordedtime)}}" id="recordtime" value="{{ trim($frecord->recordedtime) ?? 'N/A' }}" readonly>
                <input type="datetime-local" class="form-control" name="{{'recordtime'.$rowcount}}" id="recordtime" value="{{ trim($frecord->recordedtime) ?? 'N/A' }}" hidden>
            </td>
            <td>
                <button class="form-control edit-btn" type="button" id="edit-btn" onclick="financialRecordEdit(event,{{$rowcount}})"><i class="fa-solid fa-pen-to-square"></i></button>
                <button class="form-control delete-btn" formaction="{{route('toFinancialRecordsActions',['actionType' => 'Delete'])}}" name="deletebtn" value="{{ $frecord -> id }}"><i class="fa-solid fa-trash-can"></i></button>
                <button class="form-control cancel-btn" onclick="cancelfinancialRecordEdit(event,{{$rowcount}})" value="{{ $frecord -> id }}" id="cancel-btn" type="button" hidden><i class="fa-solid fa-ban"></i></button>
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

@section('buttonText','New Financial Record')
@section('addBoxContent')
@section('addBoxType','showAddBox()')
    @include('Pages.Admin-Business.addfinancialrecords')
@endsection
