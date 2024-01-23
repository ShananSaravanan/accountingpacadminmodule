@extends('Pages.tablelayout')
@section('title', 'Admin | Assignments')
@section('pagename','Assignments')
@section('updatecontent')
<form id="userForm" action="" method="POST">
@csrf
    @method('post')
    <div class="text-right mb-3"> <!-- Added mb-3 class for margin-bottom -->
        <button id="update-btn" onclick="removeData()" formaction="{{route('toAssignmentsActions',['actionType' => 'Edit'])}}"
            hidden><i class="fa-solid fa-floppy-disk"></i> Update All Entries</button>
    </div>
    <table id="example1" class="table table-bordered table-striped" width="100%">
        <thead>
        <tr>
            <th>ID</th>
            <th>Assignor(Business User) </th>
            <th>Assignee(Firm User)</th>
            <th>Appointed Date Valid From</th>
            <th>Appointed Date Valid To</th>
            <th>Allowed Access Code</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
        <tbody>
        <input hidden type="text" id="rowcount" name="rowcount" value="">
        @foreach($assignments as $assignment)
        <tr id = "{{$assignment->id}}">
        
        <td> <label hidden>{{$assignment->id?? 'N/A'}}</label>  
        <input class="form-control" type="text" name="{{'assignmentid'.$rowcount}}" value="{{$assignment->id?? 'N/A'}}" readonly>
    </td>
        
        <td>
        <label hidden>{{$assignment -> businessuser->user->id?? 'N/A'}}</label>  
        <input type="text" class="form-control" id="{{$assignment -> businessuser->user->id?? 'N/A'}}" name="{{$assignment -> businessuser->user-> id}}" value="{{$assignment -> businessuser -> user -> email}} {{$assignment -> businessuser -> business -> businessName}}" readonly>
        <select class="form-control" name="{{'buser'.$rowcount}}" id="{{'user'.$rowcount}}" onchange="handleSubscriptionUserChange(this,event,'business')" hidden>
        @foreach($busers as $buser)
        <option value="{{$buser ->user-> id}}" id="buseroptions">{{$buser -> user -> email}} ({{$buser -> business -> businessName}})</option>
        @endforeach
        </select>
        <input class="form-control" type="hidden" id="{{'businessusercode'.$rowcount}}" name="{{'businessusercode'.$rowcount}}}}" value="" >
        </td>

        <td>
        <label hidden>{{$assignment -> firmuser->user-> id?? 'N/A'}}</label>  
        <input type="text" class="form-control" id="{{$assignment -> firmuser->user-> id?? 'N/A'}}" name="{{$assignment -> firmuser->user-> id}}" value="{{$assignment -> firmuser -> user -> email}} {{$assignment -> firmuser -> firm -> firmName}}" readonly>
        <select class="form-control" name="{{'firmuser'.$rowcount}}" id="{{'user'.$rowcount}}" onchange="handleSubscriptionUserChange(this,event,'firm')" hidden>
        @foreach($firmusers as $firmuser)
        <option value="{{$firmuser ->user-> id}}" id="firmuseroptions">{{$firmuser -> user -> email??'N/A'}} {{$firmuser -> firm -> firmName??'N/A'}}</option>
        @endforeach
        </select>
        <input class="form-control" type="hidden" id="{{'firmusercode'.$rowcount}}" name="{{'firmusercode'.$rowcount}}" value="" >
        </td>

        <td>
        <label hidden>{{$assignment->appointedDateValidFrom?? 'N/A'}}</label>  
        <input  type="text" class="form-control" name="{{ trim($assignment->appointedDateValidFrom?? 'N/A') }}"  id="" value="{{ trim($assignment->appointedDateValidFrom) }}" readonly>
        <input  type="datetime-local" class="form-control" name="{{'DateValidFrom'.$rowcount}}"  id="{{'DateValidFrom'.$rowcount}}" value="{{ trim($assignment->appointedDateValidFrom) }}" onchange="changeValidDateTimeTo({{$rowcount}})" hidden>
        </td>

        <td>
        <label hidden>{{$assignment->appointedDateValidTo?? 'N/A'}}</label> 
        <input  type="text" class="form-control" name="{{ trim($assignment->appointedDateValidTo?? 'N/A') }}"  id="" value="{{ trim($assignment->appointedDateValidTo) }}" readonly>
        <input  type="datetime-local" class="form-control" name="{{'DateValidTo'.$rowcount}}"  id="{{'DateValidTo'.$rowcount}}" value="{{ trim($assignment->appointedDateValidTo) }}"  hidden>
        </td>

        <td>
        <label hidden>{{$assignment->allowedAccessCode?? 'N/A'}}</label> 
        <input  type="text" class="form-control" name="{{'accesscode'.$rowcount}}"  id="" value="{{ trim($assignment->allowedAccessCode?? 'N/A') }}" readonly>
        </td>
        
        <td>
        <label hidden>{{$assignment ->Status?? 'N/A'}}</label> 
        <input type="text" class="form-control" id="status" name="{{$assignment ->Status}}" value="{{$assignment ->Status?? 'N/A'}}" readonly>
        <select class="form-control" name="{{'status'.$rowcount}}" id="{{'status'.$rowcount}}"  hidden>
        <option  value="Active" id="">Active</option>
        <option  value="Completed" id="">Completed</option>
        <option  value="Rejected" id="">Rejected</option>
        <option  value="Pending" id="">Pending</option>
        </select>
        </td>
        
        <td>
            <button class="form-control edit-btn" type="button" onclick="AssignmentEdit(event,{{$rowcount}})"><i class="fa-solid fa-pen-to-square"></i></button>
            <button class="form-control cancel-btn" type="button" hidden onclick="cancelAssignmentEdit(event,{{$rowcount}})"><i
                            class="fa-solid fa-ban"></i></button>
            <br><button name="deletebtn" class="form-control delete-btn" value="{{$assignment->id}}" formaction="{{route('toAssignmentsActions',['actionType' => 'Delete'])}}"><i class="fa-solid fa-trash-can"></i></button>
        </td>
        <input class="form-control" type="hidden" id="{{'duration'.$rowcount}}" name="{{'duration'.$rowcount}}" value="" >
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

@section('buttonText','New Assignment')
@section('addBoxContent')
@section('addBoxType','showAddBoxForAjax("new-buser")')
    @include('Pages.Admin-Assignment.addassignments')
@endsection                                                                          