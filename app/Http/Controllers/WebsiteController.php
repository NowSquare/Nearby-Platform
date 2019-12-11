<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use League\CommonMark\Converter;

class WebsiteController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	*/

  public function __construct() {
  }

	/**
	 * Home
	 */
	public function home($locale = 'en') {
    app()->setLocale($locale);

		return view('welcome');
	}

	/**
	 * Dashboard
	 */
	public function dashboard() {
		return view('auth.dashboard', compact('header_img', 'color_dark', 'color_light'));
	}

	/**
	 * Contact
	 */
	public function contact() {
    return view('contact', compact('header_img', 'color_dark', 'color_light'));
	}

	/**
	 * Contact form post
	 */
	public function postContact(Request $request) {
		$name = $request->input('name', '');
		$email = $request->input('email', '');
		$subject = 'Mail from ' . config('system.premium_name');
		$message = $request->input('message', '');
		$botcheck = $request->input('phone', '');

    $ip_address = request()->ip();

		if($botcheck == '' && $name != '' && $email != '' && $message != '')
		{
      $body = $message . "\n\n" . $ip_address;
      \Mail::raw($body, function($message) use($email, $name, $subject)
      {
        $message->from($email, $name);
        $message->replyTo($email, $name);
        $message->to(config('system.mail_address_from'))->subject($subject);
      });

      $msg = "Thank you for your message. We will get back as soon as possible if necessary.";
      $type = 'success';
    }

    return \Response::json(['msg' => $msg, 'type' => $type]);
	}

	/**
	 * Use cases
	 */
	public function useCases() {
    $header_img = url('assets/images/backgrounds/triangles05.jpg');
    $color_dark = 'rgba(245,249,250,0.9)';
    $color_light = '#d9dfdf';

		return view('use-cases', compact('header_img', 'color_dark', 'color_light'));
	}

	/**
	 * FAQ
	 */
	public function faq() {
    $header_img = url('assets/images/backgrounds/triangles05.jpg');
    $color_dark = 'rgba(245,249,250,0.9)';
    $color_light = '#d9dfdf';

		return view('faq', compact('header_img', 'color_dark', 'color_light'));
	}

	/**
	 * Legal
	 */
	public function legal() {
    $header_img = url('assets/images/backgrounds/triangles05.jpg');
    $color_dark = 'rgba(245,249,250,0.9)';
    $color_light = '#d9dfdf';

    return view('legal', compact('header_img', 'color_dark', 'color_light'));
	}

	/**
	 * Privacy Policy
	 */
	public function privacyPolicy() {
    $header_img = url('assets/images/backgrounds/triangles05.jpg');
    $color_dark = 'rgba(245,249,250,0.9)';
    $color_light = '#d9dfdf';

		return view('privacy-policy', compact('header_img', 'color_dark', 'color_light'));
	}

}