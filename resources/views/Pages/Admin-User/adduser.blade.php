        @csrf
        @method('post')

        <div class="form-group">
            <label for="newuser-id">ID:</label>
            <input type="text" class="form-control" name="newuser-id" id="newuser-id" value={{$newUserID}} readonly required>
        </div>

        <div class="form-group">
            <label for="newuser-fname">First Name:</label>
            <input type="text" class="form-control" name="newuser-fname" id="newuser-fname" required>
        </div>

        <div class="form-group">
            <label for="newuser-lname">Last Name:</label>
            <input type="text" class="form-control" name="newuser-lname" id="newuser-lname" required>
        </div>

        <div class="form-group">
            <label for="newuser-contact">Contact Number:</label>
            <input type="text" class="form-control" name="newuser-contact" id="newuser-contact" required>
        </div>

        <div class="form-group">
            <label for="hCodeOptions2">Honorific Code:</label>
            <select class="form-control" name="hCodeChoice2" id="hCodeOptions2">
                @foreach($hCodeNames as $hCodeName)
                    <option value="{{$hCodeName->CodeName}}" id="newuser-hcode2">{{$hCodeName->CodeName}}</option>
                @endforeach
            </select>
            <button type="button" class="btn btn-primary btn-block mb-4" id="qAbtn1" onclick="setQuickAdd('qAbtn1','hCodeOptions2','newHcode','cancelbtn1')"><i class="fa-solid fa-bolt"></i> Quick Add</button>
            <input class="form-control" type="text" id="newHcode" name="newHcode" hidden>
            <button type="button" class="btn btn-danger btn-block mb-4" id="cancelbtn1" onclick="cancelQuickAdd('qAbtn1','hCodeOptions2','newHcode','cancelbtn1')" hidden><i class="fa-solid fa-ban"></i> Cancel</button>
        </div>

        <div class="form-group">
            <label for="roleOptions2">Role:</label>
            <select class="form-control" name="roleChoice2" id="roleOptions2">
                @foreach($roleNames as $roleName)
                    <option value="{{$roleName->name}}" id="newuser-rname2">{{$roleName->name}}</option>
                @endforeach
            </select>
            <button type="button" class="btn btn-primary btn-block mb-4" id="qAbtn2" onclick="setQuickAdd('qAbtn2','roleOptions2','newRole','cancelbtn2')"><i class="fa-solid fa-bolt"></i> Quick Add</button>
            <input class="form-control" type="text" id="newRole" name="newRole" hidden>
            <button type="button" class="btn btn-danger btn-block mb-4" id="cancelbtn2" onclick="cancelQuickAdd('qAbtn2','roleOptions2','newRole','cancelbtn2')" hidden><i class="fa-solid fa-ban"></i> Cancel</button>
        </div>

        <div class="form-group">
            <label for="newuser-email">Registered Email Address:</label>
            <input type="email" class="form-control" name="newuser-email" id="newuser-email" required>
        </div>

        <div class="form-group">
            <label for="newuser-password">Registered Password:</label>
            <input type="text" class="form-control" name="newuser-password" id="newuser-password" required>
        </div>

        <div class="form-group text-right">
        <button class="btn btn-success" formaction="{{route('toUserActions',['actionType' => 'Add'])}}" id="addUserBtn"><i class="fa-solid fa-user-plus"></i> Add New User</button>
        <button type="button" class="btn btn-danger" onclick="closeAddBox()"><i class="fa-solid fa-ban"></i> Cancel</button>
        </div>
     

