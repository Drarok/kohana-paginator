<div class="paginator paginator-default">
<?php

for ($page = 1; $page <= $paginator->getPageCount(); ++$page):
	$class = 'paginator-link';

	if ($page == $paginator->getPage()) {
		$class .= ' paginator-current';
	}

	$attr = array('class' => $class);
	echo "\t", HTML::anchor($paginator->getPageURL($page), $page, $attr), PHP_EOL;
endfor;
?>
</div>