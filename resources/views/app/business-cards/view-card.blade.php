<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/Article">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />

    <title>{{ $card->name . ' - ' . $card->title }}</title>
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

<?php if ($card->avatar_file_name != null) { ?>
    <link rel="shortcut icon" type="image/x-icon" href="{{ $card->getFavicon() }}" />
<?php } elseif ($card->image_file_name != null) { ?>
    <link rel="icon" type="image/png" href="{{ url($card->image->url('favicon')) }}" />
<?php } else { ?>
    <link rel="shortcut icon" href="{{ url('favicon.ico') }}" type="image/x-icon" />
<?php } ?>

@include('layouts._general-content-includes')

    <meta name="description" content="{!! $description !!}">
<?php if (isset($color)) { ?>
    <meta name="theme-color" content="{{ $color }}">
<?php } ?>

    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $card->name . ' - ' . $card->title }}">
    <meta itemprop="description" content="{!! $description !!}">
<?php if ($card->avatar_file_name != null) { ?>
    <meta itemprop="image" content="{{ url($card->avatar->url('l')) }}">
<?php } ?>

    <!-- Twitter Card data -->
<?php if ($card->avatar_file_name != null) { ?>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image:src" content="{{ url($card->avatar->url('l')) }}">
<?php } else { ?>
    <meta name="twitter:card" content="summary">
<?php } ?>
    <meta name="twitter:title" content="{{ $card->name . ' - ' . $card->title }}">
    <meta name="twitter:description" content="{!! $description !!}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $card->name . ' - ' . $card->title }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->full() }}">
    <meta property="og:description" content="{!! $description !!}">
<?php if ($card->avatar_file_name != null) { ?>
    <meta property="og:image" content="{{ url($card->avatar->url('l')) }}">
<?php } ?>

<?php if ($card->lat != null && $card->lng != null) { ?>
    <meta property="place:location:latitude" content="{{ $card->lat }}">
    <meta property="place:location:longitude" content="{{ $card->lng }}">
    <meta name="geo.position" content="{{ $card->lat }}; {{ $card->lng }}">
<?php } ?>
<?php if ($card->city != null) { ?>
    <meta name="geo.placename" content="{{ $card->city }}">
<?php } ?>
<?php 
    if ($card->country != null || $card->state != null || $card->postal_code != null) { 
      $code = '';
      if ($card->country != null) $code .= $card->country;
      if ($card->state != null) $code .= ', ' . $card->state;
      if ($card->postal_code != null) $code .= ', ' . $card->postal_code;
?>
    <meta name="geo.region" content="{{ $code }}">
<?php } ?>
  </head>
  <body>
    <div class="container max-width-600">
      <div class="row">
        <div class="col-12">
          <div class="mt-md-5">
<?php if ($card->image_file_name != null) { ?>
            <img src="{{ url($card->image->url('2x')) }}" class="img-fluid rounded mt-3 mb-4 mdl-shadow--2dp" alt="<?php echo str_replace('"', '&quot;', $card->name . ' - ' . $card->title); ?>" style="min-width: 100%">
<?php } ?>
<?php if ($card->avatar_file_name != null) { ?>
            <div class="text-center">
              <img id="preview_avatar" src="{{ $card->avatar->url('l') }}" alt="<?php echo str_replace('"', '&quot;', $card->name . ' - ' . $card->title); ?>" class="img-thumbnail rounded-circle mx-auto mdl-shadow--4dp" style="width: 192px; height: 192px;<?php if ($card->image_file_name != null) echo 'margin-top:-124px'; ?>">
            </div>
<?php } ?>
            <h1 class="mb-0 mt-0 mb-md-2 text-center">{!! $card->name !!}</h1>
            <h5 class="mb-4 mt-0 text-muted text-center">{!! $card->title !!}</h5>
            <p class="lead text-center">{!! nl2br($card->details) !!}</p>

            <div class="row mt-0 d-print-none">
              <div class="col-12">
<?php
if ($card->address != null) {
  $link = 'https://maps.google.com/?saddr=My+Location&daddr=' . urlencode($card->address);
  if ($card->lat != null && $card->lng != null) $link = 'https://maps.google.com/?saddr=My+Location&daddr=' . $card->lat . ',' . $card->lng . '';
  //if ($card->lat != null && $card->lng != null) $link = 'https://maps.google.com/?ll=' . $card->lat . ',' . $card->lng . '';
?>              <p><i class="mi location_on d-print-none"></i> <a href="{{ $link }}" class="link" target="_blank">{{ $card->address }}</a></p>
<?php } ?>
              </div>
            </div>

            <div class="row mt-4 d-print-block d-none">
              <div class="col-12">
<?php
if ($card->address != null) {
  $link = 'https://maps.google.com/?saddr=My+Location&daddr=' . urlencode($card->address);
  if ($card->lat != null && $card->lng != null) $link = 'https://maps.google.com/?saddr=My+Location&daddr=' . $card->lat . ',' . $card->lng . '';
  //if ($card->lat != null && $card->lng != null) $link = 'https://maps.google.com/?ll=' . $card->lat . ',' . $card->lng . '';
?>              <p><img src="{{ url('assets/images/icons/baseline_location_on_black_18dp.png') }}" style="width: 18px; margin: 5px 5px 0 0"> <a href="{{ $link }}" class="link" target="_blank">{{ $card->address }}</a></p>
<?php } ?>
<?php if ($card->phone != null || $card->website != null) { ?>
<?php if ($card->phone != null) { ?>
                <p><img src="{{ url('assets/images/icons/baseline_phone_black_18dp.png') }}" style="width: 18px; margin: 5px 5px 0 0"> <a href="tel:{{ $card->phone }}" class="link">{!! $card->phone !!}</a></p>
<?php } ?>
<?php if ($card->website != null) {
  $website = (! starts_with($card->website, 'http')) ? 'http://' . $card->website: $card->website;
?>
                <p><img src="{{ url('assets/images/icons/baseline_info_black_18dp.png') }}" style="width: 18px; margin: 5px 5px 0 0"> <a href="{{ $website }}" class="link">{!! $card->website !!}</a></p>
<?php } ?>
<?php } ?>
              </div>
            </div>

            <div class="row mt-2 d-print-none">
              <div class="col-12">
                <a href="{{ url('card/download/' . $card_hash) }}" class="btn btn-{{ $card->primaryColor }} text-truncate btn-xlg text-truncate rounded-0 btn-block mb-4"><i class="mi contacts" style="top:3px; font-size: 26px"></i> {!! $card->primaryBtnText !!}</a>
              </div>
            </div>

            <div class="row d-print-none">
<?php if ($card->phone != null) { ?>
              <div class="col">
                <a href="tel:{{ $card->phone }}" class="btn btn-{{ $card->secondaryColor }} btn-lg text-truncate rounded-0 btn-block mb-4"><i class="mi phone"></i> {!! $card->callBtnText !!}</a>
              </div>
<?php } ?>
<?php
if ($card->website != null) {
  $website = (! starts_with($card->website, 'http')) ? 'http://' . $card->website: $card->website;
?>
              <div class="col">
                <a href="{{ $website }}" class="btn btn-{{ $card->secondaryColor }} btn-lg text-truncate rounded-0 btn-block mb-4" target="_blank"><i class="mi info_outline"></i> {!! $card->moreBtnText !!}</a>
              </div>
<?php } ?>
              <div class="col">
                <div class="dropdown">
                  <button class="btn btn-{{ $card->secondaryColor }} btn-lg text-truncate rounded-0 btn-block mb-4 dropdown-toggle" type="button" id="dropdownShare" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {!! $card->shareBtnText !!}
                  </button>
                  <div class="dropdown-menu rounded-0 dropdown-menu-right btn-block" aria-labelledby="dropdownShare">
                    <a class="dropdown-item" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->full()) }}" target="_blank"><span style="width:25px;float:left; text-align: center"><i class="fab fa-facebook" aria-hidden="true"></i></span> Facebook</a>
                    <a class="dropdown-item" href="https://plus.google.com/share?url={{ urlencode(url()->full()) }}" target="_blank"><span style="width:25px;float:left; text-align: center"><i class="fab fa-google" aria-hidden="true"></i></span> Google+</a>
                    <a class="dropdown-item" href="https://twitter.com/intent/tweet?url={{ urlencode(url()->full()) }}&text={{ urlencode($card->name . ', ' . $card->title . ' - ') }}" target="_blank"><span style="width:25px;float:left; text-align: center"><i class="fab fa-twitter" aria-hidden="true"></i></span> Twitter</a>
                    <a class="dropdown-item" href="mailto:?subject={{ urlencode($card->name . ' - ' . $card->title) }}&body={{ urlencode(url()->full()) }}" target="_blank"><span style="width:25px;float:left; text-align: center"><i class="far fa-envelope" aria-hidden="true"></i></span> Mail</a>
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