@csrf
@method('post')
<label for="id">ID</label><input type="text"  name="new-buser-id" value="{{$newbUserID}}" readonly>
<label for="">Business Name</label><select class="column-data" name="selectedBusiness" id="businessOptions">
        @foreach($businesses as $business)
        <option value="{{$business -> businessName}}" id="business-name">{{$business -> businessName}} ({{$business->btype->businessTypeName}})</option>
        @endforeach
    </select>
<label for="">User Email</label><select class="column-data" name="selectedUser" id="userOptions">
        @foreach($users as $user)
        <option value="{{$user -> email}}" id="user-email">{{$user -> email}} ({{$user->FirstName}} {{$user->LastName}})</option>
        @endforeach
    </select>