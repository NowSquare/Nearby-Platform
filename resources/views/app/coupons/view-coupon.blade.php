<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/Article">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />

    <title>{{ $coupon->title }}</title>
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

<?php if ($coupon->favicon != null) { ?>
    <link rel="shortcut icon" type="image/x-icon" href="{{ $coupon->favicon }}" />
<?php } elseif ($coupon->image_file_name != null) { ?>
    <link rel="icon" type="image/png" href="{{ url($coupon->image->url('favicon')) }}" />
<?php } else { ?>
    <link rel="shortcut icon" href="{{ url('favicon.ico') }}" type="image/x-icon" />
<?php } ?>

@include('layouts._general-content-includes')

    <meta name="description" content="{!! $description !!}">
<?php if (isset($color)) { ?>
    <meta name="theme-color" content="{{ $color }}">
<?php } ?>

    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $coupon->title }}">
    <meta itemprop="description" content="{!! $description !!}">
<?php if ($coupon->image_file_name != null) { ?>
    <meta itemprop="image" content="{{ url($coupon->image->url('4x')) }}">
<?php } ?>

    <!-- Twitter Card data -->
<?php if ($coupon->image_file_name != null) { ?>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image:src" content="{{ url($coupon->image->url('4x')) }}">
<?php } else { ?>
    <meta name="twitter:card" content="summary">
<?php } ?>
    <meta name="twitter:title" content="{{ $coupon->title }}">
    <meta name="twitter:description" content="{!! $description !!}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $coupon->title }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->full() }}">
    <meta property="og:description" content="{!! $description !!}">
<?php if ($coupon->image_file_name != null) { ?>
    <meta property="og:image" content="{{ url($coupon->image->url('4x')) }}">
<?php } ?>

<?php if ($coupon->lat != null && $coupon->lng != null) { ?>
    <meta property="place:location:latitude" content="{{ $coupon->lat }}">
    <meta property="place:location:longitude" content="{{ $coupon->lng }}">
    <meta name="geo.position" content="{{ $coupon->lat }}; {{ $coupon->lng }}">
<?php } ?>
<?php if ($coupon->city != null) { ?>
    <meta name="geo.placename" content="{{ $coupon->city }}">
<?php } ?>
<?php 
    if ($coupon->country != null || $coupon->state != null || $coupon->postal_code != null) { 
      $code = '';
      if ($coupon->country != null) $code .= $coupon->country;
      if ($coupon->state != null) $code .= ', ' . $coupon->state;
      if ($coupon->postal_code != null) $code .= ', ' . $coupon->postal_code;
?>
    <meta name="geo.region" content="{{ $code }}">
<?php } ?>
  </head>
  <body>
    <div class="container max-width-600">
      <div class="row">
        <div class="col-12">
          <div class="mt-3 mt-md-5">
            <h1 class="mb-0 mb-md-2">{!! $coupon->title !!}</h1>
<?php if ($coupon->image_file_name != null) { ?>
            <img src="{{ url($coupon->image->url('4x')) }}" class="img-fluid mt-3 mb-4 mdl-shadow--2dp" alt="<?php echo str_replace('"', '&quot;', $coupon->title); ?>" style="min-width: 100%">
<?php } ?>

            <p class="lead">{!! nl2br($coupon->details) !!}</p>

            <div class="row mt-0 d-print-none">
              <div class="col-12">
<?php
if ($coupon->address != null) {
  $link = 'https://maps.google.com/?saddr=My+Location&daddr=' . urlencode($coupon->address);
  if ($coupon->lat != null && $coupon->lng != null) $link = 'https://maps.google.com/?saddr=My+Location&daddr=' . $coupon->lat . ',' . $coupon->lng . '';
  //if ($coupon->lat != null && $coupon->lng != null) $link = 'https://maps.google.com/?ll=' . $coupon->lat . ',' . $coupon->lng . '';
?>              <p><i class="mi location_on d-print-none"></i> <a href="{{ $link }}" class="link" target="_blank">{{ $coupon->address }}</a></p>
<?php } ?>
              </div>
            </div>

            <div class="row mt-4 d-print-block d-none">
              <div class="col-12">
<?php
if ($coupon->address != null) {
  $link = 'https://maps.google.com/?saddr=My+Location&daddr=' . urlencode($coupon->address);
  if ($coupon->lat != null && $coupon->lng != null) $link = 'https://maps.google.com/?saddr=My+Location&daddr=' . $coupon->lat . ',' . $coupon->lng . '';
  //if ($coupon->lat != null && $coupon->lng != null) $link = 'https://maps.google.com/?ll=' . $coupon->lat . ',' . $coupon->lng . '';
?>              <p><img src="{{ url('assets/images/icons/baseline_location_on_black_18dp.png') }}" style="width: 18px; margin: 5px 5px 0 0"> <a href="{{ $link }}" class="link" target="_blank">{{ $coupon->address }}</a></p>
<?php } ?>
<?php if ($coupon->phone != null || $coupon->website != null) { ?>
<?php if ($coupon->phone != null) { ?>
                <p><img src="{{ url('assets/images/icons/baseline_phone_black_18dp.png') }}" style="width: 18px; margin: 5px 5px 0 0"> <a href="tel:{{ $coupon->phone }}" class="link">{!! $coupon->phone !!}</a></p>
<?php } ?>
<?php if ($coupon->website != null) {
  $website = (! starts_with($coupon->website, 'http')) ? 'http://' . $coupon->website: $coupon->website;
?>
                <p><img src="{{ url('assets/images/icons/baseline_info_black_18dp.png') }}" style="width: 18px; margin: 5px 5px 0 0"> <a href="{{ $website }}" class="link">{!! $coupon->website !!}</a></p>
<?php } ?>
<?php } ?>
              </div>
            </div>

<?php
if ($coupon->expiration_date_time != null) {
  $expires = $coupon->expiration_date_time->formatLocalized('Expires %A, %B ' . App\Http\Controllers\CouponsController::ordinal($coupon->expiration_date_time->day) . ' %Y at %I:%M %p');
} elseif ($coupon->expiration_date != null) {
  $expires = $coupon->expiration_date->formatLocalized('Expires %A, %B ' . App\Http\Controllers\CouponsController::ordinal($coupon->expiration_date->day) . ' %Y');
} else {
  $expires = null;
}

if ($expires != null) { ?>
            <small class="text-muted">{{ $expires }}</small>
<?php } ?>

<?php if (! $redeemed) { ?>
            <div class="row mt-2 d-print-none">
              <div class="col-12">
                <a href="javascript:void(0);" data-toggle="modal" data-target="#redeemModal" class="btn btn-{{ $coupon->primaryColor }} btn-xlg text-truncate rounded-0 btn-block mb-4"><i class="mi redeem" style="top:2px; font-size: 24px"></i> {!! $coupon->primaryBtnText !!}</a>
              </div>
            </div>
<?php } ?>

<?php if ($redeemed) { ?>
            <div class="row mt-2 d-print-none">
              <div class="col-12 text-center">
                <i class="mi text-success done my-5" style="font-size: 7rem"></i>
              </div>
            </div>
<?php } ?>

            <div class="row d-print-none">
<?php if ($coupon->phone != null) { ?>
              <div class="col">
                <a href="tel:{{ $coupon->phone }}" class="btn btn-{{ $coupon->secondaryColor }} btn-lg text-truncate rounded-0 btn-block mb-4"><i class="mi phone"></i> {!! $coupon->callBtnText !!}</a>
              </div>
<?php } ?>
<?php
if ($coupon->website != null) {
  $website = (! starts_with($coupon->website, 'http')) ? 'http://' . $coupon->website: $coupon->website;
?>
              <div class="col">
                <a href="{{ $website }}" class="btn btn-{{ $coupon->secondaryColor }} btn-lg text-truncate rounded-0 btn-block mb-4" target="_blank"><i class="mi info_outline"></i> {!! $coupon->moreBtnText !!}</a>
              </div>
<?php } ?>
              <div class="col">
                <div class="dropdown">
                  <button class="btn btn-{{ $coupon->secondaryColor }} btn-lg text-truncate rounded-0 btn-block mb-4 dropdown-toggle" type="button" id="dropdownShare" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {!! $coupon->shareBtnText !!}
                  </button>
                  <div class="dropdown-menu rounded-0 dropdown-menu-right btn-block" aria-labelledby="dropdownShare">
                    <a class="dropdown-item" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->full()) }}" target="_blank"><span style="width:25px;float:left; text-align: center"><i class="fab fa-facebook" aria-hidden="true"></i></span> Facebook</a>
                    <a class="dropdown-item" href="https://plus.google.com/share?url={{ urlencode(url()->full()) }}" target="_blank"><span style="width:25px;float:left; text-align: center"><i class="fab fa-google" aria-hidden="true"></i></span> Google+</a>
                    <a class="dropdown-item" href="https://twitter.com/intent/tweet?url={{ urlencode(url()->full()) }}&text={{ urlencode($coupon->title . ' - ') }}" target="_blank"><span style="width:25px;float:left; text-align: center"><i class="fab fa-twitter" aria-hidden="true"></i></span> Twitter</a>
                    <a class="dropdown-item" href="mailto:?subject={{ urlencode($coupon->title) }}&body={{ urlencode(url()->full()) }}" target="_blank"><span style="width:25px;float:left; text-align: center"><i class="far fa-envelope" aria-hidden="true"></i></span> Mail</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php if (! $redeemed) { ?>
    <div class="modal fade" id="redeemModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog mt-5" role="document">
        <div class="modal-content rounded-0 mdl-shadow--8dp border border-white">

          <form class="form-horizontal" method="POST" action="{{ url('coupon/redeem/' . $coupon_hash) }}" autocomplete="off">

          <div class="modal-header border-0 p-3">
            <h5 class="modal-title">{!! $coupon->title !!}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body px-3 py-0 border-0">
<?php
$aFields = \App\Http\Controllers\CouponsController::requiredFieldList();

if (isset($coupon->fields) && is_array($coupon->fields)) {
  foreach ($coupon->fields as $id => $saved_field) {

    // Check if id exists
    foreach ($aFields as $i => $required_field) {
      if ($id == $required_field['id']) {
        $name = ($saved_field['name'] != null && $saved_field['name'] != '') ? $saved_field['name'] : $aFields[$i]['placeholder'];
        $checked = ($saved_field['required'] == 1) ? true : false;
        $aFields[$i]['name'] = $name;
        $aFields[$i]['checked'] = $checked;
      }
    }
  }
}
?>
<?php
$first_field = '';
foreach ($aFields as $i => $field) {
  if ($field['checked']) {
    if ($i == 0) $first_field = $field['id'];
?>
                    <div class="form-group row">
                      <label for="field_{{ $field['id'] }}" class="col-12 col-form-label">{{ $field['name'] }}</label>
                      <div class="col-12">
                        <input id="field_{{ $field['id'] }}" type="{{ $field['type'] }}" maxlength="{{ $field['maxlength'] }}" class="form-control rounded-0 form-control-lg" name="{{ $field['id'] }}" value="{{ old('' . $field['id'] . '', '') }}" autocomplete="off" placeholder="" required>
                      </div>
                    </div>
<?php
  }
}
?>
          </div>
          <div class="modal-footer border-0 p-3">
            <button type="submit" class="btn rounded-0 btn-{{ $coupon->primaryColor }} btn-xlg btn-block text-truncate">{!! $coupon->primaryBtnText !!}</button>
          </div>
          </form>
        </div>
      </div>
    </div>
<?php } ?>

@include('layouts._general-content-includes-footer')

<script type="text/javascript">
<?php if (isset($first_field) && $first_field != '') { ?>
$('#redeemModal').on('shown.bs.modal', function () {
  $('#field_{{ $first_field }}').trigger('focus')
})
<?php } ?>
</script>

  </body>
</html>