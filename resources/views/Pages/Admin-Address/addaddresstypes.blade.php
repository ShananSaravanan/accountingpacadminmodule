
@csrf
@method('post')
<div class="form-group">
<label for="">ID</label><input class="form-control" type="text" readonly value={{$AddressTypeID}}><br>
</div>

<div class="form-group">
<label for="">Address Type Name</label><input class="form-control" type="text" name="new-address-type">
</div>
<div class="form-group text-right">
<button type="submit" class="btn btn-success" formaction="{{route('toAddressTypeActions',['actionType' => 'Add'])}}"><i class="fa-solid fa-user-plus"></i>Add New Address Type</button>
        <button type="button" class="btn btn-danger" onclick="closeAddBox()"><i class="fa-solid fa-ban"></i> Cancel</button>
</div>


