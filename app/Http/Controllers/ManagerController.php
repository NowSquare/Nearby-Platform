<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Core;

class ManagerController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Users Controller
	|--------------------------------------------------------------------------
	|
	*/

  public function __construct() {
  }

	/**
	 * Users
	 */
	public function showUsers() {

    $sortby = request()->get('sortby', 'last_login');
    $order = request()->get('order', 'desc');

    $users = auth()->user()->childUsers()->orderBy($sortby, $order)->paginate(20);

    $links = $users->appends(['sortby' => $sortby, 'order' => $order])->links('vendor.pagination.bootstrap-4');

    $order = ($order == 'desc') ? 'asc' : 'desc';

		return view('app.manage.users', compact('users', 'sortby', 'order', 'links'));
	}

	/**
	 * Add user
	 */
	public function showAddUser() {
    if (\Gate::allows('manager')) {
      $count = auth()->user()->childUsers()->count();
      $item = 'users';

      $limit_reached = \App\Http\Controllers\Core\Helpers::getLimitReached($count, $item);

      if ($limit_reached !== false) {
        return $limit_reached;
      }

      $user = new \stdClass;
      $user->active = true;
      $user->name = '';
      $user->email = '';
      $user->locale = auth()->user()->locale;
      $user->timezone = auth()->user()->timezone;

      return view('app.manage.user', compact('user'));
    }
  }

	/**
	 * Edit user
	 */
	public function showEditUser($sl) {
    $qs = Core\Secure::string2array($sl);

    $user = \App\User::where('parent_id', auth()->user()->id)->where('id', $qs['user_id'])->first();

    return view('app.manage.user', compact('sl', 'user'));
	}

	/**
	 * Post new or update user
	 */
	public function postUser() {
    $sl = request()->get('sl', null);

    if ($sl != null) {
      $qs = Core\Secure::string2array($sl);
      $user = \App\User::where('parent_id', auth()->user()->id)->where('id', $qs['user_id'])->first();
      $msg = trans('nearby-platform.item_saved_succesfully', ['item' => trans('nearby-platform.user')]);
    } else {
      $user = new \App\User;
      $msg = trans('nearby-platform.item_created_succesfully', ['item' => trans('nearby-platform.user')]);
      //$user->referrer_token = strtoupper(substr(base_convert(md5(microtime()), 16,32), 0, 12));
    }

    $input = array(
      'email' => request()->get('email'),
      'password' => request()->get('password')
    );

    $email_rule = ($sl != null) ? 'required|email|unique:users,email,' . $qs['user_id'] : 'required|email|unique:users,email';
    $password_rule = ($sl != null) ? 'nullable|min:6': 'nullable';

    $rules = array(
      'email'  => $email_rule,
      'password'  => $password_rule
    );

    $validator = \Validator::make($input, $rules);

    if($validator->fails()) {
      return redirect()
        ->back()
        ->withErrors($validator)
        ->withInput();
    }

    $user->parent_id = auth()->user()->id;
    $user->active = (bool) request()->get('active', false);
    $user->name = request()->get('name');
    $user->email = request()->get('email');
    $user->locale = request()->get('language');
    $user->timezone = request()->get('timezone');

    // Generate password
    if ($sl == null) {
      $password = str_random(6);
      $user->password = bcrypt($password);
    }

    // Update existing password
    if ($sl != null && request()->get('password', null) != null) {
      $user->password = bcrypt(request()->get('password'));
    }

    $user->save();

    // Send email with password to user
    if ($sl == null) {
      $mail = new \stdClass;
      $mail->to = $user->email;

      $mail->html = '';
      $mail->html .= "Hi " . $user->name . ",<br><br>";
      $mail->html .= "An account has been created for you on <strong>" . env('APP_URL') . "</strong>.<br><br>";
      $mail->html .= "<strong>Email address</strong>: " . $user->email . "<br>";
      $mail->html .= "<strong>Password</strong>: " . $password . "<br><br>";
      $mail->html .= "You can login and manage content.<br><br>";
      $mail->html .= "Kind regards,<br>";
      $mail->html .= "The " . auth()->user()->verified_host . " Team";

      \Mail::send('emails.default', ['body' => $mail->html, 'header' => '', 'footer' => ''], function($message) use($mail) {
        $message->from(auth()->user()->email, auth()->user()->name);
        $message->replyTo(auth()->user()->email, auth()->user()->name);
        $message->subject('Account created - ' . auth()->user()->verified_host);
        $message->bcc(auth()->user()->email);
        $message->to($mail->to);
      });
    }

    return redirect('dashboard/manage/users')->with('message', $msg);
  }

  /**
   * Login as user
   */
  public function loginAsUser($sl) {
    if($sl != '') {
      $qs = Core\Secure::string2array($sl);

      // First check if the user has permission to log in as the selected user
      $user = \App\User::where('parent_id', auth()->user()->id)->where('id', $qs['user_id'])->first();

      if (! empty($user)) {
        // Set session to redirect to in case of logout
        $logout = Core\Secure::array2string(['user_id' => auth()->user()->id]);
        \Session::put('logout', $logout);

        \Auth::loginUsingId($qs['user_id']);

        return redirect('dashboard');
      } else {
        return redirect('dashboard/manage/users');
      }
    }
  }

  /**
   * Delete user
   */
  public function postDeleteUser(Request $request) {

    $sl = $request->input('sl', '');

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);

      $user = \App\User::where('parent_id', auth()->user()->id)->where('id', $qs['user_id'])->first();

      if (! empty($user)) {

        // Delete deal PDFs and favicons
        $deals = \App\Deal::where('user_id', $user->id)->get();
        if (! empty($deals)) {
          foreach ($deals as $deal) {
            // Deal
            $path = storage_path('app/deals/pdf/');
            $pdf = $path . $deal->id . '.pdf';
            \File::delete($pdf);

            // Favicon
            $path = public_path('favicons/');
            $favicon = 'deal-' . \App\Http\Controllers\Core\Secure::staticHash($deal->id) . '.ico';
            \File::delete($path . $favicon);

            // Metrics
            $metrics = \DB::table('metrics')->where('metricable_type', 'App\Deal')->where('metricable_id', $deal->id)->delete();
          }
        }

        // Delete coupon favicons
        $coupons = \App\Coupon::where('user_id', $user->id)->get();
        if (! empty($coupons)) {
          foreach ($coupons as $coupon) {
            // Favicon
            $path = public_path('favicons/');
            $favicon = 'coupon-' . \App\Http\Controllers\Core\Secure::staticHash($coupon->id) . '.ico';
            \File::delete($path . $favicon);

            // Metrics
            $metrics = \DB::table('metrics')->where('metricable_type', 'App\Coupon')->where('metricable_id', $coupon->id)->delete();
          }
        }

        // Delete reward favicons
        $rewards = \App\Reward::where('user_id', $user->id)->get();
        if (! empty($rewards)) {
          foreach ($rewards as $reward) {
            // Favicon
            $path = public_path('favicons/');
            $favicon = 'reward-' . \App\Http\Controllers\Core\Secure::staticHash($reward->id) . '.ico';
            \File::delete($path . $favicon);

            // Metrics
            $metrics = \DB::table('metrics')->where('metricable_type', 'App\Reward')->where('metricable_id', $reward->id)->delete();
          }
        }

        // Delete property favicons
        $properties = \App\Property::where('user_id', $user->id)->get();
        if (! empty($properties)) {
          foreach ($properties as $property) {
            // Favicon
            $path = public_path('favicons/');
            $favicon = 'property-' . \App\Http\Controllers\Core\Secure::staticHash($property->id) . '.ico';
            \File::delete($path . $favicon);

            // Metrics
            $metrics = \DB::table('metrics')->where('metricable_type', 'App\Property')->where('metricable_id', $coupon->id)->delete();
          }
        }

        // Delete business card vCards and favicons
        $cards = \App\BusinessCard::where('user_id', $user->id)->get();
        if (! empty($cards)) {
          foreach ($cards as $card) {
            // vCard
            $path = storage_path('app/cards/');
            $vcard = $path . \App\Http\Controllers\Core\Secure::staticHash($card->id) . '.vcf';
            \File::delete($vcard);

            // Favicon
            $path = public_path('favicons/');
            $favicon = 'card-' . \App\Http\Controllers\Core\Secure::staticHash($card->id) . '.ico';
            \File::delete($path . $favicon);

            // Metrics
            $metrics = \DB::table('metrics')->where('metricable_type', 'App\BusinessCard')->where('metricable_id', $card->id)->delete();
          }
        }

        // Delete page favicons
        $pages = \App\Page::where('user_id', $user->id)->get();
        if (! empty($pages)) {
          foreach ($pages as $page) {
            // Favicon
            $path = public_path('favicons/');
            $favicon = 'page-' . \App\Http\Controllers\Core\Secure::staticHash($page->id) . '.ico';
            \File::delete($path . $favicon);

            // Metrics
            $metrics = \DB::table('metrics')->where('metricable_type', 'App\Page')->where('metricable_id', $page->id)->delete();
          }
        }

        // Delete user and all related records
        $user->delete();
      }

      return response()->json(['success' => true, 'redir' => 'reload']);
    }
  }
}