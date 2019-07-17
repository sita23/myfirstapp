<?php

namespace Sevo\Helper;

class Paginator
{
    protected $page;
    protected $maxItemPerPage;
    protected $totalCount;

    public function __construct($page = 1, $maxItemPerPage = 10, $totalCount = 0)
    {

        if ($page < 1) {
            $page = 1;
        }

        if ($maxItemPerPage < 1) {
            $maxItemPerPage = 1;
        }

        if ($maxItemPerPage > 1000) {
            $maxItemPerPage = 1000;
        }

        if ($totalCount < 0) {
            $totalCount = 0;
        }

        $this->page = $page;
        $this->maxItemPerPage = $maxItemPerPage;
        $this->totalCount = $totalCount;
    }

    /**
     * @return mixed
     */
    public function getOffset()
    {
        return ($this->page - 1) * $this->maxItemPerPage;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->maxItemPerPage;
    }

}