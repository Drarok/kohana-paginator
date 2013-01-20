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
	 * Factory method for creating a Paginator.
	 *
	 * @param ORM $model Model to use.
	 *
	 * @return Paginator
	 */
	static public function factory(ORM $model)
	{
		return new Paginator($model);
	}

	/**
	 * Constructor.
	 *
	 * @param ORM $model Model to use.
	 */
	public function __construct(ORM $model)
	{
		$this->_model = $model;
		$this->_per_page = (int) Kohana::$config->load('paginator.per-page', 25);
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
	 * Returns how many pages in total there are.
	 *
	 * @return int
	 */
	public function getPageCount()
	{
		return (int) ceil($this->_model->reset(FALSE)->count_all() / $this->_per_page);
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
	 * Render the Paginator into HTML.
	 *
	 * @return string
	 */
	public function render()
	{
		$pages = $this->getPageCount();

		$html = '<div>' . PHP_EOL;
		for ($page = 1; $page <= $pages; ++$page) {
			$html .= "\t" . $page . ', ';
		}
		$html .= '</div>' . PHP_EOL;
		return $html;
	}
}
