<?php namespace App\Http\Controllers\Core;

class Helpers extends \App\Http\Controllers\Controller {

  /**
   * Get setting value
   */

  public static function getSetting($name, $default, $scope = null, $scope_id = null) {

    $setting = \App\Setting::where('scope', $scope)->where('name', $name)->first();
    $setting = ($setting === null) ? $default : $setting->value;

    return $setting;
  }

  /**
   * Parse string for meta description use
   * \App\Http\Controllers\Core\Helpers::parseDescription($string)
   */

  public static function parseDescription($string, $limit = 400) {
    $description = str_replace('"', '&quot;', preg_replace('/\s+/', ' ', preg_replace('/\r|\n/', ' ', strip_tags(html_entity_decode($string)))));
    $description = str_limit($description, $limit);
    $description = trim($description);
    return $description;
  }

  /**
   * Limit reached
   */

  public static function getLimitReached($count, $item) {
    return false;
  }

  /**
   * Material color class to HEX
   */

  public static function material2hex($name) {
    switch ($name) {
      case 'red': $color = '#f44336'; break;
      case 'pink': $color = '#e91e63'; break;
      case 'purple': $color = '#5533ff'; break;
      case 'deep-purple': $color = '#673ab7'; break;
      case 'indigo': $color = '#3f51b5'; break;
      case 'blue': $color = '#1d8ccc'; break;
      case 'light-blue': $color = '#03a9f4'; break;
      case 'cyan': $color = '#00bcd4'; break;
      case 'teal': $color = '#009688'; break;
      case 'green': $color = '#4caf50'; break;
      case 'light-green': $color = '#8bc34a'; break;
      case 'lime': $color = '#cddc39'; break;
      case 'yellow': $color = '#ffeb3b'; break;
      case 'amber': $color = '#ffc107'; break;
      case 'orange': $color = '#ff9800'; break;
      case 'deep-orange': $color = '#ff5722'; break;
      case 'brown': $color = '#795548'; break;
      case 'grey': $color = '#9e9e9e'; break;
      case 'blue-grey': $color = '#607d8b'; break;
    }
    return $color;
  }

}