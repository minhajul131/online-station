<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Station</title>
</head>
<body>
    <table>
        <tr><td>Dear {{ $name }}</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>Welcome to Online Station.</td></tr>
        <tr><td>Your new password is below..</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>Your Email: {{ $email }}</td></tr>
        <tr><td>&nbsp;</td></tr>        
        <tr><td>Your Password: {{ $password }}</td></tr>        
        <tr><td>&nbsp;</td></tr>
        <tr><td>Thanks & Regards,<br></td></tr>
        <tr><td>Online Station</td></tr>
    </table>
</body>
</html>