<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Core;
use Illuminate\Http\File;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;

class CouponsController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Coupons Controller
	|--------------------------------------------------------------------------
	|
	*/

  public function __construct() {
  }

  /**
   * Fields stored in meta JSON column ($model->meta)
   */
	public static function contentFields($locale = null) {
    if ($locale !== null) {
      $old_locale = app()->getLocale();
      app()->setLocale($locale);
    }

    $content_fields = [
      'primaryColor' => [
        'default' => 'green',
        'excludeDefault' => true
      ],
      'secondaryColor' => [
        'default' => 'blue',
        'excludeDefault' => true
      ],
      'primaryBtnText' => [
        'type' => 'text',
        'label' => trans('nearby-platform.primary_button'),
        'default' => trans('nearby-platform.redeem')
      ],
      'callBtnText' => [
        'type' => 'text',
        'label' => trans('nearby-platform.call'),
        'default' => trans('nearby-platform.call')
      ],
      'moreBtnText' => [
        'type' => 'text',
        'label' => trans('nearby-platform.more'),
        'default' => trans('nearby-platform.more')
      ],
      'shareBtnText' => [
        'type' => 'text',
        'label' => trans('nearby-platform.share'),
        'default' => trans('nearby-platform.share')
      ],
      'redeemText' => [
        'type' => 'textarea',
        'label' => trans('nearby-platform.redeem_text'),
        'default' => trans('nearby-platform.redeem_text_default')
      ],
      'redeemedText' => [
        'type' => 'textarea',
        'label' => trans('nearby-platform.succesfully_redeemed_text'),
        'default' => trans('nearby-platform.succesfully_redeemed_coupon_text_default')
      ]
    ];

    if ($locale !== null) {
      app()->setLocale($old_locale);
    }

    return $content_fields;
  }

	/**
	 * View coupon
	 */
	public function viewCoupon($coupon_hash) {
    $coupon_id = \App\Http\Controllers\Core\Secure::staticHashDecode($coupon_hash);
    $coupon = \App\Coupon::where('id', $coupon_id)->first();

    if (! empty($coupon)) {
      // Check if visitor is bot
      $ua = request()->header('User-Agent');
      $dd = new DeviceDetector($ua);
      $dd->setCache(new \Doctrine\Common\Cache\PhpFileCache(storage_path() . '/app/piwik/'));
      $dd->discardBotInformation();
      $dd->parse();

      // Update metrics
      if (! $dd->isBot()) {
        $coupon->startMetrics(new \App\Metrics\CouponViewCountMetrics());
      }

      // Description that fits one line for html tags
      $description = \App\Http\Controllers\Core\Helpers::parseDescription($coupon->details);

      // Set coupon properties
      $coupon->favicon = $coupon->getFavicon();
      $coupon = $this->metaValues($coupon);
      $color = \App\Http\Controllers\Core\Helpers::material2hex($coupon->primaryColor);

      // Check if cookie is set
      $redeemed = (bool) \Cookie::get('c' . $coupon_hash);

      // Settings / Analytics
      $user = \App\User::where('id', $coupon->user_id)->first();
      $ga_code = (isset($user->settings['ga_code']) && $user->settings['ga_code'] != '') ? $user->settings['ga_code'] : '';
      $fb_pixel = (isset($user->settings['fb_pixel']) && $user->settings['fb_pixel'] != '') ? $user->settings['fb_pixel'] : '';

      // Set locale
      app()->setLocale($coupon->language);

		  return view('app.coupons.view-coupon', compact('coupon_hash', 'coupon', 'redeemed', 'description', 'color', 'ga_code', 'fb_pixel'));
    }
	}

	/**
	 * Process meta values
   * Values stored in $model->meta['var'] are converted to $model->var
	 */
  public function metaValues($model, $default_value = null) {
    foreach ($this->contentFields($model->language) as $var => $prop) {
      $excludeDefault = (isset($prop['excludeDefault']) && $prop['excludeDefault']) ? true : false;
      if (! $excludeDefault && $default_value !== null) $prop['default'] = $default_value;
      $model->{$var} = (isset($model->meta[$var])) ? $model->meta[$var] : $prop['default'];
    }

    return $model;
  }

	/**
	 * Process form post according to with $this->contentFields()
	 */
  public function postMetaValues() {
    $return = [];

    foreach ($this->contentFields() as $var => $prop) {
      $excludeDefault = (isset($prop['excludeDefault']) && $prop['excludeDefault']) ? true : false;
      $prop['val'] = ($excludeDefault) ?  $prop['default'] : '';
      $return[$var] = request()->get($var, $prop['val']);
    }

    return $return;
  }

	/**
	 * Show coupon redeemed
	 */
	public function couponRedeemed($coupon_hash) {
    $coupon_id = \App\Http\Controllers\Core\Secure::staticHashDecode($coupon_hash);
    $coupon = \App\Coupon::where('id', $coupon_id)->first();

    if (! empty($coupon)) {
      // Set coupon properties
      $coupon->favicon = $coupon->getFavicon();
      $coupon = $this->metaValues($coupon);
      $color = \App\Http\Controllers\Core\Helpers::material2hex($coupon->primaryColor);

      // Set cookie
      \Cookie::queue('c' . $coupon_hash, 1, 60 * 24 * 365);

      // Set locale
      app()->setLocale($coupon->language);

		  return view('app.coupons.redeemed', compact('coupon_hash', 'coupon', 'color'));
    }
	}

	/**
	 * Redeem coupon
	 */
	public function postRedeemCoupon($coupon_hash) {
    $coupon_id = \App\Http\Controllers\Core\Secure::staticHashDecode($coupon_hash);
    $coupon = \App\Coupon::where('id', $coupon_id)->first();

    if (! empty($coupon)) {
      // Set coupon properties
      $coupon->favicon = $coupon->getFavicon();
      $coupon = $this->metaValues($coupon);
      $color = \App\Http\Controllers\Core\Helpers::material2hex($coupon->primaryColor);

      // QR data
      $pusher_channel = 'coupon_' . uniqid();
      $post = request()->all();
      $post['pc'] = $pusher_channel;
      $post = http_build_query($post);

      $redeem_url = url('coupon/verify/' . $coupon_hash . '/?' . $post);

      // Set locale
      app()->setLocale($coupon->language);

		  return view('app.coupons.redeem-coupon', compact('coupon_hash', 'coupon', 'pusher_channel', 'redeem_url', 'color'));
    }
	}

	/**
	 * Verify redeemed coupon
	 */
	public function verifyCoupon($coupon_hash) {
    $coupon_id = \App\Http\Controllers\Core\Secure::staticHashDecode($coupon_hash);
    $coupon = \App\Coupon::where('id', $coupon_id)->first();

    if (! empty($coupon)) {
      // Set coupon properties
      $coupon->favicon = $coupon->getFavicon();
      $coupon = $this->metaValues($coupon);
      $color = \App\Http\Controllers\Core\Helpers::material2hex($coupon->primaryColor);

      // QR data
      $post = request()->all();
      $post = http_build_query($post);

      $redeem_url = url('coupon/verify/' . $coupon_hash . '/?' . $post);

      // Set locale
      app()->setLocale($coupon->language);

		  return view('app.coupons.verify-coupon', compact('coupon_hash', 'coupon', 'redeem_url', 'color'));
    }
	}

	/**
	 * Post coupon verification
	 */
	public function postVerifyCoupon($coupon_hash) {
    $coupon_id = \App\Http\Controllers\Core\Secure::staticHashDecode($coupon_hash);
    $coupon = \App\Coupon::where('id', $coupon_id)->first();

    if (! empty($coupon)) {

      $input = array(
        'redeem_code' => request()->get('redeem_code')
      );

      $rules = array(
        'redeem_code' => 'required'
      );

      $validator = \Validator::make($input, $rules);

      if ($input['redeem_code'] != $coupon->redeem_code) {
        $validator->getMessageBag()->add('redeem_code', 'Redeem code is not correct.');
      }

      if(! empty($validator->errors()->all())) {
        return redirect()
          ->back()
          ->withErrors($validator)
          ->withInput();
      }

      // Update coupon before setting non-sql columns
      $coupon->number_of_times_redeemed = $coupon->number_of_times_redeemed + 1;
      $coupon->last_redemption = \Carbon\Carbon::now('UTC');
      $coupon->save();

      // Set coupon properties
      $coupon->favicon = $coupon->getFavicon();
      $coupon = $this->metaValues($coupon);
      $color = \App\Http\Controllers\Core\Helpers::material2hex($coupon->primaryColor);

      // Add lead
      $pusher_channel = request()->get('pc');
      $post = request()->except(['_token', 'redeem_code', 'pc']);

      $lead = new \App\CouponLead;

      $lead->user_id = $coupon->user_id;
      $lead->coupon_id = $coupon->id;
      $lead->field_values = $post;
      $lead->save();

      // Push redeem
      $options = array(
        'cluster' => config('broadcasting.connections.pusher.options.cluster'),
        'useTLS' => true
      );

      $pusher = new \Pusher\Pusher(
        config('broadcasting.connections.pusher.key'),
        config('broadcasting.connections.pusher.secret'),
        config('broadcasting.connections.pusher.app_id'),
        $options
      );

      $data = [];
      $pusher->trigger($pusher_channel, 'redeemed', $data);

      // Set locale
      app()->setLocale($coupon->language);

      // app.coupons.verified 
		  return view('app.coupons.redeemed', compact('coupon_hash', 'coupon', 'color'));
    }
	}

	/**
	 * Get list of fields
	 */
  public static function requiredFieldList($locale = null) {
    if ($locale !== null) {
      $old_locale = app()->getLocale();
      app()->setLocale($locale);
    }

    $aFields = [];
    $aFields[] = ['id' => 'name', 'type' => 'text', 'maxlength' => 32, 'placeholder' => trans('nearby-platform.name'), 'name' => '', 'checked' => true, 'value' => ''];
    $aFields[] = ['id' => 'email', 'type' => 'email', 'maxlength' => 64, 'placeholder' => trans('nearby-platform.email'), 'name' => '', 'checked' => true, 'value' => ''];
    $aFields[] = ['id' => 'phone', 'type' => 'tel', 'maxlength' => 12, 'placeholder' => trans('nearby-platform.phone'), 'name' => '', 'checked' => false, 'value' => ''];
    $aFields[] = ['id' => 'address', 'type' => 'text', 'maxlength' => 64, 'placeholder' => trans('nearby-platform.address'), 'name' => '', 'checked' => false, 'value' => ''];
    $aFields[] = ['id' => 'postal_code', 'type' => 'text', 'maxlength' => 12, 'placeholder' => trans('nearby-platform.postal_code'), 'name' => '', 'checked' => false, 'value' => ''];
    $aFields[] = ['id' => 'custom', 'type' => 'text', 'maxlength' => 128, 'placeholder' => trans('nearby-platform.custom'), 'name' => '', 'checked' => false, 'value' => ''];

    if ($locale !== null) {
      app()->setLocale($old_locale);
    }

    return $aFields;
  }

	/**
	 * Ordinal function
	 */
  public static function ordinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number. 'th';
    else
        return $number. $ends[$number % 10];
  }

	/**
	 * Coupons
	 */
	public function showCoupons() {
    $sortby = request()->get('sortby', 'created_at');
    $order = request()->get('order', 'asc');

    if (! in_array($sortby, ['created_at', 'title'])) $sortby = 'created_at';
    if (! in_array($order, ['desc', 'asc'])) $order = 'asc';

    $coupons = \App\Coupon::where('user_id', auth()->user()->id)->orderBy($sortby, $order)->paginate(10);

    if (count($coupons) == 0) {
      return redirect('dashboard/coupons/add');
    }

    $links = $coupons->appends(['sortby' => $sortby, 'order' => $order])->links('vendor.pagination.bootstrap-4');

    $order = ($order == 'desc') ? 'asc' : 'desc';

		return view('app.coupons.overview', compact('coupons', 'sortby', 'order', 'links'));
	}

	/**
	 * Leads
	 */
	public function showLeads() {
    $allowed_sort = ['created_at'];
    $fields = \App\Http\Controllers\CouponsController::requiredFieldList();
    foreach ($fields as $field) { $allowed_sort[] = $field['id']; }

    $filterCoupons = request()->get('filterCoupons', []);
    $qsFilterCoupons['filterCoupons'] = [];
    $sortby = request()->get('sortby', 'created_at');
    $order = request()->get('order', 'asc');

    if (! in_array($sortby, $allowed_sort)) $sortby = 'created_at';
    if (! in_array($order, ['desc', 'asc'])) $order = 'asc';

    $coupons = \App\Coupon::where('user_id', auth()->user()->id)->orderBy('title', 'asc')->get();

    if (empty($filterCoupons)) {
      foreach ($coupons as $coupon) {
        $filterCoupons[] = $coupon->id;
        $qsFilterCoupons['filterCoupons'][] = $coupon->id;
      }
    } else {
      foreach ($filterCoupons as $coupon) { 
        $qsFilterCoupons['filterCoupons'][] = $coupon;
      }
    }

    $leads = \App\CouponLead::where('user_id', auth()->user()->id)->whereIn('coupon_id', $filterCoupons)->orderBy($sortby, $order)->paginate(10);

    $links = $leads->appends(['sortby' => $sortby, 'order' => $order, 'filterCoupons' => $filterCoupons])->links('vendor.pagination.bootstrap-4');

    $reverse_order = ($order == 'desc') ? 'asc' : 'desc';

		return view('app.coupons.leads', compact('leads', 'coupons', 'filterCoupons', 'qsFilterCoupons', 'fields', 'sortby', 'order', 'reverse_order', 'sortby', 'links'));
	}

  /**
   * Export leads
   */

  public function getExportLeads() {
    $allowed_sort = ['created_at'];
    $sql_select = [];
    $sql_select[] = 'coupons.title as Coupon';

    $fields = \App\Http\Controllers\CouponsController::requiredFieldList();
    foreach ($fields as $field) { 
      $allowed_sort[] = $field['id'];
      $sql_select[] = 'coupon_leads.field_values->' . $field['id'] . ' as ' . ucfirst(str_replace('_', ' ', $field['id'])) . '';
    }

    $sql_select[] = 'coupon_leads.created_at as Created';

    $filterCoupons = request()->get('filterCoupons', []);
    $sortby = request()->get('sortby', 'created_at');
    $order = request()->get('order', 'asc');

    if (! in_array($sortby, $allowed_sort)) $sortby = 'created_at';
    if (! in_array($order, ['desc', 'asc'])) $order = 'asc';

    $filename = str_slug('coupon-leads-' . date('Y-m-d h:i:s')) . '.xlsx';

    $leads = \DB::table('coupons')
      ->leftJoin('coupon_leads', 'coupons.id', '=', 'coupon_leads.coupon_id')
      ->where('coupons.user_id', auth()->user()->id)
      ->whereIn('coupons.id', $filterCoupons)
      ->select($sql_select)
      ->orderBy('coupon_leads.' . $sortby, 'coupon_leads.' . $order)
      ->get()->map(function ($item) {
        return get_object_vars($item);
      });

    return $leads->downloadExcel(
      $filename,
      $writerType = null,
      $headings = true
    );
  }

  /**
   * Delete lead
   */
  public function postDeleteLead(Request $request) {

    $sl = $request->input('sl', '');

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);

      $query = \App\CouponLead::where('user_id', auth()->user()->id)->where('id', $qs['coupon_id']);
      $query->delete();

      return response()->json(['success' => true, 'redir' => 'reload']);
    }
  }

  /**
   * Delete leads
   */
  public function postDeleteLeads(Request $request) {

    $rows = $request->input('rows', []);

    foreach ($rows as $row) {
      $query = \App\CouponLead::where('user_id', auth()->user()->id)->where('id', $row);
      $query->delete();
    }

    return response()->json(['success' => true, 'redir' => 'reload']);
  }

	/**
	 * New coupon
	 */
	public function showAddCoupon() {
    $count = auth()->user()->coupons->count();
    $item = trans('nearby-platform.coupons');

    $limit_reached = \App\Http\Controllers\Core\Helpers::getLimitReached($count, $item);

    if ($limit_reached !== false) {
      return $limit_reached;
    }

    $coupon = new \App\Coupon;
    $coupon->language = auth()->user()->locale;
    $coupon->expiresMonth = null;
    $coupon->expiresDay = null;
    $coupon->expiresYear = null;
    $coupon->expiresHour = null;
    $coupon->expiresMinute = null;

    // Set coupon properties
    $coupon = $this->metaValues($coupon, '');

    $coupon->fields = [];

    $colors = ['red', 'pink', 'purple', 'deep-purple', 'indigo', 'blue', 'light-blue', 'cyan', 'teal', 'green', 'light-green', 'lime', 'yellow', 'amber', 'orange', 'deep-orange', 'brown', 'grey', 'blue-grey'];

    $content_fields = $this->contentFields();

		return view('app.coupons.coupon', compact('coupon', 'count', 'colors', 'content_fields'));
	}

	/**
	 * Edit coupon
	 */
	public function showEditCoupon($sl) {
    $qs = Core\Secure::string2array($sl);

    $coupon = \App\Coupon::where('user_id', auth()->user()->id)->where('id', $qs['coupon_id'])->first();

    if ($coupon->expiration_date != null) {
      $dt = \Carbon\Carbon::parse($coupon->expiration_date);

      $coupon->expiresMonth = $dt->month;
      $coupon->expiresDay = $dt->day;
      $coupon->expiresYear = $dt->year;
    }

    if ($coupon->expiration_date_time != null) {
      $dt = \Carbon\Carbon::parse($coupon->expiration_date_time);

      $coupon->expiresMonth = $dt->month;
      $coupon->expiresDay = $dt->day;
      $coupon->expiresYear = $dt->year;
      $coupon->expiresHour = $dt->hour;
      $coupon->expiresMinute = $dt->minute;
    }

    $colors = ['red', 'pink', 'purple', 'deep-purple', 'indigo', 'blue', 'light-blue', 'cyan', 'teal', 'green', 'light-green', 'lime', 'yellow', 'amber', 'orange', 'deep-orange', 'brown', 'grey', 'blue-grey'];

    // Set coupon properties
    $coupon = $this->metaValues($coupon, '');

    $coupon->favicon = $coupon->getFavicon();

    $content_fields = $this->contentFields($coupon->language);

    return view('app.coupons.coupon', compact('sl', 'coupon', 'colors', 'content_fields'));
	}

	/**
	 * Post new or update coupon
	 */
	public function postCoupon() {
    $input = array(
      'image' => request()->file('image'),
      'extension' => (request()->file('image') != null) ? strtolower(request()->file('image')->getClientOriginalExtension()) : null,
      'favicon' => request()->file('favicon'),
      'favicon_extension' => (request()->file('favicon') != null) ? strtolower(request()->file('favicon')->getClientOriginalExtension()) : null
    );

    $rules = array(
      'file' => 'nullable|mimes:jpeg,gif,png',
      'extension'  => 'nullable|in:jpg,jpeg,png,gif',
      'favicon' => 'nullable|mimes:jpeg,gif,png',
      'favicon_extension'  => 'nullable|in:jpg,jpeg,png,gif'
    );

    $validator = \Validator::make($input, $rules);

    if($validator->fails()) {
      return redirect()
        ->back()
        ->withErrors($validator)
        ->withInput();
    }

    $sl = request()->get('sl', null);

    if ($sl != null) {
      $qs = Core\Secure::string2array($sl);
      $coupon = \App\Coupon::where('user_id', auth()->user()->id)->where('id', $qs['coupon_id'])->first();
      $msg = trans('nearby-platform.item_saved_succesfully', ['item' => trans('nearby-platform.coupon')]);
    } else {
      $coupon = new \App\Coupon;
      $msg = trans('nearby-platform.item_created_succesfully', ['item' => trans('nearby-platform.coupon')]);
    }

    $coupon->user_id = auth()->user()->id;

    $upload_image = (bool) request()->get('upload_image', false);

    if ($upload_image) {
      $coupon->image = request()->file('image', \Czim\Paperclip\Attachment\Attachment::NULL_ATTACHMENT);
    }

    $coupon->language = request()->get('language', auth()->user()->locale);
    $coupon->title = request()->get('title');
    $coupon->details = request()->get('details');
    $coupon->title = request()->get('title');
    $coupon->redeem_code = request()->get('redeem_code');
    $coupon->phone = request()->get('phone', null);
    $coupon->website = request()->get('website', null);
    $coupon->address = request()->get('address', null);
    $coupon->street = request()->get('street', null);
    $coupon->street_number = request()->get('street_number', null);
    $coupon->postal_code = request()->get('postal_code', null);
    $coupon->city = request()->get('city', null);
    $coupon->state = request()->get('state', null);
    $coupon->country = request()->get('country', null);
    $coupon->lat = request()->get('lat', null);
    $coupon->lng = request()->get('lng', null);

    $coupon->fields = request()->get('required', []);

    $coupon->meta = $this->postMetaValues();

    $expiresMonth = request()->get('expiresMonth', null);
    $expiresDay = request()->get('expiresDay', null);
    $expiresYear = request()->get('expiresYear', null);
    $expiresHour = request()->get('expiresHour', null);
    $expiresMinute = request()->get('expiresMinute', null);

    $expiration_date = null;
    $expiration_date_time = null;

    if ($expiresMonth != null && $expiresDay != null && $expiresYear != null) {
      $expiration_date = $expiresYear . '-' . $expiresMonth . '-' . $expiresDay;

      if ($expiresHour != null && $expiresMinute != null) {
        $expiration_date_time = $expiration_date . ' ' . $expiresHour . ':' . $expiresMinute . ':00';
      }
    }

    $coupon->expiration_date = $expiration_date;
    $coupon->expiration_date_time = $expiration_date_time;

    $coupon->save();

    // Favicon
    $upload_favicon = (bool) request()->get('upload_favicon', false);

    if ($upload_favicon) {
      $path = public_path('favicons/');
      $favicon = 'coupon-' . \App\Http\Controllers\Core\Secure::staticHash($coupon->id) . '.ico';

      // First delete existing
      \File::delete($path . $favicon);

      if (request()->file('favicon', null) != null) {
        $ico_lib = new \PHP_ICO(request()->file('favicon'), [[32, 32], [64, 64]]);
        $ico_lib->save_ico($path . $favicon);
      }
    }

    return redirect('dashboard/coupons')->with('message', $msg);
  }

  /**
   * Delete coupon
   */
  public function postDeleteCoupon(Request $request) {

    $sl = $request->input('sl', '');

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);

      $query = \App\Coupon::where('user_id', auth()->user()->id)->where('id', $qs['coupon_id']);
      $coupon = $query->first();

      // Delete metrics
      $metrics = \DB::table('metrics')->where('type', 'App\Metrics\CouponViewCountMetrics')->where('metricable_id', $qs['coupon_id'])->delete();

      // Delete favicon
      $path = public_path('favicons/');
      $favicon = 'coupon-' . \App\Http\Controllers\Core\Secure::staticHash($coupon->id) . '.ico';

      // First delete existing
      \File::delete($path . $favicon);

      $query->delete();

      return response()->json(['success' => true, 'redir' => 'reload']);
    }
  }
}