@csrf
@method('post')

<div class="form-group">
    <label for="new-firmuser-id">Firm User ID</label>
    <input type="text" class="form-control" name="new-firmuser-id" value="{{ $newFirmUserID }}" readonly>
</div>

<div class="form-group">
    <label for="firmOptions">Firm</label>
    <select class="form-control column-data" name="new-firm-id" id="firmOptions">
        <option value="empty">No firm</option>
        @foreach($firms as $firm)
            <option value="{{ $firm->id }}" id="firm-name">{{ $firm->firmName }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="userOptions">User</label>
    <select class="form-control column-data" name="new-user" id="userOptions">
        @foreach($users as $user)
            <option value="{{ $user->id }}" id="package-name">{{ $user->email }} ({{ $user->FirstName }} {{ $user->LastName }})</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="new-miano">MIA_NO</label>
    <input type="text" class="form-control" name="new-miano">
</div>

<div class="form-group">
    <label for="new-pcno">PC_NO</label>
    <input type="text" class="form-control" name="new-pcno">
</div>
<div class="form-group text-right">
        <button class="btn btn-success" formaction="{{route('toFirmUserActions',['actionType' => 'Add'])}}" id="addUserBtn"><i class="fa-solid fa-user-plus"></i> Add New Firm User</button>
        <button type="button" class="btn btn-danger" onclick="closeAddBox()"><i class="fa-solid fa-ban"></i> Cancel</button>
        </div>