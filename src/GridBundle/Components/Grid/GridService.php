<?php

namespace GridBundle\Components\Grid;

/**
 * Service pro vykreslení gridu v šabloně.
 */
class GridService
{
    /**
     * @var \Twig_Environment
     */
    private $twigEnvironment;

    public function __construct(\Twig_Environment $twigEnvironment)
    {
        $this->twigEnvironment = $twigEnvironment;
    }

    public function render(Grid $grid)
    {
        $filterForm = null;
        if ($grid->getFilter() instanceof \GridBundle\Components\Grid\Filter\Filter) {
            $filterForm = $grid->getFilter()->createForm()->createView();
        }
        return $this->twigEnvironment->render('@Grid/components/grid/grid.html.twig', [
            'filterForm' => $filterForm,
            'grid' => $grid,
        ]);
    }
}
