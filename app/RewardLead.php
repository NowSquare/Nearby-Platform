<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class RewardLead extends Model
{

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'reward_leads';

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
    'field_values' => 'json',
    'meta' => 'json'
  ];

  public function reward() {
    return $this->belongsTo('\App\Reward');
  }
}