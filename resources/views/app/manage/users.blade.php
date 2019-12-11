@extends('layouts.master')

@section('page_title'){{ trans('nearby-platform.users') }} - {{ trans('nearby-platform.manage') }} - {{ config()->get('system.premium_name') }} @stop
@section('meta_description') @stop

@section('content')

  <section>
    <div class="content text-dark" style="background-image: url('{{ url('assets/images/backgrounds/triangles.jpg') }}');">
      <div class="content-overlay" style="background-color:rgba(245,249,250,0.9)">
        <div class="container">
          <div class="row">
            <div class="col-md-8">
              <h1 class="mb-0">{{ trans('nearby-platform.users') }} ({{ $users->total() }})</h1>
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
                  <li class="breadcrumb-item active"><a href="javascript:void(0);"><div>{{ trans('nearby-platform.manage') }}</div></a></li>
                  <li class="breadcrumb-item active"><a href="javascript:void(0);"><div>{{ trans('nearby-platform.users') }}</div></a></li>
                </ol>

              </div>
              <div class="col-12 col-md-6 text-left text-md-right mt-2 mt-md-0">

                <a href="{{ url('dashboard/manage/users/add') }}" class="btn rounded-0 text-white btn-success"><i class="mi add"></i> {{ trans('nearby-platform.add_user') }}</a>

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
<?php
if (count($users) > 0) {
?>
              <table class="table table-hover table-striped mdl-shadow--2dp mb-0">
                <thead class="thead-dark">
                  <tr>
                    <th class="d-none d-lg-table-cell"><a href="?sortby=name&order={{ $order }}" class="link text-white">{{ trans('nearby-platform.name') }}</a></th>
                    <th><a href="?sortby=email&order={{ $order }}" class="link text-white">{{ trans('nearby-platform.email') }}</a></th>
                    <th class="text-center" width="32"><i class="mi store" title="Deals" data-toggle="tooltip"></i></th>
                    <th class="text-center" width="32"><i class="mi redeem" title="Coupons" data-toggle="tooltip"></i></th>
                    <th class="text-center" width="32"><i class="mi beenhere" title="Rewards" data-toggle="tooltip"></i></th>
                    <th class="text-center" width="32"><i class="mi fas fa-home" title="Properties" data-toggle="tooltip" style="position: relative; top: -1px"></i></th>
                    <th class="text-center" width="32"><i class="mi account_circle" title="Business Cards" data-toggle="tooltip"></i></th>
                    <th class="text-center" width="32"><i class="mi video_library" title="Videos" data-toggle="tooltip"></i></th>
                    <th class="text-center" width="32"><i class="mi insert_drive_file" title="Pages" data-toggle="tooltip"></i></th>
                    <th class="text-center d-none d-lg-table-cell"><a href="?sortby=logins&order={{ $order }}" class="link text-white">{{ trans('nearby-platform.logins') }}</a></th>
                    <th class="d-none d-lg-table-cell"><a href="?sortby=last_login&order={{ $order }}" class="link text-white">{{ trans('nearby-platform.last_login') }}</a></th>
                    <th class="d-none d-lg-table-cell"><a href="?sortby=created_at&order={{ $order }}" class="link text-white">{{ trans('nearby-platform.created') }}</a></th>
                    <th class="text-center" width="110">{{ trans('nearby-platform.actions') }}</th>
                  </tr>
                </thead>
                <tbody>
<?php
$i = 0;
foreach ($users as $user) {
  $sl = \App\Http\Controllers\Core\Secure::array2string(['user_id' => $user->id]);
  //$last_login = ($user->last_login != null) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->last_login, \Auth::user()->timezone)->diffForHumans() : '-';
  $last_login = ($user->last_login != null) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->last_login)->diffForHumans() : '-';
  $created_at = ($user->created_at != null) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->created_at)->diffForHumans() : '-';
?>
                  <tr<?php if (! $user->active) echo ' class="table-danger"'; ?></tr>
                    <td class="align-middle d-none d-lg-table-cell">{{ $user->name }}</td>
                    <td class="align-middle text-truncate">{{ $user->email }}</td>
                    <td class="align-middle text-center">{{ $user->deals->count() }}</td>
                    <td class="align-middle text-center">{{ $user->coupons->count() }}</td>
                    <td class="align-middle text-center">{{ $user->rewards->count() }}</td>
                    <td class="align-middle text-center">{{ $user->properties->count() }}</td>
                    <td class="align-middle text-center">{{ $user->businessCards->count() }}</td>
                    <td class="align-middle text-center">{{ $user->videos->count() }}</td>
                    <td class="align-middle text-center">{{ $user->pages->count() }}</td>
                    <td class="align-middle text-center d-none d-lg-table-cell"><span class="badge badge-pill badge-secondary">{{ $user->logins }}</span></td>
                    <td class="align-middle d-none d-lg-table-cell">{{ $last_login }}</td>
                    <td class="align-middle d-none d-lg-table-cell">{{ $created_at }}</td>
                    <td class="text-center align-middle">

                      <div class="btn-group btn-group-sm" role="group">
                        <a href="{{ url('dashboard/manage/users/login/' . $sl) }}" class="btn btn-sm btn-success rounded-0 mr-2" data-toggle="tooltip" title="{{ trans('nearby-platform.login_as_user') }}"><i class="mi exit_to_app"></i></a> 
                        <a href="{{ url('dashboard/manage/users/edit/' . $sl) }}" class="btn btn-sm btn-primary rounded-0 mr-2" data-toggle="tooltip" title="{{ trans('nearby-platform.edit') }}"><i class="mi mode_edit"></i></a> 
                        <a href="javascript:void(0);" data-sl="{{ $sl }}" class="btn btn-sm btn-danger rounded-0 mr-2<?php echo ($user->id == 1) ? ' disabled' : ' btn-delete-user'; ?>" data-toggle="tooltip" title="{{ trans('nearby-platform.delete') }}"><i class="mi delete"></i></a>
                      </div>
                    </td>
                  </tr>
<?php
  $i++;
}
?>
                </tbody>
              </table>

              {{ $links }}
<?php } else { // no leads  ?>
  <h3 class="m-5 text-center">{{ trans('nearby-platform.no_users_found') }} <a href="{{ url('dashboard/manage/users/add') }}" class="link">{{ trans('nearby-platform.add_user') }}</a>.</h3>
<?php } ?>
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
    $('.btn-delete-user').on('click', function() {
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
            url: "{{ url('dashboard/manage/users/delete') }}",
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