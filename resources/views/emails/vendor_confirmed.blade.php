<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Confirmation</title>
</head>
<body>
    <tr><td>Dear {{ $name }} !<br></td></tr>
    <tr><td>&nbsp;<br></td></tr>
    <tr><td>Login your Account and provide required information for next step<br></td></tr>
    <tr><td>Your Account Informations:<br></td></tr>
    <tr><td>&nbsp;<br></td></tr>
    <tr><td>Name: {{ $name }}<br></td></tr>
    <tr><td>&nbsp;<br></td></tr>
    <tr><td>Mobile: {{ $mobile }}<br></td></tr>
    <tr><td>&nbsp;<br></td></tr>
    <tr><td>Your Email: {{ $email }}<br></td></tr>
    <tr><td>&nbsp;<br></td></tr>
    <tr><td>Thanks & Regards,<br></td></tr>
    <tr><td>Online Station</td></tr>
</body>
</html>