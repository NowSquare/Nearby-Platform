@extends( '../layouts.clean' )

@section( 'page_title' )Installation @stop

@section( 'content' )

<section>
  <div class="content text-dark" style="background-image: url('{{ url('assets/images/backgrounds/triangles.jpg') }}');">
    <div class="content-overlay" style="background-color:rgba(245,249,250,0.9)">
      <div class="container">
        <div class="row">
          <div class="col-md-8">
            <h1 class="mb-0">Installation</h1>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section>
  <div class="breadcrumbs breadcrumbs-arrow breadcrumbs-light mb-0" style="background-image:url()">
    <div class="breadcrumbs-overlay" style="background-color:#d9dfdf">
      <div class="container">
        <div class="breadcrumbs-padding">
          <div class="row">
            <div class="col-12">

              <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                  <a href="javascript:void(0);">
                    <div>Nearby Platform</div>
                  </a>
                </li>
                <li class="breadcrumb-item active">
                  <a href="javascript:void(0);">
                    <div>Installation</div>
                  </a>
                </li>
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
        <div class="content-overlay" style="background-color:rgba(255,255,255,1)">
          <div class="row">
            <div class="col-4 col-sm-3 ml-auto mr-auto mr-md-0 order-12">

            </div>
            <div class="col-sm-7 col-md-8">
              <div class="content-padding-none">

                <form class="form-horizontal" method="post" id="frm" action="{{ url('install') }}">

                  <div class="card mb-4 rounded-0 shadow-sm">
                    <h4 class="card-header">Domain and name</h4>
                    <div class="card-body pb-0">
                      <p class="lead" for="APP_URL">Website url</p>

                      <div class="form-group">
                        <div class="input-group input-group-lg">
                          <input id="APP_URL" type="text" class="form-control form-control-lg rounded-0" name="APP_URL" value="{{ \Request::getSchemeAndHttpHost() }}" placeholder="">
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="APP_NAME" class="lead">Name</label>
                        <input id="APP_NAME" name="APP_NAME" type="text" placeholder="" value="Nearby Platform" required class="form-control form-control-lg rounded-0">
                      </div>

                    </div>
                  </div>

                  <div class="card mb-4 rounded-0 shadow-sm">
                    <h4 class="card-header">MySQL database</h4>
                    <div class="card-body pb-0">

                      <div class="form-group">
                        <label for="DB_HOST" class="lead">Host</label>
                        <input id="DB_HOST" name="DB_HOST" type="text" placeholder="127.0.0.1" value="127.0.0.1" class="form-control form-control-lg rounded-0" required>
                      </div>

                      <div class="form-group">
                        <label for="DB_PORT" class="lead">Port</label>
                        <input id="DB_PORT" name="DB_PORT" type="text" placeholder="3306" value="3306" class="form-control form-control-lg rounded-0" required>
                      </div>

                      <div class="form-group">
                        <label for="DB_DATABASE" class="lead">Database</label>
                        <input id="DB_DATABASE" name="DB_DATABASE" type="text" placeholder="" value="" class="form-control form-control-lg rounded-0" required>
                      </div>

                      <div class="form-group">
                        <label for="DB_USERNAME" class="lead">Username</label>
                        <input id="DB_USERNAME" name="DB_USERNAME" type="text" placeholder="" value="" class="form-control form-control-lg rounded-0" required>
                      </div>

                      <div class="form-group">
                        <label for="DB_PASSWORD" class="lead">Password</label>
                        <input id="DB_PASSWORD" name="DB_PASSWORD" type="text" placeholder="" class="form-control form-control-lg rounded-0">
                      </div>
                      
                    </div>
                  </div>


                  <div class="card mb-4 rounded-0 shadow-sm">
                    <h4 class="card-header">Email</h4>
                    <div class="card-body pb-0">
                      <p class="lead">If you want to use another mail driver (like <code>smtp</code> or <code>mailgun</code>), open the <code>.env</code> file in the web root after installation and configure additional email settings accordingly.</p>

                      <div class="form-group">
                        <label for="MAIL_DRIVER" class="lead">Driver</label>
                        <select id="MAIL_DRIVER" name="MAIL_DRIVER" class="form-control form-control-lg rounded-0" required>
                          <option value="mail">mail</option>
                          <option value="sendmail">sendmail</option>
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="MAIL_FROM_NAME" class="lead">Mail from name</label>
                        <input id="MAIL_FROM_NAME" name="MAIL_FROM_NAME" type="text" placeholder="" value="Nearby Platform" class="form-control form-control-lg rounded-0" required>
                      </div>

                      <div class="form-group">
                        <label for="MAIL_FROM_ADDRESS" class="lead">Mail from email</label>
                        <input id="MAIL_FROM_ADDRESS" name="MAIL_FROM_ADDRESS" type="text" placeholder="noreply@example.com" value="{{ 'noreply@' . \Request::getHttpHost() }}" class="form-control form-control-lg rounded-0" required>
                      </div>
                      
                    </div>
                  </div>

                  <div class="card mb-4 rounded-0 shadow-sm">
                    <h4 class="card-header">Google Maps</h4>
                    <div class="card-body pb-0">
                      <p class="lead">Get your Google Maps api key here: <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank" class="link">https://developers.google.com/maps/documentation/javascript/get-api-key</a></p>

                      <div class="form-group">
                        <label for="GMAPS_KEY" class="lead">API key</label>
                        <input id="GMAPS_KEY" name="GMAPS_KEY" type="text" placeholder="" class="form-control form-control-lg rounded-0" required>
                      </div>
                      
                    </div>
                      
                  </div>

                  <div class="card mb-4 rounded-0 shadow-sm">
                    <h4 class="card-header">Pusher</h4>
                    <div class="card-body pb-0">
                      <p class="lead">Pusher is required for coupons and rewards. Create a free Pusher app here: <a href="https://pusher.com/" target="_blank" class="link">https://pusher.com/</a>.</p>

                      <div class="form-group">
                        <label for="PUSHER_APP_ID" class="lead">App ID</label>
                        <input id="PUSHER_APP_ID" name="PUSHER_APP_ID" type="text" placeholder="" class="form-control form-control-lg rounded-0" required>
                      </div>
                      <div class="form-group">
                        <label for="PUSHER_APP_KEY" class="lead">Key</label>
                        <input id="PUSHER_APP_KEY" name="PUSHER_APP_KEY" type="text" placeholder="" class="form-control form-control-lg rounded-0" required>
                      </div>
                      <div class="form-group">
                        <label for="PUSHER_APP_SECRET" class="lead">Secret</label>
                        <input id="PUSHER_APP_SECRET" name="PUSHER_APP_SECRET" type="text" placeholder="" class="form-control form-control-lg rounded-0" required>
                      </div>
                      <div class="form-group">
                        <label for="PUSHER_APP_CLUSTER" class="lead">Cluster</label>
                        <input id="PUSHER_APP_CLUSTER" name="PUSHER_APP_CLUSTER" type="text" placeholder="" class="form-control form-control-lg rounded-0" required>
                      </div>
                      
                    </div>
                      
                  </div>

                  <div class="card mb-4 rounded-0 shadow-sm">
                    <h4 class="card-header">Admin user</h4>
                    <div class="card-body pb-0">
                      <p class="lead">You can login with this user after installation.</p>

                      <div class="form-group">
                        <label for="gl_user" class="lead">Email address</label>
                        <input id="email" name="email" type="email" placeholder="" class="form-control form-control-lg rounded-0" required>
                      </div>

                      <div class="form-group">
                        <label for="pass" class="lead">Password</label>
                        <input id="pass" name="pass" type="password" placeholder="" class="form-control form-control-lg rounded-0" required>
                      </div>
                      
                    </div>
                  </div>

                  <div class="alert alert-info rounded-0 lead mt-3 mb-4">
                    If you want to change settings after installation, edit the <code>.env</code> in the root.
                  </div>

                  <div class="form-group" id="other">
                    <div>
                      <button type="submit" class="btn btn-outline-primary btn-xlg rounded-0">Install</button>
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
@stop