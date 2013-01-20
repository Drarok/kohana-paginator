# Kohana Paginator

## About

This module is a dead simple paginator for ORM-based pages.

## Usage

Create an ORM object, filtering and sorting as required, but don't call find_all on it. Create a Paginator object, passing the ORM object, and pass the Paginator to your view. The `getItems()` method returns a Database_Result containing the paginated items. You can call the `render()` method to get links to the pages in your view.

### Controller Code

	// Start off the query, apply filters, things like that.
	$news = ORM::factory('News')
		->with('category')
		->where('news.public', '=', 1)
		->order_by('news.post_date')
	;

	$paginator = Paginator::factory($news);
	if ($page = (int) $this->request->query('page')) {
		$paginator->setPage($page);
	}

	$view->paginator = $paginator;
	$view->news = $paginator->getItems();

### View Code

	echo $paginator->render();
	foreach ($paginator->getItems() as $news) …