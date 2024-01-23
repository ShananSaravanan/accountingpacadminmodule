@csrf
@method('post')

<div class="form-group">
    <label for="new-buser-id">ID</label>
    <input type="text" class="form-control" name="new-buser-id" value="{{ $newbUserID }}" readonly>
</div>

<div class="form-group">
    <label for="businessOptions">Business Name</label>
    <select class="form-control" name="selectedBusiness" id="businessOptions">
        @foreach($businesses as $business)
            <option value="{{ $business->businessName }}" id="business-name">{{ $business->businessName }} ({{ $business->btype->businessTypeName }})</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="userOptions">User Email</label>
    <select class="form-control" name="selectedUser" id="userOptions">
        @foreach($users as $user)
            <option value="{{ $user->email }}" id="user-email">{{ $user->email }} ({{ $user->FirstName }} {{ $user->LastName }})</option>
        @endforeach
    </select>
</div>
<div class="form-group text-right">
        <button class="btn btn-success" formaction="{{route('toBusinessUserActions',['actionType' => 'Add'])}}" id="addUserBtn"><i class="fa-solid fa-user-plus"></i> Add New Business User</button>
        <button type="button" class="btn btn-danger" onclick="closeAddBox()"><i class="fa-solid fa-ban"></i> Cancel</button>
        </div>