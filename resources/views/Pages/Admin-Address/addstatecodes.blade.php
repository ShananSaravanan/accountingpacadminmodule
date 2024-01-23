
@csrf
@method('post')

<div class="form-group">
<label for="">ID</label>
<input class="form-control" type="text" name="new-statecode-id" readonly value={{$newStateCodeID}}>
</div>

<div class="form-group">
<label for="">State Name</label>
<input class="form-control" type="text" name="new-statecode-name">
</div>

<div class="form-group text-right">
        <button class="btn btn-success" formaction="{{route('toStateCodeActions',['actionType' => 'Add'])}}" id="addUserBtn"><i class="fa-solid fa-user-plus"></i> Add New State</button>
        <button type="button" class="btn btn-danger" onclick="closeAddBox()"><i class="fa-solid fa-ban"></i> Cancel</button>
        </div>