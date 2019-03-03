<?php

namespace GridBundle\Components\Grid;

use Doctrine\ORM\QueryBuilder;

/**
 * Interface, který by měla implementovat každá Repository, ze které se budou brát data pro grid
 * @package Ensis\Bundle\CmsCoreBundle\Components\Grid
 */
interface IGridRepository {

    /**
     * Metoda, která vrací základní query pro grid
     * @return QueryBuilder
     */
    public function getQueryForGrid() : QueryBuilder;

}