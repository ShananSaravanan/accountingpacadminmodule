
        @csrf
        @method('post')
    <label for="id">ID</label><input type="text"  name="business-id" value="{{$newBusinessData}}" readonly><br>
    <label for="businessType">Business Type</label>
    <select  name="businessTypeChoice" id="bTypeChoice">
    @foreach($businessTypes as $businessType)
    <option value="{{$businessType->businessTypeName}}">{{$businessType->businessTypeName}}</option>
    @endforeach
    <input type="text" id="newbusinesstype" name="new-business-type" hidden>
    </select>
    <button id="qAbtn1" type="button" onclick="setQuickAdd('qAbtn1','bTypeChoice','newbusinesstype','cancelBtn1')">+Quick Add</button>
    <button id="cancelBtn1" type="button" onclick="cancelQuickAdd('qAbtn1','bTypeChoice','newbusinesstype','cancelBtn1')" hidden>Cancel</button>
    <br>
    <label for="">Business Name</label><input type="text"   name="business-name" value="{{session('businessName')}}"><br>
    <label for="">Business Contact</label><input type="text"  name="business-contact" value="{{session('businessContact')}}"><br>
    <label for="">Business Email</label><input type="text"  name="business-email" value="{{session('businessEmail')}}"><br>
    <label for="">Logo</label><input id="imageinput2" type="file"  name="business-logo"  accept=".png, .jpg" onchange="previewImage(event,'add')">
    <img  src="" id="imagepreview2" alt=""  hidden>
    <br>
    

