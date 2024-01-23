@extends('Pages.tablelayout')
@section('title', 'Admin | Subscriptions')
@section('pagename', 'Subscriptions')
@section('updatecontent')
<form id="userForm" action="" method="POST">
@csrf
    @method('post')
<div class="text-right mb-3">
            <button id="update-btn" onclick="removeData()" formaction="{{route('toSubscriptionActions',['actionType' => 'Edit'])}}" hidden><i class="fa-solid fa-floppy-disk"></i> Update All Data</button>
        </div>

        <table id="example1" class="table table-bordered table-striped" width="100%">
            <thead>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Package Name</th>
            <th>Package Code</th>
            <th>Date Valid From</th>
            <th>Date Valid To</th>
            <th>Transaction No</th>
            <th>Approved Bank Name</th>
            <th>Paid Amount</th>
            <th>Status</th>
            <th>Cancelled Date</th>
            <th>Actions</th>
        </tr>
</thead>

       <tbody>
        <input hidden type="text" id="rowcount" name="rowcount" value="">
        @foreach($subscriptions as $subscription)
        <tr id = "{{$subscription->id}}">
        <td>
           <label for="" hidden >{{$subscription->id ?? 'N/A'}}</label> 
        <input class="form-control" type="text" name="{{'subscriptionid'.$rowcount}}" value={{$subscription->id ?? 'N/A'}} readonly>
    </td>
        
        <td>
        <label for="" hidden >{{$subscription -> user -> email ?? 'N/A'}}</label> 
        <input type="text" class="form-control" id="subuserid" name="{{$subscription -> user -> id}}" value={{$subscription -> user -> email ?? 'N/A'}} readonly>
        <select class="form-control" name="{{'user'.$rowcount}}" id="{{'user'.$rowcount}}" onchange="handleUserChange(this,event)"hidden>
        @foreach($users as $user)
        <option value="{{$user -> id}}" id="useroption">{{$user -> email}}</option>
        @endforeach
        </select>
        </td>
        
        <td>
        <label for="" hidden >{{$subscription -> packageprice -> package -> name ?? 'N/A'}}</label> 
        <input type="text" class="form-control" id="subpackageid" name="{{$subscription -> packageprice -> id}}" value="{{$subscription -> packageprice -> package -> name}} for {{$subscription -> packageprice -> duration}} days" readonly>
        <select class="form-control" name="{{'package'.$rowcount}}" id="{{'packageOptions'.$rowcount}}" onchange=handlePackageChange(this,event) hidden>
        
        <option value="" id="useroption">Auto-Generated</option>
        
        </select>
        </td>

        <td>
        <label for="" hidden >{{$subscription -> packageprice -> package -> PackageCode ?? 'N/A'}}</label> 
        <input type="text" class="form-control" id="{{'code'.$rowcount}}" name="{{'code'.$rowcount}}" value={{$subscription -> packageprice -> package -> PackageCode ?? 'N/A'}} readonly>
        </td>

        <td>
        <label for="" hidden >{{$subscription->DateValidFrom ?? 'N/A'}}</label> 
        <input  type="text" class="form-control" name="{{ trim($subscription->DateValidFrom) }}"  id="" value="{{ trim($subscription->DateValidFrom)??'N/A' }}" readonly>
        <input  type="datetime-local" class="form-control" name="{{'DateValidFrom'.$rowcount}}"  id="{{'DateValidFrom'.$rowcount}}" value="{{ trim($subscription->DateValidFrom) }}" onchange=changeValidDateTimeTo({{$rowcount}}) hidden>
        </td>

        <td>
        <label for="" hidden >{{$subscription->DateValidTo ?? 'N/A'}}</label> 
        <input type="text" class="form-control" name="{{ trim($subscription->DateValidTo) }}"  id="" value="{{ trim($subscription->DateValidTo) ?? 'N/A' }}" readonly>
        <input type="datetime-local" class="form-control" name="{{'DateValidTo'.$rowcount}}"  id="{{'DateValidTo'.$rowcount}}" value="{{ trim($subscription->DateValidTo) }}"  hidden>
        </td>

        <td>
        <label for="" hidden >{{$subscription -> transaction -> transactionNo ?? 'N/A'}}</label> 
        <input type="text" class="form-control" id="subtransactionid" name="{{$subscription -> transaction -> id}}" value={{$subscription -> transaction -> transactionNo ?? 'N/A'}} readonly>
        <select class="form-control" name="{{'transactionOptions'.$rowcount}}" id="{{'transactionOptions'.$rowcount}}" onchange=handleTransactionChange(this,event) hidden>
        @foreach($transactions as $transaction)
        <option value="{{$transaction -> id}}" id="transactionOption">{{$transaction -> transactionNo}}</option>
        @endforeach
        </select>
        </td>

        
        <td>
        <label for="" hidden >{{$subscription -> transaction -> BankID ?? 'N/A'}}</label> 
        <input type="text" class="form-control" id="{{'bank'.$rowcount}}" name="{{'bank'.$rowcount}}" value={{$subscription -> transaction -> BankID ?? 'N/A'}} readonly>
        </td>

        <td>
        <label for="" hidden >{{$subscription -> transaction -> amount ?? 'N/A'}}</label> 
        <input type="text" class="form-control" id="{{'amount'.$rowcount}}" name="{{'amount'.$rowcount}}" value={{$subscription -> transaction -> amount ?? 'N/A'}} readonly>
        </td>

        <td>
        <label for="" hidden >{{$subscription -> status ?? 'N/A'}}</label> 
        <input type="text" class="form-control" id="status" name="{{$subscription -> status}}" value={{$subscription -> status ?? 'N/A'}} readonly>
        <select class="form-control" name="{{'status'.$rowcount}}" id="{{'status'.$rowcount}}" onchange=handleStatusChange(this,event) hidden>
        <option  value="Active" id="">Active</option>
        <option  value="Inactive" id="">Inactive</option>
        <option  value="Cancelled" id="">Cancelled</option>
        </select>
        </td>

        <td>
        <input  type="text" class="form-control" name="{{ trim($subscription->cancelledDate) }}"  id="" value="{{ trim($subscription->cancelledDate?? 'UNCANCELLED') }}" readonly>
        <input  type="datetime-local" class="form-control" name="{{'cancelledDate'.$rowcount}}"  id="{{'cancelledDate'.$rowcount}}" value="{{ trim($subscription->cancelledDate)}}" hidden>
        </td>
        

        
        <td>
            <button class="form-control edit-btn" type="button" onclick="subscriptionEdit(event,{{$rowcount}})"><i class="fa-solid fa-pen-to-square"></i></button>
            <button class="form-control cancel-btn" type="button" hidden onclick="cancelsubscriptionEdit(event,{{$rowcount}})"><i class="fa-solid fa-ban"></i></button>
            <br><button name="deletebtn" class="form-control delete-btn" value="{{$subscription->id}}" formaction="{{route('toSubscriptionActions',['actionType' => 'Delete'])}}"><i class="fa-solid fa-trash-can"></i></button>
            <input class="form-control" type="hidden" id="{{'duration'.$rowcount}}" name="{{'duration'.$rowcount}}}}" value="" >
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

    @section('buttonText','New Subscription')
@section('addBoxContent')
@section('addBoxType','showAddBoxForAjax("new-user-sub")')
    @include('Pages.Admin-Subscription.addsubscription')
@endsection