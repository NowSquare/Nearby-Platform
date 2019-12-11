<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use Czim\Paperclip\Contracts\AttachableInterface;
use Czim\Paperclip\Model\PaperclipTrait;

use GurmanAlexander\Metrics\Metricable;

class Property extends Model implements AttachableInterface {
  use PaperclipTrait;
  use Metricable;

  protected $table = 'properties';

  protected $casts = [
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
    $url = url('property/' . \App\Http\Controllers\Core\Secure::staticHash($this->id));

    return $url;
  }

  public function getFavicon() {
    $favicon = 'favicons/property-' . \App\Http\Controllers\Core\Secure::staticHash($this->id) . '.ico';
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

  public function property_type() {
    return $this->belongsTo('\App\PropertyType');
  }

  public function sales_type() {
    return $this->belongsTo('\App\SalesType');
  }

  public function features() {
    return $this->belongsToMany('\App\PropertyFeature', 'property_feature', 'property_id', 'property_feature_id');
  }

  public function surrounding() {
    return $this->belongsToMany('\App\PropertySurrounding', 'property_surrounding', 'property_id', 'property_surrounding_id');
  }

  public function garages() {
    return $this->belongsToMany('\App\PropertyGarage', 'property_garage', 'property_id', 'property_garage_id');
  }

  public function scopeDistance($query, $dist, $location) {
    // Miles
    //$unit = 3959;
    // Kilometers (* 1000 = meters)
    $unit = 6371000;

    $coords = explode(',', $location);
    $lat = $coords[0];
    $lng = $coords[1];
    return $query->selectRaw("ROUND( " . $unit . " * acos( cos( radians(" . $lat . ") ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(" . $lng . ") ) + sin( radians(" . $lat . ") ) * sin(radians(lat)) ), 0) AS distance")->havingRaw('distance < '.$dist);
  }
}