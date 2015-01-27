<?php namespace Immap\Watchkeeper\Support\Pagination;
use Countable;
use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use \Illuminate\Html\HtmlBuilder as HtmlBuilder;
use \Illuminate\Support\Collection as Collection;
use \Illuminate\Support\Contracts\JsonableInterface;
use \Illuminate\Support\Contracts\ArrayableInterface;
use Immap\Watchkeeper\Support\Pagination\Interfaces\PaginatorInterface as PaginatorInterface;
class JQMPaginator implements ArrayableInterface, ArrayAccess, Countable, IteratorAggregate, JsonableInterface {
    protected $html;
    protected $paginatorParams;

    protected $items;
    protected $currentPage;
    protected $nextPage;
    protected $lastPage;
    protected $previousPage;

    protected $currentPageURL;
    protected $nextPageURL;
    protected $lastPageURL;
    protected $previousPageURL;

    protected $total;
    protected $perPage;
    protected $routeURL;


    public function __construct(HtmlBuilder $html)
    {
        $this->html = $html;
    }

    public function make($page, array $items, $total, $perPage, $routeURL)
    {
        $this->items = $items;
        $this->currentPage = (int)$page;
        $this->total = (int) $total;
        $this->perPage = (int) $perPage;
        $this->routeURL = $routeURL;
        return $this->setupPaginationContext();
    }

    /**
     * Setup the pagination context (current next page, previous page).
     *
     * @return Immap\Watchkeeper\Pagination\JQMPaginator
     */
    public function setupPaginationContext()
    {
        $this->calculatePage();
        return $this;
    }

    public function link()
    {
        extract($this->paginatorParams);
        $htmlString = '';
        if (isset($previousPage) && isset($nextPage))
        {
            $htmlString  = '<div class="ui-grid-a">';
            $htmlString .= $this->html->linkRoute($routeURL, 'Previous', array('page' => $previousPage) , array('data-ajax'=> "false" , 'class' => "ui-block-a ui-btn ui-icon-arrow-l ui-btn-icon-left"));
            $htmlString .= $this->html->linkRoute($routeURL, 'Next', array('page' => $nextPage), array('data-ajax'=> "false" , 'class' => "ui-block-b ui-btn ui-icon-arrow-r ui-btn-icon-right"));
            $htmlString .= '</div>';
        } elseif (!isset($previousPage) || !isset($nextPage)) {
            $htmlString  = '<div class="ui-grid-solo">';
            $htmlString .= isset($previousPage) ? $this->html->linkRoute($routeURL, 'Previous', array('page' => $previousPage) , array('data-ajax'=> "false" , 'class' => "ui-btn ui-icon-arrow-l ui-btn-icon-left")) : '';
            $htmlString .= isset($nextPage) ? $this->html->linkRoute($routeURL, 'Next', array('page' => $nextPage), array('data-ajax'=> "false" ,'class' => "ui-btn ui-icon-arrow-r ui-btn-icon-right") ) : '';
            $htmlString .= '</div>';
        }
        return $htmlString;
    }

    public function calculatePage()
    {
        $this->lastPage = (int) ceil($this->total / $this->perPage);
        $this->paginatorParams = array('routeURL' => $this->routeURL);
        if ($this->currentPage >= $this->lastPage && $this->currentPage - 1 !== 0 && $this->total > 0)
        {
            $this->previousPage = $this->currentPage - 1;
            $this->paginatorParams['previousPage'] = $this->previousPage;
        }
        else
        {
            if ($this->currentPage !== $this->lastPage && $this->total != 0)
                $this->nextPage = $this->currentPage + 1;
                $this->paginatorParams['nextPage'] = $this->nextPage;
            if ($this->currentPage - 1 != 0 )
                $this->previousPage = $this->currentPage - 1;
                $this->paginatorParams['previousPage'] = $this->previousPage;
        }
    }

    public function getRoutURL()
    {
        return $this->routeURL;
    }

    /**
     * Get the current page for the request.
     *
     * @param  int|null  $total
     * @return int
     */
    public function getCurrentPage($total = null)
    {
        if (is_null($total))
        {
            return $this->currentPage;
        }
        else
        {
            return min($this->currentPage, (int) ceil($total / $this->perPage));
        }
    }

    /**
     * Get the last page that should be available.
     *
     * @return int
     */
    public function getNexPage()
    {
        return $this->nextPage;
    }

    /**
     * Get the last page that should be available.
     *
     * @return int
     */
    public function getPreviousPage()
    {
        return $this->previousPage;
    }

    /**
     * Get the last page that should be available.
     *
     * @return int
     */
    public function getLastPage()
    {
        return $this->lastPage;
    }

    /**
     * Get the number of items to be displayed per page.
     *
     * @return int
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * Get the items being paginated.
     *
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set the items being paginated.
     *
     * @param  mixed  $items
     * @return void
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * Get the total number of items in the collection.
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Get a collection instance containing the items.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCollection()
    {
        return new Collection($this->items);
    }

    /**
     * Get an iterator for the items.
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /**
     * Determine if the list of items is empty or not.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->items);
    }

    /**
     * Get the number of items for the current page.
     *
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Determine if the given item exists.
     *
     * @param  mixed  $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * Get the item at the given offset.
     *
     * @param  mixed  $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->items[$key];
    }

    /**
     * Set the item at the given offset.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->items[$key] = $value;
    }

    /**
     * Unset the item at the given key.
     *
     * @param  mixed  $key
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->items[$key]);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'total' => $this->total, 'per_page' => $this->perPage,
            'current_page' => $this->currentPage, 'last_page' => $this->lastPage,
            'previous_page' => $this->previousPage,'next_page' => $this->nextPage,
            'url' => $this->html->linkRoute($this->routeURL),
            'data' => $this->getCollection()->toArray(),
        );
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Call a method on the underlying Collection
     *
     * @param string $method
     * @param array  $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array(array($this->getCollection(), $method), $arguments);
    }

}
