@extends('front.layout.layout')
@section('content')

<!-- Page Introduction Wrapper -->
<div class="page-style-a">
    <div class="container">
        <div class="page-intro">
            <h2>Contact</h2>
            <ul class="bread-crumb">
                <li class="has-separator">
                    <i class="ion ion-md-home"></i>
                    <a href="{{ url('/') }}">Home</a>
                </li>
                <li class="is-marked">
                    <a href="{{ url('contact') }}">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- Page Introduction Wrapper /- -->
<!-- Contact-Page -->
<div class="page-contact u-s-p-t-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="touch-wrapper">
                    <h1 class="contact-h1">Get In Touch With Us</h1>
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
                        <div class= "alert alert-danger alert-dismissible fade show" role="alert">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    @endif
                    <form action="{{ url('contact') }}" method="post">
                        @csrf
                        <div class="group-inline u-s-m-b-30">
                            <div class="group-1 u-s-p-r-16">
                                <label for="contact-name">Your Name
                                    <span class="astk">*</span>
                                </label>
                                <input type="text" name="name" id="contact-name" class="text-field" placeholder="Name" required>
                            </div>
                            <div class="group-2">
                                <label for="contact-email">Your Email
                                    <span class="astk">*</span>
                                </label>
                                <input type="email" name="email" id="contact-email" class="text-field" placeholder="Email" required>
                            </div>
                        </div>
                        <div class="u-s-m-b-30">
                            <label for="contact-subject">Subject
                                <span class="astk">*</span>
                            </label>
                            <input type="text" id="contact-subject" name="subject" class="text-field" placeholder="Subject" required>
                        </div>
                        <div class="u-s-m-b-30">
                            <label for="contact-message">Message:</label>
                            <span class="astk">*</span>
                            <textarea class="text-area" name="message" id="contact-message"></textarea required>
                        </div>
                        <div class="u-s-m-b-30">
                            <button type="submit" class="button button-outline-secondary">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="information-about-wrapper">
                    <h1 class="contact-h1">Information About Us</h1>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, tempora, voluptate. Architecto aspernatur, culpa cupiditate deserunt dolore eos facere in, incidunt omnis quae quam quos, similique sunt tempore vel vero.
                    </p>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, tempora, voluptate. Architecto aspernatur, culpa cupiditate deserunt dolore eos facere in, incidunt omnis quae quam quos, similique sunt tempore vel vero.
                    </p>
                </div>
                <div class="contact-us-wrapper">
                    <h1 class="contact-h1">Contact Us</h1>
                    <div class="contact-material u-s-m-b-16">
                        <h6>Location</h6>
                        <span>Dhanmondi</span>
                        <span>Dhaka</span>
                    </div>
                    <div class="contact-material u-s-m-b-16">
                        <h6>Email</h6>
                        <span>info@abc.com</span>
                    </div>
                    <div class="contact-material u-s-m-b-16">
                        <h6>Telephone</h6>
                        <span>+111-222-333</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contact-Page /- -->

@endsection