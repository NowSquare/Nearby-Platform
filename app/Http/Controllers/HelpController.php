<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use League\CommonMark\Converter;

class HelpController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Help Controller
	|--------------------------------------------------------------------------
	|
	*/

  /**
	 * Nearby Platform help
	 */
	public function nearbyPlatformHelp()
	{
    $product = "Nearby Platform";
    $doctype = "Help";
    $page_title = "Nearby Platform - Knowledge Base";

    $header_img = url('assets/images/backgrounds/triangles05.jpg');
    $color_dark = 'rgba(20,110,255,0.7)';
    $color_light = '#d0e2ff';
    $seg1 = \Request::segment(1);
    $seg2 = \Request::segment(2);

    $docs = array_sort(\File::directories(base_path() . '/resources/views/app/help'), function($dir) {
      return $dir;
    });

		return view('app.help.index', ['page_title' => $page_title, 'product' => $product, 'doctype' => $doctype, 'seg1' => $seg1, 'seg2' => $seg2, 'header_img' => $header_img, 'color_dark' => $color_dark, 'color_light' => $color_light]);
	}

	/**
	 * Nearby Platform help pages
	 */
	public function nearbyPlatformHelpPage($page)
	{
    $product = "Nearby Platform";
    $doctype = "Help";
    $page_title = "Documentation - Nearby Platform - Knowledge Base";
    $header_img = url('assets/images/backgrounds/triangles05.jpg');
    $color_dark = 'rgba(20,110,255,0.7)';
    $color_light = '#d0e2ff';
    $seg1 = \Request::segment(1);
    $seg2 = \Request::segment(2);
    $seg3 = \Request::segment(3);

		$page_short = $page;
		foreach (glob(base_path() . "/resources/views/app/help/*-" . $page) as $filename) {
			$page = basename($filename);
		}

		return view('app.help.' . $page . '.index', ['page_title' => $page_title, 'product' => $product, 'doctype' => $doctype, 'seg1' => $seg1, 'seg2' => $seg2, 'seg3' => $seg3, 'header_img' => $header_img, 'color_dark' => $color_dark, 'color_light' => $color_light, 'page' => $page, 'page_short' => $page_short]);
	}

	/**
	 * Nearby Platform help sub pages
	 */
	public function nearbyPlatformHelpPageSub($page, $sub) {
    $product = "Nearby Platform";
    $doctype = "Help";
    $page_title = "Documentation - Nearby Platform - Knowledge Base";
    $header_img = url('assets/images/backgrounds/triangles05.jpg');
    $color_dark = 'rgba(20,110,255,0.7)';
    $color_light = '#d0e2ff';
    $seg1 = \Request::segment(1);
    $seg2 = \Request::segment(2);
    $seg3 = \Request::segment(3);
    $seg4 = \Request::segment(4);

		$page_short = $page;
		foreach (glob(base_path() . "/resources/views/app/help/*-" . $page) as $filename) {
			$page = basename($filename);
      $dirname = $filename;
		}

		$sub_short = $sub;
		foreach (glob(base_path() . "/resources/views/app/help/" . $page . "/*-" . $sub . ".md") as $filename) {
			$sub = basename($filename);
			$sub = str_replace('.blade.php', '', $sub);
			$sub = str_replace('.md', '', $sub);
		}

    $markdown = \File::get(base_path() . "/resources/views/app/help/" . $page . "/" . $sub . ".md");
    $html = \GrahamCampbell\Markdown\Facades\Markdown::convertToHtml($markdown);

    $html = str_replace('<a href', '<a class="link" href', $html);
    $html = str_replace('<pre', '<pre class="prettyprint"', $html);
    $html = str_replace('<blockquote', '<blockquote class="alert alert-primary rounded-0"', $html);
    $html = str_replace('<table', '<table class="table table-bordered table-striped"', $html);
    $html = str_replace('<thead', '<thead class="thead-inverse"', $html);

		// Prev / next
		$files = array_sort(\File::files($dirname), function($file) {
			return $file;
		});

		foreach ($files as $i => $file) {
			$filename = basename($file);
			$filename = str_replace('.blade.php', '', $filename);
			$filename = str_replace('.md', '', $filename);

			if ($filename == $sub) {
        $prev = (isset($files[$i-1]) && basename($files[$i-1]) != 'index.blade.php' && basename($files[$i-1]) != 'page.blade.php') ? explode('-', str_replace('.md', '', basename($files[$i-1])), 2)[1] : '';
        $prev_link = ($prev != '') ? url('dashboard/help/' . explode('-', $page, 2)[1] . '/' . $prev) : '';
        $next = (isset($files[$i+1]) && basename($files[$i+1]) != 'index.blade.php' && basename($files[$i+1]) != 'page.blade.php') ? explode('-', str_replace('.md', '', basename($files[$i+1])), 2)[1] : '';
        $next_link = ($next != '') ? url('dashboard/help/' . explode('-', $page, 2)[1] . '/' . $next) : '';
			}
		}

    $prev = str_replace('-', ' ', ucfirst($prev));
    $next = str_replace('-', ' ', ucfirst($next));

		return view('app.help.' . $page . '.page', ['page_title' => $page_title, 'next' => $next, 'next_link' => $next_link, 'prev' => $prev, 'prev_link' => $prev_link, 'product' => $product, 'doctype' => $doctype, 'seg1' => $seg1, 'seg2' => $seg2, 'seg3' => $seg3, 'seg4' => $seg4, 'header_img' => $header_img, 'color_dark' => $color_dark, 'color_light' => $color_light, 'html' => $html, 'page' => $page, 'page_short' => $page_short, 'sub' => $sub, 'sub_short' => $sub_short]);
	}


}