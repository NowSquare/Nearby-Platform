<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/Article">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />

    <title>{{ $page->title }}</title>
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

<?php if ($page->icon_file_name != null) { ?>
    <link rel="shortcut icon" type="image/x-icon" href="{{ $page->getFavicon() }}" />
<?php } elseif ($page->image_file_name != null) { ?>
    <link rel="icon" type="image/png" href="{{ url($page->image->url('favicon')) }}" />
<?php } else { ?>
    <link rel="shortcut icon" href="{{ url('favicon.ico') }}" type="image/x-icon" />
<?php } ?>

@include('layouts._general-content-includes')

<?php if (isset($color)) { ?>
    <meta name="theme-color" content="{{ $color }}">
<?php } ?>

    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $page->title }}">
    <meta itemprop="description" content="{!! $description !!}">
<?php if ($page->image_file_name != null) { ?>
    <meta itemprop="image" content="{{ url($page->image->url('4x')) }}">
<?php } ?>

    <!-- Twitter Card data -->
<?php if ($page->image_file_name != null) { ?>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image:src" content="{{ url($page->image->url('4x')) }}">
<?php } else { ?>
    <meta name="twitter:card" content="summary">
<?php } ?>
    <meta name="twitter:title" content="{{ $page->title }}">
    <meta name="twitter:description" content="{!! $description !!}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $page->title }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->full() }}">
    <meta property="og:description" content="{!! $description !!}">
<?php if ($page->image_file_name != null) { ?>
    <meta property="og:image" content="{{ url($page->image->url('4x')) }}">
<?php } ?>
  </head>
  <body>
    <div class="container max-width-600">
      <div class="row">
        <div class="col-12">
          <div class="mt-3 mt-md-5">
<?php if ($page->image_file_name != null) { ?>
            <img src="{{ url($page->image->url('4x')) }}" class="img-fluid mb-4 mdl-shadow--2dp" alt="<?php echo str_replace('"', '&quot;', $page->title); ?>" style="min-width: 100%">
<?php } ?>

            <h1 class="mb-0 mt-0 mb-md-2">{!! $page->title !!}</h1>
            <div class="mb-4 mt-4 lead">{!! $page->content !!}</div>

          </div>
        </div>
      </div>
    </div>

@include('layouts._general-content-includes-footer')

  </body>
</html>