<?php
/**
 * Copyright © 2017 Max Pronko. All rights reserved.
 * See LICENSE for license details.
 */
namespace Pronko\Magento2ModuleReader\Test\Data;

use Pronko\Magento2ModuleReader\Data\NamespaceMapping;

/**
 * Class NamespaceMappingTest
 * @package     Pronko\Magento2ModuleReader\Test\Data
 */
class NamespaceMappingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NamespaceMapping | \PHPUnit_Framework_MockObject_MockObject
     */
    private $object;

    /**
     * @dataProvider namespaceMappingsDataProvider
     */
    public function testGet($input, $expected)
    {
        $this->object = new NamespaceMapping($input);

        $this->assertEquals($expected, $this->object->get());
    }

    /**
     * @return array
     */
    public function namespaceMappingsDataProvider()
    {
        return [
            [
                [],
                []
            ],
            [
                ['namespace'],
                ['namespace']
            ],
            [
                ['Vendor_PackageName' => 'Vendor_PackageName_Magento'],
                ['Vendor_PackageName' => 'Vendor_PackageName_Magento']
            ]
        ];
    }
}
