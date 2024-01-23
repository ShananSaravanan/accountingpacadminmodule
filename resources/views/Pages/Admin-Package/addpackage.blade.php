
    @csrf
    @method('post')

    <div class="form-group">
        <label for="new-package-id">ID</label>
        <input type="text" class="form-control" name="new-package-id" value="{{ $newPackageID }}" readonly>
    </div>

    <div class="form-group">
        <label for="new-package-PackageCode">Package Code</label>
        <input type="text" class="form-control" name="new-package-PackageCode">
    </div>

    <div class="form-group">
        <label for="new-package-name">Package Name</label>
        <input type="text" class="form-control" name="new-package-name">
    </div>

    <div class="form-group">
        <label for="new-package-userlimit">User Limit</label>
        <input type="number" class="form-control" name="new-package-userlimit">
    </div>

    <!-- Add any other form fields if needed -->

    <div class="form-group text-right">
        <button class="btn btn-success" formaction="{{route('toPackageActions',['actionType' => 'Add'])}}" id="addUserBtn"><i class="fa-solid fa-user-plus"></i> Add New Pacakge</button>
        <button type="button" class="btn btn-danger" onclick="closeAddBox()"><i class="fa-solid fa-ban"></i> Cancel</button>
        </div>

