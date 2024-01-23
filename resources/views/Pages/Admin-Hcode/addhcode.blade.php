
    @csrf
    @method('post')

    <div class="form-group">
        <label for="">ID</label>
        <input type="text" class="form-control" readonly value="{{ $newHcodeID }}">
    </div>

    <div class="form-group">
        <label for="newHcode">Honorific Code Name</label>
        <input type="text" class="form-control" name="newHcode">
    </div>

    <!-- Add any other form fields if needed -->

    <div class="form-group text-right">
        <button class="btn btn-success" formaction="{{route('toHcodeActions',['actionType' => 'Add'])}}" id="addUserBtn"><i class="fa-solid fa-user-plus"></i> Add New Honorific Code</button>
        <button type="button" class="btn btn-danger" onclick="closeAddBox()"><i class="fa-solid fa-ban"></i> Cancel</button>
        </div>

