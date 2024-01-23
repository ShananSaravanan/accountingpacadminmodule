
@csrf
@method('post')
<div class="form-group">
            <label for="newuser-id">ID</label>
            <input type="text" class="form-control" name="new-postoffice-id" id="new-postoffice-id" value={{$newPostofficeID}} readonly required>
        </div>
<div class="form-group">
            <label for="newuser-id">Post Office Name</label>
            <input type="text" class="form-control" name="new-postoffice-name" id="new-postoffice-name"  required>
</div>

<div class="form-group text-right">
        <button class="btn btn-success" formaction="{{route('toPostOfficeActions',['actionType' => 'Add'])}}" id="addUserBtn"><i class="fa-solid fa-user-plus"></i> Add New Post Office</button>
        <button type="button" class="btn btn-danger" onclick="closeAddBox()"><i class="fa-solid fa-ban"></i> Cancel</button>
        </div>



