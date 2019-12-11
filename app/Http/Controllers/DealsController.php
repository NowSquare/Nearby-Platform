<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Core;
use Illuminate\Http\File;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;
use App\Jobs\ProcessDealPdf;
use WkPdf;

class DealsController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Deals Controller
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
        'default' => trans('nearby-platform.save_to_device')
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
      ]
    ];

    if ($locale !== null) {
      app()->setLocale($old_locale);
    }

    return $content_fields;
  }

	/**
	 * View deal
	 */
	public function viewDeal($deal_hash) {
    $deal_id = \App\Http\Controllers\Core\Secure::staticHashDecode($deal_hash);
    $deal = \App\Deal::where('id', $deal_id)->first();

    if (! empty($deal)) {
      // Check if visitor is bot
      $ua = request()->header('User-Agent');
      $dd = new DeviceDetector($ua);
      $dd->setCache(new \Doctrine\Common\Cache\PhpFileCache(storage_path() . '/app/piwik/'));
      $dd->discardBotInformation();
      $dd->parse();
      $isMobile = $dd->isMobile();

      // Update metrics
      if (! $dd->isBot()) {
        $deal->startMetrics(new \App\Metrics\PageViewCountMetrics());
      }

      // $print indicates whether the view has to be rendered for PDF generation
      $print = false;

      // Description that fits one line for html tags
      $description = \App\Http\Controllers\Core\Helpers::parseDescription($deal->details);

      // Set deal properties
      $deal->favicon = $deal->getFavicon();
      $deal = $this->metaValues($deal);
      $color = \App\Http\Controllers\Core\Helpers::material2hex($deal->primaryColor);

      // Settings / Analytics
      $user = \App\User::where('id', $deal->user_id)->first();
      $ga_code = (isset($user->settings['ga_code']) && $user->settings['ga_code'] != '') ? $user->settings['ga_code'] : '';
      $fb_pixel = (isset($user->settings['fb_pixel']) && $user->settings['fb_pixel'] != '') ? $user->settings['fb_pixel'] : '';

      $canonical = 'https://' . $user->reseller_host . '/deal/' . $deal_hash;

      // Set locale
      app()->setLocale($deal->language);

		  return view('app.deals.view-deal', compact('deal_hash', 'deal', 'description', 'isMobile', 'print', 'color', 'ga_code', 'fb_pixel', 'canonical'));
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
	 * Download deal as PDF
	 */
	public function downloadPdf($deal_hash) {
    $deal_id = \App\Http\Controllers\Core\Secure::staticHashDecode($deal_hash);

    $deal = \App\Deal::where('id', $deal_id)->first();

    if (! empty($deal)) {
      $path = storage_path('app/deals/pdf/');
      $fileName = str_slug($deal->title) . '.pdf';

      if (! \File::exists($path . $deal_id . '.pdf')) {
        DealsController::generatePdf($deal);
        return "The PDF is being generated. Please try later.";
      } else {
        return response()->download($path . $deal_id . '.pdf', $fileName);
      }
    }
	}

	/**
	 * Generate PDF in cache
	 */
	public static function generatePdf($deal) {
    ProcessDealPdf::dispatch($deal);
  }

	/**
	 * Deals
	 */
	public function showDeals() {
    $sortby = request()->get('sortby', 'created_at');
    $order = request()->get('order', 'asc');

    if (! in_array($sortby, ['created_at', 'title'])) $sortby = 'created_at';
    if (! in_array($order, ['desc', 'asc'])) $order = 'asc';

    $deals = \App\Deal::where('user_id', auth()->user()->id)->orderBy($sortby, $order)->paginate(10);

    if (count($deals) == 0) {
      return redirect('dashboard/deals/add');
    }

    $links = $deals->appends(['sortby' => $sortby, 'order' => $order])->links('vendor.pagination.bootstrap-4');

    $order = ($order == 'desc') ? 'asc' : 'desc';

		return view('app.deals.overview', compact('deals', 'sortby', 'order', 'links'));
	}

	/**
	 * New deal
	 */
	public function showAddDeal() {
    $count = auth()->user()->deals->count();
    $item = trans('nearby-platform.deals');

    $limit_reached = \App\Http\Controllers\Core\Helpers::getLimitReached($count, $item);

    if ($limit_reached !== false) {
      return $limit_reached;
    }

    $deal = new \stdClass;
    $deal->language = auth()->user()->locale;
    $deal->image_file_name = null;
    $deal->favicon = null;
    $deal->title = null;
    $deal->details = null;
    $deal->phone = null;
    $deal->website = null;
    $deal->address = null;
    $deal->street = null;
    $deal->street_number = null;
    $deal->postal_code = null;
    $deal->city = null;
    $deal->state = null;
    $deal->country = null;
    $deal->lat = null;
    $deal->lng = null;
    $deal->expiresMonth = null;
    $deal->expiresDay = null;
    $deal->expiresYear = null;
    $deal->expiresHour = null;
    $deal->expiresMinute = null;

    // Set deal properties
    $deal = $this->metaValues($deal, '');

    $colors = ['red', 'pink', 'purple', 'deep-purple', 'indigo', 'blue', 'light-blue', 'cyan', 'teal', 'green', 'light-green', 'lime', 'yellow', 'amber', 'orange', 'deep-orange', 'brown', 'grey', 'blue-grey'];

    $content_fields = $this->contentFields();

		return view('app.deals.deal', compact('deal', 'count', 'colors', 'content_fields'));
	}

	/**
	 * Edit deal
	 */
	public function showEditDeal($sl) {
    if (request()->has('duplicate')) {
      $count = auth()->user()->deals->count();
      $item = trans('nearby-platform.deals');

      $limit_reached = \App\Http\Controllers\Core\Helpers::getLimitReached($count, $item);

      if ($limit_reached !== false) {
        return $limit_reached;
      }
    }

    $qs = Core\Secure::string2array($sl);

    $deal = \App\Deal::where('user_id', auth()->user()->id)->where('id', $qs['deal_id'])->first();

    if ($deal->expiration_date != null) {
      $dt = \Carbon\Carbon::parse($deal->expiration_date);

      $deal->expiresMonth = $dt->month;
      $deal->expiresDay = $dt->day;
      $deal->expiresYear = $dt->year;
    }

    if ($deal->expiration_date_time != null) {
      $dt = \Carbon\Carbon::parse($deal->expiration_date_time);

      $deal->expiresMonth = $dt->month;
      $deal->expiresDay = $dt->day;
      $deal->expiresYear = $dt->year;
      $deal->expiresHour = $dt->hour;
      $deal->expiresMinute = $dt->minute;
    }

    $colors = ['red', 'pink', 'purple', 'deep-purple', 'indigo', 'blue', 'light-blue', 'cyan', 'teal', 'green', 'light-green', 'lime', 'yellow', 'amber', 'orange', 'deep-orange', 'brown', 'grey', 'blue-grey'];

    // Set deal properties
    $deal = $this->metaValues($deal, '');

    $deal->favicon = $deal->getFavicon();

    $content_fields = $this->contentFields($deal->language);

    return view('app.deals.deal', compact('sl', 'deal', 'colors', 'content_fields'));
	}

	/**
	 * Post new or update deal
	 */
	public function postDeal() {
    $input = array(
      'title' => request()->get('title'),
      'phone' => request()->get('phone'),
      'website' => request()->get('website'),
      'details' => request()->get('details'),
      'address' => request()->get('address'),
      'image' => request()->file('image'),
      'extension' => (request()->file('image') != null) ? strtolower(request()->file('image')->getClientOriginalExtension()) : null,
      'favicon' => request()->file('favicon'),
      'favicon_extension' => (request()->file('favicon') != null) ? strtolower(request()->file('favicon')->getClientOriginalExtension()) : null
    );

    $rules = array(
      'title' => 'required|max:250',
      'phone' => 'nullable|max:24',
      'website' => 'nullable|max:250',
      'address' => 'nullable|max:250',
      'details' => 'required',
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
      $deal = \App\Deal::where('user_id', auth()->user()->id)->where('id', $qs['deal_id'])->first();
      $msg = trans('nearby-platform.item_saved_succesfully', ['item' => trans('nearby-platform.deal')]);
    } else {
      $deal = new \App\Deal;
      $msg = trans('nearby-platform.item_created_succesfully', ['item' => trans('nearby-platform.deal')]);
    }

    $deal->user_id = auth()->user()->id;

    $upload_image = (bool) request()->get('upload_image', false);

    if ($upload_image) {
      if (request()->file('image', null) == null) {
        if (request()->get('upload_image_duplicate', null) !== null) {
          $deal->image = request()->get('upload_image_duplicate');
        } else {
          $deal->image = \Czim\Paperclip\Attachment\Attachment::NULL_ATTACHMENT;
        }
      } else {
        $deal->image = request()->file('image', \Czim\Paperclip\Attachment\Attachment::NULL_ATTACHMENT);
      }
    }

    $deal->language = request()->get('language', auth()->user()->locale);
    $deal->title = request()->get('title');
    $deal->details = request()->get('details');
    $deal->phone = request()->get('phone', null);
    $deal->website = request()->get('website', null);
    $deal->address = request()->get('address', null);
    $deal->street = request()->get('street', null);
    $deal->street_number = request()->get('street_number', null);
    $deal->postal_code = request()->get('postal_code', null);
    $deal->city = request()->get('city', null);
    $deal->state = request()->get('state', null);
    $deal->country = request()->get('country', null);
    $deal->lat = request()->get('lat', null);
    $deal->lng = request()->get('lng', null);

    $deal->meta = $this->postMetaValues();

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

    $deal->expiration_date = $expiration_date;
    $deal->expiration_date_time = $expiration_date_time;

    $deal->save();

    // Favicon
    $upload_favicon = (bool) request()->get('upload_favicon', false);

    if ($upload_favicon) {
      $path = public_path('favicons/');
      $favicon = 'deal-' . \App\Http\Controllers\Core\Secure::staticHash($deal->id) . '.ico';

      // First delete existing
      \File::delete($path . $favicon);

      if (request()->file('favicon', null) != null) {
        $ico_lib = new \PHP_ICO(request()->file('favicon'), [[32, 32], [64, 64]]);
        $ico_lib->save_ico($path . $favicon);
      } elseif (request()->get('upload_favicon_duplicate', null) !== null) {
        $ico = request()->get('upload_favicon_duplicate');
        \File::copy($ico, $path . $favicon);
      }
    }

    // Update PDF in cache
    DealsController::generatePdf($deal);

    return redirect('dashboard/deals')->with('message', $msg);
  }

  /**
   * Delete deal
   */
  public function postDeleteDeal(Request $request) {

    $sl = $request->input('sl', '');

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);

      $query = \App\Deal::where('user_id', auth()->user()->id)->where('id', $qs['deal_id']);
      $deal = $query->first();

      // Delete metrics
      $metrics = \DB::table('metrics')->where('type', 'App\Metrics\PageViewCountMetrics')->where('metricable_id', $qs['deal_id'])->delete();

      // Delete favicon
      $path = public_path('favicons/');
      $favicon = 'deal-' . \App\Http\Controllers\Core\Secure::staticHash($deal->id) . '.ico';

      // First delete existing
      \File::delete($path . $favicon);

      $query->delete();

      return response()->json(['success' => true, 'redir' => 'reload']);
    }
  }
}