@extends('front.layout.layout')
@section('content')

<!-- Page Introduction Wrapper -->
<div class="page-style-a">
    <div class="container">
        <div class="page-intro">
            <h2>Account</h2>
            <ul class="bread-crumb">
                <li class="has-separator">
                    <i class="ion ion-md-home"></i>
                    <a href="index.html">Home</a>
                </li>
                <li class="is-marked">
                    <a href="account.html">Account</a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- Page Introduction Wrapper /- -->
<!-- Account-Page -->
<div class="page-account u-s-p-t-80">
    <div class="container">
        @if(Session::has('error_message'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error: </strong> {{ Session::get('error_message')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        @endif    
        @if(Session::has('success_message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success: </strong> {{ Session::get('success_message')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error: </strong> <?php echo implode('',$errors->all('<div>:message</div>')); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        @endif
        <div class="row">
            <!-- Forgot -->
            <div class="page-lost-password u-s-p-t-80">
                <div class="container">
                    <div class="page-lostpassword">
                        <p id="forgot-error"></p>
                        <p id="forgot-success"></p> 
                        <h2 class="account-h2 u-s-m-b-20">Forgot Password ?</h2>
                        <h6 class="account-h6 u-s-m-b-30">Enter your email or username below and we will send you a link to reset your password.</h6>
                        <form id="forgotForm" action="javascript:;" method="post">
                            @csrf
                            <div class="w-50">
                                <div class="u-s-m-b-13">
                                    <label for="user-name-email">Username or Email
                                        <span class="astk">*</span>
                                    </label>
                                    <input type="email" name="email" id="users-email" class="text-field" placeholder="Email">
                                    <p id="forgot-email"></p>
                                </div>
                                <div class="u-s-m-b-13">
                                    <button type="submit" class="button button-outline-secondary">Get Reset Link</button>
                                </div>
                            </div>
                            <div class="page-anchor">
                                <a href="{{ url('user/login-register') }}">
                                    <i class="fas fa-long-arrow-alt-left u-s-m-r-9"></i>Back to Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Forgot /- -->
        </div>
    </div>
</div>
<!-- Account-Page /- -->

@endsection