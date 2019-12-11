<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">

        <title>Page Not Found - {{ config('system.premium_name') }}</title>

        <link rel="shortcut icon" href="{{ url('assets/images/branding/favicon.ico') }}" type="image/x-icon" />
        <link rel="icon" type="image/png" href="{{ url('assets/images/branding/favicon-32x32.png') }}" sizes="32x32" />
        <link rel="icon" type="image/png" href="{{ url('assets/images/branding/favicon-16x16.png') }}" sizes="16x16" />

        <meta name="theme-color" content="<?php echo (config('system.reseller') === false) ? '#146eff' : config('system.reseller.theme_color'); ?>">

@include('layouts._general-site-includes')

    </head>
  <body id="home">

  @include('layouts._navbar')

    <section>
      <div class="header text-light" style="">
        <div class="header-overlay" style="background-color:#146eff">
          <div class="container">
            <div class="header-padding-xxxl text-center">
              <div class="row">
                <div class="col-12">
                  <div>
                    <h1 class="display-3 mt-5">Page Not Found</h1>
                    <p class="lead">But no worries, maybe you can find what you're looking for in the navigation.</p>
                  </div>
                  <div class="btn-container mt-3 mb-1">
                    <a class="btn btn-outline-ghost btn-xlg btn-pill -x-link" href="{{ url('/') }}" role="button">Take me home</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

  @include('layouts._footer')

  @include('layouts._general-site-includes-footer')

    </body>
</html>
