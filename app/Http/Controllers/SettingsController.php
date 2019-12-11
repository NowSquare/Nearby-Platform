<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Core;

class SettingsController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Settings Controller
	|--------------------------------------------------------------------------
	|
	*/

  public function __construct() {
  }

	/**
	 * Settings
	 */
	public function showSettings() {
		return view('app.settings.overview');
	}

	/**
	 * Profile
	 */
	public function showSettingsProfile() {
    $now = \Carbon\Carbon::now()->tz('UTC');
    $expires = auth()->user()->expires;
    $trial_ends_at = auth()->user()->trial_ends_at;

    if ($trial_ends_at != null) {
      $account_type = 'trial';
      $expires = $trial_ends_at;
    } else {
      $account_type = 'account';
    }

    $expires = ($expires === null) ? 'never' : \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $expires, \Auth::user()->timezone)->diffForHumans();

		return view('app.settings.profile', compact('account_type', 'expires'));
	}

	/**
	 * Profile update
	 */
	public function postSettingsProfile(Request $request) {
    if (env('APP_DEMO', false)) {
      return redirect()->back()->withErrors(['The system is in demo mode, you can\'t update the administrator profile']);
    }

    $validator = \Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore(auth()->user()->id)],
        'new_password' => 'nullable|string|min:6',
        'password' => 'required|string|min:6',
    ]);

    $validator->after(function ($validator) use($request) {
        if (! \Hash::check($request->get('password'), auth()->user()->password)) {
            $validator->errors()->add('password', trans('nearby-platform.incorrect_password'));
        }
    });

    if ($validator->fails()) {
        return redirect()
          ->back()
          ->withErrors($validator)
          ->withInput();
    }

    $user = auth()->user();

    $user->name = $request->get('name');
    $user->email = $request->get('email');
    $user->locale = $request->get('language');
    $user->timezone = $request->get('timezone');

    if ($request->get('new_password', null) != null) {
      $user->password = \Hash::make($request->get('new_password'));
    }

    $user->save();

    return redirect('dashboard/settings/profile')->with('message', trans('nearby-platform.changes_saved'));
	}

	/**
	 * Analytics
	 */
	public function showSettingsAnalytics() {

    $user = auth()->user();

    $ga_code = $user->settings['ga_code'] ?? null;
    $fb_pixel = $user->settings['fb_pixel'] ?? null;

		return view('app.settings.analytics', compact('ga_code', 'fb_pixel'));
	}

	/**
	 * Analytics update
	 */
	public function postSettingsAnalytics(Request $request) {
    if (env('APP_DEMO', false)) {
      return redirect()->back()->withErrors(['The system is in demo mode']);
    }

    $validator = \Validator::make($request->all(), [
        'ga_code' => 'nullable|string|regex:/^UA-\d{4,}-\d+$/'
    ]);

    $validator->after(function ($validator) use($request) {
        if (! \Hash::check($request->get('password'), auth()->user()->password)) {
            $validator->errors()->add('password', trans('nearby-platform.incorrect_password'));
        }
    });

    if ($validator->fails()) {
        return redirect()
          ->back()
          ->withErrors($validator)
          ->withInput();
    }

    $user = auth()->user();
    $user['settings->ga_code'] = request()->get('ga_code', null);
    $user['settings->fb_pixel'] = request()->get('fb_pixel', null);
    $user->save();

    return redirect('dashboard/settings/analytics')->with('message', trans('nearby-platform.changes_saved'));
	}

}