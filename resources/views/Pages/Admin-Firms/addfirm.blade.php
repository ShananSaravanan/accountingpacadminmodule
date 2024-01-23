
    @csrf
    @method('post')

    <div class="form-group">
        <label for="new-firm-id">Firm ID</label>
        <input type="text" class="form-control" name="new-firm-id" value="{{ $newFirmID }}" readonly required>
    </div>

    <div class="form-group">
        <label for="new-firm-name">Firm Name</label>
        <input type="text" class="form-control" name="new-firm-name" required>
    </div>

    <div class="form-group">
        <label for="new-firm-owner">Firm Owner</label>
        <select class="form-control" name="new-firm-owner" id="firmuserTypes">
            @foreach($firmusers as $firmuser)
                <option value="{{ $firmuser->user->email }}">{{ $firmuser->user->email }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="">Firm Type</label>
        <select class="form-control" name="firmTypeChoice" id="firmTypeChoice" >
                @foreach($firmTypes as $firmType)
                <option value="{{$firmType->name}}">{{$firmType->name}}</option>
                @endforeach
        </select>
    <input class="form-control"  type="text" id="newfirmtype" name="new-firm-type2" hidden>
    <button class="btn btn-primary btn-block mb-4" id="qAbtn1" type="button" onclick="setQuickAdd('qAbtn1','firmTypeChoice','newfirmtype','cancelBtn1')"><i class="fa-solid fa-bolt"></i> Quick Add</button>
    <button class="btn btn-danger btn-block mb-4" id="cancelBtn1" type="button" onclick="cancelQuickAdd('qAbtn1','firmTypeChoice','newfirmtype','cancelBtn1')" hidden><i class="fa-solid fa-ban"></i> Cancel</button>
    </div>

    <div class="form-group">
        <label for="">AF No</label>
        <input class="form-control" type="text" name="new-af-no" value="" >
    </div>

    <div class="form-group">
        <label for="">SSM No</label>
        <input class="form-control" type="text" name="new-ssm-no" value="" >
    </div>

    <div class="form-group">
        <label for="">Contact</label>
        <input class="form-control" type="text" name="new-firm-contact" value="" >
    </div>

    <div class="form-group">
        <label for="">Email</label>
        <input class="form-control" type="text" name="new-firm-email" value="" >
    </div>

    <div class="form-group">
        <label for="">Address</label>
        <select class="form-control" name="new-firm-address" id="firmAddress" >
                @foreach($addresses as $address)
                <option value="{{$address->id}}">{{$address->addressLine1}},{{$address->street}},{{$address->postcode->postcode}},{{$address->postcode->postoffice->name}},{{$address->postcode->statecode->name}},{{$address->country}}</option>
                @endforeach
    </select>
    </div>

    <div class="form-group">
        <label for="">Limit</label>
        <input class="form-control" type="number" name="new-limit" value="" >
    </div>

    <div class="form-group">
        <label for="">Status</label>
        <input class="form-control" type="text" name="new-status" value="" >
    </div>
    <!-- Repeat the above structure for other form fields -->

    <div class="form-group">
        <label for="new-firm-logo">Firm Logo:</label>
        <input id="imageinput2" type="file" name="new-firm-logo" accept=".png, .jpg" onchange="previewImage(event,'add')">
        <img src="" id="imagepreview2"  class="img-fluid" alt="">
    </div>

    <div class="form-group text-right">
    <button class="btn btn-success" type="submit" type="submit" formaction="{{route('toFirmActions',['actionType' => 'Add'])}}" ><i class="fa-solid fa-user-plus"></i>Add New Firm</button>
    
        <button type="button" class="btn btn-danger" onclick="closeAddBox()"><i class="fa-solid fa-ban"></i> Cancel</button>
    </div>

    




