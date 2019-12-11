<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertySurrounding extends Model {

  protected $table = 'property_surroundings';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['name'];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

  public function properties() {
    return $this->belongsToMany('App\Property', 'property_surrounding', 'property_id', 'property_surrounding_id');
  }
}