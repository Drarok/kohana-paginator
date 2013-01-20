<?php

abstract class Kohana_Paginator
{
	/**
	 * Model that we are paginating.
	 *
	 * @var ORM
	 */
	protected $_model;

	/**
	 * Total number of items to paginate.
	 *
	 * @var int
	 */
	protected $_total_items = 0;

	/**
	 * Number of items per page.
	 *
	 * @var int
	 */
	protected $_per_page;

	/**
	 * The current page.
	 *
	 * @var int
	 */
	protected $_page = 1;

	/**
	 * Paginator view to use when rendering.
	 *
	 * @var string
	 */
	protected $_view = 'paginator/default';

	/**
	 * URL base for links.
	 *
	 * @var string
	 */
	protected $_url = '';

	/**
	 * Factory method for creating a Paginator.
	 *
	 * @param ORM   $model Model to use.
	 * @param mixed $url   URL to use as a base for links, defaulting to current.
	 *
	 * @return Paginator
	 */
	static public function factory(ORM $model, $url = NULL)
	{
		return new Paginator($model, $url);
	}

	/**
	 * Constructor.
	 *
	 * @param ORM   $model Model to use.
	 * @param mixed $url   URL to use as a base for links, defaulting to current.
	 */
	public function __construct(ORM $model, $url = NULL)
	{
		$this->_model = $model;
		$this->_per_page = (int) Kohana::$config->load('paginator.per-page', 25);
		if ($url === NULL) {
			$url = Request::current()->uri();
		}
		$this->_url = $url;
		$this->_total_items = $model->reset(FALSE)->count_all();
	}

	/**
	 * Setter for the page property.
	 *
	 * @param int $page Page number.
	 */
	public function setPage($page)
	{
		$this->_page = (int) $page;
	}

	/**
	 * Getter for the page property.
	 *
	 * @return int
	 */
	public function getPage()
	{
		return $this->_page;
	}

	/**
	 * Setter for the view name property.
	 *
	 * @param string $view View name.
	 *
	 * @return void
	 */
	public function setView($view)
	{
		$this->_view = $view;
	}

	/**
	 * Getter for the view name property.
	 *
	 * @return string
	 */
	public function getView()
	{
		return $this->_view;
	}

	/**
	 * Returns how many pages in total there are.
	 *
	 * @return int
	 */
	public function getPageCount()
	{
		return (int) ceil($this->_total_items / $this->_per_page);
	}

	/**
	 * Getter for a Database_Result containing the paginated items.
	 *
	 * @return Database_Result
	 */
	public function getItems()
	{
		return $this->_model
			->limit($this->_per_page)
			->offset($this->_per_page * ($this->_page - 1))
			->reset(FALSE)
			->find_all();
	}

	/**
	 * Generate a URL to the given page number.
	 *
	 * @param int $page Page number.
	 *
	 * @return string
	 */
	public function getPageURL($page)
	{
		if ($page == 1) {
			// No need for the parameter for page 1.
			return $this->_url;
		} else {
			// Add on the page parameter.
			return $this->_url . '?page=' . (int) $page;
		}
	}

	/**
	 * Render the Paginator into HTML.
	 *
	 * @return string
	 */
	public function render()
	{
		if ($this->getPageCount() == 1) {
			return NULL;
		}
		$view = View::factory($this->getView());
		$view->paginator = $this;
		return $view->render();
	}
}
