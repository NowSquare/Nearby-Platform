<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Core;
use Illuminate\Http\File;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;
use JeroenDesloovere\VCard\VCard;

class BusinessCardController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Cards Controller
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
      'company' => [
        'default' => '',
        'excludeDefault' => true
      ],
      'email' => [
        'default' => '',
        'excludeDefault' => true
      ],
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
        'default' => trans('nearby-platform.download_vcard')
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
	 * View card
	 */
	public function viewCard($card_hash) {
    $card_id = \App\Http\Controllers\Core\Secure::staticHashDecode($card_hash);
    $card = \App\BusinessCard::where('id', $card_id)->first();

    if (! empty($card)) {
      $ua = request()->header('User-Agent');
      $dd = new DeviceDetector($ua);
      $dd->setCache(new \Doctrine\Common\Cache\PhpFileCache(storage_path() . '/app/piwik/'));
      $dd->discardBotInformation();
      $dd->parse();
      $isMobile = $dd->isMobile();

      // Update metrics
      if (! $dd->isBot()) {
        $card->startMetrics(new \App\Metrics\CardViewCountMetrics());
      }

      $print = false;

      // Description that fits one line for html tags
      $description = \App\Http\Controllers\Core\Helpers::parseDescription($card->details);

      // Set card properties
      $card->favicon = $card->getFavicon();
      $card = $this->metaValues($card);
      $color = \App\Http\Controllers\Core\Helpers::material2hex($card->primaryColor);

      // Settings / Analytics
      $user = \App\User::where('id', $card->user_id)->first();
      $ga_code = (isset($user->settings['ga_code']) && $user->settings['ga_code'] != '') ? $user->settings['ga_code'] : '';
      $fb_pixel = (isset($user->settings['fb_pixel']) && $user->settings['fb_pixel'] != '') ? $user->settings['fb_pixel'] : '';

      // Set locale
      app()->setLocale($card->language);

		  return view('app.business-cards.view-card', compact('card_hash', 'card', 'description', 'isMobile', 'print', 'color', 'ga_code', 'fb_pixel'));
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
	 * Download card as VCard
	 */
	public function downloadVCard($card_hash) {
    $card_id = \App\Http\Controllers\Core\Secure::staticHashDecode($card_hash);

    $card = \App\BusinessCard::where('id', $card_id)->first();

    if (! empty($card)) {
      $path = storage_path('app/cards/');
      $fileName = str_slug($card->name) . '.vcf';

      if (! \File::exists($path . $card_hash . '.vcf')) {
        $this->generateVCard($card);
      }

      return response()->download($path . $card_hash . '.vcf', $fileName);
    }
	}

	/**
	 * Generate VCard in cache
	 */
	public function generateVCard($card) {
    $vcard = new VCard();

    // Set card properties (company & email from meta values)
    $card = $this->metaValues($card);

    $vcard->addName($card->name);
    $vcard->addJobtitle($card->title);
    $vcard->addCompany($card->company);
    $vcard->addEmail($card->email);
    $vcard->addPhoneNumber($card->phone, 'WORK');
    $vcard->addURL($card->website);
    $vcard->addAddress($card->address, null, $card->street . ' ' . $card->street_number, $card->city, $card->state, $card->postal_code, $card->country);

    if ($card->avatar_file_name != null) {
      $vcard->addPhoto(public_path('attachments/' . $card->avatar->path()));
    }

    $path = storage_path('app/cards/');
    \File::makeDirectory($path, 0775, true, true);

    $file = $path . \App\Http\Controllers\Core\Secure::staticHash($card->id) . '.vcf';
    $contents = $vcard->getOutput();

    \File::put($file, $contents);
  }

	/**
	 * Cards
	 */
	public function showCards() {
    $sortby = request()->get('sortby', 'created_at');
    $order = request()->get('order', 'asc');

    if (! in_array($sortby, ['created_at', 'title'])) $sortby = 'created_at';
    if (! in_array($order, ['desc', 'asc'])) $order = 'asc';

    $cards = \App\BusinessCard::where('user_id', auth()->user()->id)->orderBy($sortby, $order)->paginate(10);

    if (count($cards) == 0) {
      return redirect('dashboard/business-cards/add');
    }

    $links = $cards->appends(['sortby' => $sortby, 'order' => $order])->links('vendor.pagination.bootstrap-4');

    $order = ($order == 'desc') ? 'asc' : 'desc';

		return view('app.business-cards.overview', compact('cards', 'sortby', 'order', 'links'));
	}

	/**
	 * New card
	 */
	public function showAddCard() {
    $count = auth()->user()->businessCards->count();
    $item = 'business cards';
    $item = trans('nearby-platform.business_cards');

    $limit_reached = \App\Http\Controllers\Core\Helpers::getLimitReached($count, $item);

    if ($limit_reached !== false) {
      return $limit_reached;
    }

    $card = new \stdClass;
    $card->language = auth()->user()->locale;
    $card->title = null;
    $card->image_file_name = null;
    $card->avatar_file_name = null;
    $card->title = null;
    $card->details = null;
    $card->name = null;
    $card->title = null;
    $card->phone = null;
    $card->website = null;
    $card->company = null;
    $card->email = null;
    $card->address = null;
    $card->street = null;
    $card->street_number = null;
    $card->postal_code = null;
    $card->city = null;
    $card->state = null;
    $card->country = null;
    $card->lat = null;
    $card->lng = null;

    // Set deal properties
    $card = $this->metaValues($card, '');

    $colors = ['red', 'pink', 'purple', 'deep-purple', 'indigo', 'blue', 'light-blue', 'cyan', 'teal', 'green', 'light-green', 'lime', 'yellow', 'amber', 'orange', 'deep-orange', 'brown', 'grey', 'blue-grey'];

    $content_fields = $this->contentFields();

		return view('app.business-cards.card', compact('card', 'count', 'colors', 'content_fields'));
	}

	/**
	 * Edit card
	 */
	public function showEditCard($sl) {
    $qs = Core\Secure::string2array($sl);

    $card = \App\BusinessCard::where('user_id', auth()->user()->id)->where('id', $qs['card_id'])->first();

    $colors = ['red', 'pink', 'purple', 'deep-purple', 'indigo', 'blue', 'light-blue', 'cyan', 'teal', 'green', 'light-green', 'lime', 'yellow', 'amber', 'orange', 'deep-orange', 'brown', 'grey', 'blue-grey'];

    // Set card properties
    $card = $this->metaValues($card, '');

    $card->favicon = $card->getFavicon();

    $content_fields = $this->contentFields($card->language);

    return view('app.business-cards.card', compact('sl', 'card', 'colors', 'content_fields'));
	}

	/**
	 * Post new or update card
	 */
	public function postCard() {
    $input = array(
      'image' => request()->file('image'),
      'extension' => (request()->file('image') != null) ? strtolower(request()->file('image')->getClientOriginalExtension()) : null,
      'avatar' => request()->file('avatar'),
      'avatar_extension' => (request()->file('avatar') != null) ? strtolower(request()->file('avatar')->getClientOriginalExtension()) : null
    );

    $rules = array(
      'file' => 'nullable|mimes:jpeg,gif,png',
      'extension'  => 'nullable|in:jpg,jpeg,png,gif',
      'avatar' => 'nullable|mimes:jpeg,gif,png',
      'avatar_extension'  => 'nullable|in:jpg,jpeg,png,gif'
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
      $card = \App\BusinessCard::where('user_id', auth()->user()->id)->where('id', $qs['card_id'])->first();
      $msg = trans('nearby-platform.item_saved_succesfully', ['item' => trans('nearby-platform.business_card')]);
    } else {
      $card = new \App\BusinessCard;
      $msg = trans('nearby-platform.item_created_succesfully', ['item' => trans('nearby-platform.business_card')]);
    }

    $card->user_id = auth()->user()->id;

    // Uploads
    $upload_image = (bool) request()->get('upload_image', false);
    $upload_avatar = (bool) request()->get('upload_avatar', false);

    if ($upload_image) {
      $card->image = request()->file('image', \Czim\Paperclip\Attachment\Attachment::NULL_ATTACHMENT);
    }

    if ($upload_avatar) {
      $card->avatar = request()->file('avatar', \Czim\Paperclip\Attachment\Attachment::NULL_ATTACHMENT);
    }

    $card->language = request()->get('language', auth()->user()->locale);
    $card->title = request()->get('title');
    $card->details = request()->get('details');
    $card->name = request()->get('name');
    $card->title = request()->get('title');
    $card->phone = request()->get('phone', null);
    $card->website = request()->get('website', null);
    $card->address = request()->get('address', null);
    $card->street = request()->get('street', null);
    $card->street_number = request()->get('street_number', null);
    $card->postal_code = request()->get('postal_code', null);
    $card->city = request()->get('city', null);
    $card->state = request()->get('state', null);
    $card->country = request()->get('country', null);
    $card->lat = request()->get('lat', null);
    $card->lng = request()->get('lng', null);

    $card->meta = $this->postMetaValues();

    $card->save();

    if ($upload_avatar) {
      $path = public_path('favicons/');
      $favicon = 'card-' . \App\Http\Controllers\Core\Secure::staticHash($card->id) . '.ico';

      // First delete existing
      \File::delete($path . $favicon);

      if ($card->avatar_file_name != null) {
        $ico_lib = new \PHP_ICO(url($card->avatar->url('s')), [[32, 32], [64, 64]]);
        $ico_lib->save_ico($path . $favicon);
      }
    }

    // Update VCard in cache
    BusinessCardController::generateVCard($card);

    return redirect('dashboard/business-cards')->with('message', $msg);
  }

  /**
   * Delete card
   */
  public function postDeleteCard(Request $request) {

    $sl = $request->input('sl', '');

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);

      $query = \App\BusinessCard::where('user_id', auth()->user()->id)->where('id', $qs['card_id']);
      $card = $query->first();

      // Delete metrics
      $metrics = \DB::table('metrics')->where('type', 'App\Metrics\CardViewCountMetrics')->where('metricable_id', $qs['card_id'])->delete();

      // Delete favicon
      $path = public_path('favicons/');
      $favicon = 'card-' . \App\Http\Controllers\Core\Secure::staticHash($card->id) . '.ico';

      // First delete existing
      \File::delete($path . $favicon);

      $query->delete();

      return response()->json(['success' => true, 'redir' => 'reload']);
    }
  }
}