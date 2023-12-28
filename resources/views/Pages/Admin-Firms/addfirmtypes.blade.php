<form id="addressTypeForm" method="POST" action="" enctype="multipart/form-data">
@csrf
@method('post')
<label for="">ID</label><input type="text" readonly value={{$FirmTypeID}}><br>
<label for="">Firm Type Name</label><input type="text" name="new-firm-type">
<button type="submit" formaction="{{route('toFirmTypeActions',['actionType' => 'Add'])}}">Add New Firm Type</button>
</form>