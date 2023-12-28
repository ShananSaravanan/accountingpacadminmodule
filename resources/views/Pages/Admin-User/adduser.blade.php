<table>
    <tr>
    <th>ID</th>
    <th>First Name</th>
    <th>Last Name</th>
    <th>Contact Number</th>
    <th>Honorific Code</th>
    <th>Role</th>
    <th>Registered Email Address</th>
    <th>Registered Password</th>
    </tr>
    
    
    @csrf
    @method('post')
    <tr id = "{{$newUserID}}">
    <td ><input  type="text" class="column-data"  name="newuser-id" id="newuser-id" value={{$newUserID}} readonly required></td>
    <td ><input type="text" name="newuser-fname"  id="newuser-fname" required></td>
    <td ><input type="text" name="newuser-lname"  id="newuser-lname" required></td>
    <td ><input type="text" name="newuser-contact" id="newuser-contact" required></td>
    <td><select name="hCodeChoice2" id="hCodeOptions2">
    @foreach($hCodeNames as $hCodeName)
        <option value="{{$hCodeName -> CodeName}}" id="newuser-hcode2">{{$hCodeName -> CodeName}}</option>
    @endforeach
    </select>
    <button type="button" id="qAbtn1" onclick="setQuickAdd('qAbtn1','hCodeOptions2','newHcode','cancelbtn1')">+Quick Add</button>   
    <input type="text" id="newHcode" name="newHcode" hidden>
    <button type="button" id="cancelbtn1" onclick="cancelQuickAdd('qAbtn1','hCodeOptions2','newHcode','cancelbtn1')" hidden>Cancel</button>
</td>
    <td><select name="roleChoice2" id="roleOptions2">
        @foreach($roleNames as $roleName)
        <option value="{{$roleName -> name}}" id="newuser-rname2">{{$roleName -> name}}</option>
        @endforeach
    </select>
    <button type="button" id="qAbtn2" onclick="setQuickAdd('qAbtn2','roleOptions2','newRole','cancelbtn2')">+Quick Add</button>   
    <input type="text" id="newRole" name="newRole" hidden>
    <button type="button" id="cancelbtn2" onclick="cancelQuickAdd('qAbtn2','roleOptions2','newRole','cancelbtn2')" hidden>Cancel</button>
</td>
    <td ><input type="email" name="newuser-email"  id="newuser-email" required></td>
    <td ><input type="text" name="newuser-password"  id="newuser-password" required></td>
    <td></td>
    </tr>
    
    
</table>
