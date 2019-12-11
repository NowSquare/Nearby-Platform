@extends('layouts.master')

@section('page_title')Reset password - {{ config()->get('system.premium_name') }} @stop
@section('meta_description') @stop
@section('body_tag') class="fixed-nav" @stop

@section('content')

  <section>
    <div class="content text-dark" style="background-image: url('{{ url('assets/images/backgrounds/triangles.jpg') }}');">
      <div class="content-overlay" style="background-color:rgba(245,249,250,0.9)">
        <div class="container">
          <div class="row">
            <div class="col-md-8">
              <h1 class="mb-0">Reset password</h1>
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
                  <li class="breadcrumb-item"><a href="{{ url('') }}"><div>Home</div></a></li>
                  <li class="breadcrumb-item"><a href="{{ url(trans('website.page_prefix') . 'login') }}"><div>{{ trans('nearby-platform.login') }}</div></a></li>
                  <li class="breadcrumb-item active"><a href="javascript:void(0);"><div>Reset password</div></a></li>
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
      <div class="content mt-4" style="">
        <div class="content-overlay pt-0" style="background-color:rgba(255,255,255,1)">
          <div class="row">
            <div class="col-4 col-sm-3 ml-auto mr-auto mr-md-0 order-12">

            </div>
            <div class="col-sm-7 col-md-8">
              <div class="content-padding-none mt-4">

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                    {{ csrf_field() }}

                    <div class="form-group row{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="col-md-4 col-form-label">{{ trans('nearby-platform.email_address') }}</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control form-control-lg rounded-0" name="email" value="{{ old('email') }}" required>

                            @if ($errors->has('email'))
                                <span class="form-text text-danger">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row mt-4">
                        <div class="col-md-8 offset-md-4">
                          <a href="{{ url(trans('website.page_prefix') . 'login') }}" class="btn rounded-0 btn-secondary btn-lg mr-2"><i class="mi arrow_back"></i> {{ trans('nearby-platform.login') }}</a>

                          <button type="submit" class="btn rounded-0 btn-primary btn-lg">
                              Send reset link
                          </button>
                        </div>
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