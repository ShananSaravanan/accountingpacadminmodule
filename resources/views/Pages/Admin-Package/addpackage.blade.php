
@csrf
@method('post')
<label for="id">ID</label><input type="text"  name="new-package-id" value="{{$newPackageID}}" readonly><br><br>
<label for="">Package Code</label><input type="text" name="new-package-PackageCode">
<label for="">Package Name</label><input type="text" name="new-package-name">
<label for="">User Limit</label><input type="numbers" name="new-package-userlimit">
