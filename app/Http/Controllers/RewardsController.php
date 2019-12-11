<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Core;
use Illuminate\Http\File;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;

class RewardsController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Rewards Controller
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
        'default' => trans('nearby-platform.check_in')
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
      'visitTxt' => [
        'type' => 'text',
        'label' => trans('nearby-platform.visit'),
        'default' => trans('nearby-platform.visit')
      ],
      'checkInText' => [
        'type' => 'textarea',
        'label' => trans('nearby-platform.check_in_text'),
        'default' => trans('nearby-platform.check_in_text_default')
      ],
      'checkedInText' => [
        'type' => 'textarea',
        'label' => trans('nearby-platform.succesfully_checked_in_text'),
        'default' => trans('nearby-platform.succesfully_checked_in_text_default')
      ],
      'redeemText' => [
        'type' => 'textarea',
        'label' => trans('nearby-platform.redeem_text'),
        'default' => trans('nearby-platform.redeem_reward_text_default')
      ],
      'redeemBtnText' => [
        'type' => 'text',
        'label' => trans('nearby-platform.redeem_button_text'),
        'default' => trans('nearby-platform.redeem_button_text_default')
      ],
      'redeemedText' => [
        'type' => 'textarea',
        'label' => trans('nearby-platform.succesfully_redeemed_text'),
        'default' => trans('nearby-platform.succesfully_redeemed_reward_text_default')
      ]
    ];

    if ($locale !== null) {
      app()->setLocale($old_locale);
    }

    return $content_fields;
  }

	/**
	 * View reward
	 */
	public function viewReward($reward_hash) {
    $reward_id = \App\Http\Controllers\Core\Secure::staticHashDecode($reward_hash);
    $reward = \App\Reward::where('id', $reward_id)->first();

    if (! empty($reward)) {
      $ua = request()->header('User-Agent');
      $dd = new DeviceDetector($ua);
      $dd->setCache(new \Doctrine\Common\Cache\PhpFileCache(storage_path() . '/app/piwik/'));
      $dd->discardBotInformation();
      $dd->parse();

      // Update metrics
      if (! $dd->isBot()) {
        $reward->startMetrics(new \App\Metrics\RewardViewCountMetrics());
      }

      // Description that fits one line for html tags
      $description = \App\Http\Controllers\Core\Helpers::parseDescription($reward->details);

      // Set reward properties
      $reward->favicon = $reward->getFavicon();
      $reward = $this->metaValues($reward);
      $color = \App\Http\Controllers\Core\Helpers::material2hex($reward->primaryColor);

      // Get cookies
      $redeemed = (bool) \Cookie::get('r_' . $reward_hash);
      $check_ins = \Cookie::get('rs_' . $reward_hash);

      // Check if all steps are checked in
      $hasToWaitToCheckIn = false;
      $timeToWaitForCheckIn = 0;
      $last_check_in = 0;

      // Check for check ins
      $steps = [];
      for ($step = 0; $step < $reward->steps; $step++) {
        if (isset($check_ins[$step])) {
          $check_in = $check_ins[$step];
          if (! is_numeric($check_in)) $check_in = substr($check_in, 2, strlen($check_in) - 3);
          //if (1420030799 < $check_in && $check_in <= 1451566799) {
            $steps[$step] = [
              'step' => $step + 1,
              'checked' => true,
              'time' => '<div class="day">' . \Carbon\Carbon::createFromTimestamp($check_in)->formatLocalized('%B %d') . ' </div> <div class="time">' . \Carbon\Carbon::createFromTimestamp($check_in)->tz($reward->timezone)->formatLocalized('%I:%M %p') . '</div>'
            ];
          //}
        } else {
          $steps[$step] = [
            'step' => $step + 1,
            'checked' => false,
            'time' => ''
          ];
        }
      }

      // Check if reward has been redeemed, or whether user has to wait for check in
      $readyToRedeem = false;

      if ($check_ins != null) {
        if ($reward->steps <= count($check_ins) + 1) $readyToRedeem = true;
        $last_check_in = $check_ins[count($check_ins) - 1];
        if (! is_numeric($last_check_in)) $last_check_in = substr($last_check_in, 2, strlen($last_check_in) - 3);

        $diffInMinutes = \Carbon\Carbon::createFromTimestamp($last_check_in)->diffInMinutes();
        if ($reward->interval >= $diffInMinutes) {
          $hasToWaitToCheckIn = true;
          $diffInSeconds = ($reward->interval * 60) - ($diffInMinutes * 60);
          $dtF = new \DateTime('@0');
          $dtT = new \DateTime("@$diffInSeconds");
          $daysToWait = $dtF->diff($dtT)->format('%a');
          $hoursToWait = $dtF->diff($dtT)->format('%h');
          $minutesToWait = $dtF->diff($dtT)->format('%i');
          if ($daysToWait == 0 && $hoursToWait == 0) {
            $minutes = ($minutesToWait == 1) ? 'minute' : 'minutes';
            $timeToWaitForCheckIn = $minutesToWait . ' ' . $minutes;
          } elseif ($daysToWait == 0) {
            $minutes = ($minutesToWait == 1) ? 'minute' : 'minutes';
            $hours = ($hoursToWait == 1) ? 'hour' : 'hours';
            $timeToWaitForCheckIn = $hoursToWait . ' ' . $hours . ' and ' . $minutesToWait . ' ' . $minutes;
          } else {
            $minutes = ($minutesToWait == 1) ? 'minute' : 'minutes';
            $hours = ($hoursToWait == 1) ? 'hour' : 'hours';
            $days = ($daysToWait == 1) ? 'day' : 'days';
            $timeToWaitForCheckIn = $daysToWait . ' ' . $days . ', ' . $hoursToWait . ' ' . $hours . ' and ' . $minutesToWait . ' ' . $minutes . '';
          }
        }
      }

      // QR data
      $pusher_channel = 'reward_' . uniqid();
      $data['l'] = $last_check_in;
      $data['pc'] = $pusher_channel;
      $qs = http_build_query($data);

      $check_in_url = url('reward/check-in/' . $reward_hash . '/?' . $qs);

      // Settings / Analytics
      $user = \App\User::where('id', $reward->user_id)->first();
      $ga_code = (isset($user->settings['ga_code']) && $user->settings['ga_code'] != '') ? $user->settings['ga_code'] : '';
      $fb_pixel = (isset($user->settings['fb_pixel']) && $user->settings['fb_pixel'] != '') ? $user->settings['fb_pixel'] : '';

      // Set locale
      app()->setLocale($reward->language);

		  return view('app.rewards.view-reward', compact('reward_hash', 'reward', 'readyToRedeem', 'redeemed', 'steps', 'check_ins', 'hasToWaitToCheckIn', 'timeToWaitForCheckIn', 'check_in_url', 'pusher_channel', 'description', 'color', 'ga_code', 'fb_pixel'));
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
	 * Redeem reward QR
	 */
	public function postRedeemReward($reward_hash) {
    $reward_id = \App\Http\Controllers\Core\Secure::staticHashDecode($reward_hash);
    $reward = \App\Reward::where('id', $reward_id)->first();

    if (! empty($reward)) {
      // Set reward properties
      $reward->favicon = $reward->getFavicon();
      $reward = $this->metaValues($reward);
      $color = \App\Http\Controllers\Core\Helpers::material2hex($reward->primaryColor);

      // QR data
      $pusher_channel = 'reward_' . uniqid();
      $post = request()->all();
      $post['pc'] = $pusher_channel;
      $post = http_build_query($post);

      $redeem_url = url('reward/verify/' . $reward_hash . '/?' . $post);

      // Set locale
      app()->setLocale($reward->language);

		  return view('app.rewards.redeem-reward', compact('reward_hash', 'reward', 'pusher_channel', 'redeem_url', 'color'));
    }
	}

	/**
	 * Verify redeemed reward
	 */
	public function verifyReward($reward_hash) {
    $reward_id = \App\Http\Controllers\Core\Secure::staticHashDecode($reward_hash);
    $reward = \App\Reward::where('id', $reward_id)->first();

    if (! empty($reward)) {
      // Set reward properties
      $reward->favicon = $reward->getFavicon();
      $reward = $this->metaValues($reward);
      $color = \App\Http\Controllers\Core\Helpers::material2hex($reward->primaryColor);

      // QR data
      $post = request()->all();
      $post = http_build_query($post);

      $redeem_url = url('reward/verify/' . $reward_hash . '/?' . $post);

      // Set locale
      app()->setLocale($reward->language);

		  return view('app.rewards.verify-reward', compact('reward_hash', 'reward', 'redeem_url', 'color'));
    }
	}

	/**
	 * Post reward redeemed
	 */
	public function postVerifyReward($reward_hash) {
    $reward_id = \App\Http\Controllers\Core\Secure::staticHashDecode($reward_hash);
    $reward = \App\Reward::where('id', $reward_id)->first();

    if (! empty($reward)) {

      $input = array(
        'redeem_code' => request()->get('redeem_code')
      );

      $rules = array(
        'redeem_code' => 'required'
      );

      $validator = \Validator::make($input, $rules);

      /*
      $validator->after(function ($validator) use($input, $reward) {
        if ($input['redeem_code'] != $reward->redeem_code) {
              $validator->errors()->add('redeem_code', 'Redeem code is not correct.');
          }
      });
      */
      if ($input['redeem_code'] != $reward->redeem_code) {
        $validator->getMessageBag()->add('redeem_code', 'Redeem code is not correct.');
      }

      if(! empty($validator->errors()->all())) {
        return redirect()
          ->back()
          ->withErrors($validator)
          ->withInput();
      }

      // Update reward before setting non-sql columns
      $reward->number_of_times_redeemed = $reward->number_of_times_redeemed + 1;
      $reward->last_redemption = \Carbon\Carbon::now('UTC');
      $reward->save();

      // Set coupon properties
      $reward->favicon = $reward->getFavicon();
      $reward = $this->metaValues($reward);
      $color = \App\Http\Controllers\Core\Helpers::material2hex($reward->primaryColor);

      // Add lead
      $pusher_channel = request()->get('pc');
      $post = request()->except(['_token', 'redeem_code', 'pc']);

      $lead = new \App\RewardLead;

      $lead->user_id = $reward->user_id;
      $lead->reward_id = $reward->id;
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
      app()->setLocale($reward->language);

		  return view('app.rewards.verified', compact('reward_hash', 'reward', 'color'));
    }
	}

	/**
	 * Show reward redeemed
	 */
	public function rewardRedeemed($reward_hash) {
    $reward_id = \App\Http\Controllers\Core\Secure::staticHashDecode($reward_hash);
    $reward = \App\Reward::where('id', $reward_id)->first();

    if (! empty($reward)) {
      // Set reward properties
      $reward->favicon = $reward->getFavicon();
      $reward = $this->metaValues($reward);
      $color = \App\Http\Controllers\Core\Helpers::material2hex($reward->primaryColor);

      // Set cookies
      $check_ins = \Cookie::get('rs_' . $reward_hash);
      $count = ($check_ins === null) ? 0 : count($check_ins);
      \Cookie::queue('rs_' . $reward_hash . '[' . $count . ']', time(), 60 * 24 * 365);
      \Cookie::queue('r_' . $reward_hash, 1, 60 * 24 * 365);

      // Set locale
      app()->setLocale($reward->language);

		  return view('app.rewards.redeemed', compact('reward_hash', 'reward', 'color'));
    }
	}

	/**
	 * Verify check in
	 */
	public function checkInReward($reward_hash) {

    $reward_id = \App\Http\Controllers\Core\Secure::staticHashDecode($reward_hash);
    $reward = \App\Reward::where('id', $reward_id)->first();

    if (! empty($reward)) {
      // Set reward properties
      $reward->favicon = $reward->getFavicon();
      $reward = $this->metaValues($reward);
      $color = \App\Http\Controllers\Core\Helpers::material2hex($reward->primaryColor);

      // QR data
      $post = request()->all();
      $post = http_build_query($post);

      $redeem_url = url('reward/verify/' . $reward_hash . '/?' . $post);

      // Set locale
      app()->setLocale($reward->language);

		  return view('app.rewards.check-in-reward', compact('reward_hash', 'reward', 'redeem_url', 'color'));
    }
	}

	/**
	 * Post check in reward verification
	 */
	public function postCheckInReward($reward_hash) {
    $reward_id = \App\Http\Controllers\Core\Secure::staticHashDecode($reward_hash);
    $reward = \App\Reward::where('id', $reward_id)->first();

    if (! empty($reward)) {

      $input = array(
        'redeem_code' => request()->get('redeem_code')
      );

      $rules = array(
        'redeem_code' => 'required'
      );

      $validator = \Validator::make($input, $rules);

      if ($input['redeem_code'] != $reward->redeem_code) {
        $validator->getMessageBag()->add('redeem_code', 'Redeem code is not correct.');
      }

      // Check if reward can be checked in
      $check_ins = \Cookie::get('rs_' . $reward_hash);

      if ($check_ins != null) {
        $last_check_in = $check_ins[count($check_ins) - 1];
        $diffInMinutes = \Carbon\Carbon::createFromTimestamp($last_check_in)->diffInMinutes();
        if ($reward->interval >= $diffInMinutes) {
          $diffInSeconds = ($reward->interval * 60) - ($diffInMinutes * 60);
          $dtF = new \DateTime('@0');
          $dtT = new \DateTime("@$diffInSeconds");
          $timeToWaitForCheckIn = $dtF->diff($dtT)->format('%a days, %h hours and %i minutes');

          $validator->getMessageBag()->add('redeem_code', 'Check in has to wait for ' . $timeToWaitForCheckIn . '.');
        }
      }

      if(! empty($validator->errors()->all())) {
        return redirect()
          ->back()
          ->withErrors($validator)
          ->withInput();
      }

      // Update reward before setting non-sql columns
      $reward->number_of_times_checked_in = $reward->number_of_times_checked_in + 1;
      $reward->last_check_in = \Carbon\Carbon::now('UTC');
      $reward->save();

      // Set reward properties
      $reward->favicon = $reward->getFavicon();
      $reward = $this->metaValues($reward);
      $color = \App\Http\Controllers\Core\Helpers::material2hex($reward->primaryColor);

      // Push check in
      $pusher_channel = request()->get('pc');

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
      $pusher->trigger($pusher_channel, 'checkedIn', $data);

      // Set locale
      app()->setLocale($reward->language);

		  return view('app.rewards.checked-in', compact('reward_hash', 'reward', 'color'));
    }
	}

	/**
	 * Reward checked in user view
	 */
	public function checkedInReward($reward_hash) {
    $reward_id = \App\Http\Controllers\Core\Secure::staticHashDecode($reward_hash);
    $reward = \App\Reward::where('id', $reward_id)->first();

    if (! empty($reward)) {

      // Set cookie
      $check_ins = \Cookie::get('rs_' . $reward_hash);
      $count = ($check_ins === null) ? 0 : count($check_ins);
      \Cookie::queue('rs_' . $reward_hash . '[' . $count . ']', time(), 60 * 24 * 365);

      $reward->favicon = $reward->getFavicon();
      $reward->primaryColor = (isset($reward->meta['primaryColor'])) ? $reward->meta['primaryColor'] : 'green';
      $color = \App\Http\Controllers\Core\Helpers::material2hex($reward->primaryColor);

      // Set locale
      app()->setLocale($reward->language);

		  return view('app.rewards.checked-in', compact('reward_hash', 'reward', 'color'));

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
	 * Rewards
	 */
	public function showRewards() {
    $sortby = request()->get('sortby', 'created_at');
    $order = request()->get('order', 'asc');

    if (! in_array($sortby, ['created_at', 'title'])) $sortby = 'created_at';
    if (! in_array($order, ['desc', 'asc'])) $order = 'asc';

    $rewards = \App\Reward::where('user_id', auth()->user()->id)->orderBy($sortby, $order)->paginate(10);

    if (count($rewards) == 0) {
      return redirect('dashboard/rewards/add');
    }

    $links = $rewards->appends(['sortby' => $sortby, 'order' => $order])->links('vendor.pagination.bootstrap-4');

    $order = ($order == 'desc') ? 'asc' : 'desc';

		return view('app.rewards.overview', compact('rewards', 'sortby', 'order', 'links'));
	}

	/**
	 * Leads
	 */
	public function showLeads() {
    $allowed_sort = ['created_at'];
    $fields = \App\Http\Controllers\RewardsController::requiredFieldList();
    foreach ($fields as $field) { $allowed_sort[] = $field['id']; }

    $filterRewards = request()->get('filterRewards', []);
    $qsFilterRewards['filterRewards'] = [];
    $sortby = request()->get('sortby', 'created_at');
    $order = request()->get('order', 'asc');

    if (! in_array($sortby, $allowed_sort)) $sortby = 'created_at';
    if (! in_array($order, ['desc', 'asc'])) $order = 'asc';

    $rewards = \App\Reward::where('user_id', auth()->user()->id)->orderBy('title', 'asc')->get();

    if (empty($filterRewards)) {
      foreach ($rewards as $reward) {
        $filterRewards[] = $reward->id;
        $qsFilterRewards['filterRewards'][] = $reward->id;
      }
    } else {
      foreach ($filterRewards as $reward) { 
        $qsFilterRewards['filterRewards'][] = $reward;
      }
    }

    $leads = \App\RewardLead::where('user_id', auth()->user()->id)->whereIn('reward_id', $filterRewards)->orderBy($sortby, $order)->paginate(10);

    $links = $leads->appends(['sortby' => $sortby, 'order' => $order, 'filterRewards' => $filterRewards])->links('vendor.pagination.bootstrap-4');

    $reverse_order = ($order == 'desc') ? 'asc' : 'desc';

		return view('app.rewards.leads', compact('leads', 'rewards', 'filterRewards', 'qsFilterRewards', 'fields', 'sortby', 'order', 'reverse_order', 'sortby', 'links'));
	}

  /**
   * Export leads
   */

  public function getExportLeads() {
    $allowed_sort = ['created_at'];
    $sql_select = [];
    $sql_select[] = 'rewards.title as Reward';

    $fields = \App\Http\Controllers\RewardsController::requiredFieldList();
    foreach ($fields as $field) { 
      $allowed_sort[] = $field['id'];
      $sql_select[] = 'reward_leads.field_values->' . $field['id'] . ' as ' . ucfirst(str_replace('_', ' ', $field['id'])) . '';
    }

    $sql_select[] = 'reward_leads.created_at as Created';

    $filterRewards = request()->get('filterRewards', []);
    $sortby = request()->get('sortby', 'created_at');
    $order = request()->get('order', 'asc');

    if (! in_array($sortby, $allowed_sort)) $sortby = 'created_at';
    if (! in_array($order, ['desc', 'asc'])) $order = 'asc';

    $filename = str_slug('reward-leads-' . date('Y-m-d h:i:s')) . '.xlsx';

    $leads = \DB::table('rewards')
      ->leftJoin('reward_leads', 'rewards.id', '=', 'reward_leads.reward_id')
      ->where('rewards.user_id', auth()->user()->id)
      ->whereIn('rewards.id', $filterRewards)
      ->select($sql_select)
      ->orderBy('reward_leads.' . $sortby, 'reward_leads.' . $order)
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

      $query = \App\RewardLead::where('user_id', auth()->user()->id)->where('id', $qs['reward_id']);
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
      $query = \App\RewardLead::where('user_id', auth()->user()->id)->where('id', $row);
      $query->delete();
    }

    return response()->json(['success' => true, 'redir' => 'reload']);
  }

	/**
	 * New reward
	 */
	public function showAddReward() {
    $count = auth()->user()->rewards->count();
    $item = trans('nearby-platform.rewards');

    $limit_reached = \App\Http\Controllers\Core\Helpers::getLimitReached($count, $item);

    if ($limit_reached !== false) {
      return $limit_reached;
    }

    $reward = new \stdClass;
    $reward->language = auth()->user()->locale;
    $reward->title = null;
    $reward->image_file_name = null;
    $reward->favicon = null;
    $reward->title = null;
    $reward->details = null;
    $reward->redeem_code = null;
    $reward->steps = 3;
    $reward->interval = 60 * 24;
    $reward->title = null;
    $reward->phone = null;
    $reward->website = null;
    $reward->address = null;
    $reward->street = null;
    $reward->street_number = null;
    $reward->postal_code = null;
    $reward->city = null;
    $reward->state = null;
    $reward->country = null;
    $reward->lat = null;
    $reward->lng = null;
    $reward->expiresMonth = null;
    $reward->expiresDay = null;
    $reward->expiresYear = null;
    $reward->expiresHour = null;
    $reward->expiresMinute = null;

    // Set reward properties
    $reward = $this->metaValues($reward, '');

    $reward->fields = [];

    $colors = ['red', 'pink', 'purple', 'deep-purple', 'indigo', 'blue', 'light-blue', 'cyan', 'teal', 'green', 'light-green', 'lime', 'yellow', 'amber', 'orange', 'deep-orange', 'brown', 'grey', 'blue-grey'];

    $content_fields = $this->contentFields();

		return view('app.rewards.reward', compact('reward', 'count', 'colors', 'content_fields'));
	}

	/**
	 * Edit reward
	 */
	public function showEditReward($sl) {
    $qs = Core\Secure::string2array($sl);

    $reward = \App\Reward::where('user_id', auth()->user()->id)->where('id', $qs['reward_id'])->first();

    if ($reward->expiration_date != null) {
      $dt = \Carbon\Carbon::parse($reward->expiration_date);

      $reward->expiresMonth = $dt->month;
      $reward->expiresDay = $dt->day;
      $reward->expiresYear = $dt->year;
    }

    if ($reward->expiration_date_time != null) {
      $dt = \Carbon\Carbon::parse($reward->expiration_date_time);

      $reward->expiresMonth = $dt->month;
      $reward->expiresDay = $dt->day;
      $reward->expiresYear = $dt->year;
      $reward->expiresHour = $dt->hour;
      $reward->expiresMinute = $dt->minute;
    }

    $colors = ['red', 'pink', 'purple', 'deep-purple', 'indigo', 'blue', 'light-blue', 'cyan', 'teal', 'green', 'light-green', 'lime', 'yellow', 'amber', 'orange', 'deep-orange', 'brown', 'grey', 'blue-grey'];

    // Set reward properties
    $reward = $this->metaValues($reward, '');

    $reward->favicon = $reward->getFavicon();

    $content_fields = $this->contentFields($reward->language);

    return view('app.rewards.reward', compact('sl', 'reward', 'colors', 'content_fields'));
	}

	/**
	 * Post new or update reward
	 */
	public function postReward() {
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
      $reward = \App\Reward::where('user_id', auth()->user()->id)->where('id', $qs['reward_id'])->first();
      $msg = trans('nearby-platform.item_saved_succesfully', ['item' => trans('nearby-platform.reward')]);
    } else {
      $reward = new \App\Reward;
      $msg = trans('nearby-platform.item_created_succesfully', ['item' => trans('nearby-platform.reward')]);
    }

    $reward->user_id = auth()->user()->id;

    $upload_image = (bool) request()->get('upload_image', false);

    if ($upload_image) {
      $reward->image = request()->file('image', \Czim\Paperclip\Attachment\Attachment::NULL_ATTACHMENT);
    }

    $reward->language = request()->get('language', auth()->user()->locale);
    $reward->title = request()->get('title');
    $reward->details = request()->get('details');
    $reward->title = request()->get('title');
    $reward->steps = request()->get('steps', 3);
    $reward->interval = request()->get('interval', 60*24);
    $reward->redeem_code = request()->get('redeem_code');
    $reward->phone = request()->get('phone', null);
    $reward->website = request()->get('website', null);
    $reward->address = request()->get('address', null);
    $reward->street = request()->get('street', null);
    $reward->street_number = request()->get('street_number', null);
    $reward->postal_code = request()->get('postal_code', null);
    $reward->city = request()->get('city', null);
    $reward->state = request()->get('state', null);
    $reward->country = request()->get('country', null);
    $reward->lat = request()->get('lat', null);
    $reward->lng = request()->get('lng', null);

    $reward->fields = request()->get('required', []);

    $reward->meta = $this->postMetaValues();

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

    $reward->expiration_date = $expiration_date;
    $reward->expiration_date_time = $expiration_date_time;

    $reward->save();

    // Favicon
    $upload_favicon = (bool) request()->get('upload_favicon', false);

    if ($upload_favicon) {
      $path = public_path('favicons/');
      $favicon = 'reward-' . \App\Http\Controllers\Core\Secure::staticHash($reward->id) . '.ico';

      // First delete existing
      \File::delete($path . $favicon);

      if (request()->file('favicon', null) != null) {
        $ico_lib = new \PHP_ICO(request()->file('favicon'), [[32, 32], [64, 64]]);
        $ico_lib->save_ico($path . $favicon);
      }
    }

    return redirect('dashboard/rewards')->with('message', $msg);
  }

  /**
   * Delete reward
   */
  public function postDeleteReward(Request $request) {

    $sl = $request->input('sl', '');

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);

      $query = \App\Reward::where('user_id', auth()->user()->id)->where('id', $qs['reward_id']);
      $reward = $query->first();

      // Delete metrics
      $metrics = \DB::table('metrics')->where('type', 'App\Metrics\RewardViewCountMetrics')->where('metricable_id', $qs['reward_id'])->delete();

      // Delete favicon
      $path = public_path('favicons/');
      $favicon = 'reward-' . \App\Http\Controllers\Core\Secure::staticHash($reward->id) . '.ico';

      // First delete existing
      \File::delete($path . $favicon);

      $query->delete();

      return response()->json(['success' => true, 'redir' => 'reload']);
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
	 * Get list of intervals
	 */
  public static function intervalList() {
    $aIntervals = [];
    $aIntervals[] = ['minutes' => 5, 'name' => '5 minutes'];
    $aIntervals[] = ['minutes' => 60, 'name' => '1 hour'];
    $aIntervals[] = ['minutes' => 60 * 12, 'name' => '12 hours'];
    $aIntervals[] = ['minutes' => 60 * 24, 'name' => '1 day'];
    $aIntervals[] = ['minutes' => 60 * 24 * 2, 'name' => '2 days'];
    $aIntervals[] = ['minutes' => 60 * 24 * 3, 'name' => '3 days'];
    $aIntervals[] = ['minutes' => 60 * 24 * 4, 'name' => '4 days'];
    $aIntervals[] = ['minutes' => 60 * 24 * 5, 'name' => '5 days'];
    $aIntervals[] = ['minutes' => 60 * 24 * 7, 'name' => '1 week'];
    $aIntervals[] = ['minutes' => 60 * 24 * 7 * 2, 'name' => '2 weeks'];
    $aIntervals[] = ['minutes' => 60 * 24 * 7 * 3, 'name' => '3 weeks'];
    $aIntervals[] = ['minutes' => 60 * 24 * 7 * 4, 'name' => '4 weeks'];

    return $aIntervals;
  }
}