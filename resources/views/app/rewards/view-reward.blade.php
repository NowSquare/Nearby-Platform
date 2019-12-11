<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/Article">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />

    <title>{{ $reward->title }}</title>
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

<?php if ($reward->favicon != null) { ?>
    <link rel="shortcut icon" type="image/x-icon" href="{{ $reward->favicon }}" />
<?php } elseif ($reward->image_file_name != null) { ?>
    <link rel="icon" type="image/png" href="{{ url($reward->image->url('favicon')) }}" />
<?php } else { ?>
    <link rel="shortcut icon" href="{{ url('favicon.ico') }}" type="image/x-icon" />
<?php } ?>

@include('layouts._general-content-includes')

    <meta name="description" content="{!! $description !!}">
<?php if (isset($color)) { ?>
    <meta name="theme-color" content="{{ $color }}">
<?php } ?>

    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $reward->title }}">
    <meta itemprop="description" content="{!! $description !!}">
<?php if ($reward->image_file_name != null) { ?>
    <meta itemprop="image" content="{{ url($reward->image->url('4x')) }}">
<?php } ?>

    <!-- Twitter Card data -->
<?php if ($reward->image_file_name != null) { ?>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image:src" content="{{ url($reward->image->url('4x')) }}">
<?php } else { ?>
    <meta name="twitter:card" content="summary">
<?php } ?>
    <meta name="twitter:title" content="{{ $reward->title }}">
    <meta name="twitter:description" content="{!! $description !!}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $reward->title }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->full() }}">
    <meta property="og:description" content="{!! $description !!}">
<?php if ($reward->image_file_name != null) { ?>
    <meta property="og:image" content="{{ url($reward->image->url('4x')) }}">
<?php } ?>

<?php if ($reward->lat != null && $reward->lng != null) { ?>
    <meta property="place:location:latitude" content="{{ $reward->lat }}">
    <meta property="place:location:longitude" content="{{ $reward->lng }}">
    <meta name="geo.position" content="{{ $reward->lat }}; {{ $reward->lng }}">
<?php } ?>
<?php if ($reward->city != null) { ?>
    <meta name="geo.placename" content="{{ $reward->city }}">
<?php } ?>
<?php 
    if ($reward->country != null || $reward->state != null || $reward->postal_code != null) { 
      $code = '';
      if ($reward->country != null) $code .= $reward->country;
      if ($reward->state != null) $code .= ', ' . $reward->state;
      if ($reward->postal_code != null) $code .= ', ' . $reward->postal_code;
?>
    <meta name="geo.region" content="{{ $code }}">
<?php } ?>

    <style type="text/css">
      .timeline-label div.day {
        font-weight: bold;
        margin-top: 10px;
        font-size: 90%;
      }
      .timeline-label div.time {
        font-size: 80%;
      }
      .timeline-centered {
        position: relative;
        margin-bottom: 30px;
      }
      .timeline-centered:before, .timeline-centered:after {
        content: " ";
        display: table;
      }
      .timeline-centered:after {
        clear: both;
      }
      .timeline-centered:before, .timeline-centered:after {
        content: " ";
        display: table;
      }
      .timeline-centered:after {
        clear: both;
      }
      .timeline-centered:before {
        content: '';
        position: absolute;
        display: block;
        width: 4px;
        background: #eeeeee;
        left: 50%;
        top: 20px;
        bottom: 20px;
        margin-left: -4px;
      }
      .timeline-centered .timeline-entry {
        position: relative;
        width: 50%;
        float: right;
        margin-bottom: 50px;
        clear: both;
      }
      .timeline-centered .timeline-entry:before, .timeline-centered .timeline-entry:after {
        content: " ";
        display: table;
      }
      .timeline-centered .timeline-entry:after {
        clear: both;
      }
      .timeline-centered .timeline-entry:before, .timeline-centered .timeline-entry:after {
        content: " ";
        display: table;
      }
      .timeline-centered .timeline-entry:after {
        clear: both;
      }
      .timeline-centered .timeline-entry.begin {
        margin-bottom: 0;
      }
      .timeline-centered .timeline-entry.left-aligned {
        float: left;
      }
      .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner {
        margin-left: 0;
        margin-right: -18px;
      }
      .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner .timeline-time {
        left: auto;
        right: -100px;
        text-align: left;
      }
      .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner .timeline-icon {
        float: right;
      }
      .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner .timeline-label {
        margin-left: 0;
        margin-right: 70px;
      }
      .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner .timeline-label:after {
        left: auto;
        right: 0;
        margin-left: 0;
        margin-right: -9px;
        -moz-transform: rotate(180deg);
        -o-transform: rotate(180deg);
        -webkit-transform: rotate(180deg);
        -ms-transform: rotate(180deg);
        transform: rotate(180deg);
      }
      .timeline-centered .timeline-entry .timeline-entry-inner {
        position: relative;
        margin-left: -22px;
      }
      .timeline-centered .timeline-entry .timeline-entry-inner:before, .timeline-centered .timeline-entry .timeline-entry-inner:after {
        content: " ";
        display: table;
      }
      .timeline-centered .timeline-entry .timeline-entry-inner:after {
        clear: both;
      }
      .timeline-centered .timeline-entry .timeline-entry-inner:before, .timeline-centered .timeline-entry .timeline-entry-inner:after {
        content: " ";
        display: table;
      }
      .timeline-centered .timeline-entry .timeline-entry-inner:after {
        clear: both;
      }
      .timeline-centered .timeline-entry .timeline-entry-inner .timeline-time {
        position: absolute;
        left: -100px;
        text-align: right;
        padding: 0px 10px;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
      }
      .timeline-centered .timeline-entry .timeline-entry-inner .timeline-time > span {
        display: block;
      }
      .timeline-centered .timeline-entry .timeline-entry-inner .timeline-time > span:first-child {
        font-size: 15px;
        font-weight: bold;
      }
      .timeline-centered .timeline-entry .timeline-entry-inner .timeline-time > span:last-child {
        font-size: 12px;
      }
      .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon {
        background: #fff;
        color: #737881;
        display: block;
        width: 40px;
        height: 40px;
        -webkit-background-clip: padding-box;
        -moz-background-clip: padding;
        background-clip: padding-box;
        -webkit-border-radius: 20px;
        -moz-border-radius: 20px;
        border-radius: 20px;
        text-align: center;
        -moz-box-shadow: 0 0 0 5px #eeeeee;
        -webkit-box-shadow: 0 0 0 5px #eeeeee;
        box-shadow: 0 0 0 5px #eeeeee;
        line-height: 40px;
        font-size: 18px;
        float: left;
      }
      .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon {
        color: #fff;
      }
      .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label {
        position: relative;
        background: #eeeeee;
        padding: 0.5em;
        margin-left: 70px;
        -webkit-background-clip: padding-box;
        -moz-background-clip: padding;
        background-clip: padding-box;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
      }
      .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label:after {
        content: '';
        display: block;
        position: absolute;
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 9px 9px 9px 0;
        border-color: transparent #eeeeee transparent transparent;
        left: 0;
        top: 10px;
        margin-left: -9px;
      }
      .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label h2, .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label p {
        color: #737881;
        font-family: "Noto Sans",sans-serif;
        font-size: 12px;
        margin: 0;
        line-height: 1.428571429;
      }
      .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label p + p {
        margin-top: 15px;
      }
      .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label h2 {
        font-size: 16px;
        margin-bottom: 10px;
      }
    </style>
  </head>
  <body>
    <div class="container max-width-600">
      <div class="row">
        <div class="col-12">
          <div class="mt-3 mt-md-5">
            <h1 class="mb-0 mb-md-2">{!! $reward->title !!}</h1>
<?php if ($reward->image_file_name != null) { ?>
            <img src="{{ url($reward->image->url('4x')) }}" class="img-fluid mt-3 mb-4 mdl-shadow--2dp" alt="<?php echo str_replace('"', '&quot;', $reward->title); ?>" style="min-width: 100%">
<?php } ?>

            <p class="lead">{!! nl2br($reward->details) !!}</p>

            <div class="row mt-0 d-print-none">
              <div class="col-12">
<?php
if ($reward->address != null) {
  $link = 'https://maps.google.com/?saddr=My+Location&daddr=' . urlencode($reward->address);
  if ($reward->lat != null && $reward->lng != null) $link = 'https://maps.google.com/?saddr=My+Location&daddr=' . $reward->lat . ',' . $reward->lng . '';
  //if ($reward->lat != null && $reward->lng != null) $link = 'https://maps.google.com/?ll=' . $reward->lat . ',' . $reward->lng . '';
?>              <p><i class="mi location_on d-print-none"></i> <a href="{{ $link }}" class="link" target="_blank">{{ $reward->address }}</a></p>
<?php } ?>
              </div>
            </div>

            <div class="row mt-4 d-print-block d-none">
              <div class="col-12">
<?php
if ($reward->address != null) {
  $link = 'https://maps.google.com/?saddr=My+Location&daddr=' . urlencode($reward->address);
  if ($reward->lat != null && $reward->lng != null) $link = 'https://maps.google.com/?saddr=My+Location&daddr=' . $reward->lat . ',' . $reward->lng . '';
  //if ($reward->lat != null && $reward->lng != null) $link = 'https://maps.google.com/?ll=' . $reward->lat . ',' . $reward->lng . '';
?>              <p><img src="{{ url('assets/images/icons/baseline_location_on_black_18dp.png') }}" style="width: 18px; margin: 5px 5px 0 0"> <a href="{{ $link }}" class="link" target="_blank">{{ $reward->address }}</a></p>
<?php } ?>
<?php if ($reward->phone != null || $reward->website != null) { ?>
<?php if ($reward->phone != null) { ?>
                <p><img src="{{ url('assets/images/icons/baseline_phone_black_18dp.png') }}" style="width: 18px; margin: 5px 5px 0 0"> <a href="tel:{{ $reward->phone }}" class="link">{!! $reward->phone !!}</a></p>
<?php } ?>
<?php if ($reward->website != null) {
  $website = (! starts_with($reward->website, 'http')) ? 'http://' . $reward->website: $reward->website;
?>
                <p><img src="{{ url('assets/images/icons/baseline_info_black_18dp.png') }}" style="width: 18px; margin: 5px 5px 0 0"> <a href="{{ $website }}" class="link">{!! $reward->website !!}</a></p>
<?php } ?>
<?php } ?>
              </div>
            </div>


            <div class="timeline-centered my-5">
<?php
foreach ($steps as $step) {
  $align = ($step['step'] % 2 == 0) ? ' left-aligned': '';
?>
              <article class="timeline-entry{{ $align }}" id="step{!! $step['step'] !!}">
                <div class="timeline-entry-inner">
                  <time class="timeline-time"></time>
<?php if ($step['checked']) { ?>
                  <div class="timeline-icon bg-success">
                    <i class="mi check"></i>
                  </div>
<?php } else { ?>
                  <div class="timeline-icon">
                  </div>
<?php } ?>
                  <div class="timeline-label">
                    <h2 class="mb-0">{!! $reward->visitTxt !!} {!! $step['step'] !!}</h2>
                    {!! $step['time'] !!}
                  </div>
                </div>
              </article>
<?php
}
?>
            <article class="timeline-entry begin">
              <div class="timeline-entry-inner">
                <div class="timeline-icon color-dark">
                  <i class="mi <?php if ($redeemed) echo 'done_all'; ?>"></i>
                </div>
              </div>
            </article>

          </div>

<?php
if (! $redeemed) {
  if ($reward->expiration_date_time != null) {
    $expires = $reward->expiration_date_time->formatLocalized('Expires %A, %B ' . App\Http\Controllers\RewardsController::ordinal($reward->expiration_date_time->day) . ' %Y at %I:%M %p');
  } elseif ($reward->expiration_date != null) {
    $expires = $reward->expiration_date->formatLocalized('Expires %A, %B ' . App\Http\Controllers\RewardsController::ordinal($reward->expiration_date->day) . ' %Y');
  } else {
    $expires = null;
  }

  if ($expires != null) { 
?>
            <small class="text-muted">{{ $expires }}</small>
<?php 
  }
}
?>

<?php if (! $redeemed) { ?>
            <div class="row mt-2 d-print-none">
              <div class="col-12">
                <a href="javascript:void(0);" data-toggle="modal" data-target="#checkInModal" class="btn btn-{{ $reward->primaryColor }} btn-xlg text-truncate rounded-0 btn-block mb-4"><?php if ($readyToRedeem) { ?><i class="mi redeem" style="top:2px; font-size: 24px"></i> {!! $reward->redeemBtnText !!}<?php } else { ?><i class="mi check" style="top:2px; font-size: 24px"></i> {!! $reward->primaryBtnText !!}<?php } ?></a>
              </div>
            </div>
<?php } ?>

<?php if (1==2 && $redeemed) { ?>
            <div class="row mb-2 d-print-none">
              <div class="col-12 text-center">
                <i class="mi text-success done my-5" style="font-size: 7rem"></i>
              </div>
            </div>
<?php } ?>

            <div class="row d-print-none">
<?php if ($reward->phone != null) { ?>
              <div class="col">
                <a href="tel:{{ $reward->phone }}" class="btn btn-{{ $reward->secondaryColor }} btn-lg text-truncate rounded-0 btn-block mb-4"><i class="mi phone"></i> {!! $reward->callBtnText !!}</a>
              </div>
<?php } ?>
<?php
if ($reward->website != null) {
  $website = (! starts_with($reward->website, 'http')) ? 'http://' . $reward->website: $reward->website;
?>
              <div class="col">
                <a href="{{ $website }}" class="btn btn-{{ $reward->secondaryColor }} btn-lg text-truncate rounded-0 btn-block mb-4" target="_blank"><i class="mi info_outline"></i> {!! $reward->moreBtnText !!}</a>
              </div>
<?php } ?>
              <div class="col">
                <div class="dropdown">
                  <button class="btn btn-{{ $reward->secondaryColor }} btn-lg text-truncate rounded-0 btn-block mb-4 dropdown-toggle" type="button" id="dropdownShare" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {!! $reward->shareBtnText !!}
                  </button>
                  <div class="dropdown-menu rounded-0 dropdown-menu-right btn-block" aria-labelledby="dropdownShare">
                    <a class="dropdown-item" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->full()) }}" target="_blank"><span style="width:25px;float:left; text-align: center"><i class="fab fa-facebook" aria-hidden="true"></i></span> Facebook</a>
                    <a class="dropdown-item" href="https://plus.google.com/share?url={{ urlencode(url()->full()) }}" target="_blank"><span style="width:25px;float:left; text-align: center"><i class="fab fa-google" aria-hidden="true"></i></span> Google+</a>
                    <a class="dropdown-item" href="https://twitter.com/intent/tweet?url={{ urlencode(url()->full()) }}&text={{ urlencode($reward->title . ' - ') }}" target="_blank"><span style="width:25px;float:left; text-align: center"><i class="fab fa-twitter" aria-hidden="true"></i></span> Twitter</a>
                    <a class="dropdown-item" href="mailto:?subject={{ urlencode($reward->title) }}&body={{ urlencode(url()->full()) }}" target="_blank"><span style="width:25px;float:left; text-align: center"><i class="far fa-envelope" aria-hidden="true"></i></span> Mail</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php if ($hasToWaitToCheckIn) { ?>
    <div class="modal fade" id="checkInModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog mt-5" role="document">
        <div class="modal-content rounded-0 mdl-shadow--8dp border border-white">

          <div class="modal-header border-0">
            <h5 class="modal-title">{!! $reward->title !!}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body border-0">
            <p class="lead">{{ trans('nearby-platform.wait_for_') }}: {{ $timeToWaitForCheckIn }}</p>
            <p class="lead"><a href="javascript:document.location.reload();" class="link">{{ trans('nearby-platform.refresh_page') }}</a></p>
          </div>
          <div class="modal-footer border-0 p-3">
            <button type="button" class="btn rounded-0 btn-{{ $reward->secondaryColor }} btn-xlg btn-block text-truncate" data-dismiss="modal">{{ trans('nearby-platform.close') }}</button>
          </div>
        </div>
      </div>
    </div>
<?php } elseif (! $redeemed && ! $hasToWaitToCheckIn && ! $readyToRedeem) { ?>
    <div class="modal fade" id="checkInModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog mt-5" role="document">
        <div class="modal-content rounded-0 mdl-shadow--8dp border border-white">

          <div class="modal-header border-0">
            <h5 class="modal-title">{!! $reward->title !!}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body border-0">
            <p class="lead">{!! $reward->checkInText !!}</p>

            <img src="{{ DNS2D::getBarcodePNGPath($check_in_url, 'QRCODE', 20, 20, [0,0,0]) }}" class="img-fluid my-1" alt="<?php echo str_replace('"', '&quot;', $reward->title); ?>" style="min-width: 100%">

          </div>
          <div class="modal-footer border-0 p-3">
            <button type="button" class="btn rounded-0 btn-{{ $reward->secondaryColor }} btn-xlg btn-block text-truncate" data-dismiss="modal">{{ trans('nearby-platform.close') }}</button>
          </div>
        </div>
      </div>
    </div>
<?php } elseif (! $redeemed) { ?>
    <div class="modal fade" id="checkInModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog mt-5" role="document">
        <div class="modal-content rounded-0 mdl-shadow--8dp border border-white">

          <form class="form-horizontal" method="POST" action="{{ url('reward/redeem/' . $reward_hash) }}" autocomplete="off">

          <div class="modal-header border-0 p-3">
            <h5 class="modal-title">{!! $reward->title !!}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body px-3 py-0 border-0">
            <p class="lead">{!! $reward->redeemText !!}</p>
<?php
$aFields = \App\Http\Controllers\RewardsController::requiredFieldList();

if (isset($reward->fields) && is_array($reward->fields)) {
  foreach ($reward->fields as $id => $saved_field) {

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
            <button type="submit" class="btn rounded-0 btn-{{ $reward->primaryColor }} btn-xlg btn-block text-truncate">{!! $reward->redeemBtnText !!}</button>
          </div>
          </form>
        </div>
      </div>
    </div>
<?php } ?>

@include('layouts._general-content-includes-footer')

<script src="https://js.pusher.com/4.3/pusher.min.js"></script>
<script>
var pusher = false;
$('#checkInModal').on('shown.bs.modal', function () {
  if (pusher === false) {
    pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
      cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
      forceTLS: true
    });

    var channel = pusher.subscribe('{{ $pusher_channel }}');
    channel.bind('checkedIn', function(data) {
      document.location.replace("{{ url('reward/checked-in/' . $reward_hash) }}");
    });
  }
});
</script>

<script type="text/javascript">
<?php if (isset($first_field) && $first_field != '') { ?>
$('#checkInModal').on('shown.bs.modal', function () {
  $('#field_{{ $first_field }}').trigger('focus')
})
<?php } ?>
</script>

  </body>
</html>