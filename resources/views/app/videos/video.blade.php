@extends('layouts.master')

@section('page_title')<?php echo (isset($sl)) ? trans('nearby-platform.edit_video') : trans('nearby-platform.add_video'); ?> - {{ config()->get('system.premium_name') }} @stop
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
              <h1 class="mb-0">{{ trans('nearby-platform.edit_video') }}</h1>
<?php } else { ?>
              <h1 class="mb-0">{{ trans('nearby-platform.add_video') }}</h1>
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
                  <li class="breadcrumb-item"><a href="{{ url('dashboard/videos') }}"><div>{{ trans('nearby-platform.videos') }}</div></a></li>
<?php if (isset($sl)) { ?>
                  <li class="breadcrumb-item active"><a href="javascript:void(0);"><div>{{ trans('nearby-platform.edit_video') }}</div></a></li>
<?php } else { ?>
                  <li class="breadcrumb-item active"><a href="javascript:void(0);"><div>{{ trans('nearby-platform.add_video') }}</div></a></li>
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
                <h4 class="card-header bg-secondary text-white rounded-0">{{ trans('nearby-platform.video') }}</h4>
                <div class="card-body">

                  <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{ url('dashboard/videos/save') }}" autocomplete="off">
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

                      <div class="col-12">
                        <div class="form-group">
                          <label class=" col-form-label">{{ trans('nearby-platform.icon') }} [<a href="javascript:void(0);" data-container="body" data-toggle="popover" data-trigger="focus" data-placement="top" data-content="{{ trans('nearby-platform.icon_help') }}">?</a>]</label>

                          <div class="">
                            <div class="input-group input-group-lg border">
                              <label class="custom-file">
                                <input type="file" accept="image/png, image/jpeg" name="icon" id="icon" class="custom-file-input" style="position: absolute">
<?php if ($video->icon_file_name != null) { ?>
                                <span class="custom-file-control rounded-0 text-truncate selected" data-target="icon" style="width:100%;padding:12px;top:6px;position:relative;">{{ basename($video->icon->url())  }}</span>
                                <input type="hidden" name="upload_icon" id="upload_icon" value="0">
<?php } else { ?>
                                <span class="custom-file-control rounded-0 text-truncate" style="width:100%;padding:12px;top:6px;position:relative;" data-target="icon"></span>
                                <input type="hidden" name="upload_icon" id="upload_icon" value="0">
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
                          <input id="title" type="text" class="form-control rounded-0 form-control-lg" name="title" maxlength="64" placeholder="{{ trans('nearby-platform.video_title') }}" value="{{ old('title', $video->title) }}" required autofocus>
                        </div>

                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="content" class="col-12 col-form-label">{{ trans('nearby-platform.video_url') }} [<a href="javascript:void(0);" data-container="body" data-toggle="popover" data-trigger="focus" data-placement="top" data-content="{{ trans('nearby-platform.video_url_help') }}">?</a>] <sup>*</sup></label>

                      <div class="col-12">
                        <div class="input-group input-group-lg">
                          <textarea id="video_url" type="text" class="form-control rounded-0 form-control-lg" name="video_url" rows="3" placeholder="https://" required style="height: 113px">{{ old('video_url', $video->video_url) }}</textarea>

                          <input type="hidden" value="{{ old('embed_url', $video->embed_url) }}" name="embed_url" id="embed_url">
                          <input type="hidden" value="{{ old('embed_aspect_ratio', $video->embed_aspect_ratio) }}" name="embed_aspect_ratio" id="embed_aspect_ratio">
                          <input type="hidden" value="{{ old('remote_image', $video->remote_image) }}" name="remote_image" id="remote_image">
                          <input type="hidden" value="{{ old('video_title', $video->video_title) }}" name="video_title" id="video_title">
                          <input type="hidden" value="{{ old('video_description', $video->video_description) }}" name="video_description" id="video_description">

                          <div class="invalid-feedback mt-3">
                            {{ trans('nearby-platform.video_url_help') }}
                            {{ trans('nearby-platform.the_error_is') }}
                            <div class="font-weight-bold mt-3" id="video_url_invalid_msg"></div>
                          </div>

                        </div>

                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="content" class="col-12 col-form-label">{{ trans('nearby-platform.content') }}</label>

                      <div class="col-12">
                        <div class="input-group input-group-lg">
                          <textarea id="content" type="text" class="form-control rounded-0 form-control-lg" name="content" rows="6" placeholder="">{{ old('content', $video->content) }}</textarea>
                        </div>

                      </div>
                    </div>

                    <div class="mb-2 mt-4">
                      <i class="mi add_circle_outline text-primary showEditColors" style="position: relative; top:3px"></i> <a href="javascript:void(0);" class="showEditColors">{{ trans('nearby-platform.edit_page_color') }}</a>
                    </div>

                    <div id="editColors" class="d-none ml-2 mb-2 mt-2 pl-4 pb-2 pt-2 border-left">

                      <div class="row">
                        <div class="col-12 col-sm-12">
                          <div class="form-group row">
                            <label class="col-12 col-form-label">{{ trans('nearby-platform.theme_color') }} [<a href="javascript:void(0);" data-container="body" data-toggle="popover" data-trigger="focus" data-placement="top" data-content="{{ trans('nearby-platform.theme_color_help') }}">?</a>]</label>
                            <div class="col-12">
                              <div class="btn-group btn-block colorSelector">
                                <input type="hidden" name="primaryColor" value="{{ old('primaryColor', $video->primaryColor) }}">
                                <button type="button" class="btn btn-block text-truncate btn-lg btn-{{ old('primaryColor', $video->primaryColor) }} dropdown-toggle rounded-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  {{ trans('nearby-platform.select_color_') }}
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

                      </div>
                    </div>


                    <div class="form-group mt-2 mb-0 text-right">
<?php if (isset($sl) || (isset($count) && $count > 0)) { ?>
                      <a href="{{ url('dashboard/videos') }}" class="btn rounded-0 btn-secondary btn-lg mr-2">{{ trans('nearby-platform.cancel') }}</a>
<?php } ?>
<?php if (isset($sl)) { ?>
                      <button type="submit" class="btn rounded-0 btn-primary btn-lg" id="btnSubmit">{{ trans('nearby-platform.save') }}</button>
<?php } else { ?>
                      <button type="submit" class="btn rounded-0 btn-primary btn-lg" id="btnSubmit">{{ trans('nearby-platform.add') }}</button>
<?php } ?>
                    </div>

                  </form>
                </div>
              </div>

            </div>
            <div class="col-12 col-sm-12 col-lg-5">

              <div class="card border-secondary rounded-0 mt-4 mt-lg-0">
                <h4 class="card-header bg-secondary text-white rounded-0"><?php if ($video->icon_file_name != null) { ?>
                  <img id="preview_icon" src="{{ $video->icon->url('s') }}" class="mr-2" style="width: 25px; height: 25px; position: relative; top: -2px">
<?php } else { ?>
                  <img id="preview_icon" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" class="mr-2 d-none" style="width: 25px; height: 25px; position: relative; top: -2px">
<?php } ?> {{ trans('nearby-platform.preview') }}</h4>
                <div class="card-body pb-0">

                  <h3 class="mt-0" id="preview_title">{{ trans('nearby-platform.video_title') }}</h3>

                  <div class="embed-responsive mdl-shadow--2dp" id="embed">
                    <iframe class="embed-responsive-item" src="about:blank" allowfullscreen></iframe>
                  </div>

                  <div class="lead" id="preview_content"></div>

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
  selector: '#content',
  language: '{{ app()->getLocale() }}',
  content_css : '{{ url('assets/css/tinymce-content.min.css') }}',
  plugins : 'advlist autolink link image paste table media lists charmap colorpicker textcolor code contextmenu autoresize',
  toolbar : 'styleselect | bold italic link | bullist numlist<?php /* outdent indent*/ ?> | forecolor backcolor',
  contextmenu: 'paste | link image inserttable | cell row column deletetable',
  paste_as_text: true,
  table_default_attributes: {
    class: 'table'
  },
  menu: {
      edit: {title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall'},
      insert: {title: 'Insert', items: 'link image media | template hr'},
      view: {title: 'View', items: 'visualaid'},
      format: {title: 'Format', items: 'bold italic underline strikethrough superscript subscript | formats | removeformat'},
      table: {title: 'Table', items: 'inserttable tableprops deletetable | cell row column'},
      tools: {title: 'Tools', items: 'spellchecker code'}
  },
  statusbar: false,
  convert_urls:false,
  relative_urls:false,
  height: 320,
  autoresize_min_height: 280,
  autoresize_bottom_margin: 0,
  autoresize_overflow_padding: 0,
  init_instance_callback: function (editor) {

    $('#preview_content').html(editor.getContent());

    editor.on('KeyUp', function (e) {
      $('#preview_content').html(editor.getContent());
    });
    editor.on('Change', function (e) {
      $('#preview_content').html(editor.getContent());
    });
  }
});
</script>
<script>
$('form:not(.ajax)').submit(function() {
  saveLoader();
});

var current_url = '{{ $video->video_url }}';

<?php if ($video->video_url !== null) { ?>
switch ({{ $video->embed_aspect_ratio }}) {
  case 42.85:
  case 42.86: var aspect_ratio = 'embed-responsive-21by9'; break;
  case 56.25: var aspect_ratio = 'embed-responsive-16by9'; break;
  case 75: var aspect_ratio = 'embed-responsive-4by3'; break;
  case 100: var aspect_ratio = 'embed-responsive-1by1'; break;
  default: var aspect_ratio = 'embed-responsive-16by9';
}
$('#embed').addClass(aspect_ratio + ' mb-4');
$('#embed iframe').attr('src', '{{ $video->embed_url }}');

<?php if ($video->icon_file_name === null) { ?>
$('#preview_icon').removeClass('d-none');
$('#preview_icon').attr('src', '{{ $video->remote_image }}');
<?php } ?>

<?php } ?>

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

  $('#image').on('change', function() {
    readURL(this);
  });

  $('#icon').on('change', function() {
    readURL(this);
  });

  $('#title').on('keyup', renderPreview);

  $('#video_url').on('keyup change blur input propertychange', $.debounce(500, renderPreview));

  renderPreview();

  $('.custom-file-input').on('change', function() {
    setFileVal($(this));
  });

  $('.btn-custom-file-control-delete').on('click', function() {
    $(this).parent().parent().find('.custom-file-input').val('').next('.custom-file-control').removeClass('selected').html('');

    var target = $(this).parent().parent().find('.custom-file-input').val('').next('.custom-file-control').attr('data-target');

    $('#upload_icon').val('1');
    $('#preview_icon').addClass('d-none');

  });

});
  
function setFileVal(_this) {
  var filePath = _this.val();
  var fileName = filePath.replace(/^.*\\/, "");

  var target = _this.next('.custom-file-control').attr('data-target');

  // Make sure new image is uploaded
  if (target == 'image') {
    $('#upload_image').val('1');
  } else if (target == 'icon') {
    $('#upload_icon').val('1');
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
    $('#preview_title').html('{{ trans('nearby-platform.video_title') }}');
  }

  var content = tinyMCE.get('content').getContent();

  if (content != '') {
    $('#preview_content').html(content);
  } else {
    $('#preview_content').html('');
  }

  var video_url = $('#video_url').val();
  if (video_url != '' && current_url != video_url) {
    blockUI();

    var jqxhr = $.ajax({
      url: "{{ url('dashboard/videos/verify-url') }}",
      data: {video_url: video_url, _token: '<?= csrf_token() ?>'},
      method: 'POST'
    })
    .done(function(data) {
      $('#btnSubmit').prop('disabled', true);
      $('#video_url').removeClass('is-invalid is-valid');

      if(data.valid === false) {
        $('#video_url').addClass('is-invalid');
        if (data.msg != '') {
          $('#video_url_invalid_msg').text(data.msg);   
        }
      } else {
        switch (data.aspectRatio) {
          case 42.85:
          case 42.86: var aspect_ratio = 'embed-responsive-21by9'; break;
          case 56.25: var aspect_ratio = 'embed-responsive-16by9'; break;
          case 75: var aspect_ratio = 'embed-responsive-4by3'; break;
          case 100: var aspect_ratio = 'embed-responsive-1by1'; break;
          default: var aspect_ratio = 'embed-responsive-16by9';
        }
        $('#embed').addClass(aspect_ratio + ' mb-4');
        $('#embed iframe').attr('src', data.url);

        $('#video_url').addClass('is-valid');
        $('#embed_url').val(data.url);
        $('#video_title').val(data.title);
        $('#video_description').val(data.desc);

        $('#embed_aspect_ratio').val(data.aspectRatio);
        $('#remote_image').val(data.image);

        if ($('#icon').val() == '') {
          $('#preview_icon').removeClass('d-none');
          $('#preview_icon').attr('src', data.image);
        }

        current_url = video_url;
        $('#btnSubmit').prop('disabled', false);
      }
    })
    .fail(function() {
      console.log('error');
    })
    .always(function() {
      unblockUI();
    }); 
  } else if (video_url == '') {
      $('#embed').removeClass('embed-responsive-21by9 embed-responsive-16by9 embed-responsive-4by3 embed-responsive-1by1 embed-responsive-16by9 mb-4');
      $('#embed iframe').attr('src', 'about:blank');

      $('#video_url').removeClass('is-valid is-invalid');
      $('#embed_url').val('');
      $('#video_title').val('');
      $('#video_description').val('');

      $('#embed_aspect_ratio').val('');
      $('#remote_image').val('');

      if ($('#icon').val() == '') {
        $('#preview_icon').addClass('d-none');
        $('#preview_icon').attr('src', 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
      }

      current_url = '';
      $('#btnSubmit').prop('disabled', true);
  }
}

function readURL(input) {
  if (input.files && input.files[0]) {
    var target = $(input).attr('id');

    if (target == 'image') {
      $('#preview_image').removeClass('d-none');
    } else if (target == 'icon') {
      $('#preview_icon').removeClass('d-none');
    }

    var reader = new FileReader();

    reader.onload = function(e) {
      if (target == 'image') {
        $('#preview_image').attr('src', e.target.result);
      } else if (target == 'icon') {
        $('#preview_icon').attr('src', e.target.result);
      }
    }

    reader.readAsDataURL(input.files[0]);
  }
}
</script>
@stop