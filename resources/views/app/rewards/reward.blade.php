@extends('layouts.master')

@section('page_title')<?php echo (isset($sl)) ? trans('nearby-platform.edit_reward') : trans('nearby-platform.add_reward'); ?> - {{ config()->get('system.premium_name') }} @stop
@section('meta_description') @stop

@section('head') 
<style type="text/css">
  .custom-file-control:after {
    content: "{{ trans('nearby-platform.select_image_') }}";
  }
  .custom-file-control.selected::after {
    content: "" !important;
  }

  #step2,#step3,#step4,#step5,#step6,#step7,#step8,#step9,#step10 {
    display: none;
  }
  .timeline-label div.day {
    font-weight: bold;
    margin-top: 10px;
    font-size: 90%;
  }
  .timeline-label div.time {
    font-size: 80%;
  }
  .timeline-centered {
    position: relative;
    margin-bottom: 30px;
  }
  .timeline-centered:before, .timeline-centered:after {
    content: " ";
    display: table;
  }
  .timeline-centered:after {
    clear: both;
  }
  .timeline-centered:before, .timeline-centered:after {
    content: " ";
    display: table;
  }
  .timeline-centered:after {
    clear: both;
  }
  .timeline-centered:before {
    content: '';
    position: absolute;
    display: block;
    width: 4px;
    background: #eeeeee;
    left: 50%;
    top: 20px;
    bottom: 20px;
    margin-left: -4px;
  }
  .timeline-centered .timeline-entry {
    position: relative;
    width: 50%;
    float: right;
    margin-bottom: 50px;
    clear: both;
  }
  .timeline-centered .timeline-entry:before, .timeline-centered .timeline-entry:after {
    content: " ";
    display: table;
  }
  .timeline-centered .timeline-entry:after {
    clear: both;
  }
  .timeline-centered .timeline-entry:before, .timeline-centered .timeline-entry:after {
    content: " ";
    display: table;
  }
  .timeline-centered .timeline-entry:after {
    clear: both;
  }
  .timeline-centered .timeline-entry.begin {
    margin-bottom: 0;
  }
  .timeline-centered .timeline-entry.left-aligned {
    float: left;
  }
  .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner {
    margin-left: 0;
    margin-right: -18px;
  }
  .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner .timeline-time {
    left: auto;
    right: -140px;
    text-align: left;
  }
  .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner .timeline-icon {
    float: right;
  }
  .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner .timeline-label {
    margin-left: 0;
    margin-right: 70px;
  }
  .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner .timeline-label:after {
    left: auto;
    right: 0;
    margin-left: 0;
    margin-right: -9px;
    -moz-transform: rotate(180deg);
    -o-transform: rotate(180deg);
    -webkit-transform: rotate(180deg);
    -ms-transform: rotate(180deg);
    transform: rotate(180deg);
  }
  .timeline-centered .timeline-entry .timeline-entry-inner {
    position: relative;
    margin-left: -22px;
  }
  .timeline-centered .timeline-entry .timeline-entry-inner:before, .timeline-centered .timeline-entry .timeline-entry-inner:after {
    content: " ";
    display: table;
  }
  .timeline-centered .timeline-entry .timeline-entry-inner:after {
    clear: both;
  }
  .timeline-centered .timeline-entry .timeline-entry-inner:before, .timeline-centered .timeline-entry .timeline-entry-inner:after {
    content: " ";
    display: table;
  }
  .timeline-centered .timeline-entry .timeline-entry-inner:after {
    clear: both;
  }
  .timeline-centered .timeline-entry .timeline-entry-inner .timeline-time {
    position: absolute;
    left: -140px;
    text-align: right;
    padding: 0px 10px;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
  }
  .timeline-centered .timeline-entry .timeline-entry-inner .timeline-time > span {
    display: block;
  }
  .timeline-centered .timeline-entry .timeline-entry-inner .timeline-time > span:first-child {
    font-size: 15px;
    font-weight: bold;
  }
  .timeline-centered .timeline-entry .timeline-entry-inner .timeline-time > span:last-child {
    font-size: 12px;
  }
  .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon {
    background: #fff;
    color: #737881;
    display: block;
    width: 40px;
    height: 40px;
    -webkit-background-clip: padding-box;
    -moz-background-clip: padding;
    background-clip: padding-box;
    -webkit-border-radius: 20px;
    -moz-border-radius: 20px;
    border-radius: 20px;
    text-align: center;
    -moz-box-shadow: 0 0 0 5px #eeeeee;
    -webkit-box-shadow: 0 0 0 5px #eeeeee;
    box-shadow: 0 0 0 5px #eeeeee;
    line-height: 40px;
    font-size: 18px;
    float: left;
  }
  .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon {
    color: #fff;
  }
  .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label {
    position: relative;
    background: #eeeeee;
    padding: 0.5em;
    margin-left: 70px;
    -webkit-background-clip: padding-box;
    -moz-background-clip: padding;
    background-clip: padding-box;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
  }
  .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label:after {
    content: '';
    display: block;
    position: absolute;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 9px 9px 9px 0;
    border-color: transparent #eeeeee transparent transparent;
    left: 0;
    top: 10px;
    margin-left: -9px;
  }
  .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label h2, .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label p {
    color: #737881;
    font-family: "Noto Sans",sans-serif;
    font-size: 12px;
    margin: 0;
    line-height: 1.428571429;
  }
  .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label p + p {
    margin-top: 15px;
  }
  .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label h2 {
    font-size: 16px;
    margin-bottom: 10px;
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
              <h1 class="mb-0">{{ trans('nearby-platform.edit_reward') }}</h1>
<?php } else { ?>
              <h1 class="mb-0">{{ trans('nearby-platform.add_reward') }}</h1>
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
                  <li class="breadcrumb-item"><a href="{{ url('dashboard/rewards') }}"><div>{{ trans('nearby-platform.rewards') }}</div></a></li>
<?php if (isset($sl)) { ?>
                  <li class="breadcrumb-item active"><a href="javascript:void(0);"><div>{{ trans('nearby-platform.edit_reward') }}</div></a></li>
<?php } else { ?>
                  <li class="breadcrumb-item active"><a href="javascript:void(0);"><div>{{ trans('nearby-platform.add_reward') }}</div></a></li>
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
            <div class="col-12 col-sm-12 col-lg-7">

              <div class="card border-secondary rounded-0">
                <h4 class="card-header bg-secondary text-white rounded-0">{{ trans('nearby-platform.reward') }}</h4>
                <div class="card-body">

                  <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{ url('dashboard/rewards/save') }}" autocomplete="off">
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

                    <div class="row">

                      <div class="col-12 col-sm-6">
                        <div class="form-group row">

                          <label class="col-12 col-form-label">{{ trans('nearby-platform.image') }}</label>

                          <div class="col-12">
                            <div class="input-group input-group-lg border">
                              <label class="custom-file">
                                <input type="file" accept="image/png, image/jpeg" name="image" id="image" class="custom-file-input" style="position: absolute">
<?php if ($reward->image_file_name != null) { ?>
                                <span class="custom-file-control rounded-0 text-truncate selected" data-target="image" style="width:100%;padding:12px;top:6px;position:relative;">{{ basename($reward->image->url())  }}</span>
                                <input type="hidden" name="upload_image" id="upload_image" value="0">
<?php } else { ?>
                                <span class="custom-file-control rounded-0 text-truncate" data-target="image" style="width:100%;padding:12px;top:6px;position:relative;"></span>
                                <input type="hidden" name="upload_image" id="upload_image" value="0">
<?php } ?>
                              </label>
                              <div class="input-group-append">
                                <button type="button" class="btn btn-danger rounded-0 btn-custom-file-control-delete"><i class="mi delete"></i></button>
                              </div>
                            </div>

                          </div>
                        </div>
                      </div>

                      <div class="col-12 col-sm-6">
                        <div class="form-group">
                          <label class=" col-form-label">{{ trans('nearby-platform.icon') }} [<a href="javascript:void(0);" data-container="body" data-toggle="popover" data-trigger="focus" data-placement="top" data-content="{{ trans('nearby-platform.icon_help') }}">?</a>]</label>

                          <div class="">
                            <div class="input-group input-group-lg border">
                              <label class="custom-file">
                                <input type="file" accept="image/png, image/jpeg" name="favicon" id="favicon" class="custom-file-input" style="position: absolute">
<?php if ($reward->favicon != null) { ?>
                                <span class="custom-file-control rounded-0 text-truncate selected" data-target="favicon" style="width:100%;padding:12px;top:6px;position:relative;">{{ basename($reward->favicon)  }}</span>
                                <input type="hidden" name="upload_favicon" id="upload_favicon" value="0">
<?php } else { ?>
                                <span class="custom-file-control rounded-0 text-truncate" style="width:100%;padding:12px;top:6px;position:relative;" data-target="favicon"></span>
                                <input type="hidden" name="upload_favicon" id="upload_favicon" value="0">
<?php } ?>
                              </label>
                              <div class="input-group-append">
                                <button type="button" class="btn btn-danger rounded-0 btn-custom-file-control-delete"><i class="mi delete"></i></button>
                              </div>
                            </div>

                          </div>
                        </div>
                      </div>

                    </div>

                    <div class="form-group row">
                      <label for="title" class="col-12 col-form-label">{{ trans('nearby-platform.title') }} <sup>*</sup></label>

                      <div class="col-12">
                        <div class="input-group input-group-lg">
                          <input id="title" type="text" class="form-control rounded-0 form-control-lg" name="title" maxlength="180" placeholder="{{ trans('nearby-platform.title_of_the_reward') }}" value="{{ old('title', $reward->title) }}" required autofocus>
                        </div>

                      </div>
                    </div>

                    <div class="form-group row{{ $errors->has('details') ? ' has-error' : '' }}">
                      <label for="details" class="col-12 col-form-label">{{ trans('nearby-platform.details') }} <sup>*</sup></label>

                      <div class="col-12">
                        <div class="input-group input-group-lg">
                          <textarea id="details" type="text" class="form-control rounded-0 form-control-lg" name="details" style="height: 140px" required placeholder="{{ trans('nearby-platform.reward_and_details') }}">{{ old('details', $reward->details) }}</textarea>
                        </div>

                        @if ($errors->has('details'))
                          <span class="form-text text-danger">
                            <strong>{{ $errors->first('details') }}</strong>
                          </span>
                        @endif
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-12 col-sm-6">

                        <div class="form-group">
                          <label class="col-form-label">{{ trans('nearby-platform.visits') }} [<a href="javascript:void(0);" data-container="body" data-toggle="popover" data-trigger="focus" data-placement="top" data-content="{{ trans('nearby-platform.reward_visits_help') }}">?</a>] <sup>*</sup></label>
                          <div class="input-group">
                            <select class="form-control form-control-lg rounded-0" id="steps" name="steps">
<?php 
for ($i = 2; $i <= 10; $i++) {
  $selected = (old('steps', $reward->steps) !== null && old('steps', $reward->steps) == $i) ? ' selected': '';
?>
                              <option value="{{ $i }}"{{ $selected }}>{{ $i }}</option>
<?php } ?>
                            </select>

                          </div>
                        </div>

                      </div>
                      <div class="col-12 col-sm-6">

                        <div class="form-group">
                          <label class="col-form-label">{{ trans('nearby-platform.interval') }} [<a href="javascript:void(0);" data-container="body" data-toggle="popover" data-trigger="focus" data-placement="top" data-content="{{ trans('nearby-platform.reward_interval_help') }}">?</a>] <sup>*</sup></label>
                          <div class="input-group">
                            <select class="form-control form-control-lg rounded-0" id="interval" name="interval">
<?php 
$aIntervals = \App\Http\Controllers\RewardsController::intervalList();

foreach ($aIntervals as $interval) {

  $selected = (old('interval', $reward->interval) !== null && old('interval', $reward->interval) == $interval['minutes']) ? ' selected': '';
?>
                              <option value="{{ $interval['minutes'] }}"{{ $selected }}>{{ $interval['name'] }}</option>
<?php } ?>
                            </select>

                          </div>
                        </div>

                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="redeem_code" class="col-12 col-form-label">{{ trans('nearby-platform.redeem_code') }} [<a href="javascript:void(0);" data-container="body" data-toggle="popover" data-trigger="focus" data-placement="top" data-content="{{ trans('nearby-platform.redeem_code_reward_help') }}">?</a>] <sup>*</sup></label>

                      <div class="col-12">
                        <div class="input-group input-group-lg">
                          <input id="redeem_code" type="text" class="form-control rounded-0 form-control-lg" name="redeem_code" maxlength="180" placeholder="" value="{{ old('redeem_code', $reward->redeem_code) }}" required>
                        </div>
                      </div>
                    </div>

<?php
$aFields = \App\Http\Controllers\RewardsController::requiredFieldList($reward->language);

if (isset($reward->fields) && is_array($reward->fields)) {
  foreach ($reward->fields as $id => $saved_field) {

    // Check if id exists
    foreach ($aFields as $i => $required_field) {
      if ($id == $required_field['id']) {
        $name = ($saved_field['name'] != null && $saved_field['name'] != '') ? $saved_field['name'] : $aFields[$i]['name'];
        $checked = ($saved_field['required'] == 1) ? true : false;
        $aFields[$i]['name'] = $name;
        $aFields[$i]['checked'] = $checked;
      }
    }
  }
}
?>
                    <div class="form-group row">
                      <label class="col-12 col-form-label">{{ trans('nearby-platform.required_fields_for_redeeming_reward') }}</label>

                      <div class="col-2 col-sm-1 col-lg-1">
                        <div class="row">
                          <label class="col-12 col-form-label" title="{{ trans('nearby-platform.show_this_field') }}" style="margin:1px 0 0 5px"><i class="mi visibility"></i></label>
                        </div>
                      </div>

                      <div class="col-10 col-sm-11 col-lg-11">
                        <div class="row">
                          <label class="col-12 col-form-label">{{ trans('nearby-platform.field_name') }}</label>
                        </div>
                       </div>
<?php
foreach ($aFields as $field) {
?>
                      <div class="col-2 col-sm-1 col-lg-1">
                        <div class="form-group row">
                          <div class="col-12">
                            <div class="custom-control custom-checkbox" style="margin:-15px 0 0 5px">
                              <input type="hidden" name="required[{{ $field['id'] }}][required]" value="0">
                              <input type="checkbox" name="required[{{ $field['id'] }}][required]" value="1" class="custom-control-input" id="field_{{ $field['id'] }}"<?php if ($field['checked']) echo ' checked'; ?>>
                              <label class="custom-control-label" for="field_{{ $field['id'] }}"></label>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-10 col-sm-11 col-lg-11">
                        <div class="form-group row">
                          <div class="col-12">
                            <div class="input-group input-group-sm">
                              <input id="website" type="text" class="form-control rounded-0" name="required[{{ $field['id'] }}][name]" placeholder="{{ $field['placeholder'] }}" value="{{ old('required[' . $field['id'] . '][name]', $field['name']) }}">
                            </div>
                          </div>
                        </div>
                      </div>
<?php
}
?>
                    </div>


                    <div class="row">
                      <div class="col-12 col-sm-7">
                        <div class="form-group">
                          <label>{{ trans('nearby-platform.expires') }}</label>
                          <div class="input-group">
                            <select class="form-control custom-select rounded-0" id="expiresMonth" name="expiresMonth" style="width: 45px">
                              <option value=""></option>
<?php 
for ($i = 1; $i < 13; $i++) {
  $selected = (old('expiresMonth', $reward->expiresMonth) !== null && old('expiresMonth', $reward->expiresMonth) == $i) ? ' selected': '';
  $m = date("M", mktime(0, 0, 0, $i, 1));
?>
                              <option value="{{ $i }}"{{ $selected }}>{{ $m }}</option>
<?php } ?>
                            </select>

                            <div class="input-group-append input-group-prepend">
                              <div class="input-group-text">-</div>
                            </div>

                            <select class="form-control custom-select rounded-0" id="expiresDay" name="expiresDay">
                              <option value=""></option>
<?php
for ($i = 1; $i < 32; $i += 1) {
  $selected = (old('expiresDay', $reward->expiresDay) !== null && old('expiresDay', $reward->expiresDay) == $i) ? ' selected': '';
?>
                                <option value="{{ $i }}"{{ $selected }}>{{ $i }}</option>
<?php } ?>
                            </select>

                            <div class="input-group-append input-group-prepend">
                              <div class="input-group-text">-</div>
                            </div>

                            <select class="form-control custom-select rounded-0" id="expiresYear" name="expiresYear" style="width: 50px">
                              <option value=""></option>
<?php
for ($i = date('Y'); $i < date('Y') + 15; $i += 1) {
  $selected = (old('expiresYear', $reward->expiresYear) !== null && old('expiresYear', $reward->expiresYear) == $i) ? ' selected': '';
?>
                              <option value="{{ $i }}"{{ $selected }}>{{ $i }}</option>
<?php } ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 col-sm-5">
                        <div class="form-group">
                          <label class=" d-none d-sm-block">&nbsp;</label>
                          <div class="input-group">
                            <select class="form-control custom-select rounded-0" id="expiresHour" name="expiresHour">
                              <option value=""></option>
<?php 
for ($i = 0; $i < 24; $i++) {
  $selected = (old('expiresHour', $reward->expiresHour) !== null && old('expiresHour', $reward->expiresHour) == $i) ? ' selected': '';
  $h = (strlen($i) == 1) ? '0' . $i : $i;
?>
                              <option value="{{ $h }}"{{ $selected }}>{{ $h }}</option>
<?php } ?>
                            </select>

                            <div class="input-group-append input-group-prepend">
                              <div class="input-group-text">:</div>
                            </div>

                            <select class="form-control custom-select rounded-0" id="expiresMinute" name="expiresMinute">
                              <option value=""></option>
<?php
for ($i = 0; $i < 60; $i += 5) {
  $selected = (old('expiresMinute', $reward->expiresMinute) !== null && old('expiresMinute', $reward->expiresMinute) == $i) ? ' selected': '';
  $m = (strlen($i) == 1) ? '0' . $i : $i;
?>
                              <option value="{{ $m }}"{{ $selected }}>{{ $m }}</option>
<?php } ?>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="address" class="col-12 col-form-label">{{ trans('nearby-platform.location') }}</label>

                      <div class="col-12">
                        <div class="input-group input-group-lg">
                          <div class="input-group-prepend">
                            <span class="input-group-text rounded-0"><i class="mi location_on"></i></span>
                          </div>
                          <input id="address" type="text" class="form-control rounded-0 form-control-lg" autocomplete="address-{{ substr(base_convert(md5(microtime()), 16, 32), 0, 12) }}" name="address" value="{{ old('address', $reward->address) }}" autocomplete="off" placeholder="{{ trans('nearby-platform.google_address_placeholder') }}" onFocus="geolocate()">
                        </div>

                      </div>
                    </div>

                    <input type="hidden" id="lat" name="lat" value="{{ old('lat', $reward->lat) }}">
                    <input type="hidden" id="lng" name="lng" value="{{ old('lng', $reward->lng) }}">
                    <input type="hidden" id="street_number" name="street_number" value="{{ old('street_number', $reward->street_number) }}">
                    <input type="hidden" id="route" name="street" value="{{ old('street', $reward->street) }}">
                    <input type="hidden" id="locality" name="city" value="{{ old('city', $reward->city) }}">
                    <input type="hidden" id="administrative_area_level_1" name="state" value="{{ old('state', $reward->state) }}">
                    <input type="hidden" id="postal_code" name="postal_code" value="{{ old('postal_code', $reward->postal_code) }}">
                    <input type="hidden" id="country" name="country" value="{{ old('country', $reward->country) }}">

                    <div class="row">
                      <div class="col-12 col-sm-6">

                        <div class="form-group row">
                          <label for="phone" class="col-12 col-form-label">{{ trans('nearby-platform.phone') }}</label>

                          <div class="col-12">
                            <div class="input-group input-group-lg">
                              <input id="phone" type="text" class="form-control rounded-0 form-control-lg" name="phone" placeholder="" value="{{ old('phone', $reward->phone) }}">
                            </div>

                          </div>
                        </div>

                      </div>
                      <div class="col-12 col-sm-6">

                        <div class="form-group row">
                          <label for="website" class="col-12 col-form-label">{{ trans('nearby-platform.website') }}</label>

                          <div class="col-12">
                            <div class="input-group input-group-lg">
                              <input id="website" type="text" class="form-control rounded-0 form-control-lg" name="website" placeholder="https://" value="{{ old('website', $reward->website) }}">
                            </div>

                          </div>
                        </div>

                       </div>
                    </div>

                    <div class="mb-2 mt-2">
                      <i class="mi add_circle_outline text-primary showEditColors" style="position: relative; top:3px"></i> <a href="javascript:void(0);" class="showEditColors">{{ trans('nearby-platform.edit_button_colors') }}</a>
                    </div>

                    <div id="editColors" class="d-none ml-2 mb-2 mt-2 pl-4 pb-2 pt-2 border-left">

                      <div class="row">
                        <div class="col-12 col-sm-6">
                          <div class="form-group row">
                            <label class="col-12 col-form-label">{{ trans('nearby-platform.colors') }}</label>
                            <div class="col-12">
                              <div class="btn-group btn-block colorSelector">
                                <input type="hidden" name="primaryColor" value="{{ old('primaryColor', $reward->primaryColor) }}">
                                <button type="button" class="btn btn-block text-truncate btn-lg btn-{{ old('primaryColor', $reward->primaryColor) }} dropdown-toggle rounded-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  {{ trans('nearby-platform.primary') }}
                                </button>
                                <div class="dropdown-menu">
<?php
foreach ($colors as $color) {
?>
                                  <a class="dropdown-item btn-{{ $color }} " data-color="{{ $color }}" href="javascript:void(0);">{{ trans('nearby-platform.' . $color) }}</a>
<?php
}
?>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-6">
                          <div class="form-group row">
                            <label class="col-12 col-form-label d-none d-sm-block">&nbsp;</label>
                            <div class="col-12">
                              <div class="btn-group btn-block colorSelector">
                                <input type="hidden" name="secondaryColor" value="{{ old('secondaryColor', $reward->secondaryColor) }}">
                                <button type="button" class="btn btn-block text-truncate btn-lg btn-{{ old('secondaryColor', $reward->secondaryColor) }} dropdown-toggle rounded-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  {{ trans('nearby-platform.secondary') }}
                                </button>
                                <div class="dropdown-menu">
<?php
foreach ($colors as $color) {
?>
                                  <a class="dropdown-item btn-{{ $color }}" data-color="{{ $color }}" href="javascript:void(0);">{{ trans('nearby-platform.' . $color) }}</a>
<?php
}
?>
                                </div>
                              </div>
                            </div>
                          </div>
                         </div>
                      </div>
                    </div>

                    <div class="mb-2">
                      <i class="mi add_circle_outline text-primary showEditBtnLabels" style="position: relative; top:3px"></i> <a href="javascript:void(0);" class="showEditBtnLabels">{{ trans('nearby-platform.edit_button_labels_and_texts') }}</a>
                    </div>

                    <div id="editBtnLabels" class="d-none ml-2 mb-2 mt-2 pl-4 pb-2 pt-2 border-left">

                      <div class="form-group row">
                        <label for="language" class="col-12 col-form-label">{{ trans('nearby-platform.language') }} [<a href="javascript:void(0);" data-container="body" data-toggle="popover" data-trigger="focus" data-placement="top" data-content="{{ trans('nearby-platform.language_item_help') }}">?</a>] <sup>*</sup></label>

                        <div class="col-12">
                          <div class="input-group input-group-lg">

                            <select class="form-control form-control-lg rounded-0" name="language">
<?php
foreach (config('system.available_languages') as $code => $language) {
$selected = (old('language', $reward->language) == $code) ? ' selected' : '';
echo '<option value="' .  $code . '"' . $selected . '>' . $language . '</option>';
}
?>
                            </select>
                          </div>

                        </div>
                      </div>
<?php 
foreach ($content_fields as $name => $field) {
  $type = (isset($field['type'])) ? $field['type'] : '';
  switch ($type) {
    case 'text':
?>
                      <div class="form-group row">
                        <label for="{{ $name }}" class="col-12 col-form-label">{{ $field['label'] }}</label>
                        <div class="col-12">
                          <div class="input-group input-group-lg">
                            <input id="{{ $name }}" type="text" class="form-control rounded-0 form-control-lg" name="{{ $name }}" maxlength="180" placeholder="{{ $field['default'] }}" value="{{ old($name, $reward->{$name}) }}">
                          </div>
                        </div>
                      </div>
<?php
      break;
    case 'textarea':
?>
                      <div class="form-group row">
                        <label for="{{ $name }}" class="col-12 col-form-label">{{ $field['label'] }}</label>
                        <div class="col-12">
                          <div class="input-group input-group-lg">
                            <textarea id="{{ $name }}" name="{{ $name }}" class="form-control rounded-0 form-control-lg" rows="3" placeholder="{{ $field['default'] }}">{{ old($name, $reward->{$name}) }}</textarea>
                          </div>
                        </div>
                      </div>
<?php
      break;
  }
}
?>

                    </div>

                    <div class="form-group mt-2 mb-0 text-right">
<?php if (isset($sl) || (isset($count) && $count > 0)) { ?>
                      <a href="{{ url('dashboard/rewards') }}" class="btn rounded-0 btn-secondary btn-lg mr-2">{{ trans('nearby-platform.cancel') }}</a>
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
            <div class="col-12 col-sm-12 col-lg-5">

              <div class="card border-secondary rounded-0 mt-4 mt-lg-0">
                <h4 class="card-header bg-secondary text-white rounded-0"><?php if ($reward->favicon != null) { ?>
                  <img id="preview_favicon" src="{{ $reward->favicon }}?{{ $reward->updated_at->timestamp }}" class="mr-2" style="width: 25px; height: 25px; position: relative; top: -2px">
<?php } else { ?>
                  <img id="preview_favicon" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" class="mr-2 d-none" style="width: 25px; height: 25px; position: relative; top: -2px">
<?php } ?> {{ trans('nearby-platform.preview') }}</h4>
                <div class="card-body pb-0">

                  <h3 class="mt-0" id="preview_title">{{ trans('nearby-platform.title_of_the_reward') }}</h3>
<?php if ($reward->image_file_name != null) { ?>
                  <img id="preview_image" src="{{ $reward->image->url('2x') }}" class="img-fluid mdl-shadow--2dp mb-4" style="min-width: 100%">
<?php } else { ?>
                  <img id="preview_image" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" class="img-fluid mb-4 mdl-shadow--2dp d-none" style="min-width: 100%">
<?php } ?>
<?php /*
                  <div class="card rounded-0 border-0 text-black bg-light mb-4" id="preview_details_card">
                    <div class="card-body">
                      <p class="lead mb-0" id="preview_details">{{ trans('nearby-platform.reward_and_details') }}</p>
                    </div>
                  </div>
*/ ?>
                  <p class="lead" id="preview_details">{{ trans('nearby-platform.reward_and_details') }}</p>

                    <div class="timeline-centered my-5">

                      <article class="timeline-entry">
                        <div class="timeline-entry-inner">
                          <time class="timeline-time"></time>

                          <div class="timeline-icon bg-success">
                            <i class="mi check"></i>
                          </div>

                          <div class="timeline-label">
                            <h2 class="mb-0"><span class="preview_visitTxt">{{ trans('nearby-platform.visit') }}</span> 1</h2>
                            <div class="day">{{ \Carbon\Carbon::now()->formatLocalized('%B %d') }} </div> <div class="time">{{ \Carbon\Carbon::now()->tz(auth()->user()->timezone)->formatLocalized('%I:%M %p') }}</div>
                          </div>
                        </div>
                      </article>

                    <article class="timeline-entry left-aligned" id="step2">
                      <div class="timeline-entry-inner">
                        <time class="timeline-time"></time>

                        <div class="timeline-icon bg-none">

                        </div>

                        <div class="timeline-label">
                          <h2 class="mb-0"><span class="preview_visitTxt">{{ trans('nearby-platform.visit') }}</span> 2</h2>
                        </div>
                      </div>
                    </article>

                    <article class="timeline-entry" id="step3">
                      <div class="timeline-entry-inner">
                        <time class="timeline-time"></time>

                        <div class="timeline-icon bg-none">
                        </div>

                        <div class="timeline-label">
                          <h2 class="mb-0"><span class="preview_visitTxt">{{ trans('nearby-platform.visit') }}</span> 3</h2>
                        </div>
                      </div>
                    </article>

                    <article class="timeline-entry left-aligned" id="step4">
                      <div class="timeline-entry-inner">
                        <time class="timeline-time"></time>

                        <div class="timeline-icon bg-none">

                        </div>

                        <div class="timeline-label">
                          <h2 class="mb-0"><span class="preview_visitTxt">{{ trans('nearby-platform.visit') }}</span> 4</h2>
                        </div>
                      </div>
                    </article>

                    <article class="timeline-entry" id="step5">
                      <div class="timeline-entry-inner">
                        <time class="timeline-time"></time>

                        <div class="timeline-icon bg-none">
                        </div>

                        <div class="timeline-label">
                          <h2 class="mb-0"><span class="preview_visitTxt">{{ trans('nearby-platform.visit') }}</span> 5</h2>
                        </div>
                      </div>
                    </article>

                    <article class="timeline-entry left-aligned" id="step6">
                      <div class="timeline-entry-inner">
                        <time class="timeline-time"></time>

                        <div class="timeline-icon bg-none">

                        </div>

                        <div class="timeline-label">
                          <h2 class="mb-0"><span class="preview_visitTxt">{{ trans('nearby-platform.visit') }}</span> 6</h2>
                        </div>
                      </div>
                    </article>

                    <article class="timeline-entry" id="step7">
                      <div class="timeline-entry-inner">
                        <time class="timeline-time"></time>

                        <div class="timeline-icon bg-none">
                        </div>

                        <div class="timeline-label">
                          <h2 class="mb-0"><span class="preview_visitTxt">{{ trans('nearby-platform.visit') }}</span> 7</h2>
                        </div>
                      </div>
                    </article>

                    <article class="timeline-entry left-aligned" id="step8">
                      <div class="timeline-entry-inner">
                        <time class="timeline-time"></time>

                        <div class="timeline-icon bg-none">

                        </div>

                        <div class="timeline-label">
                          <h2 class="mb-0"><span class="preview_visitTxt">{{ trans('nearby-platform.visit') }}</span> 8</h2>
                        </div>
                      </div>
                    </article>

                    <article class="timeline-entry" id="step9">
                      <div class="timeline-entry-inner">
                        <time class="timeline-time"></time>

                        <div class="timeline-icon bg-none">
                        </div>

                        <div class="timeline-label">
                          <h2 class="mb-0"><span class="preview_visitTxt">{{ trans('nearby-platform.visit') }}</span> 9</h2>
                        </div>
                      </div>
                    </article>

                    <article class="timeline-entry left-aligned" id="step10">
                      <div class="timeline-entry-inner">
                        <time class="timeline-time"></time>

                        <div class="timeline-icon bg-none">

                        </div>

                        <div class="timeline-label">
                          <h2 class="mb-0"><span class="preview_visitTxt">{{ trans('nearby-platform.visit') }}</span> 10</h2>
                        </div>
                      </div>
                    </article>

                    <article class="timeline-entry begin">
                      <div class="timeline-entry-inner">
                        <div class="timeline-icon">
                          <i class="mi "></i>
                        </div>
                      </div>
                    </article>

                  </div>

                  <small class="text-muted" id="preview_expires"></small>

                  <div class="row mt-4">
                    <div class="col-12">
                      <a href="javascript:void(0);" class="btn btn-{{ $reward->primaryColor }} btn-xlg text-truncate rounded-0 btn-block mb-4 primaryColor"><i class="mi beenhere icon-2" style="top:7px"></i> <span id="preview_primaryBtnText">{{ trans('nearby-platform.check_in') }}</span></a>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col">
                      <a href="javascript:void(0);" class="btn btn-{{ $reward->secondaryColor }} btn-lg text-truncate rounded-0 btn-block mb-4 secondaryColor"><i class="mi phone"></i> <span id="preview_callBtnText">{{ trans('nearby-platform.call') }}</span></a>
                    </div>
                    <div class="col">
                      <a href="javascript:void(0);" class="btn btn-{{ $reward->secondaryColor }} btn-lg text-truncate rounded-0 btn-block mb-4 secondaryColor"><i class="mi info_outline"></i> <span id="preview_moreBtnText">{{ trans('nearby-platform.more') }}</span></a>
                    </div>
                    <div class="col">
                      <div class="dropdown">
                        <button class="btn btn-{{ $reward->secondaryColor }} btn-lg text-truncate rounded-0 btn-block mb-4 dropdown-toggle secondaryColor" type="button" id="dropdownShare" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <span id="preview_shareBtnText">{{ trans('nearby-platform.share') }}</span>
                        </button>
                        <div class="dropdown-menu rounded-0 dropdown-menu-right btn-block" aria-labelledby="dropdownShare">
                          <a class="dropdown-item" href="javascript:void(0);"><span style="width:25px;float:left; text-align: center"><i class="fab fa-facebook" aria-hidden="true"></i></span> Facebook</a>
                          <a class="dropdown-item" href="javascript:void(0);"><span style="width:25px;float:left; text-align: center"><i class="fab fa-google" aria-hidden="true"></i></span> Google+</a>
                          <a class="dropdown-item" href="javascript:void(0);"><span style="width:25px;float:left; text-align: center"><i class="fab fa-twitter" aria-hidden="true"></i></span> Twitter</a>
                          <a class="dropdown-item" href="javascript:void(0);"><span style="width:25px;float:left; text-align: center"><i class="far fa-envelope" aria-hidden="true"></i></span> Mail</a>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>

            </div>

          </div>
        </div>
      </div>
    </section>

</div>
@endsection

@section('page_bottom')
<script>
$('form:not(.ajax)').submit(function() {
  saveLoader();
});

function saveLoader() {
  $.blockUI({
    message: '<div class="loader" style="margin-top:30px"></div>',
    fadeIn: 0,
    fadeOut: 100,
    baseZ: 21000,
    overlayCSS: {
      backgroundColor: '#000'
    },
    css: {
      border: 'none',
      padding: '0',
      backgroundColor: 'transparant',
      opacity: 1,
      color: '#fff'
    }
  });
}

$(function(){
  var btnColors = '<?php
foreach ($colors as $color) { echo 'btn-' . $color . ' '; }
?> btn-success';

  $('.colorSelector .dropdown-item').on('click', function() {
    var color = $(this).attr('data-color');
    $(this).parent().parent().find('.dropdown-toggle').removeClass(btnColors);
    $(this).parent().parent().find('.dropdown-toggle').addClass('btn-' + color);
    $(this).parent().parent().find('input[type=hidden]').val(color);

    var getColor = $(this).parent().parent().find('input[type=hidden]').attr('name');

    $('.' + getColor).removeClass(btnColors);
    $('.' + getColor).addClass('btn-' + color);
  });

  $('.showEditColors').on('click', function() {
    if ($('#editColors').hasClass('d-none')) {
      $('#editColors').removeClass('d-none');
      $('i.showEditColors').addClass('remove_circle_outline').removeClass('add_circle_outline');
    } else {
      $('#editColors').addClass('d-none');
      $('i.showEditColors').addClass('add_circle_outline').removeClass('remove_circle_outline');
    }
  });

  $('.showEditBtnLabels').on('click', function() {
    if ($('#editBtnLabels').hasClass('d-none')) {
      $('#editBtnLabels').removeClass('d-none');
      $('i.showEditBtnLabels').addClass('remove_circle_outline').removeClass('add_circle_outline');
    } else {
      $('#editBtnLabels').addClass('d-none');
      $('i.showEditBtnLabels').addClass('add_circle_outline').removeClass('remove_circle_outline');
    }
  });

  $('#image').on('change', function() {
    readURL(this);
  });

  $('#favicon').on('change', function() {
    readURL(this);
  });

  $('#steps,#expiresMonth,#expiresDay,#expiresYear,#expiresHour,#expiresMinute').on('change', renderPreview);
  $('#title,#details,#primaryBtnText,#callBtnText,#moreBtnText,#shareBtnText,#visitTxt').on('keyup', renderPreview);

  renderPreview();

  $('.custom-file-input').on('change', function() {
    setFileVal($(this));
  });

  $('.btn-custom-file-control-delete').on('click', function() {
    $(this).parent().parent().find('.custom-file-input').val('').next('.custom-file-control').removeClass('selected').html('');

    var target = $(this).parent().parent().find('.custom-file-input').val('').next('.custom-file-control').attr('data-target');

    if (target == 'image') {
      $('#upload_image').val('1');
      $('#preview_image').addClass('d-none');
    } else if (target == 'favicon') {
      $('#upload_favicon').val('1');
      $('#preview_favicon').addClass('d-none');
    }
  });

});

function setFileVal(_this) {
  var filePath = _this.val();
  var fileName = filePath.replace(/^.*\\/, "");

  var target = _this.next('.custom-file-control').attr('data-target');

  // Make sure new image is uploaded
  if (target == 'image') {
    $('#upload_image').val('1');
  } else if (target == 'favicon') {
    $('#upload_favicon').val('1');
  }

  if (filePath != '') {
    _this.next('.custom-file-control').addClass('selected').html(fileName);
  } else {
    _this.next('.custom-file-control').removeClass('selected');
  }
}

function renderPreview() {
  var steps = parseInt($('#steps').val());
  $('#step2,#step3,#step4,#step5,#step6,#step7,#step8,#step9,#step10').hide();
  $(new Array(steps)).each(function(index) {
    $('#step' + (index + 1)).show();
  });

  var title = $('#title').val();
  if (title != '') {
    $('#preview_title').html(title);
  } else {
    $('#preview_title').html('{{ trans('nearby-platform.title_of_the_reward') }}');
  }

  var visitTxt = $('#visitTxt').val();
  if (visitTxt != '') {
    $('.preview_visitTxt').html(visitTxt);
  } else {
    $('.preview_visitTxt').html('{{ trans('nearby-platform.visit') }}');
  }

  var details = $('#details').val();
  details = details.replace(/(?:\r\n|\r|\n)/g, '<br>');

  if (details != '') {
    $('#preview_details').html(details);
  } else {
    $('#preview_details').html('{{ trans('nearby-platform.reward_and_details') }}');
  }

  var primaryBtnText = $('#primaryBtnText').val();
  if (primaryBtnText != '') {
    $('#preview_primaryBtnText').html(primaryBtnText);
  } else {
    $('#preview_primaryBtnText').html('{{ trans('nearby-platform.check_in') }}');
  }

  var callBtnText = $('#callBtnText').val();
  if (callBtnText != '') {
    $('#preview_callBtnText').html(callBtnText);
  } else {
    $('#preview_callBtnText').html('{{ trans('nearby-platform.call') }}');
  }

  var moreBtnText = $('#moreBtnText').val();
  if (moreBtnText != '') {
    $('#preview_moreBtnText').html(moreBtnText);
  } else {
    $('#preview_moreBtnText').html('{{ trans('nearby-platform.more') }}');
  }

  var shareBtnText = $('#shareBtnText').val();
  if (shareBtnText != '') {
    $('#preview_shareBtnText').html(shareBtnText);
  } else {
    $('#preview_shareBtnText').html('{{ trans('nearby-platform.share') }}');
  }

  var expiresMonth = $('#expiresMonth').val();
  var expiresDay = $('#expiresDay').val();
  var expiresYear = $('#expiresYear').val();

  if (expiresMonth != '' && expiresDay != '' && expiresYear != '') {
    var expiresHour = $('#expiresHour').val();
    var expiresMinute = $('#expiresMinute').val();

    if (expiresHour != '' && expiresMinute != '') {
      expiresHour = parseInt(expiresHour);
      var period = (expiresHour >= 12) ? 'pm': 'am';
      if (expiresHour > 12) expiresHour -= 12;
      $('#preview_expires').html('Expires ' + expiresYear + '/' + expiresMonth + '/' + expiresDay + ' (Y/M/D) at ' + expiresHour + ':' + expiresMinute + ' ' + period);
    } else {
      $('#preview_expires').html('Expires ' + expiresYear + '/' + expiresMonth + '/' + expiresDay + ' (Y/M/D)');
    }
  } else {
    $('#preview_expires').html('');
  }

  var phone = $('#phone').val();
  var website = $('#website').val();

  var column_count = (phone == '' && website == '') ? 1 : 2;
  if (phone != '' && website != '') column_count = 3;

  if (phone != '') {
      
  }
}

function readURL(input) {
  if (input.files && input.files[0]) {
    var target = $(input).attr('id');

    if (target == 'image') {
      $('#preview_image').removeClass('d-none');
    } else if (target == 'favicon') {
      $('#preview_favicon').removeClass('d-none');
    }

    var reader = new FileReader();

    reader.onload = function(e) {
      if (target == 'image') {
        $('#preview_image').attr('src', e.target.result);
      } else if (target == 'favicon') {
        $('#preview_favicon').attr('src', e.target.result);
      }
    }

    reader.readAsDataURL(input.files[0]);
  }
}

var placeSearch, address;
var componentForm = {
  street_number: 'short_name',
  route: 'long_name',
  locality: 'long_name',
  administrative_area_level_1: 'short_name',
  country: 'short_name',
  postal_code: 'short_name'
};

function initAutocomplete() {
  // Create the autocomplete object, restricting the search to geographical
  // location types.
  autocomplete = new google.maps.places.Autocomplete(
      /** @type {!HTMLInputElement} */(document.getElementById('address')),
      {types: ['geocode']});

  // When the user selects an address from the dropdown, populate the address
  // fields in the form.
  autocomplete.addListener('place_changed', fillInAddress);
}

function fillInAddress() {
  // Get the place details from the autocomplete object.
  var place = autocomplete.getPlace();

  for (var component in componentForm) {
    document.getElementById(component).value = '';
  }

  if (typeof place !== 'undefined') { 
    document.getElementById('lat').value = place.geometry.location.lat();
    document.getElementById('lng').value = place.geometry.location.lng();

    // Get each component of the address from the place details
    // and fill the corresponding field on the form.
    for (var i = 0; i < place.address_components.length; i++) {
      var addressType = place.address_components[i].types[0];
      if (componentForm[addressType]) {
        var val = place.address_components[i][componentForm[addressType]];
        document.getElementById(addressType).value = val;
      }
    }
  } else {
    console.log('autocomplete not found');
  }
}

// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var geolocation = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
      var circle = new google.maps.Circle({
        center: geolocation,
        radius: position.coords.accuracy
      });
      autocomplete.setBounds(circle.getBounds());
    });
  }
}

$('input').on('keypress', function(e) {
  if (e.keyCode == 13) {
    if ($(this).attr('id') == 'address') {
      fillInAddress();
    }
    return false;
    e.preventDefault();
  }
});
</script>

<script async defer
src="https://maps.googleapis.com/maps/api/js?key={{ env('GMAPS_KEY') }}&libraries=places&callback=initAutocomplete&language={{ $reward->language }}">
</script>

@stop