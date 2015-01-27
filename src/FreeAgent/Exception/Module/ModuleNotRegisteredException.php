<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 27/01/15
 * Time: 13:53
 */

namespace FreeAgent\Exception\Module;


use FreeAgent\Module\Module;

class ModuleNotRegisteredException extends ModuleException
{
    /**
     * Builds exception and sets default message
     *
     * @param Module|string $module
     * @param string        $message
     * @param int           $code
     * @param null          $previousException
     *
     * @return static
     */
    public static function create($module, $message = '', $code = 0, $previousException = null)
    {
        if ($module instanceof Module) {
            $moduleName = $module->getName();
        } else {
            $moduleName = $module;
        }

        if (empty($message)) {
            $message = "Module {$moduleName} is already registered";
        }

        return parent::create($module, $message, $code, $previousException);
    }
}