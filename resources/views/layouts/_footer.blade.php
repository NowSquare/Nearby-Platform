        <section>
          <div class="footer text-dark footer-padding-xxl" style="background-image:url('{{ url('assets/images/backgrounds/triangles.jpg') }}');">
            <div class="footer-overlay" style="background-color:rgba(245,249,250,0.9)">
              <div class="container">
                <div class="row">
                  <div class="col-12 col-sm-6 col-md-4 col-sm-3">
                    <a href="{{ url('/') }}"><img src="{{ url('assets/images/branding/icon-circle-dark.svg') }}" alt="Mobile Content for Location Marketing" style="height:48px; max-width: 100%; margin-bottom: 18px;" class="mt-5 mb-3 mt-md-2"></a>
                    <address>
                      Copyright &copy; {{ date('Y') }} {{ config('system.legal_name') }}.<br>
                      All rights reserved.
                    </address>

                  </div>

                  <div class="col-12 col-sm-12 col-md-2 col-sm-3">
                  </div>

                  <div class="col-12 col-sm-12 col-md-2 col-sm-3">
                  </div>

                  <div class="col-12 col-sm-6 col-md-2 col-sm-3">
                    <h4 class="mt-5 mb-3 mt-md-2">Company</h4>
                    <ul class="list-unstyled list-dark mb-0">
                        <li><a href="{{ url('legal') }}" role="button" rel="nofollow">Terms &amp; Conditions</a></li>
                        <li><a href="{{ url('privacy-policy') }}" role="button" rel="nofollow">Privacy Policy</a></li>
                    </ul>
                  </div>

                  <div class="col-12 col-sm-6 col-md-2 col-sm-3">
                    <h4 class="mt-5 mb-3 mt-md-2">Services</h4>
                    <ul class="list-unstyled list-dark mb-0">
                      <li><a href="{{ url(trans('website.page_prefix') . 'login') }}" role="button">Nearby Platform</a></li>
                      <li><a href="{{ url(trans('website.page_prefix') . 'password/reset') }}" role="button">Reset Password</a></li>
                    </ul>
                  </div>

                  </div>
                </div>
              </div>
            </div>
        </section>