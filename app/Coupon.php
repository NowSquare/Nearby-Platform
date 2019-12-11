<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Czim\Paperclip\Contracts\AttachableInterface;
use Czim\Paperclip\Model\PaperclipTrait;

use GurmanAlexander\Metrics\Metricable;

class Coupon extends Model implements AttachableInterface
{
  use PaperclipTrait;
  use Metricable;

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'coupons';

  protected $dates = ['created_at', 'updated_at', 'valid_from_date', 'expiration_date', 'expiration_date_time', 'last_redemption'];

  protected $casts = [
    'fields' => 'json',
    'meta' => 'json',
    'settings' => 'json'
  ];

  /**
   * Fix for Stapler: https://github.com/CodeSleeve/laravel-stapler/issues/64
   *
   * Get all of the current attributes on the model.
   *
   * @return array
   */
  public function getAttributes()
  {
      return parent::getAttributes();
  }

  public function getUrl() {
    $url = url('coupon/' . \App\Http\Controllers\Core\Secure::staticHash($this->id));

    return $url;
  }

  public function getFavicon() {
    $favicon = 'favicons/coupon-' . \App\Http\Controllers\Core\Secure::staticHash($this->id) . '.ico';
    $favicon = (\File::exists(public_path($favicon))) ? url($favicon) : null;

    return $favicon;
  }

  public function __construct(array $attributes = array()) {

      $this->hasAttachedFile('image', [
          'variants' => [
              'favicon' => '32x32#',
              'icon-s' => '320x320#',
              '1xportrait' => 'x160',
              '1x' => '160',
              '2x' => '800',
              '4x' => '1920'
          ]
      ]);

      parent::__construct($attributes);
  }

  public function user() {
    return $this->belongsTo('\App\User');
  }

  public function couponLeads() {
    return $this->hasMany('\App\CouponLead');
  }
}