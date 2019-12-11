<div class="list-group">
	<a href="{{ url('' . $seg1 . '/' . $seg2) }}" class="list-group-item rounded-0<?php if (\Request::segment(3) == NULL) echo ' active';?>"><strong>{{ $doctype }}</strong></a>
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

		$selected = (\Request::segment(3) == $dirname) ? true : false;
		$active = (\Request::segment(3) == $dirname && \Request::segment(4) == NULL) ? ' active' : '';

		echo '<a href="' . url('' . $seg1 . '/' . $seg2 . '/' . $dirname) . '" class="list-group-item rounded-0' . $active . '"><strong>' . $chapter . '</strong></a>';

		// Check for subs
		$files = array_sort(File::files($dir), function($file) {
			return $file;
		});

		foreach ($files as $file) {
			$filename = basename($file);
			$filename = str_replace('.blade.php', '', $filename);
			$filename = str_replace('.md', '', $filename);
	
			if ($filename != 'index' && $filename != 'page' && ! starts_with($filename, '-'))
			{
				$filename = explode('-', $filename);
				array_shift($filename);
				$filename = implode('-', $filename);
				$name = str_replace('-', ' ', $filename);
				$name = ucfirst($name);

				$active = (\Request::segment(3) == $dirname && \Request::segment(4) == $filename) ? ' active' : '';
				$style = ($selected) ? '' : ' style="display:none"';
	
				echo '<a href="' . url('' . $seg1 . '/' . $seg2 . '/' . $dirname . '/' . $filename) . '" class="list-group-item rounded-0' . $active . '"' . $style . '> - ' . $name . '</a>';
			}
		}
	}
}
?>
</div>