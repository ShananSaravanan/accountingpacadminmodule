@csrf
@method('post')

<div class="form-group">
    <label for="new-frecord-id">ID</label>
    <input type="number" class="form-control" name="new-frecord-id" id="new-frecord-id" value="{{ $newFRecordID }}" required readonly>
</div>

<div class="form-group">
    <label for="businessOptions">Business Name</label>
    <select class="form-control" name="new-business-name" id="businessOptions">
        @foreach($businesses as $business)
            <option value="{{ $business->businessName }}" id="business-name">{{ $business->businessName }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="categoryOptions">Record Category</label>
    <select class="form-control" name="new-recordcategory" id="categoryOptions">
        <option value="Income">Income</option>
        <option value="Expense">Expense</option>
    </select>
</div>

<div class="form-group">
    <label for="amount">Amount (in RM)</label>
    <input type="text" class="form-control" name="new-amount" id="amount" value="" pattern="\d+(\.\d{1,2})?" oninput="validateAmount(this)">
</div>

<div class="form-group">
    <label for="description">Description</label>
    <input type="text" class="form-control" name="new-description" id="description">
</div>

<div class="form-group">
    <label for="recordtime">Time of Record</label>
    <input type="datetime-local" class="form-control" name="new-recordtime" id="recordtime" value="{{ $formattedDateTime }}">
</div>

<div class="form-group text-right">
        <button class="btn btn-success" formaction="{{route('toFinancialRecordsActions',['actionType' => 'Add'])}}" id="addUserBtn"><i class="fa-solid fa-user-plus"></i> Add New Financial Record</button>
        <button type="button" class="btn btn-danger" onclick="closeAddBox()"><i class="fa-solid fa-ban"></i> Cancel</button>
        </div>