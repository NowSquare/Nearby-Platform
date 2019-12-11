@extends('layouts.master')

@section('page_title')<?php echo (isset($sl)) ? (request()->has('duplicate')) ? trans('nearby-platform.duplicate_deal') : trans('nearby-platform.edit_deal') : trans('nearby-platform.add_deal'); ?> - {{ config()->get('system.premium_name') }} @stop
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
              <h1 class="mb-0">{{ (request()->has('duplicate')) ? trans('nearby-platform.duplicate_deal') : trans('nearby-platform.edit_deal') }}</h1>
<?php } else { ?>
              <h1 class="mb-0">{{ trans('nearby-platform.add_deal') }}</h1>
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
                  <li class="breadcrumb-item"><a href="{{ url('dashboard/deals') }}"><div>{{ trans('nearby-platform.deals') }}</div></a></li>
<?php if (isset($sl)) { ?>
                  <li class="breadcrumb-item active"><a href="javascript:void(0);"><div>{{ (request()->has('duplicate')) ? trans('nearby-platform.duplicate_deal') : trans('nearby-platform.edit_deal') }}</div></a></li>
<?php } else { ?>
                  <li class="breadcrumb-item active"><a href="javascript:void(0);"><div>{{ trans('nearby-platform.add_deal') }}</div></a></li>
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
                <h4 class="card-header bg-secondary text-white rounded-0">{{ trans('nearby-platform.deal') }}</h4>
                <div class="card-body">

                  <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{ url('dashboard/deals/save') }}" autocomplete="off">
<?php if (isset($sl) && ! request()->has('duplicate')) { ?>
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
<?php if ($deal->image_file_name != null) { ?>
                                <span class="custom-file-control rounded-0 text-truncate selected" data-target="image" style="width:100%;padding:12px;top:6px;position:relative;">{{ basename($deal->image->url())  }}</span>
                                <input type="hidden" name="upload_image" id="upload_image" value="{{ (request()->has('duplicate')) ? '1' : '0' }}">
<?php if ($deal->image_file_name != null && request()->has('duplicate')) { ?>
                                <input type="hidden" name="upload_image_duplicate" value="{{ url($deal->image->url()) }}">
<?php } ?>
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
<?php if ($deal->favicon != null) { ?>
                                <span class="custom-file-control rounded-0 text-truncate selected" data-target="favicon" style="width:100%;padding:12px;top:6px;position:relative;">{{ basename($deal->favicon) }}</span>
                                <input type="hidden" name="upload_favicon" id="upload_favicon" value="{{ (request()->has('duplicate')) ? '1' : '0' }}">
<?php if ($deal->favicon != null && request()->has('duplicate')) { ?>
                                <input type="hidden" name="upload_favicon_duplicate" value="{{ $deal->favicon }}">
<?php } ?>
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
                          <input id="title" type="text" class="form-control rounded-0 form-control-lg" name="title" maxlength="180" placeholder="{{ trans('nearby-platform.title_of_the_deal') }}" value="{{ old('title', $deal->title) }}" required autofocus>
                        </div>

                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="details" class="col-12 col-form-label">{{ trans('nearby-platform.details') }} <sup>*</sup></label>

                      <div class="col-12">
                        <div class="input-group input-group-lg">
<?php /*str_replace("\n", "</p><p>", '<p>' . $deal->details . '</p>')*/ ?><?php /* nl2br */ ?>
                          <textarea id="details" type="text" class="form-control rounded-0 form-control-lg" name="details" rows="4" novalidate placeholder="{{ trans('nearby-platform.details_and_terms') }}">{!! old('details', ($deal->details)) !!}</textarea>
                        </div>

                      </div>
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
  $selected = (old('expiresMonth', $deal->expiresMonth) !== null && old('expiresMonth', $deal->expiresMonth) == $i) ? ' selected': '';
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
  $selected = (old('expiresDay', $deal->expiresDay) !== null && old('expiresDay', $deal->expiresDay) == $i) ? ' selected': '';
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
  $selected = (old('expiresYear', $deal->expiresYear) !== null && old('expiresYear', $deal->expiresYear) == $i) ? ' selected': '';
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
  $selected = (old('expiresHour', $deal->expiresHour) !== null && old('expiresHour', $deal->expiresHour) == $i) ? ' selected': '';
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
  $selected = (old('expiresMinute', $deal->expiresMinute) !== null && old('expiresMinute', $deal->expiresMinute) == $i) ? ' selected': '';
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
                          <input id="address" type="text" class="form-control rounded-0 form-control-lg" autocomplete="address-{{ substr(base_convert(md5(microtime()), 16, 32), 0, 12) }}" name="address" value="{{ old('address', $deal->address) }}" autocomplete="off" placeholder="{{ trans('nearby-platform.google_address_placeholder') }}" onFocus="geolocate()">
                        </div>

                      </div>
                    </div>

                    <input type="hidden" id="lat" name="lat" value="{{ old('lat', $deal->lat) }}">
                    <input type="hidden" id="lng" name="lng" value="{{ old('lng', $deal->lng) }}">
                    <input type="hidden" id="street_number" name="street_number" value="{{ old('street_number', $deal->street_number) }}">
                    <input type="hidden" id="route" name="street" value="{{ old('street', $deal->street) }}">
                    <input type="hidden" id="locality" name="city" value="{{ old('city', $deal->city) }}">
                    <input type="hidden" id="administrative_area_level_1" name="state" value="{{ old('state', $deal->state) }}">
                    <input type="hidden" id="postal_code" name="postal_code" value="{{ old('postal_code', $deal->postal_code) }}">
                    <input type="hidden" id="country" name="country" value="{{ old('country', $deal->country) }}">

                    <div class="row">
                      <div class="col-12 col-sm-6">

                        <div class="form-group row">
                          <label for="phone" class="col-12 col-form-label">{{ trans('nearby-platform.phone') }}</label>

                          <div class="col-12">
                            <div class="input-group input-group-lg">
                              <input id="phone" type="text" class="form-control rounded-0 form-control-lg" name="phone" placeholder="" value="{{ old('phone', $deal->phone) }}">
                            </div>

                          </div>
                        </div>

                      </div>
                      <div class="col-12 col-sm-6">

                        <div class="form-group row">
                          <label for="website" class="col-12 col-form-label">{{ trans('nearby-platform.website') }}</label>

                          <div class="col-12">
                            <div class="input-group input-group-lg">
                              <input id="website" type="text" class="form-control rounded-0 form-control-lg" name="website" placeholder="https://" value="{{ old('website', $deal->website) }}">
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
                                <input type="hidden" name="primaryColor" value="{{ old('primaryColor', $deal->primaryColor) }}">
                                <button type="button" class="btn btn-block text-truncate btn-lg btn-{{ old('primaryColor', $deal->primaryColor) }} dropdown-toggle rounded-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                                <input type="hidden" name="secondaryColor" value="{{ old('secondaryColor', $deal->secondaryColor) }}">
                                <button type="button" class="btn btn-block text-truncate btn-lg btn-{{ old('secondaryColor', $deal->secondaryColor) }} dropdown-toggle rounded-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                      <i class="mi add_circle_outline text-primary showEditBtnLabels" style="position: relative; top:3px"></i> <a href="javascript:void(0);" class="showEditBtnLabels">{{ trans('nearby-platform.edit_button_labels') }}</a>
                    </div>

                    <div id="editBtnLabels" class="d-none ml-2 mb-2 mt-2 pl-4 pb-2 pt-2 border-left">

                      <div class="form-group row">
                        <label for="language" class="col-12 col-form-label">{{ trans('nearby-platform.language') }} [<a href="javascript:void(0);" data-container="body" data-toggle="popover" data-trigger="focus" data-placement="top" data-content="{{ trans('nearby-platform.language_item_help') }}">?</a>] <sup>*</sup></label>

                        <div class="col-12">
                          <div class="input-group input-group-lg">

                            <select class="form-control form-control-lg rounded-0" name="language">
<?php
foreach (config('system.available_languages') as $code => $language) {
$selected = (old('language', $deal->language) == $code) ? ' selected' : '';
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
                            <input id="{{ $name }}" type="text" class="form-control rounded-0 form-control-lg" name="{{ $name }}" maxlength="180" placeholder="{{ $field['default'] }}" value="{{ old($name, $deal->{$name}) }}">
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
                            <textarea id="{{ $name }}" name="{{ $name }}" class="form-control rounded-0 form-control-lg" rows="3" placeholder="{{ $field['default'] }}">{{ old($name, $deal->{$name}) }}</textarea>
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
                      <a href="{{ url('dashboard/deals') }}" class="btn rounded-0 btn-secondary btn-lg mr-2">{{ trans('nearby-platform.cancel') }}</a>
<?php } ?>
<?php if (isset($sl)) { ?>
                      <button type="submit" class="btn rounded-0 btn-primary btn-lg">{{ (request()->has('duplicate')) ? trans('nearby-platform.duplicate') : trans('nearby-platform.save') }}</button>
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
                <h4 class="card-header bg-secondary text-white rounded-0"><?php if ($deal->favicon != null) { ?>
                  <img id="preview_favicon" src="{{ $deal->favicon }}?{{ $deal->updated_at->timestamp }}" class="mr-2" style="width: 25px; height: 25px; position: relative; top: -2px">
<?php } else { ?>
                  <img id="preview_favicon" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" class="mr-2 d-none" style="width: 25px; height: 25px; position: relative; top: -2px">
<?php } ?> {{ trans('nearby-platform.preview') }}</h4>
                <div class="card-body pb-0">

                  <h3 class="mt-0" id="preview_title">{{ trans('nearby-platform.title_of_the_deal') }}</h3>
<?php if ($deal->image_file_name != null) { ?>
                  <img id="preview_image" src="{{ $deal->image->url('2x') }}" class="img-fluid mdl-shadow--2dp mb-4" style="min-width: 100%">
<?php } else { ?>
                  <img id="preview_image" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" class="img-fluid mb-4 mdl-shadow--2dp d-none" style="min-width: 100%">
<?php } ?>
<?php /*
                  <div class="card rounded-0 border-0 text-black bg-light mb-4" id="preview_details_card">
                    <div class="card-body">
                      <p class="lead mb-0" id="preview_details">{{ trans('nearby-platform.details_and_terms') }}</p>
                    </div>
                  </div>
*/ ?>
                  <p class="lead" id="preview_details">{{ trans('nearby-platform.details_and_terms') }}</p>

                  <small class="text-muted" id="preview_expires"></small>

                  <div class="row mt-4">
                    <div class="col-12">
                      <a href="javascript:void(0);" class="btn btn-{{ $deal->primaryColor }} btn-xlg text-truncate rounded-0 btn-block mb-4 primaryColor"><i class="mi file_download icon-2" style="top:8px"></i> <span id="preview_primaryBtnText">{{ trans('nearby-platform.save_to_device') }}</span></a>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col">
                      <a href="javascript:void(0);" class="btn btn-{{ $deal->secondaryColor }} btn-lg text-truncate rounded-0 btn-block mb-4 secondaryColor"><i class="mi phone"></i> <span id="preview_callBtnText">{{ trans('nearby-platform.call') }}</span></a>
                    </div>
                    <div class="col">
                      <a href="javascript:void(0);" class="btn btn-{{ $deal->secondaryColor }} btn-lg text-truncate rounded-0 btn-block mb-4 secondaryColor"><i class="mi info_outline"></i> <span id="preview_moreBtnText">{{ trans('nearby-platform.more') }}</span></a>
                    </div>
                    <div class="col">
                      <div class="dropdown">
                        <button class="btn btn-{{ $deal->secondaryColor }} btn-lg text-truncate rounded-0 btn-block mb-4 dropdown-toggle secondaryColor" type="button" id="dropdownShare" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
<script src="{{ url('assets/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script>
tinymce.init({
  selector: '#details',
  language: '{{ app()->getLocale() }}',
  content_css: '{{ url('assets/css/tinymce-content.min.css') }}',
  forced_root_block: '',
  plugins: 'advlist autolink link image paste lists colorpicker textcolor contextmenu placeholder autoresize',
  toolbar: 'fontsizeselect | bold italic link | bullist numlist<?php /* outdent indent*/ ?> | forecolor backcolor',
  contextmenu: 'paste | link image',
  paste_as_text: true,
  fontsize_formats: "0.75rem 1rem 1.25rem 1.5rem 1.75rem 2rem 2.25rem 2.5rem 2.75rem",
  menubar: false,
  statusbar: false,
  convert_urls:false,
  relative_urls:false,
  placeholder_attrs: {
    style: {
      'font-family': '"Open Sans", sans-serif',
      'font-size': '1.25rem',
      'font-weight': '300',
      position: 'absolute',
      top:'8px',
      left:'10px',
      color: '#495057',
      padding: '1%',
      width:'98%',
      overflow: 'hidden',
      'white-space': 'pre-wrap'
    }
  },
  height: 220,
  autoresize_min_height: 220,
  autoresize_bottom_margin: 0,
  autoresize_overflow_padding: 0,
  setup : function(ed) {
    ed.on('init', function() {
      this.execCommand("fontSize", false, "1.25rem");
      this.getDoc().body.style.fontSize = "1.25rem";
    });
  },
  init_instance_callback: function (editor) {
    editor.on('KeyUp', function (e) {
      var c = editor.getContent();
      if (c == '') c = '{{ trans('nearby-platform.details_and_terms') }}';
      $('#preview_details').html(c);
    });
    editor.on('Change', function (e) {
      var c = editor.getContent();
      if (c == '') c = '{{ trans('nearby-platform.details_and_terms') }}';
      $('#preview_details').html(c);
    });
  }
});
</script>

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

  $('#expiresMonth,#expiresDay,#expiresYear,#expiresHour,#expiresMinute').on('change', renderPreview);
  $('#title,#details,#primaryBtnText,#callBtnText,#moreBtnText,#shareBtnText').on('keyup', renderPreview);

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
  var title = $('#title').val();
  if (title != '') {
    $('#preview_title').html(title);
  } else {
    $('#preview_title').html('{{ trans('nearby-platform.title_of_the_deal') }}');
  }

  var details = $('#details').val();
  //details = details.replace(/(?:\r\n|\r|\n)/g, '<br>');

  if (details != '') {
    $('#preview_details').html(details);
  } else {
    $('#preview_details').html('{{ trans('nearby-platform.details_and_terms') }}');
  }

  var primaryBtnText = $('#primaryBtnText').val();
  if (primaryBtnText != '') {
    $('#preview_primaryBtnText').html(primaryBtnText);
  } else {
    $('#preview_primaryBtnText').html('{{ trans('nearby-platform.save_to_device') }}');
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
src="https://maps.googleapis.com/maps/api/js?key={{ env('GMAPS_KEY') }}&libraries=places&callback=initAutocomplete&language={{ $deal->language }}">
</script>

@stop