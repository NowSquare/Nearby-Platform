<!DOCTYPE html>
<html lang="{{ \App::getLocale() }}">
<head>
<title>@yield('page_title')</title>
  <meta name="description" content="@yield('meta_description')">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  @yield('head')
  <link rel="shortcut icon" href="{{ url('assets/images/branding/favicon.ico') }}" type="image/x-icon" />
  <link rel="icon" type="image/png" href="{{ url('assets/images/branding/favicon-32x32.png') }}" sizes="32x32" />
  <link rel="icon" type="image/png" href="{{ url('assets/images/branding/favicon-16x16.png') }}" sizes="16x16" />
  <meta name="theme-color" content="#146eff">

@include('layouts._general-site-includes')

@yield('head_end')
</head>
<body @yield('body_tag') class="fixed-nav">

   <div id="fixed-nav">
      <nav class="navbar navbar-expand-lg navbar-full navbar-light bg-light" style="padding:0">
        <div class="container">
            <a class="navbar-brand navbar-logo" href="javascript:void(0);"><img src="{{ url('assets/images/branding/logo-horizontal-dark.svg') }}" alt="Nearby Platform" style="height:28px"></a>
        </div>
      </nav>
  </div>

@yield('content')

  <section>
    <div class="footer text-dark footer-padding-xl" style="background-image:url('{{ url('assets/images/backgrounds/triangles.jpg') }}');">
      <div class="footer-overlay" style="background-color:rgba(245,249,250,0.9)">
        <div class="container">
          <div class="row">
            <div class="col-12 col-sm-6 col-md-4 col-sm-3">
              <a href="{{ url('/') }}"><img src="{{ url('assets/images/branding/icon-circle-dark.svg') }}" alt="Mobile Content for Location Marketing" style="height:48px; max-width: 100%; margin-bottom: 18px;" class="mt-5 mb-3 mt-md-2"></a>
              <address>
                Copyright &copy; {{ date('Y') }} {{ env('APP_NAME', 'Nearby Platform') }}.<br>
                All rights reserved.
              </address>

            </div>

            <div class="col-12 col-sm-12 col-md-2 col-sm-3">
            </div>

            <div class="col-12 col-sm-12 col-md-2 col-sm-3">
            </div>

            <div class="col-12 col-sm-6 col-md-2 col-sm-3">
            </div>

            <div class="col-12 col-sm-6 col-md-2 col-sm-3">
            </div>

            </div>
          </div>
        </div>
      </div>
  </section>

@include('layouts._general-site-includes-footer')

@yield('page_bottom')
</body>
</html>