@extends('../layouts.master')

@section('page_title') Privacy policy @stop
@section('meta_description') Privacy policy @stop

@section('content')

  <section>
    <div class="content text-dark" style="background-image: url('{{ url('assets/images/backgrounds/triangles.jpg') }}');">
      <div class="content-overlay" style="background-color:rgba(245,249,250,0.9)">
        <div class="container">
          <div class="row">
            <div class="col-md-8">
              <h1>Privacy policy</h1>
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
                  <li class="breadcrumb-item"><a href="{{ url('') }}"><div>Home</div></a></li>
                  <li class="breadcrumb-item active"><a href="javascript:void(0);"><div>Privacy policy</div></a></li>
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
      <div class="content content-padding-l" style="">
        <div class="content-overlay" style="background-color:rgba(255,255,255,1)">
          <div class="row">
            <div class="col-12 col-md-8">
              <h1 class="-x-text mb-4">Introduction</h1>
              <p class="lead">{{ config('system.legal_name') }} ("we" or "us") values its visitors' privacy. This privacy policy is effective May 25th 2018; it summarizes what information we might collect from a registered user or other visitor ("you"), and what we will and will not do with it.</p>
              <p class="lead">Please note that this privacy policy does not govern the collection and use of information by companies that {{ config('system.legal_name') }} does not control, nor by individuals not employed or managed by {{ config('system.legal_name') }}. If you visit a Web site that we mention or link to, be sure to review its privacy policy before providing the site with information.</p>
              <h3>What we do with your personally identifiable information</h3>
              <p class="lead">It is always up to you whether to disclose personally identifiable information to us, although if you elect not to do so, we reserve the right not to register you as a user or provide you with any products or services. "Personally identifiable information" means information that can be used to identify you as an individual, such as, for example:</p>
              <ul class="lead">
                <li>your name, company, email address, phone number, billing address, and shipping address</li>
                <li>your {{ config('system.legal_name') }} user ID and password (if applicable)</li>
                <li>any account-preference information you provide us</li>
                <li>your computer's domain name and IP address, indicating<br>
                  where your computer is located on the Internet</li>
                <li>session data for your login session, so that our computer can 'talk' to yours while you are logged in</li>
              </ul>
              <p class="lead">If you do provide personally identifiable information to us, either directly or through a reseller or other business partner, we will:</p>
              <ul class="lead">
                <li>not sell or rent it to a third party without your permission — although unless you opt out (see below), we may use your contact information to provide you with information we believe you need to know or may find useful, such as (for example) news about our services and products and modifications to the Terms of Service;</li>
                <li>take commercially reasonable precautions to protect the information from loss, misuse and unauthorized access, disclosure, alteration and destruction;</li>
                <li>not use or disclose the information except:</li>
                <ul class="lead">
                  <li>as necessary to provide services or products you have ordered, such as (for example) by providing it to a carrier to deliver products you have ordered;</li>
                  <li>in other ways described in this privacy policy or to which you have otherwise consented;</li>
                  <li>in the aggregate with other information in such a way so that your identity cannot reasonably be determined (for example, statistical compilations);</li>
                  <li>as required by law, for example, in response to a subpoena or search warrant;</li>
                  <li>to outside auditors who have agreed to keep the information confidential;</li>
                  <li>as necessary to enforce the Terms of Service;</li>
                  <li>as necessary to protect the rights, safety, or property of {{ config('system.legal_name') }}, its users, or others; this may include (for example) exchanging information with other organizations for fraud protection and/or risk reduction.</li>
                </ul>
              </ul>

              <h1 class="mb-4 mt-5">Information we collect</h1>

              <h2 class="my-4">Strictly necessary cookies</h2>
              <p class="lead">{{ config('system.legal_name') }} uses "cookies" to store data on your computer. They do not store any personally identifiable information and cannot be switched off. We minimize the use of cookies, and the very few we use are essential to provide a good service.</p>

              <h2 class="my-4">Generic data and cookies</h2>
              <p class="lead">For {{ config('system.legal_name') }} user accounts we only store an email address for login purposes and a way to recover lost passwords. We will not sell or rent these email addresses to a third party. We use these email addresses to occasionally update users with {{ config('system.legal_name') }} related product and service information, or other important issues related to the service users registered for.</p>
              <p class="lead">Visitors can register and login on the {{ config('system.legal_name') }} website to use our free Nearby Notification service. In case <code>remember me</code> is checked when logging in, a cookie is set to automatically log you in the next time you visit our website.</p>

              <table class="table my-5">
               <tr>
                 <th>Cookie Name</th>
                 <th>Expiration Time</th>
                 <th>Description</th>
               </tr>
               <tr>
                 <td><code>XSRF-TOKEN</code></td>
                 <td>2 hours</td>
                 <td>Used to prevent cross site forgery attacks on forms. Doesn't contain any personally identifiable information.</td>
               </tr>
               <tr>
                 <td><code>{{ config('session.cookie') }}</code></td>
                 <td>2 hours</td>
                 <td>Cookie used to identify a session instance by ID. Doesn't store personally identifiable information, is to ensure the working of our website.</td>
               </tr>
              </table>

              <h2 class="my-4">Analytics</h2>
              <p class="lead">{{ config('system.legal_name') }} may use <a href="http://google.com/analytics" target="_blank">Google Analytics</a> to measure and improve the performance of our site; for research and development purposes; customer- and account administration; and to help us focus our marketing efforts more precisely.</p>

              <aside class="note"><strong>Note</strong>: gtag.js and analytics.js do not require
                setting cookies to transmit data to Google Analytics.</aside>

              <p>gtag.js and analytics.js set the following cookies:</p>
              <table class="table my-5">
               <tr>
                 <th>Cookie Name</th>
                 <th>Expiration Time</th>
                 <th>Description</th>
               </tr>
               <tr>
                 <td><code>_ga</code></td>
                 <td>2 years</td>
                 <td>Used to distinguish users.</td>
               </tr>
               <tr>
                 <td><code>_gid</code></td>
                 <td>24 hours</td>
                 <td>Used to distinguish users.</td>
               </tr>
               <tr>
                <td><code>_gat</code></td>
                <td>1 minute</td>
                <td>Used to throttle request rate. If Google Analytics is deployed via Google Tag Manager, this
                  cookie will be named <code>_dc_gtm_&lt;property-id&gt;</code>.</td>
               </tr>
               <tr>
                <td><code>AMP_TOKEN</code></td>
                <td>30 seconds to 1 year</td>
                <td>Contains a token that can be used to retrieve a Client ID from AMP Client ID service. Other
                  possible values indicate opt-out, inflight request or an error retrieving a Client ID from AMP
                  Client ID service.</td>
               </tr>
               <tr>
                <td><code>_gac_&lt;property-id&gt;</code></td>
                <td>90 days</td>
                <td>Contains campaign related information for the user. If you have
                  linked your Google Analytics and AdWords accounts, AdWords
                  website conversion tags will read this cookie unless you opt-out.
                  <a href="//support.google.com/adwords/answer/7521212">Learn more</a>.
                </td>
               </tr>
              </table>

              <h3>Your privacy responsibilities</h3>
              <p class="lead">To help protect your privacy, be sure:</p>
              <ul class="lead">
                <li>not to share your user ID or password with anyone else;</li>
                <li>to log off the {{ config('system.legal_name') }} website when you are finished;</li>
                <li>to take customary precautions to guard against "malware" (viruses, Trojan horses, bots, etc.), for example by installing and updating suitable anti-virus software.</li>
              </ul>
              <h3>Information collected from children</h3>
              <p class="lead">You must be at least 16 years old to use {{ config('system.legal_name') }}'s Web site(s) and service(s). {{ config('system.legal_name') }} does not knowingly collect information from children under 16.</p>
              <h3>Changes to this privacy policy</h3>
              <p class="lead">We reserve the right to change this privacy policy as we deem necessary or appropriate because of legal compliance requirements or changes in our business practices. If you have provided us with an email address, we will endeavor to notify you, by email to that address, of any material change to how we will use personally identifiable information.</p>

              <p class="lead">Thank you for choosing {{ config('system.legal_name') }}</span>!</p>

            </div>
          </div>
        </div>
      </div>
    </section>

  </div>

@stop