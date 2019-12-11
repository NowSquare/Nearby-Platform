<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/Article">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />

    <title>{{ $property->title }}</title>
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

<?php if ($property->favicon != null) { ?>
    <link rel="shortcut icon" type="image/x-icon" href="{{ $property->favicon }}" />
<?php } elseif ($property->image_file_name != null) { ?>
    <link rel="icon" type="image/png" href="{{ url($property->image->url('favicon')) }}" />
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
    <meta itemprop="name" content="{{ $property->title }}">
    <meta itemprop="description" content="{!! $description !!}">
<?php if ($property->image_file_name != null) { ?>
    <meta itemprop="image" content="{{ url($property->image->url('4x')) }}">
<?php } ?>

    <!-- Twitter Card data -->
<?php if ($property->image_file_name != null) { ?>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image:src" content="{{ url($property->image->url('4x')) }}">
<?php } else { ?>
    <meta name="twitter:card" content="summary">
<?php } ?>
    <meta name="twitter:title" content="{{ $property->title }}">
    <meta name="twitter:description" content="{!! $description !!}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $property->title }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->full() }}">
    <meta property="og:description" content="{!! $description !!}">
<?php if ($property->image_file_name != null) { ?>
    <meta property="og:image" content="{{ url($property->image->url('4x')) }}">
<?php } ?>

<?php if ($property->lat != null && $property->lng != null) { ?>
    <meta property="place:location:latitude" content="{{ $property->lat }}">
    <meta property="place:location:longitude" content="{{ $property->lng }}">
    <meta name="geo.position" content="{{ $property->lat }}; {{ $property->lng }}">
<?php } ?>
<?php if ($property->city != null) { ?>
    <meta name="geo.placename" content="{{ $property->city }}">
<?php } ?>
<?php 
    if ($property->country != null || $property->state != null || $property->postal_code != null) { 
      $code = '';
      if ($property->country != null) $code .= $property->country;
      if ($property->state != null) $code .= ', ' . $property->state;
      if ($property->postal_code != null) $code .= ', ' . $property->postal_code;
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
            <h1 class="mb-0 mb-md-2">{!! $property->title !!}</h1>
<?php if ($property->image_file_name != null) { ?>
<?php if (1==1 || $print) { ?>
            <img src="{{ url($property->image->url('4x')) }}" class="img-fluid my-4 mdl-shadow--2dp" alt="<?php echo str_replace('"', '&quot;', $property->title); ?>" style="min-width: 100%">
<?php } else { ?>
<?php
list($width, $height) = getimagesize(url($property->image->url('4x')));
?>
            <amp-img src="{{ url($property->image->url('4x')) }}" alt="<?php echo str_replace('"', '&quot;', $property->title); ?>" class="img-fluid my-4 mdl-shadow--2dp" height="{{ $height }}" width="{{ $width }}" layout="responsive"></amp-img>
<?php } ?>
<?php } ?>

            <p class="lead">{!! $property->description !!}</p>

            <h3>{{ trans('nearby-platform.features') }}</h3>

            <table class="table">
              <thead>
                <tr>
                  <th colspan="2">
                    {{ trans('nearby-platform.transfer_of_ownership') }}
                  </th>
                </tr>
              </thead>
              <tbody>
<?php if ($property->price_rent !== null) { ?>
                <tr>
                  <td>{{ trans('nearby-platform.rental_price') }}</td>
                  <td>{{ $property->price_rent }}</td>
                </tr>
<?php } ?>
<?php if ($property->price_sale !== null) { ?>
                <tr>
                  <td>{{ trans('nearby-platform.asking_price') }}</td>
                  <td>{{ $property->price_sale }}</td>
                </tr>
<?php } ?>
                <tr>
                  <td>{{ trans('nearby-platform.listed_since') }}</td>
                  <td>{{ $property->created_at->formatLocalized('%a %B %e, %Y') }}</td>
                </tr>
              </tbody>
              <thead>
                <tr>
                  <th colspan="2">
                    {{ trans('nearby-platform.construction') }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>{{ trans('nearby-platform.building_type') }}</td>
                  <td>{{ trans('nearby-platform.' . $property->building_type) }}</td>
                </tr>
<?php if ($property->year_of_construction !== null) { ?>
                <tr>
                  <td>{{ trans('nearby-platform.year_of_construction') }}</td>
                  <td>{{ $property->year_of_construction }}</td>
                </tr>
<?php } ?>
<?php if ($property->beds !== null) { ?>
                <tr>
                  <td>{{ trans('nearby-platform.bedrooms') }}</td>
                  <td>{{ $property->beds }}</td>
                </tr>
<?php } ?>
<?php if ($property->baths !== null && $property->baths > 0) { ?>
                <tr>
                  <td>{{ trans('nearby-platform.bathrooms') }}</td>
                  <td>{{ $property->baths }}</td>
                </tr>
<?php } ?>
              </tbody>
<?php if (
  ($property->living_area !== null && $property->living_area > 0) || 
  ($property->external_storage_space !== null && $property->external_storage_space > 0) || 
  ($property->plot_size !== null && $property->plot_size > 0) || 
  ($property->volume !== null && $property->volume > 0)
) { ?>
              <thead>
                <tr>
                  <th colspan="2">
                    {{ trans('nearby-platform.surface_areas_and_volume') }}
                  </th>
                </tr>
              </thead>
              <tbody>
<?php if ($property->living_area !== null && $property->living_area > 0) { ?>
                <tr>
                  <td>{{ trans('nearby-platform.living_area') }}</td>
                  <td>{{ $property->living_area }}</td>
                </tr>
<?php } ?>

<?php if ($property->plot_size !== null && $property->plot_size > 0) { ?>
                <tr>
                  <td>{{ trans('nearby-platform.plot_size') }}</td>
                  <td>{{ $property->plot_size }}</td>
                </tr>
<?php } ?>
              </tbody>
<?php } ?>
<?php if ($property->features()->count() > 0) { ?>
              <thead>
                <tr>
                  <th colspan="2">
                    {{ trans('nearby-platform.comes_with') }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="2">
<?php
foreach ($property->features as $feature) {
  echo '<span class="badge badge-secondary py-2 px-2 mb-2 mr-1">' . trans('nearby-platform.' . $feature->name) . '</span>';
}
?>
                  </td>
                </tr>
              </tbody>
<?php } ?>
<?php if ($property->features()->count() > 0) { ?>
              <thead>
                <tr>
                  <th colspan="2">
                    {{ trans('nearby-platform.surrounding') }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="2">
<?php
foreach ($property->surrounding as $surrounding) {
  echo '<span class="badge badge-secondary py-2 px-2 mb-2 mr-1">' . trans('nearby-platform.' . $surrounding->name) . '</span>';
}
?>
                  </td>
                </tr>
              </tbody>
<?php } ?>
            </table>

            <div class="row mt-0 d-print-none">
              <div class="col-12">
<?php
if ($property->address != null && 1==2) {
  $link = 'https://maps.google.com/?saddr=My+Location&daddr=' . urlencode($property->address);
  if ($property->lat != null && $property->lng != null) $link = 'https://maps.google.com/?saddr=My+Location&daddr=' . $property->lat . ',' . $property->lng . '';
  //if ($property->lat != null && $property->lng != null) $link = 'https://maps.google.com/?ll=' . $property->lat . ',' . $property->lng . '';
?>              <p><i class="mi location_on d-print-none"></i> <a href="{{ $link }}" class="link" target="_blank">{{ $property->address }}</a></p>
<?php } ?>
              </div>
            </div>

            <div class="row mt-4 d-print-block d-none">
              <div class="col-12">
<?php
if ($property->address != null) {
  $link = 'https://maps.google.com/?saddr=My+Location&daddr=' . urlencode($property->address);
  if ($property->lat != null && $property->lng != null) $link = 'https://maps.google.com/?saddr=My+Location&daddr=' . $property->lat . ',' . $property->lng . '';
  //if ($property->lat != null && $property->lng != null) $link = 'https://maps.google.com/?ll=' . $property->lat . ',' . $property->lng . '';
?>              <p><img src="{{ url('assets/images/icons/baseline_location_on_black_18dp.png') }}" style="width: 18px; margin: 5px 5px 0 0"> <a href="{{ $link }}" class="link" target="_blank">{{ $property->address }}</a></p>
<?php } ?>
<?php if ($property->phone != null || $property->website != null) { ?>
<?php if ($property->phone != null) { ?>
                <p><img src="{{ url('assets/images/icons/baseline_phone_black_18dp.png') }}" style="width: 18px; margin: 5px 5px 0 0"> <a href="tel:{{ $property->phone }}" class="link">{!! $property->phone !!}</a></p>
<?php } ?>
<?php if ($property->website != null) {
  $website = (! starts_with($property->website, 'http')) ? 'http://' . $property->website: $property->website;
?>
                <p><img src="{{ url('assets/images/icons/baseline_info_black_18dp.png') }}" style="width: 18px; margin: 5px 5px 0 0"> <a href="{{ $website }}" class="link">{!! $property->website !!}</a></p>
<?php } ?>
<?php } ?>
              </div>
            </div>

<?php
if ($property->expiration_date_time != null) {
  $expires = $property->expiration_date_time->formatLocalized('Expires %A, %B ' . App\Http\Controllers\PropertiesController::ordinal($property->expiration_date_time->day) . ' %Y at %I:%M %p');
} elseif ($property->expiration_date != null) {
  $expires = $property->expiration_date->formatLocalized('Expires %A, %B ' . App\Http\Controllers\PropertiesController::ordinal($property->expiration_date->day) . ' %Y');
} else {
  $expires = null;
}

if ($expires != null) { ?>
            <small class="text-muted">{{ $expires }}</small>
<?php } ?>
<?php
if ($property->address != null) {
  $link = 'https://maps.google.com/?saddr=My+Location&daddr=' . urlencode($property->address);
  if ($property->lat != null && $property->lng != null) $link = 'https://maps.google.com/?saddr=My+Location&daddr=' . $property->lat . ',' . $property->lng . '';
?>
            <div class="row mt-2 d-print-none">
              <div class="col-12">
                <a href="{{ $link }}" target="_blank" class="btn btn-{{ $property->primaryColor }} btn-xlg text-truncate rounded-0 btn-block mb-4"><i class="mi map" style="top:4px; font-size: 24px"></i> {!! $property->primaryBtnText !!}</a>
              </div>
            </div>
<?php
}
?>
            <div class="row d-print-none">
<?php if ($property->phone != null) { ?>
              <div class="col">
                <a href="tel:{{ $property->phone }}" class="btn btn-{{ $property->secondaryColor }} btn-lg rounded-0 btn-block mb-4"><i class="mi phone"></i> {!! $property->callBtnText !!}</a>
              </div>
<?php } ?>
<?php
if ($property->website != null) {
  $website = (! starts_with($property->website, 'http')) ? 'http://' . $property->website: $property->website;
?>
              <div class="col">
                <a href="{{ $website }}" class="btn btn-{{ $property->secondaryColor }} btn-lg rounded-0 btn-block mb-4" target="_blank"><i class="mi info_outline"></i> {!! $property->moreBtnText !!}</a>
              </div>
<?php } ?>
              <div class="col">
                <div class="dropdown">
                  <button class="btn btn-{{ $property->secondaryColor }} btn-lg rounded-0 btn-block mb-4 dropdown-toggle" type="button" id="dropdownShare" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {!! $property->shareBtnText !!}
                  </button>
                  <div class="dropdown-menu rounded-0 dropdown-menu-right btn-block" aria-labelledby="dropdownShare">
                    <a class="dropdown-item" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->full()) }}" target="_blank"><span style="width:25px;float:left; text-align: center"><i class="fab fa-facebook" aria-hidden="true"></i></span> Facebook</a>
                    <a class="dropdown-item" href="https://plus.google.com/share?url={{ urlencode(url()->full()) }}" target="_blank"><span style="width:25px;float:left; text-align: center"><i class="fab fa-google" aria-hidden="true"></i></span> Google+</a>
                    <a class="dropdown-item" href="https://twitter.com/intent/tweet?url={{ urlencode(url()->full()) }}&text={{ urlencode($property->title . ' - ') }}" target="_blank"><span style="width:25px;float:left; text-align: center"><i class="fab fa-twitter" aria-hidden="true"></i></span> Twitter</a>
                    <a class="dropdown-item" href="mailto:?subject={{ urlencode($property->title) }}&body={{ urlencode(url()->full()) }}" target="_blank"><span style="width:25px;float:left; text-align: center"><i class="far fa-envelope" aria-hidden="true"></i></span> Mail</a>
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