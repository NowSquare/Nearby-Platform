<!DOCTYPE html>
<html lang="{{ \App::getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />

    <title>{{ $coupon->title }}</title>

    <link rel="canonical" href="{{ url()->full() }}">

<?php if ($coupon->favicon != null) { ?>
    <link rel="shortcut icon" type="image/x-icon" href="{{ $coupon->favicon }}" />
<?php } elseif ($coupon->image_file_name != null) { ?>
    <link rel="icon" type="image/png" href="{{ url($coupon->image->url('favicon')) }}" />
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
            <h1 class="mb-0 mb-md-2">{!! $coupon->title !!}</h1>

            @if ($errors->any())
              <div class="alert alert-danger mb-3 mt-4 rounded-0">
                {{ trans('nearby-platform.redeem_coupon_failed_text') }}

                <ul class="mt-4">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <p class="lead mt-3">{{ trans('nearby-platform.redeem_coupon_text') }}</p>

            <form class="form-horizontal" method="POST" action="{{ url('coupon/verify/' . $coupon_hash) }}" autocomplete="off">
              {{ csrf_field() }}
              <input type="hidden" name="pc" value="{{ request()->get('pc') }}">
<?php
$aFields = \App\Http\Controllers\CouponsController::requiredFieldList();

if (isset($coupon->fields) && is_array($coupon->fields)) {
  foreach ($coupon->fields as $id => $saved_field) {

    // Check if id exists
    foreach ($aFields as $i => $required_field) {
      if ($id == $required_field['id']) {
        $name = ($saved_field['name'] != null && $saved_field['name'] != '') ? $saved_field['name'] : $aFields[$i]['placeholder'];
        $checked = ($saved_field['required'] == 1) ? true : false;
        $aFields[$i]['name'] = $name;
        $aFields[$i]['checked'] = $checked;
      }
    }
  }
}
?>
<?php
$first_field = '';
foreach ($aFields as $i => $field) {
  if ($field['checked']) {
    if ($i == 0) $first_field = $field['id'];
?>
              <div class="form-group row">
                <label for="field_{{ $field['id'] }}" class="col-12 col-form-label">{{ $field['name'] }} <sup>*</sup></label>
                <div class="col-12">
                  <input id="field_{{ $field['id'] }}" type="{{ $field['type'] }}" maxlength="{{ $field['maxlength'] }}" class="form-control rounded-0 form-control-lg" name="{{ $field['id'] }}" value="{{ old($field['id'] , request()->get($field['id'], '')) }}" autocomplete="off" placeholder="" required>
                </div>
              </div>
<?php
  }
}
?>
              <div class="form-group row">
                <label for="redeem_code" class="col-12 col-form-label">{{ trans('nearby-platform.redeem_code') }} <sup>*</sup></label>
                <div class="col-12">
                  <input id="redeem_code" type="password" class="form-control rounded-0 form-control-lg{{ $errors->has('redeem_code') ? ' is-invalid' : '' }}" name="redeem_code" value="" autocomplete="off" placeholder="" required>
                  <small class="form-text text-muted">{{ trans('nearby-platform.redeem_coupon_code_help') }}</small>

                  @if ($errors->has('redeem_code'))
                    <span class="form-text text-danger">
                      <strong>{{ $errors->first('redeem_code') }}</strong>
                    </span>
                  @endif

                </div>
              </div>

              <button type="submit" class="mt-5 btn rounded-0 btn-{{ $coupon->primaryColor }} btn-xlg btn-block text-truncate">{{ trans('nearby-platform.redeem') }}</button>
            </form>
          </div>
        </div>
      </div>
    </div>

@include('layouts._general-content-includes-footer')

  </body>
</html>