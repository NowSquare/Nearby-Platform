<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Czim\Paperclip\Contracts\AttachableInterface;
use Czim\Paperclip\Model\PaperclipTrait;

use GurmanAlexander\Metrics\Metricable;

class Qr extends Model implements AttachableInterface
{
  use PaperclipTrait;
  use Metricable;

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'qr_codes';

  protected $dates = ['created_at', 'updated_at'];

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
    $url = url('qr/' . \App\Http\Controllers\Core\Secure::staticHash($this->id));

    return $url;
  }

  public function __construct(array $attributes = array()) {

      $this->hasAttachedFile('qr', [
          'variants' => [
              'sm' => '640x640#',
              'lg' => '1920x1920#'
          ]
      ]);

      $this->hasAttachedFile('image', [
          'variants' => [
              'square-sm' => '320x320#',
              'square-md' => '800x800#',
              'md' => '800',
              'lg' => '1920'
          ]
      ]);

      parent::__construct($attributes);
  }

  public function user() {
    return $this->belongsTo('\App\User');
  }
}