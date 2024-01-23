@csrf
@method('post')

<div class="form-group">
    <label for="new-assignment-id">ID</label>
    <input type="text" class="form-control" name="new-assignment-id" value="{{ $newAssignmentID }}" readonly>
</div>

<div class="form-group">
    <label for="new-buser">Assignor (Business User)</label>
    <select class="form-control" name="new-buser" id="new-buser" onchange="handleSubscriptionUserChange(this,'Add','business')">
        @foreach($busers as $buser)
            <option value="{{ $buser->user->id }}" id="buseroptions">{{ $buser->user->email }} ({{ $buser->business->businessName }})</option>
        @endforeach
    </select>
    <input type="hidden" class="form-control" id="new-buser-code" name="new-buser-code" value="">
</div>

<div class="form-group">
    <label for="new-fuser">Assignee (Firm User)</label>
    <select class="form-control" name="new-fuser" id="new-fuser" onchange="handleSubscriptionUserChange(this,'Add','firm')">
        @foreach($firmusers as $firmuser)
            <option value="{{ $firmuser->user->id }}" id="firmuseroptions">{{ $firmuser->user->email??'N/A' }} {{ $firmuser->firm->firmName ?? 'N/A' }}</option>
        @endforeach
    </select>
    <input type="hidden" class="form-control" id="new-fuser-code" name="new-fuser-code" value="">
</div>

<div class="form-group">
    <label for="new-date-valid-from">Appointed Date Valid From</label>
    <input type="datetime-local" class="form-control" name="new-date-valid-from" id="new-date-valid-from" value="{{ $formattedDateTime }}">
</div>

<div class="form-group">
    <label for="new-date-valid-to">Appointed Date Valid To</label>
    <input type="datetime-local" class="form-control" name="new-date-valid-to" id="new-date-valid-to" value="{{ $formattedDateTime }}" readonly>
</div>

<div class="form-group">
    <label for="new-access-code">Allowed Access Code</label>
    <input type="text" class="form-control" name="new-access-code" id="new-access-code" value="" readonly>
</div>

<div class="form-group">
    <label for="new-status">Status</label>
    <select class="form-control" name="new-status" id="new-status">
        <option value="Active">Active</option>
        <option value="Completed">Completed</option>
        <option value="Rejected">Rejected</option>
        <option value="Pending">Pending</option>
    </select>
    <input type="hidden" class="form-control" id="new-duration" name="new-duration" value="">
</div>
<div class="form-group text-right">
        <button class="btn btn-success" formaction="{{route('toAssignmentsActions',['actionType' => 'Add'])}}" id="addUserBtn"><i class="fa-solid fa-user-plus"></i> Add New Assignment</button>
        <button type="button" class="btn btn-danger" onclick="closeAddBox()"><i class="fa-solid fa-ban"></i> Cancel</button>
        </div>