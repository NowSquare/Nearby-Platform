<?php namespace App\Http\Controllers\Core;

use Illuminate\Routing\Controller;

class Localization extends Controller {

  /*
   |--------------------------------------------------------------------------
   | Localization Controller
   |--------------------------------------------------------------------------
   |
   | Localization related logic
   |--------------------------------------------------------------------------
   */

  /**
   * Localization::getAllLanguages();
   * Get all base languages
   */
  public static function getAllLanguages($two_char_only = true)
  {
    $current_language = (auth()->check()) ? auth()->user()->locale : \App::getLocale();

    // Reads the language definitions from resources/language.
    $languageRepository = new \CommerceGuys\Intl\Language\LanguageRepository;
    $languages = $languageRepository->getAll($current_language);

    foreach ($languages as $language_code => $language) {

      if ($two_char_only && strlen($language_code) == 2) {
        $return[$language_code] = ['name' => $language->getName()];
      } elseif(! $two_char_only) {
        $return[$language_code] = ['name' => $language->getName()];
      }
    }
    return $return;
  }

  /**
   * Get language from browser
   */

  public static function getBrowserLocale()
  {

    $browser_language = new \Sinergi\BrowserDetector\Language();
    $browser_language = substr($browser_language->getLanguage(), 0, 2);
    return strtolower($browser_language);
/*
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
      // Parse the Accept-Language according to:
      // http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.4
      preg_match_all(
        '/([a-z]{1,8})' .       // M1 - First part of language e.g en
        '(-[a-z]{1,8})*\s*' .   // M2 -other parts of language e.g -us
        // Optional quality factor M3 ;q=, M4 - Quality Factor
        '(;\s*q\s*=\s*((1(\.0{0,3}))|(0(\.[0-9]{0,3}))))?/i',
        $_SERVER['HTTP_ACCEPT_LANGUAGE'],
        $langParse);

      $langs = $langParse[1]; // M1 - First part of language
      $quals = $langParse[4]; // M4 - Quality Factor

      $numLanguages = count($langs);
      $langArr = array();

      for ($num = 0; $num < $numLanguages; $num++)
      {
        $newLang = strtoupper($langs[$num]);
        $newQual = isset($quals[$num]) ?
           (empty($quals[$num]) ? 1.0 : floatval($quals[$num])) : 0.0;

        // Choose whether to upgrade or set the quality factor for the
        // primary language.
        $langArr[$newLang] = (isset($langArr[$newLang])) ?
           max($langArr[$newLang], $newQual) : $newQual;
      }

      // sort list based on value
      // langArr will now be an array like: array('EN' => 1, 'ES' => 0.5)
      arsort($langArr, SORT_NUMERIC);

      // The languages the client accepts in order of preference.
      $acceptedLanguages = array_keys($langArr);
    } else {
      $acceptedLanguages = ['EN'];
    }
    return $acceptedLanguages;
    */
  }
}