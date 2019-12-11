@extends('../../layouts.master')

@section('page_title'){{ trans('nearby-platform.update') }} - {{ config()->get('system.premium_name') }} @stop

@section('content')

  <section>
    <div class="content text-dark" style="background-image: url('{{ url('assets/images/backgrounds/triangles.jpg') }}');">
      <div class="content-overlay" style="background-color:rgba(245,249,250,0.9)">
        <div class="container">
          <div class="row">
            <div class="col-md-8">
              <h1 class="mb-0">{{ trans('nearby-platform.update') }}</h1>
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
                  <li class="breadcrumb-item"><a href="{{ url('dashboard') }}"><div>{{ trans('nearby-platform.dashboard') }}</div></a></li>
                  <li class="breadcrumb-item active"><a href="javascript:void(0);"><div>{{ trans('nearby-platform.update') }}</div></a></li>
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
      <div class="content my-0" style="">
        <div class="content-overlay" style="background-color:rgba(255,255,255,1)">
          <div class="row">
            <div class="col-12 col-sm-8 col-lg-9 order-12">
              <div class="content-padding-none">

                <div class="card border-secondary rounded-0 mb-4">
                  <h4 class="card-header bg-secondary text-white rounded-0">{{ trans('nearby-platform.check_for_updates') }}</h4>
                  <div class="card-body">

                    <h2 class="m-0 mb-3">{{ trans('nearby-platform.current_version_is', ['version' => $version]) }}</h2>
                    <h2 class="m-0">{{ trans('nearby-platform.latest_version_is', ['latest_version' => $latest_version]) }}</h2>

                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

  </div>

@stop