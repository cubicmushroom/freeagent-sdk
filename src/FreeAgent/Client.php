<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 26/01/15
 * Time: 18:21
 */

namespace FreeAgent;


use FreeAgent\Exception\Module\ModuleAlreadyRegisteredException;
use FreeAgent\Module\Module;

class Client
{
    /**
     * @var APIConfig
     */
    protected $config;

    /**
     * @var Module[]
     */
    protected $modules;


    /**
     * @param APIConfig $config
     */
    function __construct(APIConfig $config)
    {
        $this->setConfig($config);

        $this->addModule(new AuthModule($this, $config));
    }


    /**
     * @return APIConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param APIConfig $config
     */
    public function setConfig(APIConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return Module
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * @param Module[] $modules
     */
    public function setModules(array $modules)
    {
        $this->modules = $modules;
    }

    /**
     * @param Module $module
     *
     * @return $this
     *
     * @throws ModuleAlreadyRegisteredException
     */
    public function addModule(Module $module)
    {
        $modules = $this->getModules();

        if (isset($modules[$module->getName()])) {
            throw ModuleAlreadyRegisteredException::create($module);
        }

        $modules[$module->getName()] = $module;

        return $this;
    }

    /**
     * @param Module|string $module Module object or name of module to be removed
     *
     * @return $this
     */
    public function removeModule($module)
    {
        if ($module instanceof Module) {
            $module = $module->getName();
        }
        $modules = $this->getModules();

        if (!isset($modules[$module])) {
            throw ModuleNotRegisteredException::create($module);
        }

        unset($modules[$module]);

        return $this;
    }
}