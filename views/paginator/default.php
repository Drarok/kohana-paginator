<div class="paginator paginator-default">
<?php

for ($page = 1; $page <= $paginator->getPageCount(); ++$page):
	$class = 'paginator-link';

	if ($page == $paginator->getPage()) {
		$class .= ' paginator-current';
	}

	echo "\t", HTML::anchor($paginator->getPageURL($page), $page, array('class' => $class)), PHP_EOL;
endfor;
?>
</div>