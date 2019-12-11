@extends('../../../layouts.master')

@section('page_title') {{ ucwords(str_replace('-', ' ', $sub_short)) }} - {{ ucwords(str_replace('-', ' ', $page_short)) }} - {{ $page_title }} @stop
@section('meta_description') {{ ucwords(str_replace('-', ' ', $sub_short)) }} - {{ ucwords(str_replace('-', ' ', $page_short)) }} @stop

@section('content')

  @include("app.{$seg2}._breadcrumbs")

<section class="my-3">
  <div class="container">
    <div class="row">
      <div class="col-12 col-lg-9 mb-3 order-md-12">
      {!! $html !!}
        <div class="mt-4">
<?php if ($prev != '') { ?>
        <a href="{{ $prev_link }}" class="btn btn-outline-primary btn-sm btn-pill mr-2"><i class="fas fa-arrow-left"></i> {{ $prev }}</a>
<?php } ?>
<?php if ($next != '') { ?>
        <a href="{{ $next_link }}" class="btn btn-outline-primary btn-sm btn-pill">{{ $next }} <i class="fas fa-arrow-right"></i></a>
<?php } ?>
        </div>
      </div>
      <div class="col-lg-3 mb-3 order-md-1"> @include("app.{$seg2}._nav") </div>
    </div>
  </div>
</section>
@stop