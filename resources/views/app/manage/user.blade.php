@extends('../layouts.master')

@section('page_title')<?php echo (isset($sl)) ? trans('nearby-platform.edit_user') : trans('nearby-platform.add_user'); ?> - {{ config()->get('system.premium_name') }} @stop
@section('meta_description') @stop

@section('content')

  <section>
    <div class="content text-dark" style="background-image: url('{{ url('assets/images/backgrounds/triangles.jpg') }}');">
      <div class="content-overlay" style="background-color:rgba(245,249,250,0.9)">
        <div class="container">
          <div class="row">
            <div class="col-md-8">
<?php if (isset($sl)) { ?>
              <h1 class="mb-0">{{ trans('nearby-platform.edit_user') }}</h1>
<?php } else { ?>
              <h1 class="mb-0">{{ trans('nearby-platform.add_user') }}</h1>
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
                  <li class="breadcrumb-item active"><a href="javascript:void(0);"><div>{{ trans('nearby-platform.manage') }}</div></a></li>
                  <li class="breadcrumb-item"><a href="{{ url('dashboard/manage/users') }}"><div>{{ trans('nearby-platform.users') }}</div></a></li>
                  <li class="breadcrumb-item active"><a href="javascript:void(0);"><div><?php if (isset($sl)) { ?>{{ trans('nearby-platform.edit_user') }}<?php } else { ?>{{ trans('nearby-platform.add_user') }}<?php } ?></div></a></li>
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
      <div class="content my-4" style="">
        <div class="content-overlay" style="background-color:rgba(255,255,255,1)">
          <div class="row">
            <div class="col-12 col-sm-8 col-lg-9 order-12">
              <div class="content-padding-none">


                  <form class="form-horizontal" method="POST" action="{{ url('dashboard/manage/users/save') }}" autocomplete="off">
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

<?php if (isset($sl)) { ?>
                      <div class="card rounded-0 border-secondary mb-4">
                        <h4 class="card-header bg-secondary text-white">{{ trans('nearby-platform.details') }}</h4>
                        <div class="card-body p-0">

                          <table class="table mb-0">
                            <tr>
                              <td><strong>{{ trans('nearby-platform.created') }}</strong></td>
                              <td>{{ ($user->created_at != null) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->created_at)->tz(\Auth::user()->timezone)->toDateTimeString() : '-' }}</td>
                            </tr>
                            <tr>
                              <td><strong>{{ trans('nearby-platform.logins') }}</strong></td>
                              <td>{{ $user->logins }}</td>
                            </tr>
                            <tr>
                              <td><strong>{{ trans('nearby-platform.last_login') }}</strong></td>
                              <td><?php echo ($user->last_login == null) ? '-' : \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->last_login)->tz(\Auth::user()->timezone)->toDateTimeString(); ?></td>
                            </tr>
                          </table>

                        </div>
                      </div>
<?php } ?>

                      <div class="card rounded-0 border-secondary mb-4">
                        <h4 class="card-header bg-secondary text-white">{{ trans('nearby-platform.personal') }}</h4>
                        <div class="card-body">

                          <div class="form-group row">
                              <label for="name" class="col-12 col-sm-4 col-form-label">{{ trans('nearby-platform.name') }} <sup>*</sup></label>

                              <div class="col-12 col-sm-8">
                                <div class="input-group input-group-lg">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text rounded-0"><i class="mi person"></i></span>
                                  </div>
                                  <input id="name" type="text" class="form-control rounded-0 form-control-lg" name="name" value="{{ old('name', $user->name) }}" required>
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
                                  <input id="email" type="email" class="form-control rounded-0 form-control-lg" name="email" value="{{ old('email', $user->email) }}" required>
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
$selected = (old('language', $user->locale) == $code) ? ' selected' : '';
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
    $selected = (old('timezone', $user->timezone) == $fullzone) ? ' selected' : '';
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

                      <div class="card rounded-0 border-secondary mb-4">
                        <h4 class="card-header bg-secondary text-white">{{ trans('nearby-platform.account') }}</h4>
                        <div class="card-body">

                          <div class="form-group row mb-0">
                              <div class="col-12">
                                <div class="custom-control custom-checkbox">
                                  <input type="checkbox" class="custom-control-input" id="active" name="active" value="1"<?php if ($user->active) echo ' checked'; ?>>
                                  <label class="custom-control-label" for="active">{{ trans('nearby-platform.active') }}</label>
                                </div>
                                <small class="form-text text-muted mt-2">{{ trans('nearby-platform.user_active_help') }}</small>
                              </div>
                          </div>

                        </div>
                      </div>

                      <div class="card rounded-0 border-secondary mb-4">
                        <h4 class="card-header bg-secondary text-white">{{ trans('nearby-platform.security') }}</h4>
                        <div class="card-body">
<?php if (isset($sl)) { ?>
                          <div class="form-group row mb-0">
                              <label for="password" class="col-12 col-sm-4 col-form-label">{{ trans('nearby-platform.password') }} <?php if (! isset($sl)) echo '<sup>*</sup>'; ?></label>

                              <div class="col-12 col-sm-8">
                                <div class="input-group input-group-lg">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text rounded-0"><i class="mi lock_outline"></i></span>
                                  </div>
                                  <input id="password" type="password" class="form-control rounded-0 form-control-lg" name="password" <?php if (! isset($sl)) echo ' required'; ?>>
                                </div>

                                  @if ($errors->has('password'))
                                      <span class="form-text text-danger">
                                          <strong>{{ $errors->first('password') }}</strong>
                                      </span>
                                  @endif

                                  <span class="form-text text-muted">
                                      <small>{{ trans('nearby-platform.manage_user_password_help') }}</small>
                                  </span>

                              </div>
                          </div>
<?php } else { ?>
                          <p class="lead mb-0">{{ trans('nearby-platform.manage_user_after_create_msg') }}</p>
<?php } ?>
                        </div>
                      </div>

                      <div class="form-group mt-2 mb-0 text-right">
<?php if (isset($sl)) { ?>
                        <button type="submit" class="btn rounded-0 btn-blue btn-lg">{{ trans('nearby-platform.save') }}</button>
<?php } else { ?>
                        <button type="submit" class="btn rounded-0 btn-blue btn-lg">{{ trans('nearby-platform.add') }}</button>
<?php } ?>
                      </div>

                  </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

</div>
@endsection
