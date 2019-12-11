<?php namespace App\Http\Controllers\Core;

class Secure extends \App\Http\Controllers\Controller {

  /**
   * Array to encrypted string - $sl = \App\Http\Controllers\Core\Secure\Secure::array2string(array('id' => 1))
   */

  public static function array2string($array)
  {
    $string = http_build_query($array);
    $string =  \Crypt::encrypt($string);
    return rawurlencode($string);
  }

  /**
   * Encrypted string to array - $sl = \App\Http\Controllers\Core\Secure\Secure::string2array($sl)
   */

  public static function string2array($string)
  {
    try {
      $string = rawurldecode($string);
      $string = \Crypt::decrypt($string);
    }
    catch(\Illuminate\Encryption\DecryptException  $e)
    {
      echo 'Decrypt Error';
      die();
    }

    parse_str($string, $array);
    return $array;
  }

  /**
   * Short hash ONLY for numbers, for example user_id to create upload directory. $hash = \App\Http\Controllers\Core\Secure::staticHash(1)
   */

  public static function staticHash($number, $obfuscate = false)
  {
    $hashids = new \Hashids\Hashids(\Config::get('app.key'));

    if ($obfuscate) {
      $number = $number * intval(config()->get('system.obfuscator_prefix', 8312));
    }

    $string = $hashids->encode($number);
    return $string;
  }

  /**
   * Decode hash. $number = \App\Http\Controllers\Core\Secure::staticHashDecode($hash)
   */

  public static function staticHashDecode($hash, $obfuscate = false)
  {
    $hashids = new \Hashids\Hashids(\Config::get('app.key'));
    $number = $hashids->decode($hash);
    if (isset($number[0])) {
      $number = $number[0];

      if ($obfuscate) {
        $number = intval($number) / intval(config()->get('system.obfuscator_prefix', 8312));
      }
    } else {
      $number = false;
    }

    return $number;
  }
}