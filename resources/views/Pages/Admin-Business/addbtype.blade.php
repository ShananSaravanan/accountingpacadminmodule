
@csrf
@method('post')

<div class="form-group">
<label for="">ID</label><input class="form-control" type="text" readonly value={{$bTypeID}}><br>
    </div>
<div class="form-group">
<label for="">Business Type Name</label><input class="form-control" type="text" name="new-business-type">
</div>
<div class="form-group text-right">
        <button class="btn btn-success" formaction="{{route('toBusinessTypeActions',['actionType' => 'Add'])}}" id="addUserBtn"><i class="fa-solid fa-user-plus"></i> Add New Business Type</button>
        <button type="button" class="btn btn-danger" onclick="closeAddBox()"><i class="fa-solid fa-ban"></i> Cancel</button>
        </div>
    



