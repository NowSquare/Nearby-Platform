@extends('layouts.master')

@section('page_title')<?php echo (isset($sl)) ? (request()->has('duplicate')) ? trans('nearby-platform.duplicate_property') : trans('nearby-platform.edit_property') : trans('nearby-platform.add_property'); ?> - {{ config()->get('system.premium_name') }} @stop
@section('meta_description') @stop

@section('head') 
<style type="text/css">
  .custom-file-control:after {
    content: "{{ trans('nearby-platform.select_image_') }}";
  }
  .custom-file-control.selected::after {
    content: "" !important;
  }
  .surface_areas {
    display: none;
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
              <h1 class="mb-0">{{ (request()->has('duplicate')) ? trans('nearby-platform.duplicate_property') : trans('nearby-platform.edit_property') }}</h1>
<?php } else { ?>
              <h1 class="mb-0">{{ trans('nearby-platform.add_property') }}</h1>
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
                  <li class="breadcrumb-item"><a href="{{ url('dashboard/properties') }}"><div>{{ trans('nearby-platform.properties') }}</div></a></li>
<?php if (isset($sl)) { ?>
                  <li class="breadcrumb-item active"><a href="javascript:void(0);"><div>{{ (request()->has('duplicate')) ? trans('nearby-platform.duplicate_property') : trans('nearby-platform.edit_property') }}</div></a></li>
<?php } else { ?>
                  <li class="breadcrumb-item active"><a href="javascript:void(0);"><div>{{ trans('nearby-platform.add_property') }}</div></a></li>
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
                <h4 class="card-header bg-secondary text-white rounded-0">{{ trans('nearby-platform.property') }}</h4>
                <div class="card-body">

                  <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{ url('dashboard/properties/save') }}" autocomplete="off">
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
<?php if ($property->image_file_name != null) { ?>
                                <span class="custom-file-control rounded-0 text-truncate selected" data-target="image" style="width:100%;padding:12px;top:6px;position:relative;">{{ basename($property->image->url())  }}</span>
                                <input type="hidden" name="upload_image" id="upload_image" value="{{ (request()->has('duplicate')) ? '1' : '0' }}">
<?php if ($property->image_file_name != null && request()->has('duplicate')) { ?>
                                <input type="hidden" name="upload_image_duplicate" value="{{ url($property->image->url()) }}">
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
<?php if ($property->favicon != null) { ?>
                                <span class="custom-file-control rounded-0 text-truncate selected" data-target="favicon" style="width:100%;padding:12px;top:6px;position:relative;">{{ basename($property->favicon) }}</span>
                                <input type="hidden" name="upload_favicon" id="upload_favicon" value="{{ (request()->has('duplicate')) ? '1' : '0' }}">
<?php if ($property->favicon != null && request()->has('duplicate')) { ?>
                                <input type="hidden" name="upload_favicon_duplicate" value="{{ $property->favicon }}">
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
                          <input id="title" type="text" class="form-control rounded-0 form-control-lg" name="title" maxlength="180" placeholder="{{ trans('nearby-platform.title_of_the_property') }}" value="{{ old('title', $property->title) }}" required autofocus>
                        </div>

                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="description" class="col-12 col-form-label">{{ trans('nearby-platform.description') }} <sup>*</sup></label>

                      <div class="col-12">
                        <div class="input-group input-group-lg">
                          <textarea id="description" type="text" class="form-control rounded-0 form-control-lg" name="description" rows="4" novalidate placeholder="{{ trans('nearby-platform.description') }}">{!! old('description', ($property->description)) !!}</textarea>
                        </div>

                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="address" class="col-12 col-form-label">{{ trans('nearby-platform.address') }} <sup>*</sup></label>

                      <div class="col-12">
                        <div class="input-group input-group-lg">
                          <div class="input-group-prepend">
                            <span class="input-group-text rounded-0"><i class="mi location_on"></i></span>
                          </div>
                          <input id="address" type="text" class="form-control rounded-0 form-control-lg" autocomplete="address-{{ substr(base_convert(md5(microtime()), 16, 32), 0, 12) }}" name="address" value="{{ old('address', $property->address) }}" autocomplete="off" placeholder="{{ trans('nearby-platform.google_address_placeholder') }}" onFocus="geolocate()" required>
                        </div>

                      </div>
                    </div>

                    <input type="hidden" id="lat" name="lat" value="{{ old('lat', $property->lat) }}">
                    <input type="hidden" id="lng" name="lng" value="{{ old('lng', $property->lng) }}">
                    <input type="hidden" id="street_number" name="street_number" value="{{ old('street_number', $property->street_number) }}">
                    <input type="hidden" id="route" name="street" value="{{ old('street', $property->street) }}">
                    <input type="hidden" id="locality" name="city" value="{{ old('city', $property->city) }}">
                    <input type="hidden" id="administrative_area_level_1" name="state" value="{{ old('state', $property->state) }}">
                    <input type="hidden" id="postal_code" name="postal_code" value="{{ old('postal_code', $property->postal_code) }}">
                    <input type="hidden" id="country" name="country" value="{{ old('country', $property->country) }}">

                    <div class="form-group row">
                      <div class="col-12 col-sm-4">
                        <label for="price_sale" class="col-form-label">{{ trans('nearby-platform.asking_price') }}</label>
                        <input id="price_sale" type="number" class="form-control rounded-0 form-control-lg numeric" name="price_sale" value="{{ old('price_sale', $property->price_sale) }}" autocomplete="off" placeholder="0">
                      </div>

                      <div class="col-12 col-sm-4">
                        <label for="price_rent" class="col-form-label">{{ trans('nearby-platform.rental_price') }}</label>
                        <input id="price_rent" type="number" class="form-control rounded-0 form-control-lg numeric" name="price_rent" value="{{ old('price_rent', $property->price_rent) }}" autocomplete="off" placeholder="0">
                      </div>

                      <div class="col-12 col-sm-4">
                        <label for="price_rent" class="col-form-label">{{ trans('nearby-platform.currency') }}</label>
                        <select class="form-control custom-select custom-select-lg rounded-0" id="currency_code" name="currency_code">
                            <option value="USD">USD</option>
                            <option value="EUR">EUR</option>
                            <option value="EUR">GBP</option>
                            <option value="" disabled>------</option>
<?php
foreach ($currencies as $currency) {
  $currency_code = $currency->getCode();
  $selected = (old('currency_code', $property->currency_code) == $currency_code) ? ' selected': '';
?>
                            <option value="{{ $currency_code }}"{{ $selected }}>{{ $currency_code }}</option>
<?php } ?>
                        </select>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-12 col-sm-6">

                        <div class="form-group row">
                          <label for="building_type" class="col-12 col-form-label">{{ trans('nearby-platform.building_type') }}</label>

                          <div class="col-12">
                            <select class="form-control custom-select custom-select-lg rounded-0" id="building_type" name="building_type">
<?php
$building_types = ['house', 'apartment', 'parking', 'land', 'storage_space', 'storage', 'berth', 'substructure'];

foreach ($building_types as $building_type) {
  $selected = (old('building_type', $property->building_type) == $building_type) ? ' selected': '';
?>
                              <option value="{{ $building_type }}"{{ $selected }}>{{ trans('nearby-platform.' . $building_type) }}</option>
<?php } ?>
                            </select>

                          </div>
                        </div>

                      </div>
                      <div class="col-12 col-sm-6">

                        <div class="form-group row">
                          <label for="year_of_construction" class="col-12 col-form-label">{{ trans('nearby-platform.year_of_construction') }}</label>

                          <div class="col-12">
                            <select class="form-control custom-select custom-select-lg rounded-0" id="year_of_construction" name="year_of_construction">
                              <option value=""></option>
<?php
for ($year = 1900; $year < date('Y'); $year++) {
  $selected = (old('year_of_construction', $property->year_of_construction) == $year) ? ' selected': '';
?>
                              <option value="{{ $year }}"{{ $selected }}>{{ $year }}</option>
<?php } ?>
                            </select>
                          </div>
                        </div>

                       </div>
                    </div>

                    <div class="row">
                      <div class="col-12 col-sm-6">

                        <div class="form-group row">
                          <label for="beds" class="col-12 col-form-label">{{ trans('nearby-platform.bedrooms') }}</label>

                          <div class="col-12">
                            <div class="input-group input-group-lg">
                              <input id="beds" type="number" min="1" max="20" class="form-control rounded-0 form-control-lg numeric" name="beds" value="{{ old('beds', $property->beds) }}" autocomplete="off" placeholder="1">
                            </div>
                          </div>
                        </div>

                      </div>
                      <div class="col-12 col-sm-6">

                        <div class="form-group row">
                          <label for="baths" class="col-12 col-form-label">{{ trans('nearby-platform.bathrooms') }}</label>

                          <div class="col-12">
                            <div class="input-group input-group-lg">
                              <input id="baths" type="number" min="0" step=".5" class="form-control rounded-0 form-control-lg decimal-05" name="baths" value="{{ old('baths', $property->baths) }}" autocomplete="off" placeholder="0">
                            </div>
                          </div>
                        </div>

                       </div>
                    </div>

                    <div class="row">
                      <div class="col-12 col-sm-6">

                        <div class="form-group row">
                          <label for="living_area" class="col-12 col-form-label">{{ trans('nearby-platform.living_area') }}</label>

                          <div class="col-12">
                            <div class="input-group input-group-lg">
                              <input id="living_area" type="number" min="0" class="form-control rounded-0 form-control-lg numeric" name="living_area" value="{{ old('living_area', $property->living_area) }}" autocomplete="off" placeholder="0"><?php /*
                              <div class="input-group-prepend">
                                <span class="input-group-text rounded-0">m²</span>
                              </div>*/ ?>
                            </div>
                          </div>
                        </div>

                      </div>
                      <div class="col-12 col-sm-6">

                        <div class="form-group row">
                          <label for="plot_size" class="col-12 col-form-label">{{ trans('nearby-platform.plot_size') }}</label>

                          <div class="col-12">
                            <div class="input-group input-group-lg">
                              <input id="plot_size" type="number" min="0" class="form-control rounded-0 form-control-lg numeric" name="plot_size" value="{{ old('plot_size', $property->plot_size) }}" autocomplete="off" placeholder="0"><?php /*
                              <div class="input-group-prepend">
                                <span class="input-group-text rounded-0">m²</span>
                              </div>*/ ?>
                            </div>
                          </div>
                        </div>

                       </div>
                    </div>

                    <div class="row mb-4">
                      <div class="col-12">

                        <div class="form-group row mb-0">
                          <label class="col-12 col-form-label">{{ trans('nearby-platform.comes_with') }}</label>
                        </div>

                        <div class="row">
<?php
$features = App\PropertyFeature::orderBy('name', 'asc')->get();

foreach ($features as $feature) {
  $checked = (in_array($feature->id, $existing_features)) ? ' checked' : '';
?>
                        <div class="col-6">
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="feature{{ $feature->id }}" name="feature[]" value="{{ $feature->id }}" data-label="{{ trans('nearby-platform.' . $feature->name) }}"{{ $checked }}>
                            <label class="custom-control-label" for="feature{{ $feature->id }}">{{ trans('nearby-platform.' . $feature->name) }}</label>
                          </div>
                        </div>
<?php
}
?>
                        </div>
                      </div>
                    </div>

                    <div class="row mb-4">
                      <div class="col-12">

                        <div class="form-group row mb-0">
                          <label class="col-12 col-form-label">{{ trans('nearby-platform.surrounding') }}</label>
                        </div>

                        <div class="row">
<?php
$surroundings = App\PropertySurrounding::orderBy('name', 'asc')->get();

foreach ($surroundings as $surrounding) {
  $checked = (in_array($surrounding->id, $existing_surroundings)) ? ' checked' : '';
?>
                        <div class="col-6">
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="surrounding{{ $surrounding->id }}" name="surrounding[]" value="{{ $surrounding->id }}" data-label="{{ trans('nearby-platform.' . $surrounding->name) }}"{{ $checked }}>
                            <label class="custom-control-label" for="surrounding{{ $surrounding->id }}">{{ trans('nearby-platform.' . $surrounding->name) }}</label>
                          </div>
                        </div>
<?php
}
?>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-12 col-sm-6">

                        <div class="form-group row">
                          <label for="phone" class="col-12 col-form-label">{{ trans('nearby-platform.phone') }}</label>

                          <div class="col-12">
                            <div class="input-group input-group-lg">
                              <input id="phone" type="text" class="form-control rounded-0 form-control-lg" name="phone" placeholder="" value="{{ old('phone', $property->phone) }}">
                            </div>

                          </div>
                        </div>

                      </div>
                      <div class="col-12 col-sm-6">

                        <div class="form-group row">
                          <label for="website" class="col-12 col-form-label">{{ trans('nearby-platform.website') }}</label>

                          <div class="col-12">
                            <div class="input-group input-group-lg">
                              <input id="website" type="text" class="form-control rounded-0 form-control-lg" name="website" placeholder="https://" value="{{ old('website', $property->website) }}">
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
                                <input type="hidden" name="primaryColor" value="{{ old('primaryColor', $property->primaryColor) }}">
                                <button type="button" class="btn btn-block text-truncate btn-lg btn-{{ old('primaryColor', $property->primaryColor) }} dropdown-toggle rounded-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                                <input type="hidden" name="secondaryColor" value="{{ old('secondaryColor', $property->secondaryColor) }}">
                                <button type="button" class="btn btn-block text-truncate btn-lg btn-{{ old('secondaryColor', $property->secondaryColor) }} dropdown-toggle rounded-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
$selected = (old('language', $property->language) == $code) ? ' selected' : '';
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
                            <input id="{{ $name }}" type="text" class="form-control rounded-0 form-control-lg" name="{{ $name }}" maxlength="180" placeholder="{{ $field['default'] }}" value="{{ old($name, $property->{$name}) }}">
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
                            <textarea id="{{ $name }}" name="{{ $name }}" class="form-control rounded-0 form-control-lg" rows="3" placeholder="{{ $field['default'] }}">{{ old($name, $property->{$name}) }}</textarea>
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
                      <a href="{{ url('dashboard/properties') }}" class="btn rounded-0 btn-secondary btn-lg mr-2">{{ trans('nearby-platform.cancel') }}</a>
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
                <h4 class="card-header bg-secondary text-white rounded-0"><?php if ($property->favicon != null) { ?>
                  <img id="preview_favicon" src="{{ $property->favicon }}?{{ $property->updated_at->timestamp }}" class="mr-2" style="width: 25px; height: 25px; position: relative; top: -2px">
<?php } else { ?>
                  <img id="preview_favicon" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" class="mr-2 d-none" style="width: 25px; height: 25px; position: relative; top: -2px">
<?php } ?> {{ trans('nearby-platform.preview') }}</h4>
                <div class="card-body pb-0">

                  <h3 class="mt-0" id="preview_title">{{ trans('nearby-platform.title_of_the_property') }}</h3>
<?php if ($property->image_file_name != null) { ?>
                  <img id="preview_image" src="{{ $property->image->url('2x') }}" class="img-fluid mdl-shadow--2dp mb-4" style="min-width: 100%">
<?php } else { ?>
                  <img id="preview_image" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" class="img-fluid mb-4 mdl-shadow--2dp d-none" style="min-width: 100%">
<?php } ?>

                  <p class="lead" id="preview_details">{{ trans('nearby-platform.description') }}</p>

                  <h3>{{ trans('nearby-platform.features') }}</h3>

                  <table class="table">
                    <thead>
                      <tr>
                        <th colspan="2">
                          {{ trans('nearby-platform.transfer_of_ownership') }}
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr id="preview_price_rent">
                        <td>{{ trans('nearby-platform.rental_price') }}</td>
                        <td class="preview_cell"></td>
                      </tr>
                      <tr id="preview_price_sale">
                        <td>{{ trans('nearby-platform.asking_price') }}</td>
                        <td class="preview_cell"></td>
                      </tr>
                      <tr>
                        <td>{{ trans('nearby-platform.listed_since') }}</td>
                        <td>{{ \Carbon\Carbon::now()->formatLocalized('%a %B %e, %Y') }}</td>
                      </tr>
                    </tbody>
                    <thead>
                      <tr>
                        <th colspan="2">
                          {{ trans('nearby-platform.construction') }}
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>{{ trans('nearby-platform.building_type') }}</td>
                        <td id="preview_building_type"></td>
                      </tr>
                      <tr id="preview_year_of_construction">
                        <td>{{ trans('nearby-platform.year_of_construction') }}</td>
                        <td class="preview_cell"></td>
                      </tr>
                      <tr id="preview_beds">
                        <td>{{ trans('nearby-platform.bedrooms') }}</td>
                        <td class="preview_cell"></td>
                      </tr>
                      <tr id="preview_baths">
                        <td>{{ trans('nearby-platform.bathrooms') }}</td>
                        <td class="preview_cell"></td>
                      </tr>
                    </tbody>
                    <thead class="surface_areas">
                      <tr>
                        <th colspan="2">
                          {{ trans('nearby-platform.surface_areas_and_volume') }}
                        </th>
                      </tr>
                    </thead>
                    <tbody class="surface_areas">
                      <tr id="preview_living_area">
                        <td>{{ trans('nearby-platform.living_area') }}</td>
                        <td class="preview_cell"></td>
                      </tr>
                      <tr id="preview_plot_size">
                        <td>{{ trans('nearby-platform.plot_size') }}</td>
                        <td class="preview_cell"></td>
                      </tr>
                    </tbody>
                    <thead class="comes_with">
                      <tr>
                        <th colspan="2">
                          {{ trans('nearby-platform.comes_with') }}
                        </th>
                      </tr>
                    </thead>
                    <tbody class="comes_with">
                      <tr>
                        <td colspan="2" id="preview_comes_with"></td>
                      </tr>
                    </tbody>
                    <thead class="surrounding">
                      <tr>
                        <th colspan="2">
                          {{ trans('nearby-platform.surrounding') }}
                        </th>
                      </tr>
                    </thead>
                    <tbody class="surrounding">
                      <tr>
                        <td colspan="2" id="preview_surrounding"></td>
                      </tr>
                    </tbody>
                  
                  </table>

                  <div class="row mt-4">
                    <div class="col-12">
                      <a href="javascript:void(0);" class="btn btn-{{ $property->primaryColor }} btn-xlg text-truncate rounded-0 btn-block mb-4 primaryColor"><i class="mi map icon-2" style="top:8px"></i> <span id="preview_primaryBtnText">{{ trans('nearby-platform.view_on_map') }}</span></a>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col">
                      <a href="javascript:void(0);" class="btn btn-{{ $property->secondaryColor }} btn-lg text-truncate rounded-0 btn-block mb-4 secondaryColor"><i class="mi phone"></i> <span id="preview_callBtnText">{{ trans('nearby-platform.call') }}</span></a>
                    </div>
                    <div class="col">
                      <a href="javascript:void(0);" class="btn btn-{{ $property->secondaryColor }} btn-lg text-truncate rounded-0 btn-block mb-4 secondaryColor"><i class="mi info_outline"></i> <span id="preview_moreBtnText">{{ trans('nearby-platform.more') }}</span></a>
                    </div>
                    <div class="col">
                      <div class="dropdown">
                        <button class="btn btn-{{ $property->secondaryColor }} btn-lg text-truncate rounded-0 btn-block mb-4 dropdown-toggle secondaryColor" type="button" id="dropdownShare" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
  selector: '#description',
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
      if (c == '') c = '{{ trans('nearby-platform.description') }}';
      $('#preview_details').html(c);
    });
    editor.on('Change', function (e) {
      var c = editor.getContent();
      if (c == '') c = '{{ trans('nearby-platform.description') }}';
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

  $('.numeric').keydown(function(event) {
    return ( event.ctrlKey || event.altKey 
      || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) 
      || (95<event.keyCode && event.keyCode<106)
      || (event.keyCode==8) || (event.keyCode==9) 
      || (event.keyCode>34 && event.keyCode<40) 
      || (event.keyCode==46));
  });

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

  $('#price_sale,#price_rent,#currency_code,#building_type,#year_of_construction,#beds,#baths,#living_area,#plot_size,[name="feature[]"],[name="surrounding[]"]').on('change', renderPreview);
  $('#title,#details,#price_sale,#price_rent,#beds,#baths,#living_area,#plot_size,#primaryBtnText,#callBtnText,#moreBtnText,#shareBtnText').on('keyup', renderPreview);

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
    $('#preview_title').html('{{ trans('nearby-platform.title_of_the_property') }}');
  }

  var details = $('#details').val();
  //details = details.replace(/(?:\r\n|\r|\n)/g, '<br>');

  if (details != '') {
    $('#preview_details').html(details);
  } else {
    $('#preview_details').html('{{ trans('nearby-platform.description') }}');
  }

  var primaryBtnText = $('#primaryBtnText').val();
  if (primaryBtnText != '') {
    $('#preview_primaryBtnText').html(primaryBtnText);
  } else {
    $('#preview_primaryBtnText').html('{{ trans('nearby-platform.view_on_map') }}');
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

  var currency_code = $('#currency_code').val();

  var price_sale = $('#price_sale').val();
  if (price_sale != '' && price_sale != '0') {
    $('#preview_price_sale .preview_cell').html(addCommas(price_sale) + ' ' + currency_code);
    $('#preview_price_sale').show();
  } else {
    $('#preview_price_sale .preview_cell').val('');
    $('#preview_price_sale').hide();
  }

  var price_rent = $('#price_rent').val();
  if (price_rent != '' && price_rent != '0') {
    $('#preview_price_rent .preview_cell').html(addCommas(price_rent) + ' ' + currency_code);
    $('#preview_price_rent').show();
  } else {
    $('#preview_price_rent .preview_cell').val('');
    $('#preview_price_rent').hide();
  }

  var beds = $('#beds').val();
  if (beds != '' && beds != '0') {
    $('#preview_beds .preview_cell').html(beds);
    $('#preview_beds').show();
  } else {
    $('#preview_beds .preview_cell').val('');
    $('#preview_beds').hide();
  }

  var baths = $('#baths').val();
  if (baths != '') {
    $('#preview_baths .preview_cell').html(baths);
    $('#preview_baths').show();
  } else {
    $('#preview_baths .preview_cell').val('');
    $('#preview_baths').hide();
  }

  var building_type = $('#building_type option:selected').text();
  $('#preview_building_type').html(building_type);

  var year_of_construction = $('#year_of_construction').val();
  if (year_of_construction != '') {
    $('#preview_year_of_construction .preview_cell').html(year_of_construction);
    $('#preview_year_of_construction').show();
  } else {
    $('#preview_year_of_construction .preview_cell').val('');
    $('#preview_year_of_construction').hide();
  }

  var living_area = $('#living_area').val();
  var plot_size = $('#plot_size').val();

  if (
    (living_area != '' && living_area != '0') || 
    (plot_size != '' && plot_size != '0')
  ) {
    $('.surface_areas').show();
  } else {
    $('.surface_areas').hide();
  }

  if (living_area != '' && living_area != '0') {
    $('#preview_living_area .preview_cell').html(living_area<?php /* + ' m²'*/ ?>);
    $('#preview_living_area').show();
  } else {
    $('#preview_living_area .preview_cell').html('');
    $('#preview_living_area').hide();
  }

  if (plot_size != '' && plot_size != '0') {
    $('#preview_plot_size .preview_cell').html(plot_size<?php /* + ' m²'*/ ?>);
    $('#preview_plot_size').show();
  } else {
    $('#preview_plot_size .preview_cell').html('');
    $('#preview_plot_size').hide();
  }

  var features = '';
  $('input[name="feature[]"]:checked').each(function() {
    features += '<span class="badge badge-secondary py-2 px-2 mb-2 mr-1">' + $(this).attr('data-label') + '</span> ';
  });

  if (features != '') {
    $('#preview_comes_with').html(features);
    $('.comes_with').show();
  } else {
    $('#preview_comes_with').html('');
    $('.comes_with').hide();
  }

  var surrounding = '';
  $('input[name="surrounding[]"]:checked').each(function() {
    surrounding += '<span class="badge badge-secondary py-2 px-2 mb-2 mr-1">' + $(this).attr('data-label') + '</span> ';
  });

  if (surrounding != '') {
    $('#preview_surrounding').html(surrounding);
    $('.surrounding').show();
  } else {
    $('#preview_surrounding').html('');
    $('.surrounding').hide();
  }

  var phone = $('#phone').val();
  var website = $('#website').val();

  var column_count = (phone == '' && website == '') ? 1 : 2;
  if (phone != '' && website != '') column_count = 3;

  if (phone != '') {
      
  }
}

function addCommas(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
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
src="https://maps.googleapis.com/maps/api/js?key={{ env('GMAPS_KEY') }}&libraries=places&callback=initAutocomplete&language={{ $property->language }}">
</script>

@stop