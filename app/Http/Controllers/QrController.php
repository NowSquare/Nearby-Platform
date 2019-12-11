<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Core;
use Illuminate\Http\File;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;
use Embed\Embed;

class QrController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| QR Code Controller
	|--------------------------------------------------------------------------
	|
	*/

  public function __construct() {
  }

	/**
	 * View qr_code
	 */
	public function viewQrCode($qr_code_hash) {
    $qr_code_id = \App\Http\Controllers\Core\Secure::staticHashDecode($qr_code_hash);
    $qr_code = \App\Qr::where('id', $qr_code_id)->first();

    if (! empty($qr_code)) {
      $ua = request()->header('User-Agent');
      $dd = new DeviceDetector($ua);
      $dd->setCache(new \Doctrine\Common\Cache\PhpFileCache(storage_path() . '/app/piwik/'));
      $dd->discardBotInformation();
      $dd->parse();
      $isMobile = $dd->isMobile();

      // Update metrics
      if (! $dd->isBot()) {
        $qr_code->startMetrics(new \App\Metrics\QrViewCountMetrics());
      }

      return redirect()->to($qr_code->redirect_to_url, 301);
    }
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
	 * Qr overview
	 */
	public function showQrCodes() {
    $sortby = request()->get('sortby', 'updated_at');
    $order = request()->get('order', 'desc');

    if (! in_array($sortby, ['updated_at', 'created_at', 'title'])) $sortby = 'updated_at';
    if (! in_array($order, ['desc', 'asc'])) $order = 'asc';

    $qr_codes = \App\Qr::where('user_id', auth()->user()->id)->orderBy($sortby, $order)->paginate(6);

    if (count($qr_codes) == 0) {
      return redirect('dashboard/qr/add');
    }

    $links = $qr_codes->appends(['sortby' => $sortby, 'order' => $order])->links('vendor.pagination.bootstrap-4');

    $order = ($order == 'desc') ? 'asc' : 'desc';

		return view('app.qr.overview', compact('qr_codes', 'sortby', 'order', 'links'));
	}

	/**
	 * New qr_code
	 */
	public function showAddQrCode() {
    $count = auth()->user()->qr_codes->count();
    $item = trans('nearby-platform.qr_codes');

    $limit_reached = \App\Http\Controllers\Core\Helpers::getLimitReached($count, $item);

    if ($limit_reached !== false) {
      return $limit_reached;
    }

    $qr_code = new \stdClass;
    $qr_code->redirect_to_url = null;

    $deals = \App\Deal::where('user_id', auth()->user()->id)->orderBy('title', 'asc')->get();
    $coupons = \App\Coupon::where('user_id', auth()->user()->id)->orderBy('title', 'asc')->get();
    $rewards = \App\Reward::where('user_id', auth()->user()->id)->orderBy('title', 'asc')->get();
    $properties = \App\Property::where('user_id', auth()->user()->id)->orderBy('title', 'asc')->get();
    $cards = \App\BusinessCard::where('user_id', auth()->user()->id)->orderBy('name', 'asc')->get();
    $videos = \App\Video::where('user_id', auth()->user()->id)->orderBy('title', 'asc')->get();
    $pages = \App\Page::where('user_id', auth()->user()->id)->orderBy('title', 'asc')->get();

		return view('app.qr.qr', compact('qr_code', 'count', 'deals', 'coupons', 'rewards', 'properties', 'cards', 'videos', 'pages'));
	}

	/**
	 * Edit qr_code
	 */
	public function showEditQrCode($sl) {
    $qs = Core\Secure::string2array($sl);

    $qr_code = \App\Qr::where('user_id', auth()->user()->id)->where('id', $qs['qr_code_id'])->first();

    $deals = \App\Deal::where('user_id', auth()->user()->id)->orderBy('title', 'asc')->get();
    $coupons = \App\Coupon::where('user_id', auth()->user()->id)->orderBy('title', 'asc')->get();
    $rewards = \App\Reward::where('user_id', auth()->user()->id)->orderBy('title', 'asc')->get();
    $properties = \App\Property::where('user_id', auth()->user()->id)->orderBy('title', 'asc')->get();
    $cards = \App\BusinessCard::where('user_id', auth()->user()->id)->orderBy('name', 'asc')->get();
    $videos = \App\Video::where('user_id', auth()->user()->id)->orderBy('title', 'asc')->get();
    $pages = \App\Page::where('user_id', auth()->user()->id)->orderBy('title', 'asc')->get();

    return view('app.qr.qr', compact('sl', 'qr_code', 'deals', 'coupons', 'rewards', 'properties', 'cards', 'videos', 'pages'));
	}

	/**
	 * Post new or update qr_code
	 */
	public function postQrCode() {
    $input = array(
      'redirect_to_url' => request()->get('redirect_to_url')
    );

    $rules = array(
      'redirect_to_url' => 'required|url'
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
      $qr_code = \App\Qr::where('user_id', auth()->user()->id)->where('id', $qs['qr_code_id'])->first();
      $msg = trans('nearby-platform.item_saved_succesfully', ['item' => trans('nearby-platform.qr_code')]);
    } else {
      $qr_code = new \App\Qr;
      $msg = trans('nearby-platform.item_created_succesfully', ['item' => trans('nearby-platform.qr_code')]);
    }

    // Get url info
    $error = '';
    $url = request()->get('redirect_to_url', null);

    try {
      $info = Embed::create($url);

      $qr_code->user_id = auth()->user()->id;
      $qr_code->redirect_to_url = $url;
      $qr_code->title = $info->title;
      $qr_code->description = $info->description;
      $image = ($info->image !== null) ? $info->image : \Czim\Paperclip\Attachment\Attachment::NULL_ATTACHMENT;
      $qr_code->image = $image;
      $qr_code->icon = $info->providerIcon;

      $qr_code->save();

      if ($sl == null) {
        $qr_code->qr = new \SplFileInfo(public_path(\DNS2D::getBarcodePNGPath($qr_code->getUrl(), 'QRCODE', 70, 70, [0,0,0])));
        $qr_code->save();
      }

      return redirect('dashboard/qr')->with('message', $msg);

    } catch (\ErrorException $e) {
      $validator->getMessageBag()->add('redirect_to_url', $e->getMessage());
    } catch (\Embed\Exceptions\InvalidUrlException $e) {
      $validator->getMessageBag()->add('redirect_to_url', $e->getMessage());
    }

    return redirect()
      ->back()
      ->withErrors($validator)
      ->withInput();
  }

  /**
   * Delete qr_code
   */
  public function postDeleteQrCode(Request $request) {

    $sl = $request->input('sl', '');

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);

      $query = \App\Qr::where('user_id', auth()->user()->id)->where('id', $qs['qr_code_id']);
      $qr_code = $query->first();

      // Delete metrics
      $metrics = \DB::table('metrics')->where('type', 'App\Metrics\QrViewCountMetrics')->where('metricable_id', $qs['qr_code_id'])->delete();

      $query->delete();

      return response()->json(['success' => true, 'redir' => 'reload']);
    }
  }

  /**
   * Download qr_code
   */
  public function getDownloadQrCode($sl) {

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);

      $qr_code = \App\Qr::where('user_id', auth()->user()->id)->where('id', $qs['qr_code_id'])->first();
      if (! empty($qr_code)) {
        return response()->download(public_path('attachments/' . $qr_code->qr->path()), 'qr-' . str_slug($qr_code->title) . '.png');
      }

      return redirect('dashboard/qr');
    }
  }
}