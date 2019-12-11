<input type="hidden" id="select_deal_input" value="">
<div class="modal fade" id="dealSelectModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog mt-5" role="document">
    <div class="modal-content rounded-0 mdl-shadow--8dp border border-white">
      <div class="modal-header border-0 p-3">
        <h5 class="modal-title">{{ trans('nearby-platform.select_a_', ['item' => trans('nearby-platform.deal')]) }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('nearby-platform.close') }}">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body px-3 py-0 border-0">
        <div class="list-group rounded-0">
<?php
if (count($deals) == 0) {
?>
          <a href="{{ url('dashboard/deals/add') }}" class="list-group-item rounded-0 list-group-item-action text-truncate">{{ trans('nearby-platform.create_a_new_', ['item' => trans('nearby-platform.deal')]) }}</a>
<?php
} else {
  foreach ($deals as $deal) {
    $img_class = ($deal->image_file_name != null) ? ' mdl-shadow--2dp' : '';
    $img_src = ($deal->image_file_name == null) ? 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' : $deal->image->url('favicon');
?>
          <a href="javascript:void(0);" onclick="selectDeal('{{ url('deal/' . \App\Http\Controllers\Core\Secure::staticHash($deal->id)) }}');" class="list-group-item rounded-0 list-group-item-action text-truncate"><img src="{{ $img_src }}" class="mr-2 {{ $img_class }}" width="32" height="32"> {!! $deal->title !!}</a>
<?php
  }
}
?>
        </div>
      </div>
      <div class="modal-footer border-0 p-3">
        <button type="button" class="btn rounded-0 btn-secondary" data-dismiss="modal" id="dealSelectModalClose">{{ trans('nearby-platform.close') }}</button>
      </div>
    </div>
  </div>
</div>

<input type="hidden" id="select_coupon_input" value="">
<div class="modal fade" id="couponSelectModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog mt-5" role="document">
    <div class="modal-content rounded-0 mdl-shadow--8dp border border-white">
      <div class="modal-header border-0 p-3">
        <h5 class="modal-title">{{ trans('nearby-platform.select_a_', ['item' => trans('nearby-platform.coupon')]) }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('nearby-platform.close') }}">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body px-3 py-0 border-0">
        <div class="list-group rounded-0">
<?php
if (count($coupons) == 0) {
?>
          <a href="{{ url('dashboard/coupons/add') }}" class="list-group-item rounded-0 list-group-item-action text-truncate">{{ trans('nearby-platform.create_a_new_', ['item' => trans('nearby-platform.coupon')]) }}</a>
<?php
} else {
  foreach ($coupons as $coupon) {
    $img_class = ($coupon->image_file_name != null) ? ' mdl-shadow--2dp' : '';
    $img_src = ($coupon->image_file_name == null) ? 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' : $coupon->image->url('favicon');
?>
          <a href="javascript:void(0);" onclick="selectCoupon('{{ url('coupon/' . \App\Http\Controllers\Core\Secure::staticHash($coupon->id)) }}');" class="list-group-item rounded-0 list-group-item-action text-truncate"><img src="{{ $img_src }}" class="mr-2 {{ $img_class }}" width="32" height="32"> {!! $coupon->title !!}</a>
<?php
  }
}
?>
        </div>
      </div>
      <div class="modal-footer border-0 p-3">
        <button type="button" class="btn rounded-0 btn-secondary" data-dismiss="modal" id="couponSelectModalClose">{{ trans('nearby-platform.close') }}</button>
      </div>
    </div>
  </div>
</div>

<input type="hidden" id="select_reward_input" value="">
<div class="modal fade" id="rewardSelectModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog mt-5" role="document">
    <div class="modal-content rounded-0 mdl-shadow--8dp border border-white">
      <div class="modal-header border-0 p-3">
        <h5 class="modal-title">{{ trans('nearby-platform.select_a_', ['item' => trans('nearby-platform.reward')]) }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('nearby-platform.close') }}">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body px-3 py-0 border-0">
        <div class="list-group rounded-0">
<?php
if (count($rewards) == 0) {
?>
          <a href="{{ url('dashboard/rewards/add') }}" class="list-group-item rounded-0 list-group-item-action text-truncate">{{ trans('nearby-platform.create_a_new_', ['item' => trans('nearby-platform.reward')]) }}</a>
<?php
} else {
  foreach ($rewards as $reward) {
    $img_class = ($reward->image_file_name != null) ? ' mdl-shadow--2dp' : '';
    $img_src = ($reward->image_file_name == null) ? 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' : $reward->image->url('favicon');
?>
          <a href="javascript:void(0);" onclick="selectReward('{{ url('reward/' . \App\Http\Controllers\Core\Secure::staticHash($reward->id)) }}');" class="list-group-item rounded-0 list-group-item-action text-truncate"><img src="{{ $img_src }}" class="mr-2 {{ $img_class }}" width="32" height="32"> {!! $reward->title !!}</a>
<?php
  }
}
?>
        </div>
      </div>
      <div class="modal-footer border-0 p-3">
        <button type="button" class="btn rounded-0 btn-secondary" data-dismiss="modal" id="rewardSelectModalClose">{{ trans('nearby-platform.close') }}</button>
      </div>
    </div>
  </div>
</div>

<input type="hidden" id="select_property_input" value="">
<div class="modal fade" id="propertySelectModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog mt-5" role="document">
    <div class="modal-content rounded-0 mdl-shadow--8dp border border-white">
      <div class="modal-header border-0 p-3">
        <h5 class="modal-title">{{ trans('nearby-platform.select_a_', ['item' => trans('nearby-platform.property')]) }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('nearby-platform.close') }}">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body px-3 py-0 border-0">
        <div class="list-group rounded-0">
<?php
if (count($properties) == 0) {
?>
          <a href="{{ url('dashboard/properties/add') }}" class="list-group-item rounded-0 list-group-item-action text-truncate">{{ trans('nearby-platform.create_a_new_', ['item' => trans('nearby-platform.property')]) }}</a>
<?php
} else {
  foreach ($properties as $property) {
    $img_class = ($property->image_file_name != null) ? ' mdl-shadow--2dp' : '';
    $img_src = ($property->image_file_name == null) ? 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' : $property->image->url('favicon');
?>
          <a href="javascript:void(0);" onclick="selectProperty('{{ url('property/' . \App\Http\Controllers\Core\Secure::staticHash($property->id)) }}');" class="list-group-item rounded-0 list-group-item-action text-truncate"><img src="{{ $img_src }}" class="mr-2 {{ $img_class }}" width="32" height="32"> {!! $property->title !!}</a>
<?php
  }
}
?>
        </div>
      </div>
      <div class="modal-footer border-0 p-3">
        <button type="button" class="btn rounded-0 btn-secondary" data-dismiss="modal" id="propertySelectModalClose">{{ trans('nearby-platform.close') }}</button>
      </div>
    </div>
  </div>
</div>

<input type="hidden" id="select_card_input" value="">
<div class="modal fade" id="cardSelectModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog mt-5" role="document">
    <div class="modal-content rounded-0 mdl-shadow--8dp border border-white">
      <div class="modal-header border-0 p-3">
        <h5 class="modal-title">{{ trans('nearby-platform.select_a_', ['item' => trans('nearby-platform.business_card')]) }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('nearby-platform.close') }}">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body px-3 py-0 border-0">
        <div class="list-group rounded-0">
<?php
if (count($cards) == 0) {
?>
          <a href="{{ url('dashboard/business-cards/add') }}" class="list-group-item rounded-0 list-group-item-action text-truncate">{{ trans('nearby-platform.create_a_new_', ['item' => trans('nearby-platform.business_card')]) }}</a>
<?php
  } else {
  foreach ($cards as $card) {
    $img_class = ($card->avatar_file_name != null) ? ' mdl-shadow--2dp' : '';
    $img_src = ($card->avatar_file_name == null) ? 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' : $card->avatar->url('s');
?>
          <a href="javascript:void(0);" onclick="selectCard('{{ url('card/' . \App\Http\Controllers\Core\Secure::staticHash($card->id)) }}');" class="list-group-item rounded-0 list-group-item-action text-truncate"><img src="{{ $img_src }}" class="mr-2 {{ $img_class }}" width="32" height="32"> {!! $card->name . ' - ' . $card->title !!}</a>
<?php
  }
}
?>
        </div>
      </div>
      <div class="modal-footer border-0 p-3">
        <button type="button" class="btn rounded-0 btn-secondary" data-dismiss="modal" id="cardSelectModalClose">{{ trans('nearby-platform.close') }}</button>
      </div>
    </div>
  </div>
</div>

<input type="hidden" id="select_video_input" value="">
<div class="modal fade" id="videoSelectModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog mt-5" role="document">
    <div class="modal-content rounded-0 mdl-shadow--8dp border border-white">
      <div class="modal-header border-0 p-3">
        <h5 class="modal-title">{{ trans('nearby-platform.select_a_', ['item' => trans('nearby-platform.video')]) }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('nearby-platform.close') }}">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body px-3 py-0 border-0">
        <div class="list-group rounded-0">
<?php
if (count($videos) == 0) {
?>
          <a href="{{ url('dashboard/videos/add') }}" class="list-group-item rounded-0 list-group-item-action text-truncate">{{ trans('nearby-platform.create_a_new_', ['item' => trans('nearby-platform.video')]) }}</a>
<?php
  } else {
  foreach ($videos as $video) {
    $img_class = ($video->image_file_name != null) ? ' mdl-shadow--2dp' : '';
    $img_src = ($video->image_file_name == null) ? 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' : $video->image->url('1x');
?>
          <a href="javascript:void(0);" onclick="selectVideo('{{ url('video/' . \App\Http\Controllers\Core\Secure::staticHash($video->id)) }}');" class="list-group-item rounded-0 list-group-item-action text-truncate"><img src="{{ $img_src }}" class="mr-2 {{ $img_class }}" width="32" height="32"> {!! $video->title !!}</a>
<?php
  }
}
?>
        </div>
      </div>
      <div class="modal-footer border-0 p-3">
        <button type="button" class="btn rounded-0 btn-secondary" data-dismiss="modal" id="videoSelectModalClose">{{ trans('nearby-platform.close') }}</button>
      </div>
    </div>
  </div>
</div>

<input type="hidden" id="select_page_input" value="">
<div class="modal fade" id="pageSelectModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog mt-5" role="document">
    <div class="modal-content rounded-0 mdl-shadow--8dp border border-white">
      <div class="modal-header border-0 p-3">
        <h5 class="modal-title">{{ trans('nearby-platform.select_a_', ['item' => trans('nearby-platform.page')]) }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('nearby-platform.close') }}">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body px-3 py-0 border-0">
        <div class="list-group rounded-0">
<?php
if (count($pages) == 0) {
?>
          <a href="{{ url('dashboard/pages/add') }}" class="list-group-item rounded-0 list-group-item-action text-truncate">{{ trans('nearby-platform.create_a_new_', ['item' => trans('nearby-platform.page')]) }}</a>
<?php
  } else {
  foreach ($pages as $page) {
    $img_class = ($page->icon_file_name != null) ? ' mdl-shadow--2dp' : '';
    $img_src = ($page->icon_file_name == null) ? 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' : $page->icon->url('s');
?>
          <a href="javascript:void(0);" onclick="selectPage('{{ url('page/' . \App\Http\Controllers\Core\Secure::staticHash($page->id)) }}');" class="list-group-item rounded-0 list-group-item-action text-truncate"><img src="{{ $img_src }}" class="mr-2 {{ $img_class }}" width="32" height="32"> {!! $page->title !!}</a>
<?php
  }
}
?>
        </div>
      </div>
      <div class="modal-footer border-0 p-3">
        <button type="button" class="btn rounded-0 btn-secondary" data-dismiss="modal" id="pageSelectModalClose">{{ trans('nearby-platform.close') }}</button>
      </div>
    </div>
  </div>
</div>