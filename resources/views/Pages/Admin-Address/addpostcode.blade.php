@csrf
@method('post')

<div class="form-group">
    <label for="new-postcode-id">ID</label>
    <input type="text" class="form-control" name="new-postcode-id" readonly value="{{ $newpostCodeID }}" required>
</div>

<div class="form-group">
    <label for="new-postcode">Post Code</label>
    <input class="form-control" type="text" class="form-control" name="new-postcode" id="new-postcode" required>
</div>

<div class="form-group">
    <label for="new-postcode-location">Location</label>
    <input class="form-control" type="text" class="form-control" name="new-postcode-location" id="new-postcode-location" required>
</div>

<div class="form-group">
    <label for="postOfficeOptions2">Post Office Name</label>
    <select class="form-control" name="officename" id="postOfficeOptions2">
        @foreach($postoffices as $postoffice)
            <option value="{{ $postoffice->name }}" id="postofficename2">{{ $postoffice->name }}</option>
        @endforeach
    </select>
    <button type="button" class="btn btn-primary btn-block mb-4" id="qAbtn1" onclick="setQuickAdd('qAbtn1','postOfficeOptions2','new-postoffice-name','cancelbtn1')"><i class="fa-solid fa-bolt"></i> Quick Add</button>   
    <input class="form-control" type="text" id="new-postoffice-name" name="new-postoffice-name" hidden>
    <button type="button" class="btn btn-danger btn-block mb-4" id="cancelbtn1" onclick="cancelQuickAdd('qAbtn1','postOfficeOptions2','new-postoffice-name','cancelbtn1')" hidden><i
                            class="fa-solid fa-ban"></i>Cancel</button>
</div>

<div class="form-group">
    <label for="stateOptions2">State Name</label>
    <select class="form-control" name="statename" id="stateOptions2">
        @foreach($statecodes as $statecode)
            <option value="{{ $statecode->name }}" id="user-rname2">{{ $statecode->name }}</option>
        @endforeach
    </select>
    <button type="button" class="btn btn-primary btn-block mb-4" id="qAbtn2" onclick="setQuickAdd('qAbtn2','stateOptions2','new-statecode-name','cancelbtn2')"><i class="fa-solid fa-bolt"></i> Quick Add</button>   
    <input class="form-control" type="text" id="new-statecode-name" name="new-statecode-name" hidden>
    <button type="button" class="btn btn-danger btn-block mb-4" id="cancelbtn2" onclick="cancelQuickAdd('qAbtn2','stateOptions2','new-statecode-name','cancelbtn2')" hidden><i
                            class="fa-solid fa-ban"></i> Cancel</button>
</div>

<!-- Repeat the structure for other form fields -->

<div class="form-group text-right">
    <button class="btn btn-success" type="submit" formaction="{{ route('toPostCodeActions', ['actionType' => 'Add']) }}"><i class="fa-solid fa-user-plus"></i>Add New Post Code</button>
    <button type="button" class="btn btn-danger" onclick="closeAddBox()"><i class="fa-solid fa-ban"></i> Cancel</button>
</div>


<!-- <table>
    <tr>
    <th>ID</th>
    <th>Post Code</th>
    <th>Location</th>
    <th>Post Office Name</th>
    <th>State Name</th>
    </tr>
    @csrf
    @method('post')
    <tr id = "{{$newpostCodeID}}">
    <td ><input  type="number" class="column-data"  name="new-postcode-id" id="new-postcode-id" value={{$newpostCodeID}} readonly required></td>
    <td ><input  type="number" class="column-data"  name="new-postcode" id="new-postcode"  required></td>
    <td ><input type="text" name="new-postcode-location"  id="new-postcode-location" required></td>
    
    <td><select class="column-data" name="officename" id="postOfficeOptions2" >
    @foreach($postoffices as $postoffice)
        <option value="{{$postoffice -> name}}" id="postofficename2">{{$postoffice -> name}}</option>
    @endforeach
    </select>
    <button type="button" id="qAbtn1" onclick="setQuickAdd('qAbtn1','postOfficeOptions2','new-postoffice-name','cancelbtn1')">+Quick Add</button>   
    <input type="text" id="new-postoffice-name" name="new-postoffice-name" hidden>
    <button type="button" id="cancelbtn1" onclick="cancelQuickAdd('qAbtn1','postOfficeOptions2','new-postoffice-name','cancelbtn1')" hidden>Cancel</button>
</td>
    <td><select class="column-data" name="statename" id="stateOptions2" >
        @foreach($statecodes as $statecode)
        <option value="{{$statecode -> name}}" id="user-rname2">{{$statecode -> name}}</option>
        @endforeach
    </select>
    <button type="button" id="qAbtn2" onclick="setQuickAdd('qAbtn2','stateOptions2','new-statecode-name','cancelbtn2')">+Quick Add</button>   
    <input type="text" id="new-statecode-name" name="new-statecode-name" hidden>
    <button type="button" id="cancelbtn2" onclick="cancelQuickAdd('qAbtn2','stateOptions2','new-statecode-name','cancelbtn2')" hidden>Cancel</button>
</td>
    </tr>
</table> -->
