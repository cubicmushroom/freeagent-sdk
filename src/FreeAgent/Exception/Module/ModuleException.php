<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 27/01/15
 * Time: 13:40
 */

namespace FreeAgent\Exception\Module;


use FreeAgent\Exception\Exception;
use FreeAgent\Exception\InvalidArgumentException;
use FreeAgent\Module\Module;

class ModuleException extends Exception
{
    /**
     * @param Module|string $module
     * @param string        $message
     * @param int           $code
     * @param null          $previousException
     *
     * @return static
     */
    public static function create($module, $message = '', $code = 0, $previousException = null)
    {
        $e = new static($message, $code, $previousException);

        if ($module instanceof Module) {
            $e->setModule($module);
        } else {
            $e->setModuleName($module);
        }

        return $e;
    }


    /**
     * @var Module
     */
    protected $module;

    /**
     * @var string
     */
    protected $moduleName;

    /**
     * @return Module
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @param Module $module
     */
    public function setModule(Module $module)
    {
        $this->module = $module;
        $this->setModuleName($module->getName());
    }

    /**
     * @return string
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * @param string $moduleName
     *
     * @throws InvalidArgumentException
     */
    public function setModuleName($moduleName)
    {
        if (!is_string($moduleName)) {
            throw new InvalidArgumentException('$module name must be a string');
        }

        $this->moduleName = $moduleName;
    }
}