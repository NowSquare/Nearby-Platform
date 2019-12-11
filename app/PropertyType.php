<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyType extends Model {

  protected $table = 'property_types';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

  public function properties() {
    return $this->hasMany('Modules\Properties\Http\Models\Property');
  }
}