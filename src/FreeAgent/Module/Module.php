<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 27/01/15
 * Time: 13:23
 */

namespace FreeAgent\Module;


use FreeAgent\APIConfig;
use FreeAgent\Client;

abstract class Module
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var APIConfig
     */
    protected $config;


    // ---
    // Final
    /**
     *
     * @param Client $client
     * @param APIConfig $config
     */
    final public function __construct(Client $client, APIConfig $config)
    {
        $this->setConfig($config);
        $this->setClient($client);
    }


    // ---
    // Abstract methods
    // ---

    /**
     * Returns the name of the module
     *
     * @return string
     */
    abstract public function getName();


    // ---
    // Getters & Setters
    // ---

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
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
    public function setConfig($config)
    {
        $this->config = $config;
    }
}