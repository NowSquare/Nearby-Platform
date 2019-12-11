<?php
$route = (\Request::route() != NULL) ? \Request::route()->getName() : '';
if (isset($route_name)) $route = $route_name;

$content = (in_array($route, ['coupons', 'deals', 'rewards', 'properties', 'videos', 'businessCards', 'pages'])) ? true : false;

$fab = (starts_with($route, 'settings')) ? true : false;

if ($route == 'home') {
  $logo = '<img src="' . url('assets/images/branding/logo-horizontal-light.svg') . '" alt="Nearby Platform" style="height:28px">';
} else {
  $logo = '<img src="' . url('assets/images/branding/logo-horizontal-dark.svg') . '" alt="Nearby Platform" style="height:28px">';
}
?>
 <div id="fixed-nav">
    <nav class="navbar navbar-expand-lg navbar-full <?php echo ($route == 'home' || $route == '404') ? 'navbar-light bg-light' : 'navbar-light bg-light'; ?>" style="padding:0">
      <div class="container">
<?php if ($route == 'home' || $route == '404') { ?>
          <a class="navbar-brand hidden-sm-down navbar-logo" href="{{ url(trans('website.page_suffix')) }}">{!! $logo !!}</a>
<?php } else { ?>
          <a class="navbar-brand navbar-logo" href="{{ url(trans('website.page_suffix')) }}">{!! $logo !!}</a>
<?php } ?>
        <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon mi"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="{{ url(trans('website.page_prefix') . '/#about') }}">{{ trans('nearby-platform.about') }}</a></li>
<?php if (! auth()->check()) { ?>
            <li class="nav-item d-block d-lg-none<?php if ($route == 'login') echo ' active'; ?>"><a class="nav-link" href="{{ url(trans('website.page_prefix') . 'login') }}">{{ trans('nearby-platform.login') }}</a></li>
<?php } else { ?>
            <li class="nav-item<?php if ($route != 'home' && ! $fab) echo ' active'; ?>"><a class="nav-link" href="{{ url(trans('website.page_prefix') . 'dashboard') }}">{{ trans('nearby-platform.dashboard') }}</a></li>
            <li class="nav-item d-block d-lg-none<?php if ($route == 'settings' || starts_with($route, 'settings')) echo ' active'; ?>"><a class="nav-link" href="{{ url('dashboard/settings/profile') }}">{{ trans('nearby-platform.settings') }}</a></li>
<?php if (Gate::allows('owner')) { ?>
            <li class="nav-item d-block d-lg-none<?php if ($route == 'update') echo ' active'; ?>"><a class="nav-link" href="{{ url('dashboard/update') }}">{{ trans('nearby-platform.check_for_updates') }}</a></li>
<?php } ?>
            <li class="nav-item d-block d-lg-none"><a class="nav-link" href="{{ url('logout') }}"><i class="mi power_settings_new"></i> {{ trans('nearby-platform.logout') }}</a></li>
<?php } ?>

<?php if (($route == 'home' || $route == 'login') && count(config('system.available_languages')) > 1) { ?>
            <li class="nav-item dropdown d-none d-lg-block">
              <a class="nav-link" href="javascript:void(0);" id="navbarDropdownMenuLanguage" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="mi language" style="font-size: 1.8rem; top: 8px"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLanguage">
<?php
foreach (config('system.available_languages') as $code => $language) {
  $lang_code = ($code == 'en') ? '' : $code;
  $selected = (app()->getLocale() == $code) ? true : false;
  $url = '/' . $lang_code;
  if ($route == 'login') {
    $url = $lang_code . '/login';
  }
?>
                <a class="dropdown-item<?php if ($selected) echo ' active'; ?>" href="{{ url($url) }}">{{ $language }}</a>
<?php
}
?>
              </div>
            </li>
<?php } ?>
<?php if (auth()->check()) { ?>
             <li class="nav-item dropdown d-none d-lg-block<?php if ($fab) echo ' active'; ?>">
              <a class="nav-link" href="javascript:void(0);" id="navbarDropdownMenuContent" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="mi more_vert" style="font-size: 1.8rem; top: 8px"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuContent">
                <h6 class="dropdown-header" style="padding: .8rem 1.2rem">{{ auth()->user()->email }}</h6>
                <a class="dropdown-item<?php if ($route == 'settings' || starts_with($route, 'settings')) echo ' active'; ?>" href="{{ url('dashboard/settings/profile') }}">{{ trans('nearby-platform.settings') }}</a>
<?php if (Gate::allows('owner')) { ?>
                <a class="dropdown-item<?php if ($route == 'update') echo ' active'; ?>" href="{{ url('dashboard/update') }}">{{ trans('nearby-platform.check_for_updates') }}</a>
<?php } ?>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ url('logout') }}"><i class="mi power_settings_new"></i> {{ trans('nearby-platform.logout') }}</a>
              </div>
            </li>
<?php } ?>
          </ul>
<?php if (! auth()->check()) { ?>
          <div class="my-2 my-lg-0 d-none d-lg-block">
            <div class="ml-3 my-2">
              <a href="{{ url(trans('website.page_prefix') . 'login') }}" class="btn btn-outline-primary<?php if ($route == 'login') echo ' active'; ?>" style="padding: 2px 8px">{{ trans('nearby-platform.login') }}</a>
            </div>
          </div>
<?php } ?>
        </div>
      </div>
    </nav>
<?php if ($subnav) { ?>
    <div id="navbar-sub">
      <section>
        <nav class="navbar navbar-expand-lg navbar-dark">
          <div class="container">
            <div class="row">
              <div class="col-12">

                <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#collapsingNavbarSub" aria-controls="collapsingNavbarSub" aria-expanded="false" aria-label="Toggle navigation">
                  &#9776;
                </button>

                <div class="collapse navbar-collapse" id="collapsingNavbarSub">

                  <ul class="navbar-nav">

                    <li class="nav-item<?php if ($route == 'dashboard') echo ' active'; ?>"><a class="nav-link" href="{{ url('dashboard') }}">{{ trans('nearby-platform.dashboard') }}</a></li>
                    <li class="nav-item<?php if ($route == 'qr') echo ' active'; ?>"><a class="nav-link" href="{{ url('dashboard/qr') }}">{{ trans('nearby-platform.qr') }}</a></li>
                    <li class="nav-item dropdown<?php if ($content) echo ' active'; ?>">
                      <a class="nav-link dropdown-toggle" href="javascript:void(0);" id="navbarDropdownMenuContent" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ trans('nearby-platform.content') }}
                      </a>
                      <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuContent">
                        <a class="dropdown-item<?php if ($route == 'deals') echo ' active'; ?>" href="{{ url('dashboard/deals') }}"><i class="mi store mr-1" style="top:3px;"></i> {{ trans('nearby-platform.deals') }}</a>
                        <a class="dropdown-item<?php if ($route == 'coupons') echo ' active'; ?>" href="{{ url('dashboard/coupons') }}"><i class="mi redeem mr-1" style="top:3px;"></i> {{ trans('nearby-platform.coupons') }}</a>
                        <a class="dropdown-item<?php if ($route == 'rewards') echo ' active'; ?>" href="{{ url('dashboard/rewards') }}"><i class="mi beenhere mr-1" style="top:3px;"></i> {{ trans('nearby-platform.rewards') }}</a>
                        <a class="dropdown-item<?php if ($route == 'properties') echo ' active'; ?>" href="{{ url('dashboard/properties') }}"><i class="mi fas fa-home mr-1" style="top:0px;"></i> {{ trans('nearby-platform.properties') }}</a>
                        <a class="dropdown-item<?php if ($route == 'businessCards') echo ' active'; ?>" href="{{ url('dashboard/business-cards') }}"><i class="mi account_circle mr-1" style="top:3px;"></i> {{ trans('nearby-platform.business_cards') }}</a>
                        <a class="dropdown-item<?php if ($route == 'videos') echo ' active'; ?>" href="{{ url('dashboard/videos') }}"><i class="mi video_library mr-1" style="top:3px;"></i> {{ trans('nearby-platform.videos') }}</a>
                        <a class="dropdown-item<?php if ($route == 'pages') echo ' active'; ?>" href="{{ url('dashboard/pages') }}"><i class="mi insert_drive_file mr-1" style="top:3px;"></i> {{ trans('nearby-platform.pages') }}</a>
                      </div>
                    </li>
<?php if (Gate::allows('manager') || Gate::allows('owner')) { ?>
                    <li class="nav-item<?php if ($route == 'manageUsers') echo ' active'; ?>"><a class="nav-link" href="{{ url('dashboard/manage/users') }}">{{ trans('nearby-platform.users') }}</a></li>
<?php } ?>
                    <li class="nav-item d-block d-lg-none<?php if ($route == 'settings') echo ' active'; ?>"><a class="nav-link" href="{{ url('dashboard/settings/profile') }}">{{ trans('nearby-platform.settings') }}</a></li>
                    <li class="nav-item d-block d-lg-none"><a class="nav-link" href="{{ url('logout') }}"><i class="mi power_settings_new" style="top:3px;"></i> {{ trans('nearby-platform.logout') }}</a>
<?php if (count(config('system.available_languages')) > 1) { ?>
                    <div class="dropdown-divider d-block d-lg-none"></div>
<?php
foreach (config('system.available_languages') as $code => $language) {
$selected = (auth()->user()->locale == $code) ? true : false;
?>
                    <li class="nav-item d-block d-lg-none<?php if ($selected) echo ' active'; ?>"><a class="nav-link" href="?set_lang={{ $code }}">{{ $language }}</a></li>
<?php
}
?>
<?php } ?>
<?php if (\Request::segment(1) == 'dashboard' && count(config('system.available_languages')) > 1) { ?>
                    <li class="nav-item dropdown d-none d-lg-block">
                      <a class="nav-link" href="javascript:void(0);" id="navbarDropdownMenuLanguage" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="mi language" style="font-size: 1.8rem; top: 8px"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLanguage">
<?php
foreach (config('system.available_languages') as $code => $language) {
$selected = (auth()->user()->locale == $code) ? true : false;
?>
                        <a class="dropdown-item<?php if ($selected) echo ' active'; ?>" href="?set_lang={{ $code }}">{{ $language }}</a>
<?php
}
?>
                      </div>
                    </li>
<?php
}
?>


                  </ul>
                </div>
              </div>
            </div>
          </div>
        </nav>
      </section>
    </div>
<?php } ?>
</div>