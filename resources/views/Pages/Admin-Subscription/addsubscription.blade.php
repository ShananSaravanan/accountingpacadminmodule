
    @csrf
    @method('post')

    <div class="form-group">
        <label for="">ID</label>
        <input type="text" class="form-control" name="new-subscription-id" readonly value="{{ $newSubscriptionID }}" required>
    </div>

    <div class="form-group">
        <label for="new-user-sub">User Email</label>
        <select name="new-user-sub" id="new-user-sub" onchange="handleUserChange(this,'Add')" class="form-control">
            @foreach($users as $user)
                <option value="{{ $user->id }}" id="newuseroption">{{ $user->email }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="new-package-id">Package</label>
        <select name="new-package" id="new-package-id" onchange="handlePackageChange(this,'Add')" class="form-control">
            <option value="" id="">Auto-Generated</option>
        </select>
    </div>

    <div class="form-group">
        <label for="new-package-code">Package Code</label>
        <input type="text" id="new-package-code" name="new-package-code" readonly class="form-control">
    </div>

    <input type="hidden" id="new-duration" name="new-duration" value="">

    <div class="form-group">
        <label for="new-date-valid-from">Date Valid From</label>
        <input type="datetime-local" name="new-date-valid-from" id="new-date-valid-from" value="{{ $formattedDateTime }}" onchange="changeValidDateTimeTo('Add')" class="form-control">
    </div>

    <div class="form-group">
        <label for="new-date-valid-to">Date Valid To</label>
        <input type="datetime-local" name="new-date-valid-to" id="new-date-valid-to" value="{{ $formattedDateTime }}" readonly class="form-control">
    </div>

    <!-- Continue adding form fields -->

    <div class="form-group">
        <label for="new-transaction">Transaction No</label>
        <select name="new-transaction" id="new-transaction" onchange="handleTransactionChange(this,'Add')" class="form-control">
            @foreach($transactions as $transaction)
                <option value="{{ $transaction->id }}" id="transactionOption">{{ $transaction->transactionNo }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="new-bank">Bank Name</label>
        <input type="text" id="new-bank" name="new-bank" readonly class="form-control">
    </div>

    <div class="form-group">
        <label for="new-amount">Amount</label>
        <input type="text" id="new-amount" name="new-amount" readonly class="form-control">
    </div>

    <div class="form-group">
        <label for="new-status">Status</label>
        <select name="new-status" id="new-status" onchange="handleStatusChange(this,'Add')" class="form-control">
            <option value="Active" id="">Active</option>
            <option value="Inactive" id="">Inactive</option>
            <option value="Cancelled" id="">Cancelled</option>
        </select>
    </div>

    <div class="form-group">
        <label for="new-cancelleddate-text">Cancellation Date</label>
        <input type="text" name="new-cancelleddate-text" id="new-cancelleddate-text" value="UNCANCELLED" readonly class="form-control">
        <input type="datetime-local" name="new-cancelleddate" id="new-cancelleddate" value="" hidden class="form-control">
    </div>

    <!-- Add any other form fields if needed -->

    <div class="form-group text-right">
        <button class="btn btn-success" formaction="{{route('toSubscriptionActions',['actionType' => 'Add'])}}" id="addUserBtn"><i class="fa-solid fa-user-plus"></i> Add New Subscription</button>
        <button type="button" class="btn btn-danger" onclick="closeAddBox()"><i class="fa-solid fa-ban"></i> Cancel</button>
        </div>

