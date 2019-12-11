@extends('../../layouts.master')

@section('page_title'){{ trans('nearby-platform.dashboard') }} - {{ config()->get('system.premium_name') }} @stop

@section('content')
<?php
$count_deals = auth()->user()->deals()->count();
$count_coupons = auth()->user()->coupons()->count();
$count_rewards = auth()->user()->rewards()->count();
$count_properties = auth()->user()->properties()->count();
$count_cards = auth()->user()->businessCards()->count();
$count_videos = auth()->user()->videos()->count();
$count_pages = auth()->user()->pages()->count();
$count_qr = auth()->user()->qr_codes()->count();
?>
  <section>
    <div class="content text-dark" style="background-image: url('{{ url('assets/images/backgrounds/triangles.jpg') }}');">
      <div class="content-overlay" style="background-color:rgba(245,249,250,0.9)">
        <div class="container">
          <div class="row">
            <div class="col-md-8">
              <h1 class="mb-0">{{ trans('nearby-platform.dashboard') }}</h1>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="breadcrumbs breadcrumbs-arrow breadcrumbs-light mb-0" style="background-image:url()">
      <div class="breadcrumbs-overlay" style="background-color:#ddd">
        <div class="container">
          <div class="breadcrumbs-padding">
            <div class="row">
              <div class="col-12 col-md-12">

                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ url('') }}"><div>{{ trans('nearby-platform.home') }}</div></a></li>
                  <li class="breadcrumb-item active"><a href="javascript:void(0);"><div>{{ trans('nearby-platform.dashboard') }}</div></a></li>
                </ol>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="container">

    <section>
      <div class="content">

        <div class="content-overlay" style="background-color:rgba(255,255,255,1)">
<?php
$apps = [];
$apps[] = [
  'url' => url('dashboard/qr'),
  'icon' => 'fas fa-qrcode',
  'count' => $count_qr,
  'trans' => 'qr_codes',
];
$apps[] = [
  'url' => url('dashboard/deals'),
  'icon' => 'store',
  'count' => $count_deals,
  'trans' => 'deals',
];
$apps[] = [
  'url' => url('dashboard/coupons'),
  'icon' => 'redeem',
  'count' => $count_coupons,
  'trans' => 'coupons',
];
$apps[] = [
  'url' => url('dashboard/rewards'),
  'icon' => 'beenhere',
  'count' => $count_rewards,
  'trans' => 'rewards',
];
$apps[] = [
  'url' => url('dashboard/properties'),
  'icon' => 'fas fa-home',
  'count' => $count_properties,
  'trans' => 'properties',
];
$apps[] = [
  'url' => url('dashboard/business-cards'),
  'icon' => 'account_circle',
  'count' => $count_cards,
  'trans' => 'business_cards',
];
$apps[] = [
  'url' => url('dashboard/videos'),
  'icon' => 'video_library',
  'count' => $count_videos,
  'trans' => 'videos',
];
$apps[] = [
  'url' => url('dashboard/pages'),
  'icon' => 'insert_drive_file',
  'count' => $count_pages,
  'trans' => 'pages',
];

?>
          <div class="card border-0 rounded-0 mb-4 mb-lg-4 mdl-shadow--4dp"><?php /*
            <h4 class="card-header rounded-0 bg-dark text-white"><i class="mi collections" style="font-size: 1.3rem;"></i> {{ trans('nearby-platform.content') }}</h4>*/ ?>
            <div class="card-body pb-0 pt-2 px-2" style="background-color: rgba(0,0,0,0.025)">
              <div class="row">
<?php
foreach ($apps as $app) {
?>
                <div class="col-4 col-sm-4 col-md-3 col-lg-2">
                  <a href="{{ $app['url'] }}" class="btn btn-lg btn-block border-0 rounded-0 btn-outline-blue-grey mb-2 text-center badge-blue-grey-hover">
                    <div class="" style="height: 54px">
<?php if ($app['count'] > 0) { ?>
                      <span class="badge badge-danger badge-pill" style="position: absolute; right: 25px; margin-top: 10px; font-size: 11px">{{ $app['count'] }}</span>
<?php } ?>
                      <i class="mi {{ $app['icon'] }} icon-3"></i>
                    </div>
                    <div class="text-truncate" style="font-size: 15px" title="{{ trans_choice('nearby-platform.' . $app['trans'], $app['count']) }}">{{ trans_choice('nearby-platform.' . $app['trans'], $app['count']) }}</div>
                  </a>
                </div>
<?php
}
?>
            </div>
          </div>
        </div>

        <div class="row row-eq-height">
          <div class="col-12 col-lg-12">
            <div class="card border-blue-grey rounded-0 mt-lg-0 mt-4 mdl-shadow--4dp">
              <h4 class="card-header bg-blue-grey text-white rounded-0"><i class="mi timer" style="font-size: 1.3rem;"></i> {{ trans('nearby-platform.quick_start') }}</h4>
              <div class="card-body py-0">
                <ul class="my-4 list-actions">
                  <li><a href="{{ url('dashboard/settings/profile') }}">{{ trans('nearby-platform.update_personal_info') }}</a></li>
<?php if ($count_deals == 0) {  ?>
                  <li><a href="{{ url('dashboard/deals') }}">{{ trans('nearby-platform.create_first_deal') }}</a></li>
<?php }  ?>
<?php if ($count_coupons == 0) {  ?>
                  <li><a href="{{ url('dashboard/coupons') }}">{{ trans('nearby-platform.create_first_coupon') }}</a></li>
<?php }  ?>
<?php if ($count_rewards == 0) {  ?>
                  <li><a href="{{ url('dashboard/rewards') }}">{{ trans('nearby-platform.create_first_reward') }}</a></li>
<?php }  ?>
<?php if ($count_cards == 0) {  ?>
                  <li><a href="{{ url('dashboard/business-cards') }}">{{ trans('nearby-platform.create_first_business_card') }}</a></li>
<?php }  ?>
<?php if ($count_pages == 0) {  ?>
                  <li><a href="{{ url('dashboard/pages') }}">{{ trans('nearby-platform.create_first_page') }}</a></li>
<?php }  ?>
                  <li><a href="{{ url('dashboard/settings/analytics') }}">{{ trans('nearby-platform.add_ga_tracking_code') }}</a></li>
                </ul>
              </div>
            </div>

          </div>

        </div>

      </div>
    </section>

  </div>

@stop