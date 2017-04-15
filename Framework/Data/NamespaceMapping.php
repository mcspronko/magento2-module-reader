<?php

namespace Pronko\Magento2ModuleReader\Data;

class NamespaceMapping
{
    /**
     * @var array
     */
    private $mappings = [];

    /**
     * NamespaceMapping constructor.
     * @param array $mappings
     */
    public function __construct(array $mappings = [])
    {
        $this->mappings = $mappings;
    }

    /**
     * @return array
     */
    public function get()
    {
        return $this->mappings;
    }
}