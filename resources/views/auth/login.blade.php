@extends('layouts.master')

@section('page_title')Nearby Platform - Login - {{ config('system.premium_name') }} @stop
@section('meta_description') Mobile and SEO optimized content for mobile devices. For Social Media, QR codes, NFC and proximity marketing. @stop

@section('content')

  <section>
    <div class="breadcrumbs breadcrumbs-arrow breadcrumbs-light mb-0" style="background-image:url()">
      <div class="breadcrumbs-overlay" style="background-color:#ddd">
        <div class="container">
          <div class="breadcrumbs-padding">
            <div class="row">
              <div class="col-12">

                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ url('') }}"><div>Home</div></a></li>
                  <li class="breadcrumb-item active"><a href="javascript:void(0);"><div>Login</div></a></li>
                </ol>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="header text-dark" style="background-image:url();">
      <div class="header-overlay" id="particles-js-bubble" style="background-color:rgba(245,249,250,1)">
        <div class="container">
          <div class="header-padding-l text-center no-padding-b no-padding-t">

            <div class="row">
              <div class="col-8 text-center text-lg-left col-sm-6 col-lg-4 mr-lg-0 order-1 ml-auto mr-auto">

<style type="text/css">
.device-wrapper {
  max-width: 300px;
  width: 100%; }

.device {
  position: relative;
  background-size: cover; }
  .device .screen {
    background-color: #fff;
    position: absolute;
    background-size: cover;
    overflow: hidden;
    /*pointer-events: none; */
  }
  .device .button {
    position: absolute;
    cursor: pointer; }

.device[data-device="Pixel"][data-orientation="portrait"][data-color="white"] {
  padding-bottom: 202.62009%;
  background-image: url({{ url('assets/images/devices/Pixel_portrait_white.png') }}); }
  .device[data-device="Pixel"][data-orientation="portrait"][data-color="white"] .screen {
    top: 9.69828%;
    left: 4.36681%;
    width: 90.39301%;
    height: 78.66379%; }
  .device[data-device="Pixel"][data-orientation="portrait"][data-color="white"] .button {
    top: 90.51724%;
    left: 44.97817%;
    width: 11.35371%;
    height: 4.31034%; }

.owl-dots {
  position: relative;
  top: -63px;
}
</style>

                <div class="device-wrapper d-none d-lg-block">
                  <div class="device" data-device="Pixel" data-orientation="portrait" data-color="white">
                    <div class="screen owl-carousel owl-theme">
                      <div class="item">
                        <img src="{{ url('assets/images/visuals/content-deal.png') }}" alt="Nearby Notification Deal" class="img-fluid">
                      </div>
                      <div class="item">
                        <img src="{{ url('assets/images/visuals/content-coupon.png') }}" alt="Nearby Notification Coupon" class="img-fluid">
                      </div>
                      <div class="item">
                        <img src="{{ url('assets/images/visuals/content-reward.png') }}" alt="Nearby Notification Reward" class="img-fluid">
                      </div>
                      <div class="item">
                        <img src="{{ url('assets/images/visuals/content-property.png') }}" alt="Nearby Notification Properties" class="img-fluid">
                      </div>
                      <div class="item">
                        <img src="{{ url('assets/images/visuals/content-business-card.png') }}" alt="Nearby Notification Business Card" class="img-fluid">
                      </div>
                      <div class="item">
                        <img src="{{ url('assets/images/visuals/content-video.png') }}" alt="Nearby Notification Video" class="img-fluid">
                      </div>
                      <div class="item">
                        <img src="{{ url('assets/images/visuals/content-page.png') }}" alt="Nearby Notification Page" class="img-fluid">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-lg-7 order-12 text-md-center text-lg-left">

                <h2 class="display-3 mt-0 mt-lg-4 mb-0 color-dark">Nearby Platform</h2>

                <h2 class="mb-5 mt-4">{!! trans('nearby-platform.customer_loyalty') !!}</h2>


                @if(session()->has('error'))
                    <div class="alert alert-danger mb-5 rounded-0">
                        {{ session()->get('error') }}
                    </div>
                @endif
<?php
if(env('APP_DEMO', false)) {
?>
                    <div class="alert alert-warning mb-5 rounded-0">
                        <div class="font-weight-bold">This demo is reset daily</div><br>Admin login is <strong>info@example.com</strong> / <strong>welcome</strong>
                    </div>
<?php
}
?>
                <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}

                    <div class="form-group row">
                        <label for="email" class="col-md-3 col-lg-4 col-form-label text-left text-md-right text-lg-left" style="font-size: 1.2rem">{{ trans('nearby-platform.email_address') }}</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control form-control-lg rounded-0" name="email" value="<?php echo old('email'); ?>" required autofocus>
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label for="password" class="col-md-3 col-lg-4 col-form-label text-left text-md-right text-lg-left" style="font-size: 1.2rem">{{ trans('nearby-platform.password') }}</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control form-control-lg rounded-0 mb-2" name="password" required>

                          <small class="float-right"><a href="{{ url(trans('website.page_prefix') . 'password/reset') }}" tabindex="-1" class="text-dark">{!! trans('nearby-platform.forgot_your_password') !!}</a></small>

                            @if ($errors->has('email'))
                                <span class="form-text text-danger">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif

                            @if ($errors->has('password'))
                                <span class="form-text text-danger">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif

                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6 offset-md-3 offset-lg-4">
                            <div class="custom-control custom-checkbox" style="margin:0 0 2px">
                              <input type="checkbox" class="custom-control-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> 
                              <label class="custom-control-label float-left" for="remember">{!! trans('nearby-platform.remember_me') !!}</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6 offset-md-3 offset-lg-4">
                            <button type="submit" class="btn rounded-0 btn-primary btn-block btn-lg ml-0">
                                {{ trans('nearby-platform.login') }}
                            </button>

                        </div>
                    </div>

                </form>


              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>

@endsection

@section('page_bottom')
<script>
$(function() {
  var owl = $('.owl-carousel');
  owl.owlCarousel({
    loop: true,
    autoplay: 4000,
    autoplaySpeed: 1000,
    margin: 200,
    dots: true,
    nav: false,
    navigation : false,
    slideSpeed : 300,
    paginationSpeed : 400,
    singleItem:true,
    items: 1
  });

  $('.owl-carousel .item').on('click',function() {
    //owl.trigger('stop.owl.autoplay');
  });
});
</script>
@endsection
