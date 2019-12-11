<!DOCTYPE html>
<html lang="{{ \App::getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />

    <title>{{ $reward->title }}</title>

    <link rel="canonical" href="{{ url()->full() }}">

<?php if ($reward->favicon != null) { ?>
    <link rel="shortcut icon" type="image/x-icon" href="{{ $reward->favicon }}" />
<?php } elseif ($reward->image_file_name != null) { ?>
    <link rel="icon" type="image/png" href="{{ url($reward->image->url('favicon')) }}" />
<?php } else { ?>
    <link rel="shortcut icon" href="{{ url('favicon.ico') }}" type="image/x-icon" />
<?php } ?>

@include('layouts._general-content-includes')

<?php if (isset($color)) { ?>
    <meta name="theme-color" content="{{ $color }}">
<?php } ?>

  </head>
  <body>
    <div class="container max-width-600">
      <div class="row">
        <div class="col-12">
          <div class="mt-4 mt-md-5">
            <h1 class="mb-0 mb-md-2">{!! $reward->title !!}</h1>

            @if ($errors->any())
              <div class="alert alert-danger mb-3 mt-4 rounded-0">
                {{ trans('nearby-platform.check_in_reward_failed_text') }}

                <ul class="mt-4">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <p class="lead mt-3">{{ trans('nearby-platform.check_in_reward_text') }}</p>

            <form class="form-horizontal" method="POST" action="{{ url('reward/check-in/' . $reward_hash) }}" autocomplete="off">
              {{ csrf_field() }}
              <input type="hidden" name="pc" value="{{ request()->get('pc') }}">

              <div class="form-group row">
                <label for="redeem_code" class="col-12 col-form-label">{{ trans('nearby-platform.redeem_code') }} <sup>*</sup></label>
                <div class="col-12">
                  <input id="redeem_code" type="password" class="form-control rounded-0 form-control-lg{{ $errors->has('redeem_code') ? ' is-invalid' : '' }}" name="redeem_code" value="" autocomplete="off" placeholder="" required>
                  <small class="form-text text-muted">{{ trans('nearby-platform.check_in_reward_code_help') }}</small>

                  @if ($errors->has('redeem_code'))
                    <span class="form-text text-danger">
                      <strong>{{ $errors->first('redeem_code') }}</strong>
                    </span>
                  @endif

                </div>
              </div>

              <button type="submit" class="mt-5 btn rounded-0 btn-{{ $reward->primaryColor }} btn-xlg btn-block text-truncate">{{ trans('nearby-platform.check_in') }}</button>
            </form>
          </div>
        </div>
      </div>
    </div>

@include('layouts._general-content-includes-footer')

  </body>
</html>