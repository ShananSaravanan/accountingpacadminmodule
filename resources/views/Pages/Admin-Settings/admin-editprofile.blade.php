<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Edit Profile</title>
</head>
<body>
    @include('Pages.templates.sidebar') 
    
    <table>
        <tr>
        <td>First Name</td>
        <td>Last Name</td>
        <td>Email Address</td>
        <td>Password</td>
        <td>Confirm Password</td>
        <td>Contact No</td>
        </tr>
        <form action="{{route('toUpdateProfile')}}" method="post">
        @csrf
        @method('post')
        <tr>
            <td><input type="text" name="fname" value={{$userData->FirstName}} required></td>
            <td><input type="text" name="lname" value={{$userData->LastName}} required></td>
            <td><input type="text" name="email" value={{$userData->email}} required></td>
            <td><input type="text" name="password" value={{$userData->password}} required></td>
            <td><input type="text" name="confirmpassword" value={{$userData->password}} required></td>
            <td><input type="text" name="contactNo" value={{$userData->contactNo}} required></td>
        </tr>
        <tr>
            <td><button onclick="window.location.href='{{route('toUpdateProfile')}}'" id="confirm-edit-btn" >Confirm Edit</button>
        </form>
            <button onclick="window.location.href='{{route('toprofile')}}'" id="cancel-edit-btn" >Cancel Edit</button></td>
           
        </tr>
    </table>
    
</body>

</html>