@csrf
@method('post')
<label for="">ID</label><input required type="text" name="new-address-id" readonly value={{$newaddressID}}><br>
<label for="">User Email</label><select name="new-address-user" id="newuserOptions">
    @foreach($users as $user)
        <option value="{{$user -> email}}" id="">{{$user -> email}}</option>
    @endforeach
</select><br>
<label for="">Address Type</label>
<select  name="selectedaddresstype" id="newaddressTypeOptions">
    @foreach($addressTypes as $addressType)
        <option value="{{$addressType -> name}}" id="">{{$addressType -> name}}</option>
    @endforeach
</select>
<button type="button" id="qAbtn1" onclick="setQuickAdd('qAbtn1','newaddressTypeOptions','new-address-type','cancelbtn1')">+Quick Add</button>   
<input type="text" id="new-address-type" name="new-address-type" hidden>
<button type="button" id="cancelbtn1" onclick="cancelQuickAdd('qAbtn1','newaddressTypeOptions','new-address-type','cancelbtn1')" hidden>Cancel</button>
<br>
<label for="">Address Line 1</label><input required required type="text" name="new-address-line"><br>
<label for="">Street</label><input required required type="text" name="new-address-street"><br>
<label for="">State</label>
<select   name="new-address-state" id="new-address-state" onchange="handleStateCodeChange(this,'Add')">
        @foreach($stateCodes as $statecode)
        <option  value="{{$statecode -> name}}" id="">{{$statecode -> name}}</option>
        @endforeach
</select><br>
<label for="">Post Office Name</label>
<select  name="new-address-postoffice" id="new-address-postoffice" onchange="handlepostOfficeChange(this,'Add')">
        <option  value="" id="">Auto-Generated</option>
</select><br>
<label for="">Post Code</label><input required required type="text" id="new-address-postcode" name="new-address-postcode" value="Auto-Generated" readonly><br>
<label for="">Country</label><input required required type="text" name="new-address-country" value="Malaysia" readonly><br>


