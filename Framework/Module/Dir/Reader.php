<?php

namespace Pronko\Magento2ModuleReader\Framework\Module\Dir;

use Magento\Framework\Module\Dir;
use Magento\Framework\Module\Dir\Reader as FrameworkReader;

/**
 * Class Reader
 *
 * This class provides support for custom module namespace
 * to have all controllers located in the Girosolution\Girocheckout\Magento directory
 *
 * @package     Girosolution\Girocheckout\Magento\Framework\Module\Dir
 */
class Reader extends FrameworkReader
{
    /**
     * @var array
     */
    private $namespaceMappings;

    /**
     * @return array
     */
    public function getActionFiles()
    {
        $actions = [];
        foreach ($this->modulesList->getNames() as $moduleName) {
            $actionDir = $this->getModuleDir(Dir::MODULE_CONTROLLER_DIR, $moduleName);
            if (!file_exists($actionDir)) {
                continue;
            }
            $dirIterator = new \RecursiveDirectoryIterator($actionDir, \RecursiveDirectoryIterator::SKIP_DOTS);
            $recursiveIterator = new \RecursiveIteratorIterator($dirIterator, \RecursiveIteratorIterator::LEAVES_ONLY);
            /* @see \Magento\Framework\Module\Dir\Reader::getActionFiles() method */
            $namespace = $this->getNamespace($moduleName);
            /** @var \SplFileInfo $actionFile */
            foreach ($recursiveIterator as $actionFile) {
                $actionName = str_replace('/', '\\', str_replace($actionDir, '', $actionFile->getPathname()));
                $action = $namespace . "\\" . Dir::MODULE_CONTROLLER_DIR . substr($actionName, 0, -4);
                $actions[strtolower($action)] = $action;
            }
        }
        return $actions;
    }

    /**
     * @param string $moduleName
     * @return string
     */
    private function getNamespace($moduleName)
    {
        return str_replace('_', '\\', $this->getNamespaceMapping($moduleName));
    }

    /**
     * @param string $moduleName
     * @return string
     */
    private function getNamespaceMapping($moduleName)
    {
        if (!is_array($this->namespaceMappings)) {
            $this->namespaceMappings = \Magento\Framework\App\ObjectManager::getInstance()
                ->get('Pronko\Magento2ModuleReader\Data\NamespaceMapping')->get();
        }
        return isset($this->namespaceMappings[$moduleName]) ? $this->namespaceMappings[$moduleName] : $moduleName;
    }
}
