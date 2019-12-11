<?php
if (auth()->check() && \Request::segment(1) == 'dashboard' && \Request::segment(2) != 'help') {
  $subnav = true;
}
?><!DOCTYPE html>
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
<body @yield('body_tag') class="fixed-nav<?php if ($subnav) echo ' sub-nav'; ?>">
@include('layouts._navbar')

@yield('content')

@include('layouts._footer')

@include('layouts._general-site-includes-footer')

@yield('page_bottom')
</body>
</html>