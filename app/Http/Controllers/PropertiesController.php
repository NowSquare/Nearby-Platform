<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Core;
use Illuminate\Http\File;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;
use WkPdf;

class PropertiesController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Properties Controller
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
        'default' => trans('nearby-platform.view_on_map')
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
	 * View property
	 */
	public function viewProperty($property_hash) {
    $property_id = \App\Http\Controllers\Core\Secure::staticHashDecode($property_hash);
    $property = \App\Property::where('id', $property_id)->first();

    if (! empty($property)) {
      // Check if visitor is bot
      $ua = request()->header('User-Agent');
      $dd = new DeviceDetector($ua);
      $dd->setCache(new \Doctrine\Common\Cache\PhpFileCache(storage_path() . '/app/piwik/'));
      $dd->discardBotInformation();
      $dd->parse();
      $isMobile = $dd->isMobile();

      // Update metrics
      if (! $dd->isBot()) {
        $property->startMetrics(new \App\Metrics\PageViewCountMetrics());
      }

      // $print indicates whether the view has to be rendered for PDF generation
      $print = false;

      // Description that fits one line for html tags
      $description = \App\Http\Controllers\Core\Helpers::parseDescription($property->description);

      // Set property properties
      $property->favicon = $property->getFavicon();
      $property = $this->metaValues($property);
      $color = \App\Http\Controllers\Core\Helpers::material2hex($property->primaryColor);

      // Settings / Analytics
      $user = \App\User::where('id', $property->user_id)->first();
      $ga_code = (isset($user->settings['ga_code']) && $user->settings['ga_code'] != '') ? $user->settings['ga_code'] : '';
      $fb_pixel = (isset($user->settings['fb_pixel']) && $user->settings['fb_pixel'] != '') ? $user->settings['fb_pixel'] : '';

      $canonical = 'https://' . $user->reseller_host . '/property/' . $property_hash;

      $currencies = new \Money\Currencies\ISOCurrencies();
      $numberFormatter = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
      $numberFormatter->setTextAttribute($numberFormatter::CURRENCY_CODE, $property->currency_code);
      $numberFormatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 0);
      $moneyFormatter = new \Money\Formatter\IntlMoneyFormatter($numberFormatter, $currencies);

      if (is_numeric($property->price_sale)) $property->price_sale = $moneyFormatter->format(new \Money\Money($property->price_sale * 100, new \Money\Currency($property->currency_code)));
      if (is_numeric($property->price_rent)) $property->price_rent = $moneyFormatter->format(new \Money\Money($property->price_rent * 100, new \Money\Currency($property->currency_code)));

      // Set locale
      app()->setLocale($property->language);

		  return view('app.properties.view-property', compact('property_hash', 'property', 'description', 'isMobile', 'print', 'color', 'ga_code', 'fb_pixel', 'canonical'));
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
	 * Download property as PDF
	 */
	public function downloadPdf($property_hash) {
    $property_id = \App\Http\Controllers\Core\Secure::staticHashDecode($property_hash);

    $property = \App\Property::where('id', $property_id)->first();

    if (! empty($property)) {
      $path = storage_path('app/properties/pdf/');
      $fileName = str_slug($property->title) . '.pdf';

      if (! \File::exists($path . $property_id . '.pdf')) {
        PropertiesController::generatePdf($property);
      }

      return response()->download($path . $property_id . '.pdf', $fileName);
    }
	}

	/**
	 * Generate PDF in cache
	 */
	public static function generatePdf($property) {
    //ProcessPropertyPdf::dispatch($property);

    $path = storage_path('app/properties/pdf/');

    \File::makeDirectory($path, 0775, true, true);

    // Update PDF in cache
    $property_hash = \App\Http\Controllers\Core\Secure::staticHash($property->id);
    $url = url('property/' . $property_hash);

    // $print indicates whether the view has to be rendered for PDF generation
    $print = true;
    $isMobile = true;
    $ga_code = '';

    // Description that fits one line for html tags
    $description = preg_replace('/\s+/', ' ', preg_replace('/\r|\n/', ' ', $property->details));

    // Set locale
    app()->setLocale($property->language);

    $html = view('app.properties.view-property', compact('property_hash', 'property', 'description', 'isMobile', 'print', 'ga_code'))->render();

    \File::delete($path . $property->id . '.pdf');

    $snappy = app()->make('snappy.pdf');
    WkPDF::loadHTML($html)->setOrientation('portrait')->setOption('margin-bottom', 0)->setOption('page-width', 78)->setOption('page-height', 130)->save($path . $property->id . '.pdf');
  }

	/**
	 * Properties
	 */
	public function showProperties() {
    $sortby = request()->get('sortby', 'created_at');
    $order = request()->get('order', 'asc');

    if (! in_array($sortby, ['created_at', 'title'])) $sortby = 'created_at';
    if (! in_array($order, ['desc', 'asc'])) $order = 'asc';

    $properties = \App\Property::where('user_id', auth()->user()->id)->orderBy($sortby, $order)->paginate(10);

    if (count($properties) == 0) {
      return redirect('dashboard/properties/add');
    }

    $links = $properties->appends(['sortby' => $sortby, 'order' => $order])->links('vendor.pagination.bootstrap-4');

    $order = ($order == 'desc') ? 'asc' : 'desc';

		return view('app.properties.overview', compact('properties', 'sortby', 'order', 'links'));
	}

	/**
	 * New property
	 */
	public function showAddProperty() {
    $count = auth()->user()->properties->count();
    $item = trans('nearby-platform.properties');

    $limit_reached = \App\Http\Controllers\Core\Helpers::getLimitReached($count, $item);

    if ($limit_reached !== false) {
      return $limit_reached;
    }

    $property = new \stdClass;
    $property->language = auth()->user()->locale;
    $property->image_file_name = null;
    $property->favicon = null;
    $property->title = null;
    $property->description = null;
    $property->description = null;
    $property->year_of_construction = null;
    $property->price_sale = null;
    $property->price_rent = null;
    $property->for_sale = true;
    $property->for_rent = false;
    $property->currency_code = config('system.languages_default_currency.' . auth()->user()->locale);
    $property->building_type = 'house';
    $property->beds = null;
    $property->baths = null;
    $property->living_area = null;
    $property->external_storage_space = null;
    $property->plot_size = null;
    //$property->volume = null;

    $property->phone = null;
    $property->website = null;
    $property->address = null;
    $property->street = null;
    $property->street_number = null;
    $property->postal_code = null;
    $property->city = null;
    $property->state = null;
    $property->country = null;
    $property->lat = null;
    $property->lng = null;

    $existing_features = [];
    $existing_surroundings = [];

    // Set property properties
    $property = $this->metaValues($property, '');

    $colors = ['red', 'pink', 'purple', 'deep-purple', 'indigo', 'blue', 'light-blue', 'cyan', 'teal', 'green', 'light-green', 'lime', 'yellow', 'amber', 'orange', 'deep-orange', 'brown', 'grey', 'blue-grey'];

    $content_fields = $this->contentFields();

    $currencies = new \Money\Currencies\ISOCurrencies();

		return view('app.properties.property', compact('property', 'count', 'colors', 'content_fields', 'currencies', 'existing_features', 'existing_surroundings'));
	}

	/**
	 * Edit property
	 */
	public function showEditProperty($sl) {
    if (request()->has('duplicate')) {
      $count = auth()->user()->properties->count();
      $item = trans('nearby-platform.properties');

      $limit_reached = \App\Http\Controllers\Core\Helpers::getLimitReached($count, $item);

      if ($limit_reached !== false) {
        return $limit_reached;
      }
    }

    $qs = Core\Secure::string2array($sl);

    $property = \App\Property::where('user_id', auth()->user()->id)->where('id', $qs['property_id'])->first();

    $colors = ['red', 'pink', 'purple', 'deep-purple', 'indigo', 'blue', 'light-blue', 'cyan', 'teal', 'green', 'light-green', 'lime', 'yellow', 'amber', 'orange', 'deep-orange', 'brown', 'grey', 'blue-grey'];

    // Set property properties
    $property = $this->metaValues($property, '');

    $property->favicon = $property->getFavicon();

    $existing_features = $property->features()->pluck('property_feature_id')->toArray();
    $existing_surroundings = $property->surrounding()->pluck('property_surrounding_id')->toArray();

    $content_fields = $this->contentFields($property->language);

    $currencies = new \Money\Currencies\ISOCurrencies();

    return view('app.properties.property', compact('sl', 'property', 'colors', 'content_fields', 'currencies', 'existing_features', 'existing_surroundings'));
	}

	/**
	 * Post new or update property
	 */
	public function postProperty() {
    $input = array(
      'title' => request()->get('title'),
      'phone' => request()->get('phone'),
      'website' => request()->get('website'),
      'description' => request()->get('description'),
      'address' => request()->get('address'),
      'price_sale' => request()->get('price_sale'),
      'price_rent' => request()->get('price_rent'),
      'beds' => request()->get('beds'),
      'baths' => request()->get('baths'),
      'living_area' => request()->get('living_area'),
      'external_storage_space' => request()->get('external_storage_space'),
      'plot_size' => request()->get('plot_size'),
      //'volume' => request()->get('volume'),
      'image' => request()->file('image'),
      'extension' => (request()->file('image') != null) ? strtolower(request()->file('image')->getClientOriginalExtension()) : null,
      'favicon' => request()->file('favicon'),
      'favicon_extension' => (request()->file('favicon') != null) ? strtolower(request()->file('favicon')->getClientOriginalExtension()) : null
    );

    $rules = array(
      'title' => 'required|max:250',
      'phone' => 'nullable|max:24',
      'website' => 'nullable|max:250',
      'address' => 'required|max:250',
      'price_sale' => 'nullable|integer|min:0|max:100000000',
      'price_rent' => 'nullable|integer|min:0|max:10000',
      'beds' => 'nullable|integer|min:1|max:20',
      'baths' => 'nullable|regex:/^\d*(\.\d{1,2})?$/',
      'living_area' => 'nullable|integer|min:0|max:10000',
      'external_storage_space' => 'nullable|integer|min:0|max:10000',
      'plot_size' => 'nullable|integer|min:0|max:10000',
      //'volume' => 'nullable|integer|min:0|max:10000',
      'description' => 'required',
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
      $property = \App\Property::where('user_id', auth()->user()->id)->where('id', $qs['property_id'])->first();
      $msg = trans('nearby-platform.item_saved_succesfully', ['item' => trans('nearby-platform.property')]);
    } else {
      $property = new \App\Property;
      $msg = trans('nearby-platform.item_created_succesfully', ['item' => trans('nearby-platform.property')]);
    }

    $property->user_id = auth()->user()->id;

    $upload_image = (bool) request()->get('upload_image', false);

    if ($upload_image) {
      if (request()->file('image', null) == null) {
        if (request()->get('upload_image_duplicate', null) !== null) {
          $property->image = request()->get('upload_image_duplicate');
        } else {
          $property->image = \Czim\Paperclip\Attachment\Attachment::NULL_ATTACHMENT;
        }
      } else {
        $property->image = request()->file('image', \Czim\Paperclip\Attachment\Attachment::NULL_ATTACHMENT);
      }
    }

    $property->language = request()->get('language', auth()->user()->locale);
    $property->title = request()->get('title');
    $property->description = request()->get('description');
    $property->phone = request()->get('phone', null);
    $property->website = request()->get('website', null);
    $property->address = request()->get('address', null);
    $property->street = request()->get('street', null);
    $property->street_number = request()->get('street_number', null);
    $property->postal_code = request()->get('postal_code', null);
    $property->city = request()->get('city', null);
    $property->state = request()->get('state', null);
    $property->country = request()->get('country', null);
    $property->lat = request()->get('lat', null);
    $property->lng = request()->get('lng', null);

    $property->price_sale = request()->get('price_sale', null);
    $property->price_rent = request()->get('price_rent', null);
    $property->year_of_construction = request()->get('year_of_construction', null);
    $property->currency_code = request()->get('currency_code', null);
    $property->building_type = request()->get('building_type', null);
    $property->living_area = request()->get('living_area', null);
    $property->external_storage_space = request()->get('external_storage_space', null);
    $property->plot_size = request()->get('plot_size', null);
    //$property->volume = request()->get('volume', null);
    $property->beds = request()->get('beds', null);
    $property->baths = request()->get('baths', null);
    //$property->car_spaces = request()->get('car_spaces', null);

    $property->meta = $this->postMetaValues();

    if ($sl != null) {
      $property->features()->sync(request()->get('feature', null));
      $property->surrounding()->sync(request()->get('surrounding', null));
    }

    $property->save();

    if ($sl == null) {
      $property->features()->sync(request()->get('feature', null));
      $property->surrounding()->sync(request()->get('surrounding', null));
      $property->save();
    }

    // Favicon
    $upload_favicon = (bool) request()->get('upload_favicon', false);

    if ($upload_favicon) {
      $path = public_path('favicons/');
      $favicon = 'property-' . \App\Http\Controllers\Core\Secure::staticHash($property->id) . '.ico';

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
    //PropertiesController::generatePdf($property);

    return redirect('dashboard/properties')->with('message', $msg);
  }

  /**
   * Delete property
   */
  public function postDeleteProperty(Request $request) {

    $sl = $request->input('sl', '');

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);

      $query = \App\Property::where('user_id', auth()->user()->id)->where('id', $qs['property_id']);
      $property = $query->first();

      // Delete metrics
      $metrics = \DB::table('metrics')->where('type', 'App\Metrics\PageViewCountMetrics')->where('metricable_id', $qs['property_id'])->delete();

      // Delete favicon
      $path = public_path('favicons/');
      $favicon = 'property-' . \App\Http\Controllers\Core\Secure::staticHash($property->id) . '.ico';

      // First delete existing
      \File::delete($path . $favicon);

      $query->delete();

      return response()->json(['success' => true, 'redir' => 'reload']);
    }
  }
}