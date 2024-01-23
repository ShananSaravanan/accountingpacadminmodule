
    @csrf
    @method('post')

    <div class="form-group">
        <label for="">ID</label>
        <input type="text" class="form-control" readonly value="{{ $FirmTypeID }}">
    </div>

    <div class="form-group">
        <label for="new-firm-type">Firm Type Name</label>
        <input type="text" class="form-control" name="new-firm-type">
    </div>

    <!-- Add any other form fields if needed -->

    <div class="form-group text-right">
        <button class="btn btn-success" formaction="{{route('toFirmTypeActions',['actionType' => 'Add'])}}" id="addUserBtn"><i class="fa-solid fa-user-plus"></i> Add New Firm Type</button>
        <button type="button" class="btn btn-danger" onclick="closeAddBox()"><i class="fa-solid fa-ban"></i> Cancel</button>
        </div>

