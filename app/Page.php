<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Czim\Paperclip\Contracts\AttachableInterface;
use Czim\Paperclip\Model\PaperclipTrait;

use GurmanAlexander\Metrics\Metricable;

class Page extends Model implements AttachableInterface
{
  use PaperclipTrait;
  use Metricable;

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'pages';

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
    $url = url('page/' . \App\Http\Controllers\Core\Secure::staticHash($this->id));

    return $url;
  }

  public function getFavicon() {
    $favicon = 'favicons/page-' . \App\Http\Controllers\Core\Secure::staticHash($this->id) . '.ico';
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

      $this->hasAttachedFile('icon', [
          'variants' => [
              's' => '64x64#'
          ]
      ]);

      parent::__construct($attributes);
  }

  public function user() {
    return $this->belongsTo('\App\User');
  }
}