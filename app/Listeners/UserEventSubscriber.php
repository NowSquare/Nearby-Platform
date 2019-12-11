<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Core;
use Platform\Controllers\Helper;
use Carbon\Carbon;

class UserEventSubscriber {
  /**
   * Handle user login events.
   */
  public function onUserLogin($event) {
    // Check if owner logs in
    $is_owner = false;
    $sl = \Session::get('logout', '');

    if ($sl != '') {
      $qs = Core\Secure::string2array($sl);
      $is_owner = (is_numeric($qs['user_id'])) ? true : false;
    }

    if (! $is_owner) {
      // Check if user active
      if ($event->user->active == 0) {
        \Auth::guard('web')->logout();
      }

      // Update user
      $event->user->logins = $event->user->logins + 1;
      //$event->user->last_ip =  request()->ip();
      $event->user->last_login = Carbon::now('UTC');

      $event->user->save();
    }
  }

  /**
   * Handle user logout events.
   */
  public function onUserLogout($event) {
    // Log admin back in
    $sl = \Session::pull('logout', '');
    if($sl != '') {
      $qs = Core\Secure::string2array($sl);
      \Auth::loginUsingId($qs['user_id'], true);
      //return redirect('dashboard/admin/users');
    }
  }

  /**
   * Handle user registration events.
   */
  public function onLogRegisteredUser($event) {
  }

  /**
   * Register the listeners for the subscriber.
   *
   * @param  Illuminate\Events\Dispatcher  $events
   */
  public function subscribe($events) {
    $events->listen('Illuminate\Auth\Events\Login', 'App\Listeners\UserEventSubscriber@onUserLogin');
    $events->listen('Illuminate\Auth\Events\Logout', 'App\Listeners\UserEventSubscriber@onUserLogout');
    //$events->listen('Illuminate\Auth\Events\Registered', 'App\Listeners\UserEventSubscriber@onLogRegisteredUser');
    //$events->listen('Illuminate\Auth\Events\Attempting', 'App\Listeners\UserEventSubscriber@onLogAuthenticationAttempt');
    //$events->listen('Illuminate\Auth\Events\Authenticated', 'App\Listeners\UserEventSubscriber@onLogAuthenticated');
    //$events->listen('Illuminate\Auth\Events\Lockout', 'App\Listeners\UserEventSubscriber@onLogLockout');
  }
}