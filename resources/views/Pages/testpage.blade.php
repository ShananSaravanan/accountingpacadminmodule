<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token()}}">
    <title>Document</title>
    @foreach($photos as $photo)
    <h4>Hi</h4>
    <img src="{{asset('storage/logos'. $photo->logo)}}" alt="test">
    @endforeach
</head>
<body>
<h5>Hi</h5>
</body>
</html>
