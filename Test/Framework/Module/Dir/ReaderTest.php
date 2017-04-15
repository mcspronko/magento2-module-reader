<?php
/**
 * Copyright © 2017 Max Pronko. All rights reserved.
 * See LICENSE for license details.
 */
namespace Pronko\Magento2ModuleReader\Test\Framework\Module\Dir;

use Pronko\Magento2ModuleReader\Framework\Module\Dir\Reader;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\Module\ModuleListInterface;
use Magento\Framework\Module\Dir;

/**
 * Class NamespaceMappingTest
 * @package     Pronko\Magento2ModuleReader\Framework\Module\Dir
 */
class ReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Reader | \PHPUnit_Framework_MockObject_MockObject
     */
    private $object;

    /**
     * @var ModuleListInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $modulesList;

    /**
     * @var Dir | \PHPUnit_Framework_MockObject_MockObject
     */
    private $moduleDirs;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->modulesList = $this->getMock('Magento\Framework\Module\ModuleListInterface', [], [], '', false);
        $this->moduleDirs = $this->getMock('Magento\Framework\Module\Dir', [], [], '', false);

        $this->object = $objectManager->getObject(
            'Pronko\Magento2ModuleReader\Framework\Module\Dir\Reader',
            [
                'moduleList' => $this->modulesList,
                'moduleDirs' => $this->moduleDirs
            ]
        );
    }

    public function testGetActionFiles()
    {
        $moduleName = 'Vendor_Package';
        $actionDir = __DIR__ . '/TestVendor/Package/Magento';

        $moduleNames = [$moduleName];
        $this->modulesList->expects($this->any())
            ->method('getNames')
            ->willReturn($moduleNames);
        $this->moduleDirs->expects($this->once())
            ->method('getDir')
            ->with($moduleName, 'Controller')
            ->willReturn($actionDir);

        $this->object->getActionFiles();
    }
}
