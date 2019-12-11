@extends('../layouts.master')

@section('page_title'){{ trans('nearby-platform.profile') }} - {{ trans('nearby-platform.settings') }} - {{ config()->get('system.premium_name') }} @stop
@section('meta_description') @stop

@section('content')

  <section>
    <div class="content text-dark" style="background-image: url('{{ url('assets/images/backgrounds/triangles.jpg') }}');">
      <div class="content-overlay" style="background-color:rgba(245,249,250,0.9)">
        <div class="container">
          <div class="row">
            <div class="col-md-8">
              <h1 class="mb-0">{{ trans('nearby-platform.profile') }}</h1>
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
                  <li class="breadcrumb-item active"><a href="javascript:void(0);"><div>{{ trans('nearby-platform.profile') }}</div></a></li>
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


                  <form class="form-horizontal" method="POST" action="{{ url('dashboard/settings/profile') }}">
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
                        <h4 class="card-header bg-secondary text-white rounded-0">{{ trans('nearby-platform.personal') }}</h4>
                        <div class="card-body">

                          <div class="form-group row">
                              <label for="name" class="col-12 col-sm-4 col-form-label">{{ trans('nearby-platform.name') }} <sup>*</sup></label>

                              <div class="col-12 col-sm-8">
                                <div class="input-group input-group-lg">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text rounded-0"><i class="mi person"></i></span>
                                  </div>
                                  <input id="name" type="text" class="form-control rounded-0 form-control-lg" name="name" value="<?php echo (old('name') == '') ? auth()->user()->name : old('name'); ?>" required>
                                </div>

                              </div>
                          </div>

                          <div class="form-group row mb-0">
                              <label for="email" class="col-12 col-sm-4 col-form-label">{{ trans('nearby-platform.email_address') }} <sup>*</sup></label>

                              <div class="col-12 col-sm-8">
                                <div class="input-group input-group-lg">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text rounded-0"><i class="mi mail_outline"></i></span>
                                  </div>
                                  <input id="email" type="email" class="form-control rounded-0 form-control-lg" name="email" value="<?php echo (old('email') == '') ? auth()->user()->email : old('email'); ?>" required>
                                </div>

                              </div>
                          </div>

                        </div>
                      </div>

                      <div class="card border-secondary rounded-0 mb-4">
                        <h4 class="card-header bg-secondary text-white rounded-0">{{ trans('nearby-platform.localization') }}</h4>
                        <div class="card-body">

                          <div class="form-group row">
                            <label for="language" class="col-12 col-sm-4 col-form-label">{{ trans('nearby-platform.language') }} <sup>*</sup></label>

                            <div class="col-12 col-sm-8">
                              <div class="input-group input-group-lg">
                                <div class="input-group-prepend">
                                  <span class="input-group-text rounded-0"><i class="mi language"></i></span>
                                </div>

                                <select class="form-control form-control-lg select2-required-xl" name="language">
<?php
foreach (config('system.available_languages') as $code => $language) {
$selected = (old('language', auth()->user()->locale) == $code) ? ' selected' : '';
echo '<option value="' .  $code . '"' . $selected . '>' . $language . '</option>';
}
?>
                                </select>
                              </div>

                            </div>
                          </div>

                          <div class="form-group row mb-0">
                              <label for="name" class="col-12 col-sm-4 col-form-label">{{ trans('nearby-platform.timezone') }} <sup>*</sup></label>

                              <div class="col-12 col-sm-8">
                                <div class="input-group input-group-lg">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text rounded-0"><i class="mi access_time"></i></span>
                                  </div>
                                  <select class="form-control form-control-lg select2-required-xl" name="timezone">
<?php
foreach (trans('timezones.timezones') as $continent => $timezone) {
  echo '<optgroup label="' . $continent . '">';
  foreach ($timezone as $fullzone => $zone) {
    $selected = (old('timezone', auth()->user()->timezone) == $fullzone) ? ' selected' : '';
    echo '<option value="' .  $fullzone . '"' . $selected . '>' . $zone . '</option>';
  }
  echo '</optgroup>';
}
?>
                                  </select>
                                </div>
                              </div>
                          </div>

                        </div>
                      </div>

                      <div class="card border-secondary rounded-0 mb-4">
                        <h4 class="card-header bg-secondary text-white rounded-0">{{ trans('nearby-platform.change_password') }}</h4>
                        <div class="card-body">

                          <div class="form-group row mb-0">
                              <label for="new_password" class="col-12 col-sm-4 col-form-label">{{ trans('nearby-platform.new_password') }}</label>

                              <div class="col-12 col-sm-8">
                                <div class="input-group input-group-lg">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text rounded-0"><i class="mi lock_outline"></i></span>
                                  </div>
                                  <input id="new_password" type="password" class="form-control rounded-0 form-control-lg" name="new_password">
                                </div>

                                <span class="form-text text-muted">
                                    <small>{{ trans('nearby-platform.change_password_help') }}</small>
                                </span>
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
