<!DOCTYPE html>
<html class="no-js" lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Online Station</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('front/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('front/css/bundle.css') }}">
</head>
<body>
<!-- app -->
<div id="app">
    <!-- Checkout-Confirmation-Page -->
    <div class="page-checkout-confirm">
        <div class="vertical-center">
            <div class="text-center">
                <h1>Thank you!</h1>
                <h4>Your Order Has Been Placed Successfully..
                    Your Order number is {{ Session::get('order_id') }}  & Total amount: ৳  {{ Session::get('grand_total') }}.</h4><br>
                <a href="{{ url('/') }}" class="thank-you-back">Back to homepage</a>
            </div>
        </div>
    </div>
    <!-- Checkout-Confirmation-Page /- -->
</div>
</body>
</html>
