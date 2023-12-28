
@csrf
@method('post')
<label for="id">ID</label><input type="text"  name="new-basepackage-id" value="{{$newBasePackageID}}" readonly>
<select class="column-data" name="selectedpackagename" id="packageOptions">
@foreach($packageNames as $packageName)
<option value="{{$packageName -> name}}" id="package-name">{{$packageName -> name}}</option>
@endforeach
</select>
<label for="">Duration</label><input type="text" name="new-basepackage-duration">
<label for="">Base Price</label><input type="numbers" name="new-basepackage-price">
