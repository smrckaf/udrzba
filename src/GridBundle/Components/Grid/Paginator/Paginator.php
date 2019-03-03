<?php

namespace GridBundle\Components\Grid\Paginator;

/**
 * Pouze reprezentativní třída pro jednodušší použití KNP\Paginatoru v našem gridu
 * Class Paginator
 * @package Ensis\Bundle\CmsCoreBundle\Components\Grid\Paginator
 */
class Paginator
{

    const UNLIMITED = -1;

    /** @var  \Knp\Component\Pager\Paginator */
    private $knpPaginator;

    /** @var  int */
    private $page = 1;

    /** @var  int */
    private $limit = self::UNLIMITED;

    public function __construct(\Knp\Component\Pager\Paginator $paginator, int $page = 1, int $limit = self::UNLIMITED)
    {
        $this->knpPaginator = $paginator;
        $this->limit = $limit;
        $this->page = $page;
    }

//    public function setPage(int $page)
//    {
//        $this->page = $page;
//        return $this;
//    }
//
//    public function setLimit(int $limit)
//    {
//        $this->limit = $limit;
//        return $this;
//    }

    /**
     * @return \Knp\Component\Pager\Paginator
     */
    public function getKnpPaginator() : \Knp\Component\Pager\Paginator
    {
        return $this->knpPaginator;
    }

    /**
     * @return int
     */
    public function getPage() : int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getLimit() : int
    {
        return $this->limit;
    }


}