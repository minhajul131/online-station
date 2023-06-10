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
    <tr><td>Please click on below link to confirm your Vendor Account : <br></td></tr>
    <tr><td><a href="{{ url('vendor/confirm/'.$code) }}">{{ url('vendor/confirm/'.$code) }}</a><br></td></tr>
    <tr><td>&nbsp;<br></td></tr>
    <tr><td>Thanks & Regards,<br></td></tr>
    <tr><td>Online Station</td></tr>
</body>
</html>