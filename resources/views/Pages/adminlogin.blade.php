<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Login</title>
</head>
<body>
    <form action="{{route('login.getservice')}}" method="post">
        @csrf
        @method('post')
        <label for="admin-email-field">Email Address: </label>
        <input type="text" name="username" placeholder="Enter Admin Username/Email Address">
        <label for="admin-password-field">Password: </label>
        <input type="password" name="password" placeholder="Enter Admin Password">
        <label for="admin-confirm-password-field">Retype Password: </label>
        <input type="password" name="confirmpassword" placeholder="Enter Admin Password Again">
        <button type="submit" name="admin-login-btn">Login</button>
    </form>
</body>
@if($errors->any())
<script>alert('{{$errors->first()}}')</script>
@endif
</html>