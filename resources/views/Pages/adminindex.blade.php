<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Dashboard</title>
</head>
<body>
@if (session('sessionUsername'))
    <script>alert('Welcome to Administrator Panel,' + ' {{session('sessionUsername')}}')</script>
@endif

@include('Pages.templates.sidebar')
</body>
</html>