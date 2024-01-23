
    @csrf
    @method('post')

    <div class="form-group">
        <label for="new-trans-id">ID</label>
        <input type="text" name="new-trans-id" value="{{ $newTransID }}" class="form-control">
    </div>

    <div class="form-group">
        <label for="new-trans-no">Transaction No</label>
        <input type="text" name="new-trans-no" class="form-control">
    </div>

    <div class="form-group">
        <label for="new-trans-name">Name</label>
        <input type="text" name="new-trans-name" class="form-control">
    </div>

    <div class="form-group">
        <label for="new-trans-amount">Amount</label>
        <input type="text" name="new-trans-amount" class="form-control">
    </div>

    <div class="form-group">
        <label for="new-trans-fpxid">FPX ID</label>
        <input type="text" name="new-trans-fpxid" class="form-control">
    </div>

    <div class="form-group">
        <label for="new-trans-fpxchecksum">FPX Check Sum</label>
        <input type="text" name="new-trans-fpxchecksum" class="form-control">
    </div>

    <div class="form-group">
        <label for="new-trans-bankid">Bank ID</label>
        <input type="text" name="new-trans-bankid" class="form-control">
    </div>

    <div class="form-group">
        <label for="new-trans-status">Status</label>
        <input type="text" name="new-trans-status" class="form-control">
    </div>

    <!-- Add any other form fields if needed -->
    <div class="form-group text-right">
        <button class="btn btn-success" formaction="{{route('toTransactionActions',['actionType' => 'Add'])}}" id="addUserBtn"><i class="fa-solid fa-user-plus"></i> Add New Transaction</button>
        <button type="button" class="btn btn-danger" onclick="closeAddBox()"><i class="fa-solid fa-ban"></i> Cancel</button>
        </div>
  
