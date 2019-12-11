@extends('layouts.master')

@section('page_title'){{ trans('nearby-platform.rewards') }} \ {{ trans('nearby-platform.leads') }} - {{ config()->get('system.premium_name') }} @stop
@section('meta_description') @stop

@section('content')

  <section>
    <div class="content text-dark" style="background-image: url('{{ url('assets/images/backgrounds/triangles.jpg') }}');">
      <div class="content-overlay" style="background-color:rgba(245,249,250,0.9)">
        <div class="container">
          <div class="row">
            <div class="col-md-8">
              <h1 class="mb-0">{{ trans('nearby-platform.rewards') }} \ {{ trans('nearby-platform.leads') }} ({{ $leads->total() }})</h1>
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
                  <li class="breadcrumb-item"><a href="{{ url('dashboard/rewards') }}"><div>{{ trans('nearby-platform.rewards') }}</div></a></li>
                  <li class="breadcrumb-item active"><a href="javascript:void(0);"><div>{{ trans('nearby-platform.leads') }}</div></a></li>
                </ol>

              </div>
              <div class="col-12 col-md-6 text-left text-md-right mt-2 mt-md-0">

                <a href="{{ url('dashboard/rewards/add') }}" class="btn rounded-0 text-white btn-success"><i class="mi add"></i> {{ trans('nearby-platform.add_reward') }}</a>

                <span class="dropdown">
                  <button class="btn btn-secondary rounded-0" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="mi more_horiz"></i>
                  </button>
                  <div class="dropdown-menu rounded-0 mdl-shadow--2dp dropdown-menu-right">
                    <a class="dropdown-item<?php echo (count($leads) > 0) ? '' : ' disabled'; ?>" href="<?php echo (count($leads) > 0) ? url('dashboard/rewards/leads/export?sortby=' . $sortby . '&order=' . $order . '&' . http_build_query($qsFilterRewards)) : 'javascript:void(0);'; ?>">{{ trans('nearby-platform.export_leads') }}</a>
                  </div>
                </span>

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

              <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a class="nav-link color-dark rounded-0" href="{{ url('dashboard/rewards') }}">{{ trans('nearby-platform.rewards') }}</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link active rounded-0" href="{{ url('dashboard/rewards/leads') }}">{{ trans('nearby-platform.leads') }}</a>
                </li>
              </ul>
              <div class="border-left border-right border-bottom p-3">

              <form method="get" action="">
                <input type="hidden" name="sortby" value="{{ $sortby }}">
                <input type="hidden" name="order" value="{{ $order }}">

                <div class="row">
                  <div class="col-12 col-md-12">

                    <div class="input-group mb-3 mdl-shadow--2dp">
                      <select multiple class="form-control select2-multiple" size="1" name="filterRewards[]">
<?php
foreach ($rewards as $reward) {
  $selected = (in_array($reward->id, $filterRewards)) ? ' selected' : '';
?>
                        <option value="{{ $reward->id }}"{{ $selected }}>{{ $reward->title }}</option>
<?php } ?>
                      </select>
                      <div class="input-group-append">
                        <button type="submit" class="btn btn-primary rounded-0">{{ trans('nearby-platform.filter') }}</button>
                      </div>
                    </div>

                  </div>
                </div>

              </form>
<?php
if (count($leads) > 0) {
?>
              <table class="table table-hover table-striped mdl-shadow--2dp mb-0">
                <thead class="thead-dark">
                  <tr>
                    <th width="48" class="align-middle">
                      <div class="custom-control custom-checkbox" style="margin-top: -29px">
                        <input type="checkbox" name="" value="1" id="select_all" class="custom-control-input">
                        <label class="custom-control-label" for="select_all" onclick="$(':checkbox[name^=row]').prop('checked', ! $('#select_all').prop('checked'));"></label>
                      </div>
                    </th>
                    <th>{{ trans('nearby-platform.reward') }}</th>
<?php
foreach ($fields as $field) {
?>
                    <th><a href="?sortby=field_values->{{ $field['id'] }}&order={{ $reverse_order }}&{{ http_build_query($qsFilterRewards) }}" class="link text-white">{{ $field['placeholder'] }}</a></th>
<?php } ?>
                    <th class="d-none d-lg-table-cell"><a href="?sortby=created_at&order={{ $reverse_order }}&{{ http_build_query($qsFilterRewards) }}" class="link text-white">{{ trans('nearby-platform.created') }}</a></th>
                    <th width="88" class="text-center">{{ trans('nearby-platform.actions') }}</th>
                  </tr>
                </thead>
                <tbody>
<?php

$i = 0;
foreach ($leads as $lead) {
  $sl = \App\Http\Controllers\Core\Secure::array2string(['reward_id' => $lead->id]);
?>
                  <tr>
                    <td class="align-middle">
                      <div class="custom-control custom-checkbox" style="margin-top: -29px">
                        <input type="checkbox" name="row[]" value="{{ $lead->id }}" id="row_{{ $lead->id }}" class="custom-control-input">
                        <label class="custom-control-label" for="row_{{ $lead->id }}"></label>
                      </div>
                    </td>
                    <td class="align-middle text-truncate max-width-200" title="{{ $lead->reward->title }}">{{ $lead->reward->title }}</td>
<?php
foreach ($fields as $field) {
  $val = (isset($lead->field_values[$field['id']])) ? $lead->field_values[$field['id']] : '-';
?>
                    <td class="align-middle">{{ $val }}</td>
<?php } ?>
                    <td class="align-middle d-none d-lg-table-cell">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $lead->created_at)->diffForHumans() }}</td>
                    <td class="text-center align-middle">
                      <div class="btn-group btn-group-sm" role="group"> 
                        <a href="javascript:void(0);" data-sl="{{ $sl }}" class="btn btn-sm btn-danger rounded-0 btn-delete-lead mr-2" data-toggle="tooltip" title="{{ trans('nearby-platform.delete') }}"><i class="mi delete"></i></a>
<?php /*
                        <span data-toggle="modal" data-target="#modalViewLead{{ $i }}">
                        <a href="javascript:void(0);" class="btn btn-sm btn-info rounded-0" target="_blank" data-toggle="tooltip" title="View"><i class="mi search"></i></a>
                        </span>*/ ?>
                      </div>
                    </td>
                  </tr>
<?php
  $i++;
}
?>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="{{ count($fields) + 4 }}">

                      <div class="dropdown">
                        <button class="btn btn-secondary rounded-0 dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          {{ trans('nearby-platform.with_selected_') }}
                        </button>
                        <div class="dropdown-menu rounded-0">
                          <a href="javascript:void(0);" class="dropdown-item btn-delete-selected-leads"><i class="mi delete"></i> {{ trans('nearby-platform.delete') }}</a>
                        </div>
                      </div>

                    </td>
                  </tr>
                </tfoot>
              </table>

              {!! $links !!}
<?php } else { // no leads  ?>
  <h3 class="m-5 text-center">{{ trans('nearby-platform.no_results_found') }}</h3>
<?php } ?>
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
  $(function() {
    $('.line').peity('line', { fill: '#99caff', stroke: '#007bff', width: '100%', height: 24 });

    $('.btn-delete-lead').on('click', function() {
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
            url: "{{ url('dashboard/rewards/leads/delete') }}",
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

    $('.btn-delete-selected-leads').on('click', function() {
      var rows = [];
      $("input[name='row[]']:checked").each(function(){ rows.push($(this).val()); });

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
            url: "{{ url('dashboard/rewards/leads/delete/selected') }}",
            data: {rows: rows,  _token: '<?= csrf_token() ?>'},
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