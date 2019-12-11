<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesType extends Model {

  protected $table = 'sales_types';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

  public function properties() {
    return $this->hasMany('App\Property');
  }
}