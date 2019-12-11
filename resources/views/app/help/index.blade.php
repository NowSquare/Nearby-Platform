@extends('../layouts.master')

@section('page_title') {{ $page_title }} @stop
@section('meta_description') {{ $page_title }} @stop

@section('content')

  @include("app.{$seg2}._breadcrumbs")

<section class="my-3">
    <div class="container cards cards-hover">
        <div class="row">
          <div class="col-md-8 col-lg-9 order-md-12">

    				<div class="row">
<?php
// Return sorted dirs
$dirs = array_sort(\File::directories(base_path() . '/resources/views/app/' . $seg2), function($dir) {
    return $dir;
});

foreach ($dirs as $dir) {
	$dirname = basename($dir);

	if (strpos($dirname, '-') !== false) {
		$dirname = explode('-', $dirname);
		array_shift($dirname);
		$dirname = implode('-', $dirname);
		$chapter = str_replace('-', ' ', $dirname);
		$chapter = ucfirst($chapter);

    echo '<div class="col-6 col-lg-4">';
    echo '<a href="' . url($seg1 . '/' . $seg2 . '/' . $dirname) . '" class="card-block-link" title="' . $chapter . '">';
    echo '<div class="card card-block text-center rounded-0">';
    echo '<i class="icon mi ' . config('doc-icons.' . $dirname, 'insert_drive_file') . ' block-icon"></i>';
    echo '<h4>' . $chapter . '</h4>';
    echo '</div>';
    echo '</a>';
    echo '</div>';
	}
}
?>
	            </div>
            </div>
			      <div class="col-md-4 col-lg-3 mb-3 order-md-1">
				      @include("app.{$seg2}._nav")
            </div>
        </div>
    </div>
</section>
@stop