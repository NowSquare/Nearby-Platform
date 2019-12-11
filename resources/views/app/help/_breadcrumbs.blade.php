  <section>
    <div class="content text-light" style="background-image: url('{{ url('assets/images/backgrounds/triangles.jpg') }}');">
      <div class="content-overlay" style="background-color:rgba(20,110,255,0.7)">
        <div class="container">
          <div class="row">
            <div class="col-md-8">
              <h1>{{ $product }}</h1>
              <h2 class="mb-0">{{ $doctype }}</h2>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="breadcrumbs breadcrumbs-arrow breadcrumbs-light" style="background-image:url()">
      <div class="breadcrumbs-overlay" style="background-color:#d9dfdf">
        <div class="container">
          <div class="breadcrumbs-padding">
            <div class="row">
              <div class="col-12">

                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ url('') }}"><div>Home</div></a></li>
<?php if (\Request::segment(3) == NULL) { ?>
                  <li class="breadcrumb-item"><a href="{{ url($seg1) }}"><div>{{ $product }}</div></a></li>
                  <li class="breadcrumb-item active"><a href="javascript:void(0);"><div>{{ $doctype }}</div></a></li>
<?php } else { ?>
                  <li class="breadcrumb-item"><a href="{{ url($seg1) }}"><div>{{ $product }}</div></a></li>
                  <li class="breadcrumb-item"><a href="{{ url($seg1 .'/' . $seg2) }}"><div>{{ $doctype }}</div></a></li>
<?php if (\Request::segment(4) == NULL) { ?>
                  <li class="breadcrumb-item active"><a href="javascript:void(0);"><div>{{ ucfirst(str_replace('-', ' ', \Request::segment(3))) }}</div></a></li>
<?php } else { ?>
                  <li class="breadcrumb-item"><a href="{{ url($seg1 .'/' . $seg2 . '/' . Request::segment(3)) }}"><div>{{ ucwords(str_replace('-', ' ', \Request::segment(3))) }}</div></a></li>
                  <li class="breadcrumb-item active"><a href="javascript:void(0);"><div>{{ ucfirst(str_replace('-', ' ', \Request::segment(4))) }}</div></a></li>
<?php } ?>
<?php } ?>
                </ol>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>