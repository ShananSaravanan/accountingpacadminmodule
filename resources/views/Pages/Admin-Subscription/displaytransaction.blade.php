@extends('Pages.tablelayout')
@section('title', 'Admin | Transactions')
@section('pagename','Transactions')
@section('updatecontent')
    <form id="userForm" action="" method="POST">
        @csrf
        @method('post')
        <div class="text-right mb-3">
            <button id="update-btn" onclick="removeData()" formaction="{{ route('toTransactionActions', ['actionType' => 'Edit']) }}" hidden>
                <i class="fa-solid fa-floppy-disk"></i> Update All Data
            </button>
        </div>

        <table id="example1" class="table table-bordered table-striped" width="100%" >
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Transaction No</th>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>FPX ID</th>
                    <th>FPX Check Sum</th>
                    <th>Bank ID</th>
                    <th>Payment Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <input hidden type="text" id="rowcount" name="rowcount" value="">
                @foreach($transactions as $transaction)
                    <tr id="{{ $transaction->id }}">
                        <td>
                            <label hidden>{{ $transaction->id ?? 'N/A' }}</label>
                            <input class="form-control" type="text" name="{{ 'transactionid' . $rowcount }}" value="{{ $transaction->id ?? 'N/A' }}" readonly>
                        </td>
                        <td>
                            <label hidden>{{ $transaction->transactionNo ?? 'N/A' }}</label>
                            <input class="form-control" type="text" name="{{ 'transactionno' . $rowcount }}" value="{{ $transaction->transactionNo ?? 'N/A' }}" readonly>
                        </td>
                        <td>
                            <label hidden>{{ $transaction->name ?? 'N/A' }}</label>
                            <input class="form-control" type="text" name="{{ 'transactionname' . $rowcount }}" value="{{ $transaction->name ?? 'N/A' }}" readonly>
                        </td>
                        <td>
                            <label hidden>{{ $transaction->amount ?? 'N/A' }}</label>
                            <input class="form-control" type="text" name="{{ 'amount' . $rowcount }}" value="{{ $transaction->amount ?? 'N/A' }}" readonly>
                        </td>
                        <td>
                            <label hidden>{{ $transaction->FPX_ID ?? 'N/A' }}</label>
                            <input class="form-control" type="text" name="{{ 'fpxid' . $rowcount }}" value="{{ $transaction->FPX_ID ?? 'N/A' }}" readonly>
                        </td>
                        <td>
                            <label hidden>{{ $transaction->FPX_CheckSum ?? 'N/A' }}</label>
                            <input class="form-control" type="text" name="{{ 'fpxchecksum' . $rowcount }}" value="{{ $transaction->FPX_CheckSum ?? 'N/A' }}" readonly>
                        </td>
                        <td>
                            <label hidden>{{ $transaction->BankID ?? 'N/A' }}</label>
                            <input class="form-control" type="text" name="{{ 'bankid' . $rowcount }}" value="{{ $transaction->BankID ?? 'N/A' }}" readonly>
                        </td>
                        <td>
                            <label hidden>{{ $transaction->paymentDateTime ?? 'N/A' }}</label>
                            <input class="form-control" type="text" name="{{ 'paymenttime' . $rowcount }}" value="{{ $transaction->paymentDateTime ?? 'N/A' }}" readonly>
                        </td>
                        <td>
                            <label hidden>{{ $transaction->status ?? 'N/A' }}</label>
                            <input class="form-control" type="text" name="{{ 'status' . $rowcount }}" value="{{ $transaction->status ?? 'N/A' }}" readonly>
                        </td>
                        <td>
                            <button class="form-control edit-btn" type="button" onclick="transactionEdit(event,{{ $rowcount }})"><i class="fa-solid fa-pen-to-square"></i> </button>
                            <button class="form-control cancel-btn" type="button" onclick="canceltransactionEdit(event,{{ $rowcount }})" hidden><i class="fa-solid fa-ban"></i>  </button>
                            <br>
                            <button class="form-control delete-btn" name="deletebtn" value="{{ $transaction->id }}" formaction="{{ route('toTransactionActions', ['actionType' => 'Delete']) }}"><i class="fa-solid fa-trash-can"></i> </button>
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

@section('buttonText','New Transaction')
@section('addBoxContent')
    @section('addBoxType','showAddBox()')
    @include('Pages.Admin-Subscription.addtransaction')
    
@endsection
