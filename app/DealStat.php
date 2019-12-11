<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class DealStat extends Model
{

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'deal_stats';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

  public function getDates() {
    return array('created_at');
  }

  public function deal() {
    return $this->hasOne('\App\Deal');
  }

  public function user() {
    return $this->belongsTo('\App\User');
  }

}
