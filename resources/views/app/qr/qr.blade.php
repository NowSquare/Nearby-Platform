@extends('layouts.master')

@section('page_title')<?php echo (isset($sl)) ? trans('nearby-platform.edit_qr_code') : trans('nearby-platform.add_qr_code'); ?> - {{ config()->get('system.premium_name') }} @stop
@section('meta_description') @stop

@section('head') 
<style type="text/css">
  .custom-file-control:after {
    content: "{{ trans('nearby-platform.select_image_') }}";
  }
  .custom-file-control.selected::after {
    content: "" !important;
  }
</style>
@stop

@section('content')

  <section>
    <div class="content text-dark" style="background-image: url('{{ url('assets/images/backgrounds/triangles.jpg') }}');">
      <div class="content-overlay" style="background-color:rgba(245,249,250,0.9)">
        <div class="container">
          <div class="row">
            <div class="col-md-8">
<?php if (isset($sl)) { ?>
              <h1 class="mb-0">{{ trans('nearby-platform.edit_qr_code') }}</h1>
<?php } else { ?>
              <h1 class="mb-0">{{ trans('nearby-platform.add_qr_code') }}</h1>
<?php } ?>
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
              <div class="col-12">

                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ url('') }}"><div>{{ trans('nearby-platform.home') }}</div></a></li>
                  <li class="breadcrumb-item"><a href="{{ url('dashboard') }}"><div>{{ trans('nearby-platform.dashboard') }}</div></a></li>
                  <li class="breadcrumb-item"><a href="{{ url('dashboard/qr') }}"><div>{{ trans('nearby-platform.qr_codes') }}</div></a></li>
<?php if (isset($sl)) { ?>
                  <li class="breadcrumb-item active"><a href="javascript:void(0);"><div>{{ trans('nearby-platform.edit_qr_code') }}</div></a></li>
<?php } else { ?>
                  <li class="breadcrumb-item active"><a href="javascript:void(0);"><div>{{ trans('nearby-platform.add_qr_code') }}</div></a></li>
<?php } ?>
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
            <div class="col-12 col-sm-12 col-lg-12">

              <div class="card border-secondary rounded-0">
                <h4 class="card-header bg-secondary text-white rounded-0">{{ trans('nearby-platform.qr_code') }}</h4>
                <div class="card-body">

                  <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{ url('dashboard/qr/save') }}" autocomplete="off">
<?php if (isset($sl)) { ?>
                    <input type="hidden" name="sl" value="{{ $sl }}">
<?php } ?>
                    {{ csrf_field() }}

                    @if(session()->has('message'))
                      <div class="alert alert-success mb-3 rounded-0">
                        {{ session()->get('message') }}
                      </div>
                    @endif

                    @if ($errors->any())
                      <div class="alert alert-danger mb-3 rounded-0">
                        {{ trans('nearby-platform.form_not_saved') }}

                        <ul class="mt-4">
                          @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                          @endforeach
                        </ul>
                      </div>
                    @endif

                    <div class="form-group">
                      <label for="redirect_to_url">{{ trans('nearby-platform.url') }}</label>

                      <div class="input-group input-group-lg">
                        <input type="text" class="form-control rounded-0" name="redirect_to_url" id="redirect_to_url" placeholder="https://" value="{{ old('redirect_to_url', $qr_code->redirect_to_url) }}">
                        <div class="dropdown">
                          <button class="btn btn-secondary btn-lg rounded-0 input-group-addon dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ trans('nearby-platform.select_') }}
                          </button>
                          <div class="dropdown-menu rounded-0">
                            <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#dealSelectModal" onclick="selectDealInput('redirect_to_url');">{{ trans('nearby-platform.deal') }}</a>
                            <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#couponSelectModal" onclick="selectCouponInput('redirect_to_url');">{{ trans('nearby-platform.coupon') }}</a>
                            <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#rewardSelectModal" onclick="selectRewardInput('redirect_to_url');">{{ trans('nearby-platform.reward') }}</a>
                            <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#propertySelectModal" onclick="selectPropertyInput('redirect_to_url');">{{ trans('nearby-platform.property') }}</a>
                            <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#cardSelectModal" onclick="selectCardInput('redirect_to_url');">{{ trans('nearby-platform.business_card') }}</a>
                            <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#videoSelectModal" onclick="selectVideoInput('redirect_to_url');">{{ trans('nearby-platform.video') }}</a>
                            <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#pageSelectModal" onclick="selectPageInput('redirect_to_url');">{{ trans('nearby-platform.page') }}</a>
                          </div>
                        </div>
                      </div>

                    </div>


                    <div class="form-group mt-2 mb-0 text-right mt-5">
<?php if (isset($sl) || (isset($count) && $count > 0)) { ?>
                      <a href="{{ url('dashboard/qr') }}" class="btn rounded-0 btn-secondary btn-lg mr-2">{{ trans('nearby-platform.cancel') }}</a>
<?php } ?>
<?php if (isset($sl)) { ?>
                      <button type="submit" class="btn rounded-0 btn-primary btn-lg">{{ trans('nearby-platform.save') }}</button>
<?php } else { ?>
                      <button type="submit" class="btn rounded-0 btn-primary btn-lg">{{ trans('nearby-platform.add') }}</button>
<?php } ?>
                    </div>

                  </form>
                </div>
              </div>

            </div>

          </div>
        </div>
      </div>
    </section>

</div>

@include('app.notifications._content-modals')

@endsection

@section('page_bottom')
<script>
  @include('app.notifications._content-modals-js')
</script>
@stop