<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/Article">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />

    <title>{{ $deal->title }}</title>
<?php if (! $print) { ?>
    <link rel="canonical" href="{{ url()->full() }}">
<?php if ($ga_code != '') { ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga_code }}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '{{ $ga_code }}');
    </script>
<?php } ?>
<?php if ($fb_pixel != '') { ?>
<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '{{ $fb_pixel }}');
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id={{ $fb_pixel }}&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
<?php } ?>

<?php if ($deal->favicon != null) { ?>
    <link rel="shortcut icon" type="image/x-icon" href="{{ $deal->favicon }}" />
<?php } elseif ($deal->image_file_name != null) { ?>
    <link rel="icon" type="image/png" href="{{ url($deal->image->url('favicon')) }}" />
<?php } else { ?>
    <link rel="shortcut icon" href="{{ url('favicon.ico') }}" type="image/x-icon" />
<?php } ?>
<?php } // print ?>

@include('layouts._general-content-includes')

<?php if (! $print) { ?>
    <meta name="description" content="{!! $description !!}">
<?php if (isset($color)) { ?>
    <meta name="theme-color" content="{{ $color }}">
<?php } ?>

    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $deal->title }}">
    <meta itemprop="description" content="{!! $description !!}">
<?php if ($deal->image_file_name != null) { ?>
    <meta itemprop="image" content="{{ url($deal->image->url('4x')) }}">
<?php } ?>

    <!-- Twitter Card data -->
<?php if ($deal->image_file_name != null) { ?>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image:src" content="{{ url($deal->image->url('4x')) }}">
<?php } else { ?>
    <meta name="twitter:card" content="summary">
<?php } ?>
    <meta name="twitter:title" content="{{ $deal->title }}">
    <meta name="twitter:description" content="{!! $description !!}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $deal->title }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->full() }}">
    <meta property="og:description" content="{!! $description !!}">
<?php if ($deal->image_file_name != null) { ?>
    <meta property="og:image" content="{{ url($deal->image->url('4x')) }}">
<?php } ?>

<?php if ($deal->lat != null && $deal->lng != null) { ?>
    <meta property="place:location:latitude" content="{{ $deal->lat }}">
    <meta property="place:location:longitude" content="{{ $deal->lng }}">
    <meta name="geo.position" content="{{ $deal->lat }}; {{ $deal->lng }}">
<?php } ?>
<?php if ($deal->city != null) { ?>
    <meta name="geo.placename" content="{{ $deal->city }}">
<?php } ?>
<?php 
    if ($deal->country != null || $deal->state != null || $deal->postal_code != null) { 
      $code = '';
      if ($deal->country != null) $code .= $deal->country;
      if ($deal->state != null) $code .= ', ' . $deal->state;
      if ($deal->postal_code != null) $code .= ', ' . $deal->postal_code;
?>
    <meta name="geo.region" content="{{ $code }}">
<?php } ?>
<?php } // print ?>
  </head>
  <body>
    <div class="<?php if (! $print) { ?>container max-width-600<?php } ?>"<?php if ($print) { ?> style="width: 438px" <?php } ?>>
      <div class="row">
        <div class="col-12">
          <div class="mt-3 mt-md-5">
            <h1 class="mb-0 mb-md-2">{!! $deal->title !!}</h1>
<?php if ($deal->image_file_name != null) { ?>
<?php if (1==1 || $print) { ?>
            <img src="{{ url($deal->image->url('4x')) }}" class="img-fluid my-4 mdl-shadow--2dp" alt="<?php echo str_replace('"', '&quot;', $deal->title); ?>" style="min-width: 100%">
<?php } else { ?>
<?php
list($width, $height) = getimagesize(url($deal->image->url('4x')));
?>
            <amp-img src="{{ url($deal->image->url('4x')) }}" alt="<?php echo str_replace('"', '&quot;', $deal->title); ?>" class="img-fluid my-4 mdl-shadow--2dp" height="{{ $height }}" width="{{ $width }}" layout="responsive"></amp-img>
<?php } ?>
<?php } ?>

            <p class="lead">{!! $deal->details !!}</p>

            <div class="row mt-0 d-print-none">
              <div class="col-12">
<?php
if ($deal->address != null) {
  $link = 'https://maps.google.com/?saddr=My+Location&daddr=' . urlencode($deal->address);
  if ($deal->lat != null && $deal->lng != null) $link = 'https://maps.google.com/?saddr=My+Location&daddr=' . $deal->lat . ',' . $deal->lng . '';
  //if ($deal->lat != null && $deal->lng != null) $link = 'https://maps.google.com/?ll=' . $deal->lat . ',' . $deal->lng . '';
?>              <p><i class="mi location_on d-print-none"></i> <a href="{{ $link }}" class="link" target="_blank">{{ $deal->address }}</a></p>
<?php } ?>
              </div>
            </div>

            <div class="row mt-4 d-print-block d-none">
              <div class="col-12">
<?php
if ($deal->address != null) {
  $link = 'https://maps.google.com/?saddr=My+Location&daddr=' . urlencode($deal->address);
  if ($deal->lat != null && $deal->lng != null) $link = 'https://maps.google.com/?saddr=My+Location&daddr=' . $deal->lat . ',' . $deal->lng . '';
  //if ($deal->lat != null && $deal->lng != null) $link = 'https://maps.google.com/?ll=' . $deal->lat . ',' . $deal->lng . '';
?>              <p><img src="{{ url('assets/images/icons/baseline_location_on_black_18dp.png') }}" style="width: 18px; margin: 5px 5px 0 0"> <a href="{{ $link }}" class="link" target="_blank">{{ $deal->address }}</a></p>
<?php } ?>
<?php if ($deal->phone != null || $deal->website != null) { ?>
<?php if ($deal->phone != null) { ?>
                <p><img src="{{ url('assets/images/icons/baseline_phone_black_18dp.png') }}" style="width: 18px; margin: 5px 5px 0 0"> <a href="tel:{{ $deal->phone }}" class="link">{!! $deal->phone !!}</a></p>
<?php } ?>
<?php if ($deal->website != null) {
  $website = (! starts_with($deal->website, 'http')) ? 'http://' . $deal->website: $deal->website;
?>
                <p><img src="{{ url('assets/images/icons/baseline_info_black_18dp.png') }}" style="width: 18px; margin: 5px 5px 0 0"> <a href="{{ $website }}" class="link">{!! $deal->website !!}</a></p>
<?php } ?>
<?php } ?>
              </div>
            </div>

<?php
if ($deal->expiration_date_time != null) {
  $expires = $deal->expiration_date_time->formatLocalized('Expires %A, %B ' . App\Http\Controllers\DealsController::ordinal($deal->expiration_date_time->day) . ' %Y at %I:%M %p');
} elseif ($deal->expiration_date != null) {
  $expires = $deal->expiration_date->formatLocalized('Expires %A, %B ' . App\Http\Controllers\DealsController::ordinal($deal->expiration_date->day) . ' %Y');
} else {
  $expires = null;
}

if ($expires != null) { ?>
            <small class="text-muted">{{ $expires }}</small>
<?php } ?>

            <div class="row mt-2 d-print-none">
              <div class="col-12">
                <a href="{{ url('deal/download/' . $deal_hash) }}" class="btn btn-{{ $deal->primaryColor }} btn-xlg text-truncate rounded-0 btn-block mb-4"><i class="mi file_download" style="top:4px; font-size: 24px"></i> {!! $deal->primaryBtnText !!}</a>
              </div>
            </div>

            <div class="row d-print-none">
<?php if ($deal->phone != null) { ?>
              <div class="col">
                <a href="tel:{{ $deal->phone }}" class="btn btn-{{ $deal->secondaryColor }} btn-lg rounded-0 btn-block mb-4"><i class="mi phone"></i> {!! $deal->callBtnText !!}</a>
              </div>
<?php } ?>
<?php
if ($deal->website != null) {
  $website = (! starts_with($deal->website, 'http')) ? 'http://' . $deal->website: $deal->website;
?>
              <div class="col">
                <a href="{{ $website }}" class="btn btn-{{ $deal->secondaryColor }} btn-lg rounded-0 btn-block mb-4" target="_blank"><i class="mi info_outline"></i> {!! $deal->moreBtnText !!}</a>
              </div>
<?php } ?>
              <div class="col">
                <div class="dropdown">
                  <button class="btn btn-{{ $deal->secondaryColor }} btn-lg rounded-0 btn-block mb-4 dropdown-toggle" type="button" id="dropdownShare" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {!! $deal->shareBtnText !!}
                  </button>
                  <div class="dropdown-menu rounded-0 dropdown-menu-right btn-block" aria-labelledby="dropdownShare">
                    <a class="dropdown-item" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->full()) }}" target="_blank"><span style="width:25px;float:left; text-align: center"><i class="fab fa-facebook" aria-hidden="true"></i></span> Facebook</a>
                    <a class="dropdown-item" href="https://plus.google.com/share?url={{ urlencode(url()->full()) }}" target="_blank"><span style="width:25px;float:left; text-align: center"><i class="fab fa-google" aria-hidden="true"></i></span> Google+</a>
                    <a class="dropdown-item" href="https://twitter.com/intent/tweet?url={{ urlencode(url()->full()) }}&text={{ urlencode($deal->title . ' - ') }}" target="_blank"><span style="width:25px;float:left; text-align: center"><i class="fab fa-twitter" aria-hidden="true"></i></span> Twitter</a>
                    <a class="dropdown-item" href="mailto:?subject={{ urlencode($deal->title) }}&body={{ urlencode(url()->full()) }}" target="_blank"><span style="width:25px;float:left; text-align: center"><i class="far fa-envelope" aria-hidden="true"></i></span> Mail</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

@include('layouts._general-content-includes-footer')

  </body>
</html>