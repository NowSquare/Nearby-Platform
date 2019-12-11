<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Core;
use Illuminate\Http\File;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;

class PagesController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Pages Controller
	|--------------------------------------------------------------------------
	|
	*/

  public function __construct() {
  }

	/**
	 * View page
	 */
	public function viewPage($page_hash) {
    $page_id = \App\Http\Controllers\Core\Secure::staticHashDecode($page_hash);
    $page = \App\Page::where('id', $page_id)->first();

    if (! empty($page)) {
      $ua = request()->header('User-Agent');
      $dd = new DeviceDetector($ua);
      $dd->setCache(new \Doctrine\Common\Cache\PhpFileCache(storage_path() . '/app/piwik/'));
      $dd->discardBotInformation();
      $dd->parse();
      $isMobile = $dd->isMobile();

      // Update metrics
      if (! $dd->isBot()) {
        $page->startMetrics(new \App\Metrics\PagesViewCountMetrics());
      }

      $print = false;

      // Description that fits one line for html tags
      $description = \App\Http\Controllers\Core\Helpers::parseDescription($page->content);

      $favicon = 'favicons/page-' . \App\Http\Controllers\Core\Secure::staticHash($page->id) . '.ico';
      $page->favicon = (\File::exists(public_path($favicon))) ? url($favicon) : null;
      $page->primaryColor = (isset($page->meta['primaryColor'])) ? $page->meta['primaryColor'] : 'green';
      $color = \App\Http\Controllers\Core\Helpers::material2hex($page->primaryColor);

      // Settings / Analytics
      $user = \App\User::where('id', $page->user_id)->first();
      $ga_code = (isset($user->settings['ga_code']) && $user->settings['ga_code'] != '') ? $user->settings['ga_code'] : '';
      $fb_pixel = (isset($user->settings['fb_pixel']) && $user->settings['fb_pixel'] != '') ? $user->settings['fb_pixel'] : '';

		  return view('app.pages.view-page', compact('page_hash', 'page', 'description', 'isMobile', 'print', 'color', 'ga_code', 'fb_pixel'));
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
	 * Pages
	 */
	public function showPages() {
    $sortby = request()->get('sortby', 'created_at');
    $order = request()->get('order', 'asc');

    if (! in_array($sortby, ['created_at', 'title'])) $sortby = 'created_at';
    if (! in_array($order, ['desc', 'asc'])) $order = 'asc';

    $pages = \App\Page::where('user_id', auth()->user()->id)->orderBy($sortby, $order)->paginate(10);

    if (count($pages) == 0) {
      return redirect('dashboard/pages/add');
    }

    $links = $pages->appends(['sortby' => $sortby, 'order' => $order])->links('vendor.pagination.bootstrap-4');

    $order = ($order == 'desc') ? 'asc' : 'desc';

		return view('app.pages.overview', compact('pages', 'sortby', 'order', 'links'));
	}

	/**
	 * New page
	 */
	public function showAddPage() {
    $count = auth()->user()->pages->count();
    $item = trans('nearby-platform.pages');

    $limit_reached = \App\Http\Controllers\Core\Helpers::getLimitReached($count, $item);

    if ($limit_reached !== false) {
      return $limit_reached;
    }

    $page = new \stdClass;
    $page->title = null;
    $page->image_file_name = null;
    $page->icon_file_name = null;
    $page->content = null;
    $page->primaryColor = 'green';

    $colors = ['red', 'pink', 'purple', 'deep-purple', 'indigo', 'blue', 'light-blue', 'cyan', 'teal', 'green', 'light-green', 'lime', 'yellow', 'amber', 'orange', 'deep-orange', 'brown', 'grey', 'blue-grey'];

		return view('app.pages.page', compact('page', 'count', 'colors'));
	}

	/**
	 * Edit page
	 */
	public function showEditPage($sl) {
    $qs = Core\Secure::string2array($sl);

    $page = \App\Page::where('user_id', auth()->user()->id)->where('id', $qs['page_id'])->first();

    $colors = ['red', 'pink', 'purple', 'deep-purple', 'indigo', 'blue', 'light-blue', 'cyan', 'teal', 'green', 'light-green', 'lime', 'yellow', 'amber', 'orange', 'deep-orange', 'brown', 'grey', 'blue-grey'];

    $page->primaryColor = (isset($page->meta['primaryColor'])) ? $page->meta['primaryColor'] : 'green';

    $favicon = 'favicons/page-' . \App\Http\Controllers\Core\Secure::staticHash($page->id) . '.ico';
    $page->favicon = (\File::exists(public_path($favicon))) ? url($favicon) : null;

    return view('app.pages.page', compact('sl', 'page', 'colors'));
	}

	/**
	 * Post new or update page
	 */
	public function postPage() {
    $input = array(
      'image' => request()->file('image'),
      'extension' => (request()->file('image') != null) ? strtolower(request()->file('image')->getClientOriginalExtension()) : null,
      'icon' => request()->file('icon'),
      'icon_extension' => (request()->file('icon') != null) ? strtolower(request()->file('icon')->getClientOriginalExtension()) : null
    );

    $rules = array(
      'file' => 'nullable|mimes:jpeg,gif,png',
      'extension'  => 'nullable|in:jpg,jpeg,png,gif',
      'icon' => 'nullable|mimes:jpeg,gif,png',
      'icon_extension'  => 'nullable|in:jpg,jpeg,png,gif'
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
      $page = \App\Page::where('user_id', auth()->user()->id)->where('id', $qs['page_id'])->first();
      $msg = trans('nearby-platform.item_saved_succesfully', ['item' => trans('nearby-platform.page')]);
    } else {
      $page = new \App\Page;
      $msg = trans('nearby-platform.item_created_succesfully', ['item' => trans('nearby-platform.page')]);
    }

    $page->user_id = auth()->user()->id;

    // Uploads
    $upload_image = (bool) request()->get('upload_image', false);
    $upload_icon = (bool) request()->get('upload_icon', false);

    if ($upload_image) {
      $page->image = request()->file('image', \Czim\Paperclip\Attachment\Attachment::NULL_ATTACHMENT);
    }

    if ($upload_icon) {
      $page->icon = request()->file('icon', \Czim\Paperclip\Attachment\Attachment::NULL_ATTACHMENT);
    }

    $page->title = request()->get('title');
    $page->content = request()->get('content');

    $page->meta = [
      'primaryColor' => request()->get('primaryColor', 'green')
    ];

    $page->save();

    if ($upload_icon) {
      $path = public_path('favicons/');
      $favicon = 'page-' . \App\Http\Controllers\Core\Secure::staticHash($page->id) . '.ico';

      // First delete existing
      \File::delete($path . $favicon);

      if (request()->file('icon', null) != null) {
        $ico_lib = new \PHP_ICO(url($page->icon->url('s')), [[32, 32], [64, 64]]);
        $ico_lib->save_ico($path . $favicon);
      }
    }

    return redirect('dashboard/pages')->with('message', $msg);
  }

  /**
   * Delete page
   */
  public function postDeletePage(Request $request) {

    $sl = $request->input('sl', '');

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);

      $query = \App\Page::where('user_id', auth()->user()->id)->where('id', $qs['page_id']);
      $page = $query->first();

      // Delete metrics
      $metrics = \DB::table('metrics')->where('type', 'App\Metrics\PagesViewCountMetrics')->where('metricable_id', $qs['page_id'])->delete();

      // Delete favicon
      $path = public_path('favicons/');
      $favicon = 'page-' . \App\Http\Controllers\Core\Secure::staticHash($page->id) . '.ico';

      // First delete existing
      \File::delete($path . $favicon);

      $query->delete();

      return response()->json(['success' => true, 'redir' => 'reload']);
    }
  }
}