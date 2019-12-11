<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Core;
use Illuminate\Http\File;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;
use Embed\Embed;

class VideosController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Videos Controller
	|--------------------------------------------------------------------------
	|
	*/

  public function __construct() {
  }

	/**
	 * View video
	 */
	public function viewVideo($video_hash) {
    $video_id = \App\Http\Controllers\Core\Secure::staticHashDecode($video_hash);
    $video = \App\Video::where('id', $video_id)->first();

    if (! empty($video)) {
      $ua = request()->header('User-Agent');
      $dd = new DeviceDetector($ua);
      $dd->setCache(new \Doctrine\Common\Cache\PhpFileCache(storage_path() . '/app/piwik/'));
      $dd->discardBotInformation();
      $dd->parse();
      $isMobile = $dd->isMobile();

      // Update metrics
      if (! $dd->isBot()) {
        $video->startMetrics(new \App\Metrics\VideoViewCountMetrics());
      }

      $print = false;

      // Description that fits one line for html tags
      $description = \App\Http\Controllers\Core\Helpers::parseDescription($video->video_description);

      $favicon = 'favicons/video-' . \App\Http\Controllers\Core\Secure::staticHash($video->id) . '.ico';
      $video->favicon = (\File::exists(public_path($favicon))) ? url($favicon) : null;
      $video->primaryColor = (isset($video->meta['primaryColor'])) ? $video->meta['primaryColor'] : 'green';
      $color = \App\Http\Controllers\Core\Helpers::material2hex($video->primaryColor);

      // Settings / Analytics
      $user = \App\User::where('id', $video->user_id)->first();
      $ga_code = (isset($user->settings['ga_code']) && $user->settings['ga_code'] != '') ? $user->settings['ga_code'] : '';
      $fb_pixel = (isset($user->settings['fb_pixel']) && $user->settings['fb_pixel'] != '') ? $user->settings['fb_pixel'] : '';

		  return view('app.videos.view-video', compact('video_hash', 'video', 'description', 'isMobile', 'print', 'color', 'ga_code', 'fb_pixel'));
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
	 * Videos
	 */
	public function showVideos() {
    $sortby = request()->get('sortby', 'created_at');
    $order = request()->get('order', 'asc');

    if (! in_array($sortby, ['created_at', 'title'])) $sortby = 'created_at';
    if (! in_array($order, ['desc', 'asc'])) $order = 'asc';

    $videos = \App\Video::where('user_id', auth()->user()->id)->orderBy($sortby, $order)->paginate(10);

    if (count($videos) == 0) {
      return redirect('dashboard/videos/add');
    }

    $links = $videos->appends(['sortby' => $sortby, 'order' => $order])->links('vendor.pagination.bootstrap-4');

    $order = ($order == 'desc') ? 'asc' : 'desc';

		return view('app.videos.overview', compact('videos', 'sortby', 'order', 'links'));
	}

	/**
	 * New video
	 */
	public function showAddVideo() {
    $count = auth()->user()->videos->count();
    $item = trans('nearby-platform.videos');

    $limit_reached = \App\Http\Controllers\Core\Helpers::getLimitReached($count, $item);

    if ($limit_reached !== false) {
      return $limit_reached;
    }

    $video = new \stdClass;
    $video->title = null;
    $video->content = null;
    $video->image_file_name = null;
    $video->icon_file_name = null;
    $video->video_url = null;
    $video->video_title = null;
    $video->video_description = null;
    $video->embed_url = null;
    $video->embed_aspect_ratio = null;
    $video->remote_image = null;
    $video->primaryColor = 'green';

    $colors = ['red', 'pink', 'purple', 'deep-purple', 'indigo', 'blue', 'light-blue', 'cyan', 'teal', 'green', 'light-green', 'lime', 'yellow', 'amber', 'orange', 'deep-orange', 'brown', 'grey', 'blue-grey'];

		return view('app.videos.video', compact('video', 'count', 'colors'));
	}

	/**
	 * Edit video
	 */
	public function showEditVideo($sl) {
    $qs = Core\Secure::string2array($sl);

    $video = \App\Video::where('user_id', auth()->user()->id)->where('id', $qs['video_id'])->first();

    $colors = ['red', 'pink', 'purple', 'deep-purple', 'indigo', 'blue', 'light-blue', 'cyan', 'teal', 'green', 'light-green', 'lime', 'yellow', 'amber', 'orange', 'deep-orange', 'brown', 'grey', 'blue-grey'];

    $video->primaryColor = (isset($video->meta['primaryColor'])) ? $video->meta['primaryColor'] : 'green';

    $favicon = 'favicons/video-' . \App\Http\Controllers\Core\Secure::staticHash($video->id) . '.ico';
    $video->favicon = (\File::exists(public_path($favicon))) ? url($favicon) : null;

    return view('app.videos.video', compact('sl', 'video', 'colors'));
	}
  
	/**
	 * Verify embedable url
	 */
	public function postVerifyUrl() {
    $valid = false;
    $msg = '';
    $url = '';
    $title = '';
    $desc = '';

    $aspectRatio = '';
    $image = '';
    $url = request()->get('video_url', null);

    try {
      $valid = true;
      $info = Embed::create($url);
      $code = $info->code;
      $aspectRatio = $info->aspectRatio;
      $image = $info->image;
      $title = $info->title;
      $desc = $info->description;

      preg_match('/src="([^"]+)"/', $code, $match);
      $url = $match[1];
    } catch (\ErrorException $e) {
      $valid = false;
      $msg = $e->getMessage();
    } catch (\Embed\Exceptions\InvalidUrlException $e) {
      $valid = false;
      $msg = $e->getMessage();
    }

    return response()->json(['valid' => $valid, 'msg' => $msg, 'url' => $url, 'aspectRatio' => $aspectRatio, 'image' => $image, 'title' => $title, 'desc' => $desc]);
  }

	/**
	 * Post new or update video
	 */
	public function postVideo() {
    $input = array(
      'icon' => request()->file('icon'),
      'icon_extension' => (request()->file('icon') != null) ? strtolower(request()->file('icon')->getClientOriginalExtension()) : null
    );

    $rules = array(
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
      $video = \App\Video::where('user_id', auth()->user()->id)->where('id', $qs['video_id'])->first();
      $msg = trans('nearby-platform.item_saved_succesfully', ['item' => trans('nearby-platform.video')]);
    } else {
      $video = new \App\Video;
      $msg = trans('nearby-platform.item_created_succesfully', ['item' => trans('nearby-platform.video')]);
    }

    $video->user_id = auth()->user()->id;

    // Uploads
    $upload_icon = (bool) request()->get('upload_icon', false);

    if ($upload_icon) {
      $video->icon = request()->file('icon', \Czim\Paperclip\Attachment\Attachment::NULL_ATTACHMENT);
    } else {
      //$video->icon = request()->get('remote_image', \Czim\Paperclip\Attachment\Attachment::NULL_ATTACHMENT);
    }

    $video->title = request()->get('title');
    $video->content = request()->get('content', null);
    $video->video_url = request()->get('video_url');
    $video->embed_url = request()->get('embed_url');
    $video->embed_aspect_ratio = request()->get('embed_aspect_ratio');
    $video->video_title = request()->get('video_title');
    $video->video_description = request()->get('video_description');
    $video->remote_image = request()->get('remote_image');
    $video->image = request()->get('remote_image', \Czim\Paperclip\Attachment\Attachment::NULL_ATTACHMENT);

    $video->meta = [
      'primaryColor' => request()->get('primaryColor', 'green')
    ];

    $video->save();

    if ($upload_icon) {
      $path = public_path('favicons/');
      $favicon = 'video-' . \App\Http\Controllers\Core\Secure::staticHash($video->id) . '.ico';

      // First delete existing
      \File::delete($path . $favicon);

      if (request()->file('icon', null) != null) {
        $ico_lib = new \PHP_ICO(url($video->icon->url('s')), [[32, 32], [64, 64]]);
        $ico_lib->save_ico($path . $favicon);
      }
    }

    return redirect('dashboard/videos')->with('message', $msg);
  }

  /**
   * Delete video
   */
  public function postDeleteVideo(Request $request) {

    $sl = $request->input('sl', '');

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);

      $query = \App\Video::where('user_id', auth()->user()->id)->where('id', $qs['video_id']);
      $video = $query->first();

      // Delete metrics
      $metrics = \DB::table('metrics')->where('type', 'App\Metrics\VideoViewCountMetrics')->where('metricable_id', $qs['video_id'])->delete();

      // Delete favicon
      $path = public_path('favicons/');
      $favicon = 'video-' . \App\Http\Controllers\Core\Secure::staticHash($video->id) . '.ico';

      // First delete existing
      \File::delete($path . $favicon);

      $query->delete();

      return response()->json(['success' => true, 'redir' => 'reload']);
    }
  }
}