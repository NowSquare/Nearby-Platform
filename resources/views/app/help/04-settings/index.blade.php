@extends('../../../layouts.master')

@section('page_title') {{ ucfirst(str_replace('-', ' ', $page_short)) }} - {{ $page_title }} @stop
@section('meta_description') {{ ucfirst(str_replace('-', ' ', $page_short)) }} @stop

@section('content')
  @include("app.{$seg2}._breadcrumbs")
<section class="my-3">
  <div class="container cards cards-hover">
    <div class="row">
      <div class="col-md-8 col-lg-9 order-md-12">
        <div class="row">
          <?php
$files = array_sort(File::files(base_path() . '/resources/views/app/' . $seg2 . '/' . $page), function($file) {
	return $file;
});

foreach ($files as $file) {
	$filename = basename($file);
	$filename = str_replace('.blade.php', '', $filename);
  $filename = str_replace('.md', '', $filename);

	if ($filename != 'index' && $filename != 'page' && ! starts_with($filename, '-'))	{
		$name = str_replace('-', ' ', $filename);
		$name = explode(' ', $name);
		array_shift($name);
		$name = implode(' ', $name);
		$name = ucfirst($name);

		$filename_short = explode('-', $filename);
		array_shift($filename_short);
		$filename_short = implode('-', $filename_short);

    echo '<div class="col-6 col-lg-4">';
    echo '<a href="' . url($seg1 . '/' . $seg2 . '/' . $page_short . '/' . $filename_short) . '" class="card-block-link" title="' . $name . '">';
    echo '<div class="card card-block text-center rounded-0">';
    echo '<i class="icon mi ' . config('doc-icons.' . $filename_short, 'insert_drive_file') . ' block-icon"></i>';
    echo '<h4>' . $name . '</h4>';
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