@csrf
@method('post')

<div class="form-group">
    <label for="business-id">ID</label>
    <input type="text" class="form-control" name="business-id" value="{{ $newBusinessData }}" readonly>
</div>

<div class="form-group">
    <label for="bTypeChoice">Business Type</label>
    <select class="form-control" name="businessTypeChoice" id="bTypeChoice">
        @foreach($businessTypes as $businessType)
            <option value="{{ $businessType->businessTypeName }}">{{ $businessType->businessTypeName }}</option>
        @endforeach
    </select>
    <input type="text" class="form-control" id="newbusinesstype" name="new-business-type" hidden>
    <button class="btn btn-primary btn-block mb-4" id="qAbtn1" type="button" onclick="setQuickAdd('qAbtn1','bTypeChoice','newbusinesstype','cancelBtn1')"><i class="fa-solid fa-bolt"></i> Quick Add</button>
    <button class="btn btn-danger btn-block mb-4" id="cancelBtn1" type="button" onclick="cancelQuickAdd('qAbtn1','bTypeChoice','newbusinesstype','cancelBtn1')" hidden><i class="fa-solid fa-ban"></i> Cancel</button>
</div>

<div class="form-group">
    <label for="business-name">Business Name</label>
    <input type="text" class="form-control" name="business-name" value="{{ session('businessName') }}">
</div>

<div class="form-group">
    <label for="business-contact">Business Contact</label>
    <input type="text" class="form-control" name="business-contact" value="{{ session('businessContact') }}">
</div>

<div class="form-group">
    <label for="business-email">Business Email</label>
    <input type="text" class="form-control" name="business-email" value="{{ session('businessEmail') }}">
</div>

<div class="form-group">
    <label for="business-logo">Logo</label>
    <input id="imageinput2" type="file" class="form-control" name="business-logo" accept=".png, .jpg" onchange="previewImage(event,'add')">
    <img src="" id="imagepreview2" alt="" class="img-fluid" hidden>
</div>
<div class="form-group text-right">
        <button class="btn btn-success" formaction="{{route('toBusinessActions',['actionType' => 'Add'])}}" id="addUserBtn"><i class="fa-solid fa-user-plus"></i> Add New Business</button>
        <button type="button" class="btn btn-danger" onclick="closeAddBox()"><i class="fa-solid fa-ban"></i> Cancel</button>
        </div>