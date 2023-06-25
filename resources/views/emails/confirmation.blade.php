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
        <tr><td>Please click the below link to active your account.</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td><a href="{{ url('/user/confirm/'.$code) }}">Confirm account</a></td></tr>
        <tr><td>&nbsp;</td></tr>        
        <tr><td>&nbsp;</td></tr>
        <tr><td>Thanks & Regards,<br></td></tr>
        <tr><td>Online Station</td></tr>
    </table>
</body>
</html>