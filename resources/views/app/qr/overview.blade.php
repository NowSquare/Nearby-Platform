@extends('layouts.master')

@section('page_title'){{ trans('nearby-platform.qr_codes') }} - {{ config()->get('system.premium_name') }} @stop
@section('meta_description') @stop

@section('content')

  <section>
    <div class="content text-dark" style="background-image: url('{{ url('assets/images/backgrounds/triangles.jpg') }}');">
      <div class="content-overlay" style="background-color:rgba(245,249,250,0.9)">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <h1 class="mb-0">{{ trans('nearby-platform.qr_codes') }} ({{ $qr_codes->total() }})</h1>
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
              <div class="col-12 col-md-6">

                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ url('') }}"><div>{{ trans('nearby-platform.home') }}</div></a></li>
                  <li class="breadcrumb-item"><a href="{{ url('dashboard') }}"><div>{{ trans('nearby-platform.dashboard') }}</div></a></li>
                  <li class="breadcrumb-item active"><a href="javascript:void(0);"><div>{{ trans('nearby-platform.qr_codes') }}</div></a></li>
                </ol>

              </div>
              <div class="col-12 col-md-6 text-left text-md-right mt-2 mt-md-0">

                <a href="{{ url('dashboard/help/QR/QR-code') }}" target="_blank" class="btn btn-danger btn-sm mdl-shadow--4dp rounded-circle mr-2" data-toggle="tooltip" title="{{ trans('nearby-platform.help') }}"><i class="mi help_outline" style="font-size: 1.2rem; position: relative; top: 3px; color: #fff"></i></a>

                <a href="{{ url('dashboard/qr/add') }}" class="btn rounded-0 text-white btn-success"><i class="mi add"></i> {{ trans('nearby-platform.add_qr_code') }}</a>

              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="container">

    <section>
      <div class="content mb-0" style="">
        <div class="content-overlay" style="background-color:rgba(255,255,255,1)">
          <div class="row">
            <div class="col-12">

              @if(session()->has('message'))
                <div class="alert alert-success mb-4 rounded-0">
                  {{ session()->get('message') }}
                </div>
              @endif

            </div>
          </div>
          <div class="row">
<?php
$i = 0;
foreach ($qr_codes as $qr_code) {
  $sl = \App\Http\Controllers\Core\Secure::array2string(['qr_code_id' => $qr_code->id]);

  $image = ($qr_code->image_file_name != null) ? $qr_code->image->url('square-md') : $qr_code->qr->url('sm');

  $metrics = \DB::table('metrics')->select(['count as views', 'data->fired_at as dates'])->where('type', 'App\Metrics\QrViewCountMetrics')->where('metricable_id', $qr_code->id)->first();
  $views = (isset($metrics->views)) ? $metrics->views : 0;
  $dates = (isset($metrics->dates)) ? json_decode($metrics->dates) : [];

  $aDays = [];
  $dDay = new \Carbon\Carbon('29 days ago');

  for ($day = 0; $day < 30; $day++) {
    $aDays[$day] = 0;
    foreach ($dates as $date) {
      $dDate = new \Carbon\Carbon($date);
      if ($dDate->format('Y-m-d') == $dDay->format('Y-m-d')) {
        $aDays[$day]++;
      }
    }
    $dDay->addDay();
  }
?>
          <div class="col-12 col-sm-6 col-md-4 col-lg-4">
            <div class="card rounded-0 mdl-shadow--2dp mb-4">
              <div class="card-body">
                <h5 class="card-title text-nowrap text-truncate m-0" data-container="body" data-toggle="popover" data-placement="top" data-trigger="hover" data-content="{{ $qr_code->description }}"><?php if ($qr_code->icon != null) { ?>
                  <img id="preview_icon" src="{{ $qr_code->icon }}" style="width: 16px; height: 16px; position: relative; top: -2px">
<?php } ?> {{ $qr_code->title }}</h5>

                <div class="mt-1 mb-1 text-muted"><small>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $qr_code->updated_at)->diffForHumans() }}</small></div>

                <div class="d-flex flex-row mb-3">
                  <div class="text-nowrap"><small>{{ trans('nearby-platform.total_views') }}: {{ $views }}</small></div>
                  <div><div class="pl-2"><span class="line"><span style="display: none">{{ implode(',', $aDays) }}</span></span></div></div>
                </div>

                <img src="{{ $image }}" alt="{{ $qr_code->title }}" style="width:100%;" class="mdl-shadow--2dp">

                <div class="m-0 mt-3 form-group"><input type="text" class="form-control rounded-0" readonly value="{{ $qr_code->redirect_to_url }}"></div>

              </div>
              <div class="card-footer">

                <div class="float-right">
                  <a href="{{ url('dashboard/qr/edit/' . $sl) }}" class="btn rounded-0 btn-sm btn-primary" data-toggle="tooltip" title="{{ trans('nearby-platform.edit') }}"><i class="mi mode_edit"></i></a>
                  <a href="{{ url('dashboard/qr/download/' . $sl) }}" class="btn rounded-0 btn-sm btn-green" data-toggle="tooltip" title="{{ trans('nearby-platform.download') }}"><i class="mi cloud_download"></i></a>
                  <span data-toggle="modal" data-target="#modalViewQR{{ $i }}"><a href="javascript:void(0);" data-toggle="tooltip" class="btn btn-sm btn-info rounded-0" title="{{ trans('nearby-platform.qr') }}"><i class="fas fa-qrcode" aria-hidden="true"></i></a>
                </span>
                  <a href="javascript:void(0);" data-sl="{{ $sl }}"class="btn rounded-0 btn-sm btn-danger btn-delete-qr" data-toggle="tooltip" title="{{ trans('nearby-platform.delete') }}"><i class="mi delete"></i></a>
                </div>
              </div>
            </div>
          </div>

          <div class="modal" id="modalViewQR{{ $i }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content rounded-0 mdl-shadow--8dp border border-white">
                  <div class="modal-header">
                    <h5 class="modal-title">{{ $qr_code->title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('nearby-platform.close') }}">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">

                    <img src="{{ $qr_code->qr->url('sm') }}" alt="barcode" style="width:100%;">

                    <div class="m-0 mt-3 form-group"><input type="text" class="form-control rounded-0" value="{{ $qr_code->getUrl() }}"></div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn rounded-0 btn-secondary" data-dismiss="modal">{{ trans('nearby-platform.close') }}</button>
                  </div>
              </div>
            </div>
          </div>
<?php
  $i++;
}
?>
            <div class="col-12">
                {!! $links !!}
            </div>

          </div>
        </div>
      </div>
    </section>

</div>
@endsection

@section('page_bottom')

<script>
  $(function() {
    $('.line').peity('line', { fill: '#99caff', stroke: '#007bff', width: '100%', height: 10 });

    $('.btn-delete-qr').on('click', function() {
      var sl = $(this).attr('data-sl');

      swal({
        title: "{{ trans('nearby-platform.are_you_sure') }}",
        type: "warning",
        showCancelButton: true,
        cancelButtonText: "{{ trans('nearby-platform.cancel') }}",
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "{{ trans('nearby-platform.yes_delete') }}"
      }).then(function(result) {
        if (result.value) {
          blockUI();

          var jqxhr = $.ajax({
            url: "{{ url('dashboard/qr/delete') }}",
            data: {sl: sl,  _token: '<?= csrf_token() ?>'},
            method: 'POST'
          })
          .done(function(data) {
            if(data.success === true) {
              document.location.reload();
            } else {
              swal(data.msg);
            }
          })
          .fail(function() {
            console.log('error');
          })
          .always(function() {
            unblockUI();
          });
        }
      }, function (dismiss) {});
    });

  });
</script>
@stop