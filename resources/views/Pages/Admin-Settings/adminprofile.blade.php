<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Profile</title>
</head>
<body>
    @include('Pages.templates.sidebar') 
    <table>
        <tr>
        <td>First Name</td>
        <td>Last Name</td>
        <td>Email Address</td>
        <td>Password</td>
        <td>Contact No</td>
        </tr>
        <tr>
            <td><input type="text"  value={{$userData->FirstName}} readonly></td>
            <td><input type="text"  value={{$userData->LastName}} readonly></td>
            <td><input type="text"  value={{$userData->email}} readonly></td>
            <td><input type="text"  value={{$userData->password}} readonly></td>
            <td><input type="text"  value={{$userData->contactNo}} readonly></td>
        </tr>
        <tr>
            <td><button onclick="window.location.href='{{route('toEditProfile')}}'" id="edit-btn" >Edit</button></td>
        </tr>
    </table>
</body>

</html>