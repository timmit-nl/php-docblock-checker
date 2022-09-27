<?php

namespace TiMMiT\PhpDocBlockChecker\DocblockParser;

class ParamTag extends Tag
{
    /**
     * @var string
     */
    private $type;
    /**
     * @var string
     */
    private $var;
    /**
     * @var string
     */
    private $desc;

    /**
     * ParamTag constructor.
     * @param string $name
     * @param string $value
     */
    public function __construct($name, $value)
    {
        parent::__construct($name, $value);

        $parts = preg_split('/\s+/', $value, 3);

        if ($parts === false) {
            return;
        }

        $this->type = isset($parts[0]) ? $parts[0] : '';
        $this->var = isset($parts[1]) ? $parts[1] : '';
        $this->desc = isset($parts[2]) ? $parts[2] : '';

        if (\strpos($this->type, '$') === 0) {
            $this->desc = $this->var . ' ' . $this->desc;
            $this->var  = $this->type;
            $this->type = '';
        }

        if (empty($this->var) && !empty($this->type)) {
            $this->var  = $this->type;
            $this->type = '';
        }

        $this->type = \trim($this->type);
        $this->var  = \trim($this->var);
        $this->desc = \trim($this->desc);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getVar()
    {
        return $this->var;
    }

    /**
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }
}
