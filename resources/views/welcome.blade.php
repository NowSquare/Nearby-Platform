<?php
$subnav = false;
?><!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>{{ trans('website.page_title') }}</title>
        <meta name="description" content="{{ trans('website.page_desc') }}">

        <link rel="shortcut icon" href="{{ url('assets/images/branding/favicon.ico') }}" type="image/x-icon" />
        <link rel="icon" type="image/png" href="{{ url('assets/images/branding/favicon-32x32.png') }}" sizes="32x32" />
        <link rel="icon" type="image/png" href="{{ url('assets/images/branding/favicon-16x16.png') }}" sizes="16x16" />

        <meta name="theme-color" content="#146eff">

@include('layouts._general-site-includes')

<style type="text/css">
  body {
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center center;
    max-height: 1100px;
  }

  @media only screen and (max-width: 900px) {
    body {
      background-position: left center;
    }
  } 

  @media only screen and (min-width: 1100px) {
    body {
      max-height: 820px;
    }
  } 

  @media only screen and (min-width: 2100px) {
    body {
      max-height: 520px;
    }
  } 
  .transparent-navbar .nav-item:hover>a,
  .transparent-navbar .navbar {
    background-color: transparent !important;
  }
  .transparent-navbar .navbar.navbar-opened.transparent {
    background-color: #146eff !important;
  }
  
  .transparent-navbar .navbar-shadow {
    background-color: #fff !important;
  }

  .transparent-navbar .navbar:not(.navbar-shadow) .nav-link,
  .transparent-navbar .navbar:not(.navbar-shadow) .navbar-toggler-icon {
    color: #fff !important;
  }
</style>
    </head>
  <body id="home" class="transparent-navbar" style="background-image:url({{ url('assets/images/backgrounds/top.png') }});">

  @include('layouts._navbar')
    <section>
      <div class="header text-light">
        <div class="header-overlay" ids="particles-js-nasa" style="background-color:rgba(245,249,250,0)">
          <div class="container">
            <div class="header-padding-no text-center mt-0 mt-md-5 pb-5 pb-lg-5 no-padding-b">

              <div class="item">
                <div class="row">
                  <div class="col-12 col-lg-6 text-md-center text-lg-left">
                    <div class="mt-5 mt-md-4 mt-lg-5 pt-5 pt-md-2">
                      <div class="row">
                        <h1 class="display-4 color-light mt-lg-5 mt-5 col-12 col-lg-11">Customer Loyalty <small>with the</small> Nearby Platform</h1>
                      </div>
                      <p class="lead mb-5">Mobile optimized content for Social Media, QR codes, NFC and proximity marketing.</p>

                    </div>
                    <div class="btn-container btn-stack-md">
                      <a class="btn btn-light btn-xlg rounded-0 ml-0" href="{{ url(trans('website.page_prefix') . 'login') }}" role="button">Start</a>
                      <a class="btn btn-outline-light btn-xlg rounded-0" href="#about" role="button">About</a>
                    </div>
                  </div>
                  <div class="col-11 text-center text-lg-left col-sm-8 col-lg-6 ml-auto mr-auto pt-0 pt-lg-5">

                    <div class="owl-carousel owl-theme max-width-500 mt-5 mt-lg-5">
                      <div class="item">
                        <a href="{{ url(trans('website.page_prefix') . 'login') }}"><img src="{{ url('assets/images/slider/nearby-platform.png') }}" alt="Nearby Platform" class="img-fluid"></a>
                      </div>
                    </div>

                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="mt-0 mt-lg-5 mb-4" id="about_">

      <div class="content content-padding-xl" style="background-image: url('{{ url('assets/images/backgrounds/waves.jpg') }}');">
        <div class="content-overlay" style="background-color:rgba(255,255,255,0.8)">
          <div class="owl-carousel owl-theme">
<?php
$content_items = [];

$content_items[] = [
'image' => 'content-deal.png',
'icon' => 'store',
'title' => trans('website.deals'),
'desc' => trans('website.deals_desc')
];

$content_items[] = [
'image' => 'content-coupon.png',
'icon' => 'redeem',
'title' => trans('website.coupons'),
'desc' => trans('website.coupons_desc')
];

$content_items[] = [
'image' => 'content-reward.png',
'icon' => 'beenhere',
'title' => trans('website.rewards'),
'desc' => trans('website.rewards_desc')
];

$content_items[] = [
'image' => 'content-property.png',
'icon' => 'fas fa-home',
'title' => trans('website.properties'),
'desc' => trans('website.properties_desc')
];

$content_items[] = [
'image' => 'content-business-card.png',
'icon' => 'account_circle',
'title' => trans('website.business_cards'),
'desc' => trans('website.business_cards_desc')
];

$content_items[] = [
'image' => 'content-video.png',
'icon' => 'video_library',
'title' => trans('website.videos'),
'desc' => trans('website.videos_desc')
];

$content_items[] = [
'image' => 'content-page.png',
'icon' => 'insert_drive_file',
'title' => trans('website.pages'),
'desc' => trans('website.pages_desc')
];

foreach ($content_items as $content_item) {
?>
          <div class="item">

            <div class="container">
              <div class="row">
                <div class="col-md-6 text-center text-md-right d-flex justify-content-center justify-content-md-end order-md-1 order-12">
                  <div class="max-width-400">
                    <img src="{{ url('assets/images/visuals/' . $content_item['image']) }}" alt="Nearby Notification {!! $content_item['title'] !!}" class="img-fluid mdl-shadow--4dp bg-white my-2">
                  </div>
                </div>
                <div class="col-md-5 text-center text-md-left order-md-12 order-1">
                  <div class="content-padding">
                    <h2 class="mt-2 mt-lg-2 font-weight-bold color-primary"><i class="mi {{ $content_item['icon'] }}" style="font-size: 1.7rem;"></i> {!! $content_item['title'] !!}</h2>
                    <p class="lead my-4">{!! $content_item['desc'] !!}</p>
                    <div class="btn-container">
                      <a class="btn btn-outline-primary btn-xlg rounded-0" href="{{ url('login') }}" role="button">{!! trans('website.cm_cta') !!}</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
<?php } ?>
          </div>
        </div>
      </div>
    </section>

  @include('layouts._footer')

  @include('layouts._general-site-includes-footer')

<script>
var top_logo = new Image()
top_logo.src = '{{ url('assets/images/branding/logo-horizontal-dark.svg') }}';

$('.navbar-logo img').attr('src', '{{ url('assets/images/branding/logo-horizontal-light.svg') }}');
$('.navbar .d-none .btn').removeClass('btn-outline-primary').addClass('btn-outline-light');

$(window).scroll(listenToScroll);

function listenToScroll() {
  var el = $('.navbar');
  var scroll = $(window).scrollTop();

  if (scroll >= 80) {
    $('.navbar-logo img').attr('src', '{{ url('assets/images/branding/logo-horizontal-dark.svg') }}');
    $('.navbar .d-none .btn').removeClass('btn-outline-light').addClass('btn-outline-primary');
  } else {
    $('.navbar-logo img').attr('src', '{{ url('assets/images/branding/logo-horizontal-light.svg') }}');
    $('.navbar .d-none .btn').removeClass('btn-outline-primary').addClass('btn-outline-light');
  }
}
</script>

<script>
$(function() {
  var owl = $('.owl-carousel');

  owl.on({
    'initialized.owl.carousel': function () {
       owl.find('.item').show();
    }
  }).owlCarousel({
    loop: true,
    autoplay: true,
    autoplayTimeout: 5000,
    autoplaySpeed: 1500,
    margin: 50,
    dots: true,
    nav: false,
    navigation : false,
    slideSpeed : 300,
    paginationSpeed : 400,
    singleItem:true,
    items: 1
  });

  $('.owl-carousel .item').on('click',function() {
    owl.trigger('stop.owl.autoplay');
  });

  var owl = $('.owl-carousel-content');

  owl.on({
    'initialized.owl.carousel': function () {
       owl.find('.item').show();
    }
  }).owlCarousel({
    loop: true,
    autoplay: true,
    autoplayTimeout: 5000,
    autoplaySpeed: 1500,
    margin: 0,
    dots: true,
    nav: false,
    navigation : false,
    slideSpeed : 300,
    paginationSpeed : 400,
    singleItem:true,
    items: 1
  });

  $('.owl-carousel .item').on('click',function() {
    owl.trigger('stop.owl.autoplay');
  });
});
</script>
    </body>
</html>
