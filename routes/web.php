<?php
/*
|--------------------------------------------------------------------------
| Globals
|--------------------------------------------------------------------------
*/

// String for REGEX with all available languages, except EN as that's the default language
$languages = implode('|', array_keys(array_except(config('system.available_languages'), ['en'])));

/*
|--------------------------------------------------------------------------
| In case you can't configure www to non-www redirect in the hosting
| dashboard, you can point the DNS of both the top level as www subdomain
| to the script, and have the script redirect to non-www.
|--------------------------------------------------------------------------
*/

$domains_to_redirect = [
  'www.' . env('APP_HOST') => env('APP_HOST')
];

foreach ($domains_to_redirect as $domain_to_redirect => $redirect_to) {
  if (starts_with(request()->getHttpHost(), $domain_to_redirect)) {
    if ($domain_to_redirect == 'www.' . $redirect_to) {
      Route::get('', function () use ($redirect_to) {
        return \Redirect::to('https://' . $redirect_to, 301);
      });
    }
    Route::get('{any?}', function ($any) use ($redirect_to) {
      return \Redirect::to('https://' . $redirect_to, 301);
    })->where('any', '.*');
  }
}

/*
|--------------------------------------------------------------------------
| Show main website, reseller website or redirect to login in case of
| premium account.
|--------------------------------------------------------------------------
*/

if ($languages != '') {
  Route::get('/{language?}', 'WebsiteController@home')->where('language', '[' . $languages . ']+')->name('home');
} else {
  Route::get('/', 'WebsiteController@home')->name('home');
}

Route::get('legal', 'WebsiteController@legal')->name('legal');
Route::get('privacy-policy', 'WebsiteController@privacyPolicy')->name('privacy-policy');

Route::get('install', 'InstallationController@getInstall')->name('installation');
Route::post('install', 'InstallationController@postInstall');

// Deals
Route::get('deal/{deal_hash}', 'DealsController@viewDeal');
Route::get('deal/download/{deal_hash}', 'DealsController@downloadPdf');

// Properties
Route::get('property/{property_hash}', 'PropertiesController@viewProperty');
Route::get('property/download/{property_hash}', 'PropertiesController@downloadPdf');

// Coupons
Route::get('coupon/{coupon_hash}', 'CouponsController@viewCoupon');
Route::post('coupon/redeem/{coupon_hash}', 'CouponsController@postRedeemCoupon');
Route::get('coupon/redeemed/{coupon_hash}', 'CouponsController@couponRedeemed');
Route::get('coupon/verify/{coupon_hash}', 'CouponsController@verifyCoupon');
Route::post('coupon/verify/{coupon_hash}', 'CouponsController@postVerifyCoupon');

// Rewards
Route::get('reward/{reward_hash}', 'RewardsController@viewReward');
Route::post('reward/redeem/{reward_hash}', 'RewardsController@postRedeemReward');
Route::get('reward/redeemed/{reward_hash}', 'RewardsController@rewardRedeemed');
Route::get('reward/verify/{reward_hash}', 'RewardsController@verifyReward');
Route::post('reward/verify/{reward_hash}', 'RewardsController@postVerifyReward');
Route::get('reward/check-in/{reward_hash}', 'RewardsController@checkInReward');
Route::get('reward/checked-in/{reward_hash}', 'RewardsController@checkedInReward');
Route::post('reward/check-in/{reward_hash}', 'RewardsController@postCheckInReward');

// Business card
Route::get('card/{card_hash}', 'BusinessCardController@viewCard');
Route::get('card/download/{card_hash}', 'BusinessCardController@downloadVCard');

// Pages
Route::get('page/{page_hash}', 'PagesController@viewPage');

// Videos
Route::get('video/{video_hash}', 'VideosController@viewVideo');

// QR
Route::get('qr/{qr_hash}', 'QrController@viewQrCode');

// Nearby Platform help
Route::get('dashboard/help', 'HelpController@nearbyPlatformHelp')->name('nearby-platform-help');
Route::get('dashboard/help/{page}', 'HelpController@nearbyPlatformHelpPage')->name('nearby-platform-help');
Route::get('dashboard/help/{page}/{sub}', 'HelpController@nearbyPlatformHelpPageSub')->name('nearby-platform-help');

// Secured web routes
Route::group(['middleware' => 'auth'], function () {

  /*
  |--------------------------------------------------------------------------
  | Dashboard
  |--------------------------------------------------------------------------
  */

  Route::get('dashboard', 'DashboardController@dashboard')->name('dashboard');

  /*
  |--------------------------------------------------------------------------
  | QR codes
  |--------------------------------------------------------------------------
  */
  
  Route::get('dashboard/qr', 'QrController@showQrCodes')->name('qr');
  Route::get('dashboard/qr/add', 'QrController@showAddQrCode')->name('qr');
  Route::get('dashboard/qr/edit/{sl}', 'QrController@showEditQrCode')->name('qr');
  Route::post('dashboard/qr/save', 'QrController@postQrCode');
  Route::post('dashboard/qr/delete', 'QrController@postDeleteQrCode');
  Route::get('dashboard/qr/download/{sl}', 'QrController@getDownloadQrCode');

  /*
  |--------------------------------------------------------------------------
  | Deals
  |--------------------------------------------------------------------------
  */

  Route::get('dashboard/deals', 'DealsController@showDeals')->name('deals');
  Route::get('dashboard/deals/add', 'DealsController@showAddDeal')->name('deals');
  Route::get('dashboard/deals/edit/{sl}', 'DealsController@showEditDeal')->name('deals');
  Route::post('dashboard/deals/save', 'DealsController@postDeal');
  Route::post('dashboard/deals/delete', 'DealsController@postDeleteDeal');

  /*
  |--------------------------------------------------------------------------
  | Properties
  |--------------------------------------------------------------------------
  */

  Route::get('dashboard/properties', 'PropertiesController@showProperties')->name('properties');
  Route::get('dashboard/properties/add', 'PropertiesController@showAddProperty')->name('properties');
  Route::get('dashboard/properties/edit/{sl}', 'PropertiesController@showEditProperty')->name('properties');
  Route::post('dashboard/properties/save', 'PropertiesController@postProperty');
  Route::post('dashboard/properties/delete', 'PropertiesController@postDeleteProperty');

  /*
  |--------------------------------------------------------------------------
  | Coupons
  |--------------------------------------------------------------------------
  */

  Route::get('dashboard/coupons', 'CouponsController@showCoupons')->name('coupons');
  Route::get('dashboard/coupons/leads', 'CouponsController@showLeads')->name('coupons');
  Route::post('dashboard/coupons/leads/delete', 'CouponsController@postDeleteLead');
  Route::post('dashboard/coupons/leads/delete/selected', 'CouponsController@postDeleteLeads');
  Route::get('dashboard/coupons/leads/export', 'CouponsController@getExportLeads');
  Route::get('dashboard/coupons/add', 'CouponsController@showAddCoupon')->name('coupons');
  Route::get('dashboard/coupons/edit/{sl}', 'CouponsController@showEditCoupon')->name('coupons');
  Route::post('dashboard/coupons/save', 'CouponsController@postCoupon');
  Route::post('dashboard/coupons/delete', 'CouponsController@postDeleteCoupon');

  /*
  |--------------------------------------------------------------------------
  | Rewards
  |--------------------------------------------------------------------------
  */

  Route::get('dashboard/rewards', 'RewardsController@showRewards')->name('rewards');
  Route::get('dashboard/rewards/leads', 'RewardsController@showLeads')->name('rewards');
  Route::post('dashboard/rewards/leads/delete', 'RewardsController@postDeleteLead');
  Route::post('dashboard/rewards/leads/delete/selected', 'RewardsController@postDeleteLeads');
  Route::get('dashboard/rewards/leads/export', 'RewardsController@getExportLeads');
  Route::get('dashboard/rewards/add', 'RewardsController@showAddReward')->name('rewards');
  Route::get('dashboard/rewards/edit/{sl}', 'RewardsController@showEditReward')->name('rewards');
  Route::post('dashboard/rewards/save', 'RewardsController@postReward');
  Route::post('dashboard/rewards/delete', 'RewardsController@postDeleteReward');

  /*
  |--------------------------------------------------------------------------
  | Business cards
  |--------------------------------------------------------------------------
  */

  Route::get('dashboard/business-cards', 'BusinessCardController@showCards')->name('businessCards');
  Route::get('dashboard/business-cards/add', 'BusinessCardController@showAddCard')->name('businessCards');
  Route::get('dashboard/business-cards/edit/{sl}', 'BusinessCardController@showEditCard')->name('businessCards');
  Route::post('dashboard/business-cards/save', 'BusinessCardController@postCard');
  Route::post('dashboard/business-cards/delete', 'BusinessCardController@postDeleteCard');

  /*
  |--------------------------------------------------------------------------
  | Pages
  |--------------------------------------------------------------------------
  */

  Route::get('dashboard/pages', 'PagesController@showPages')->name('pages');
  Route::get('dashboard/pages/add', 'PagesController@showAddPage')->name('pages');
  Route::get('dashboard/pages/edit/{sl}', 'PagesController@showEditPage')->name('pages');
  Route::post('dashboard/pages/save', 'PagesController@postPage');
  Route::post('dashboard/pages/delete', 'PagesController@postDeletePage');

  /*
  |--------------------------------------------------------------------------
  | Videos
  |--------------------------------------------------------------------------
  */

  Route::get('dashboard/videos', 'VideosController@showVideos')->name('videos');
  Route::get('dashboard/videos/add', 'VideosController@showAddVideo')->name('videos');
  Route::get('dashboard/videos/edit/{sl}', 'VideosController@showEditVideo')->name('videos');
  Route::post('dashboard/videos/save', 'VideosController@postVideo');
  Route::post('dashboard/videos/delete', 'VideosController@postDeleteVideo');
  Route::post('dashboard/videos/verify-url', 'VideosController@postVerifyUrl');

  /*
  |--------------------------------------------------------------------------
  | Settings
  |--------------------------------------------------------------------------
  */
  // Overview
  Route::get('dashboard/settings', 'SettingsController@showSettings')->name('settings');

  // Profile
  Route::get('dashboard/settings/profile', 'SettingsController@showSettingsProfile')->name('settingsProfile');
  Route::post('dashboard/settings/profile', 'SettingsController@postSettingsProfile');

  // Analytics
  Route::get('dashboard/settings/analytics', 'SettingsController@showSettingsAnalytics')->name('settingsAnalytics');
  Route::post('dashboard/settings/analytics', 'SettingsController@postSettingsAnalytics');

  // For owners and managers
  Route::group(['middleware' => 'role:owner,manager'], function () {

    // User management
    Route::get('dashboard/manage/users', 'ManagerController@showUsers')->name('manageUsers');
    Route::get('dashboard/manage/users/add', 'ManagerController@showAddUser')->name('manageUsers');
    Route::get('dashboard/manage/users/login/{sl}', 'ManagerController@loginAsUser');
    Route::get('dashboard/manage/users/edit/{sl}', 'ManagerController@showEditUser')->name('manageUsers');
    Route::post('dashboard/manage/users/save', 'ManagerController@postUser');
    Route::post('dashboard/manage/users/delete', 'ManagerController@postDeleteUser');
  });

  // For owners
  Route::group(['middleware' => 'role:owner'], function () {

    // Update
    Route::get('dashboard/update', 'InstallationController@getUpdate')->name('update');
    Route::post('dashboard/update', 'InstallationController@postUpdate');
  });
});

//Auth::routes();

$optionalLanguageRoutes = function() {
  // Authentication Routes...
  Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
  Route::post('login', 'Auth\LoginController@login');
  Route::post('logout', 'Auth\LoginController@logout')->name('logout');
  Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

  // Password Reset Routes...
  Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
  Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
  Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
  Route::post('password/reset', 'Auth\ResetPasswordController@reset');
};

if ($languages != '') {
  // Add routes with language-prefix
  Route::group(['prefix' => '{language?}', 'where' => ['language' => '[' . $languages . ']+']], $optionalLanguageRoutes);
}

// Add routes without prefix
$optionalLanguageRoutes();