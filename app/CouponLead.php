<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponLead extends Model
{

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'coupon_leads';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

  protected $dates = ['created_at'];

  public function getDates() {
    return array('created_at');
  }

  protected $casts = [
    'field_names' => 'json',
    'field_values' => 'json',
    'meta' => 'json'
  ];

  public function coupon() {
    return $this->belongsTo('\App\Coupon');
  }
}