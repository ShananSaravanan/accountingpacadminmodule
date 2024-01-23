
@csrf
@method('post')

<div class="form-group">
    <label for="new-address-id">ID</label>
    <input type="text" class="form-control" name="new-address-id" readonly value="{{ $newaddressID }}" required>
</div>

<div class="form-group">
    <label for="new-address-user">User Email</label>
    <select class="form-control" name="new-address-user" id="newuserOptions">
        @foreach($users as $user)
            <option value="{{ $user->email }}">{{ $user->email }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="new-address-type">Address Type</label>
    <select class="form-control" name="selectedaddresstype" id="newaddressTypeOptions">
        @foreach($addressTypes as $addressType)
            <option value="{{ $addressType->name }}">{{ $addressType->name }}</option>
        @endforeach
    </select>
    <input class="form-control" type="text" id="new-address-type" name="new-address-type" hidden>
    <button type="button" id="qAbtn1" onclick="setQuickAdd('qAbtn1','newaddressTypeOptions','new-address-type','cancelbtn1')" class="btn btn-primary btn-block mb-4"><i class="fa-solid fa-bolt"></i> Quick Add</button>   
    <button type="button" id="cancelbtn1" onclick="cancelQuickAdd('qAbtn1','newaddressTypeOptions','new-address-type','cancelbtn1')" hidden class="btn btn-danger btn-block mb-4"><i class="fa-solid fa-ban"></i> Cancel</button>
</div>

<div class="form-group">
    <label for="new-address-line">Address Line 1</label>
    <input type="text" class="form-control" name="new-address-line"  value="" required>
</div>

<div class="form-group">
    <label for="new-address-line">Street</label>
    <input type="text" class="form-control" name="new-address-street"  value="" required>
</div>

<div class="form-group">
    <label for="new-address-line">State</label>
    <select class="form-control"  name="new-address-state" id="new-address-state" onchange="handleStateCodeChange(this,'Add')">
        @foreach($stateCodes as $statecode)
        <option  value="{{$statecode -> name}}" id="">{{$statecode -> name}}</option>
        @endforeach
</select>
</div>

<div class="form-group">
    <label for="new-address-line">Post Office</label>
    <select class="form-control"  name="new-address-postoffice" id="new-address-postoffice" onchange="handlepostOfficeChange(this,'Add')">
        <option  value="" id="">Auto-Generated</option>
</select>
</div>

<!-- Repeat the structure for other form fields -->

<div class="form-group">
    <label for="new-address-postcode">Post Code</label>
    <input type="text" class="form-control" id="new-address-postcode" name="new-address-postcode" value="Auto-Generated" readonly>
</div>

<div class="form-group">
    <label for="new-address-country">Country</label>
    <input type="text" class="form-control" name="new-address-country" value="Malaysia" readonly>
</div>

<!-- Repeat the structure for other form fields -->

<div class="form-group text-right">
    <button class="btn btn-success" type="submit" formaction="{{ route('toAddressActions', ['actionType' => 'Add']) }}"><i class="fa-solid fa-user-plus"></i>Add New Address</button>
    <button type="button" class="btn btn-danger" onclick="closeAddBox()"><i class="fa-solid fa-ban"></i> Cancel</button>
</div>



