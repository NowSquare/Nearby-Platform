@extends('../layouts.master')

@section('page_title'){{ trans('nearby-platform.analytics') }} - {{ trans('nearby-platform.settings') }} - {{ config()->get('system.premium_name') }} @stop
@section('meta_description') @stop

@section('content')

  <section>
    <div class="content text-dark" style="background-image: url('{{ url('assets/images/backgrounds/triangles.jpg') }}');">
      <div class="content-overlay" style="background-color:rgba(245,249,250,0.9)">
        <div class="container">
          <div class="row">
            <div class="col-md-8">
              <h1 class="mb-0">{{ trans('nearby-platform.analytics') }}</h1>
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
                  <li class="breadcrumb-item"><a href="{{ url('dashboard/settings') }}"><div>{{ trans('nearby-platform.settings') }}</div></a></li>
                  <li class="breadcrumb-item active"><a href="javascript:void(0);"><div>{{ trans('nearby-platform.analytics') }}</div></a></li>
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


                  <form class="form-horizontal" method="POST" action="{{ url('dashboard/settings/analytics') }}">
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

                      <div class="card border-secondary rounded-0 mb-4">
                        <h4 class="card-header bg-secondary text-white rounded-0">{{ trans('nearby-platform.google_analytics') }}</h4>
                        <div class="card-body">

                          <div class="form-group row mb-0">
                              <label for="email" class="col-12 col-sm-4 col-form-label">{{ trans('nearby-platform.tracking_id') }}</label>

                              <div class="col-12 col-sm-8">
                                <div class="input-group input-group-lg">
                                  <input id="ga_code" maxlength="24" type="text" class="form-control rounded-0 form-control-lg" placeholder="UA-xxxxxxxx-x" name="ga_code" value="<?php echo old('ga_code', $ga_code); ?>">
                                </div>
                                <small class="form-text text-muted">{!! trans('nearby-platform.tracking_id_help') !!}</small>


                                @if ($errors->has('ga_code'))
                                    <span class="form-text text-danger">
                                        <strong>{{ $errors->first('ga_code') }}</strong>
                                    </span>
                                @endif
                              </div>
                          </div>

                        </div>
                      </div>

                      <div class="card border-secondary rounded-0 mb-4">
                        <h4 class="card-header bg-secondary text-white rounded-0">{{ trans('nearby-platform.fb_pixel') }}</h4>
                        <div class="card-body">

                          <div class="form-group row mb-0">
                              <label for="email" class="col-12 col-sm-4 col-form-label">{{ trans('nearby-platform.pixel_id') }}</label>

                              <div class="col-12 col-sm-8">
                                <div class="input-group input-group-lg">
                                  <input id="fb_pixel" maxlength="24" type="text" class="form-control rounded-0 form-control-lg" placeholder="xxxxxxxxxxxxxxxx" name="fb_pixel" value="<?php echo old('fb_pixel', $fb_pixel); ?>">
                                </div>
                                <small class="form-text text-muted"><a href="https://developers.facebook.com/docs/facebook-pixel/" target="_blank" class="link">https://developers.facebook.com/docs/facebook-pixel/</a></small>


                                @if ($errors->has('fb_pixel'))
                                    <span class="form-text text-danger">
                                        <strong>{{ $errors->first('fb_pixel') }}</strong>
                                    </span>
                                @endif
                              </div>
                          </div>

                        </div>
                      </div>

                      <div class="card border-secondary rounded-0 mb-4">
                        <h4 class="card-header bg-secondary text-white rounded-0">{{ trans('nearby-platform.security') }}</h4>
                        <div class="card-body">

                          <div class="form-group row mb-0">
                              <label for="password" class="col-12 control-label">{{ trans('nearby-platform.security_current_password') }} <sup>*</sup></label>

                              <div class="col-12">
                                <div class="input-group input-group-lg">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text rounded-0"><i class="mi lock_open"></i></span>
                                  </div>
                                  <input id="password" type="password" class="form-control rounded-0 form-control-lg" name="password" required>
                                </div>

                                  @if ($errors->has('password'))
                                      <span class="form-text text-danger">
                                          <strong>{{ $errors->first('password') }}</strong>
                                      </span>
                                  @endif
                              </div>
                          </div>

                        </div>
                      </div>

                      <div class="form-group">
                        <button type="submit" class="btn rounded-0 btn-primary btn-lg">{{ trans('nearby-platform.save_changes') }}</button>
                      </div>
                  </form>

              </div>
            </div>
            <div class="col-12 col-sm-4 col-lg-3 order-12 order-sm-1">

              @include("app.settings._nav")

            </div>
          </div>
        </div>
      </div>
    </section>

</div>
@endsection
