<?php

namespace GridBundle\Components\Grid;

/**
 * Tlačítko, které se zobrazí pro každý záznam v gridu.
 */
class Button
{
    /** Tlačítko pro přidání položky */
    const BTN_ADD = 'add';
    /** Tlačítko pro editaci položky v gridu */
    const BTN_EDIT = 'edit';
    /** Tlačítko pro smazání položky v gridu */
    const BTN_DELETE = 'delete';


    const BLOCK_HEADER = 'header';
    const BLOCK_ROW = 'row';
    const BLOCK_BOTTOM = 'bottom';


    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $action;
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $block;

    /**
     * @var array
     */
    private $actionExtraParam;

    /**
     * @var string|array
     */
    private $template = null;

    /** @var  string */
    private $title = '';



    /**
     * Button constructor.
     *
     * @param string $name
     * @param string $type
     * @param string $action
     * @param string $block
     */
    public function __construct(string $name, string $type, string $action, string $block = null)
    {
        $this->name = $name;
        $this->action = $action;
        $this->type = $type;
        $this->block = $block;
        $this->actionExtraParam = [];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getBlock(): ?string
    {
        return $this->block;
    }

    /**
     * Vrátí pole parametrů k akci daného buttonu.
     *
     * @return array
     */
    public function getActionExtraParams()
    {
        return $this->actionExtraParam;
    }

    /**
     * @return string
     */
    public function getExtraActionParamsToString()
    {
        $out = '';
        $first = true;
        foreach ($this->actionExtraParam as $key => $value) {
            $out .= $first ? '{' : ',';
            $out .= "'".$key."':".$value;
            $first = false;
        }
        $out .= '}';

        return $out;
    }

    /**
     * Přidá parametr k dané akci buttonu.
     *
     * @param string $key
     * @param $value
     *
     * @return $this
     */
    public function addActionExtraParam(string $key, $value)
    {
        $this->actionExtraParam[$key] = $value;

        return $this;
    }

    /**
     * @return array|string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param array|string $template
     *
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }


    /**
     * @return string
     */
    public function getTitle() : ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(?string $title)
    {
        $this->title = $title;
        return $this;
    }
}
