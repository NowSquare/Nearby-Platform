<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'scope', 'name', 'value', 'value_json'
    ];

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'settings';

  protected $casts = [
    'value_json' => 'json'
  ];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;
}
