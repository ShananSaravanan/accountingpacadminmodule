 @csrf
    @method('post')

    <div class="form-group">
        <label for="new-basepackage-id">ID</label>
        <input type="text" class="form-control" name="new-basepackage-id" value="{{ $newBasePackageID }}" readonly>
    </div>

    <div class="form-group">
        <label for="packageOptions">Package Name</label>
        <select class="form-control column-data" name="selectedpackagename" id="packageOptions">
            @foreach($packageNames as $packageName)
                <option value="{{ $packageName->name }}" id="package-name">{{ $packageName->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="new-basepackage-duration">Duration</label>
        <input type="text" class="form-control" name="new-basepackage-duration">
    </div>

    <div class="form-group">
        <label for="new-basepackage-price">Base Price</label>
        <input type="number" class="form-control" name="new-basepackage-price" pattern="\d+(\.\d{1,2})?" oninput="validateAmount(this)">
    </div>

    <!-- Add any other form fields if needed -->

    <div class="form-group text-right">
        <button class="btn btn-success" formaction="{{route('toPackageBaseActions',['actionType' => 'Add'])}}" id="addUserBtn"><i class="fa-solid fa-user-plus"></i> Add New Pacakge Base</button>
        <button type="button" class="btn btn-danger" onclick="closeAddBox()"><i class="fa-solid fa-ban"></i> Cancel</button>
        </div>
</form>
