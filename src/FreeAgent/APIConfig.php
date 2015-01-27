<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 26/01/15
 * Time: 12:24
 */

namespace FreeAgent;


class APIConfig
{
    const SANDBOX_BASE_URI = 'https://api.sandbox.freeagent.com/v2/';
    const LIVE_BASE_URI = 'https://api.freeagent.com/v2/';


    /**
     * @var string
     */
    protected $environment;

    /**
     * @var string
     */
    protected $clientID;


    /**
     * @var string
     */
    protected $clientSecret;

    /**
     * Directory to store config values in
     * @var \SplFileInfo
     */
    protected $dir;

    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @var string
     */
    protected $refreshToken;


    /**
     * @param array $options
     */
    function __construct($clientID, $clientSecret, array $options = [])
    {
        $defaults = [
            'environment' => null,
            'dir' => null,
        ];
        $options = array_intersect_key(array_merge($defaults, $options), $defaults);

        $this->setClientID($clientID);
        $this->setClientSecret($clientID);

        if (!is_null($options['environment'])) {
            $this->setEnvironment($options['environment']);
        }

        if (!is_null($options['dir'])) {
            $this->setDir($options['dir']);
        }
    }


    /**
     * Returns the App's Client ID
     * @return string
     */
    public function getClientId()
    {
        return $this->clientID;
    }


    /**
     * @param string $clientID
     */
    public function setClientID($clientID)
    {
        $this->clientID = $clientID;
    }


    /**
     * Returns the App's Client Secret
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }


    /**
     * @param string $clientSecret
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }


    /**
     * @throws \InvalidArgumentException is an unknown environment is set
     * @return string
     */
    public function getBaseUri()
    {
        $environment = $this->getEnvironment();
        switch ($environment) {
            case 'sandbox':
                return self::SANDBOX_BASE_URI;
            case 'live':
                return self::LIVE_BASE_URI;
            default:
                throw new \InvalidArgumentException("Unknown environment ($environment)");
        }
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param string $environment
     *
     * @throws \InvalidArgumentException is an unknown environment is passed
     *
     * @return $this
     */
    public function setEnvironment($environment)
    {
        $allowedEnvironments = ['live', 'sandbox'];
        if (!in_array($environment, $allowedEnvironments)) {
            throw new \InvalidArgumentException(
                "Unknown $environment value ($environment).  Must be one of " . implode(', ', $allowedEnvironments)
            );
        }

        $this->environment = $environment;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAccessToken()
    {
        $file = $this->getConfigFile('accessToken');

        if (!is_file($file)) {
            return null;
        }

        return file_get_contents($file);
    }

    /**
     * @param string $accessToken
     */
    public function setAccessToken($accessToken)
    {
        if (false === file_put_contents($this->getConfigFile('accessToken'), $accessToken)) {
            throw new \RuntimeException('Unable to save access token');
        }
    }

    /**
     * @return string|null
     */
    public function getRefreshToken()
    {
        $file = $this->getConfigFile('refreshToken');

        if (!is_file($file)) {
            return null;
        }

        return file_get_contents($file);
    }

    /**
     * @param string $refreshToken
     */
    public function setRefreshToken($refreshToken)
    {
        if (false === file_put_contents($this->getConfigFile('refreshToken'), $refreshToken)) {
            throw new \RuntimeException('Unable to save refresh token');
        }
    }

    /**
     * @return \SplFileInfo
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * @param \SplFileInfo|string $dir
     */
    public function setDir($dir)
    {
        if (!$dir instanceof \SplFileInfo) {
            $dir = new \SplFileInfo($dir);
        }

        if (!$dir->isDir() || !$dir->isWritable()) {
            throw new \RuntimeException("Config directory ({$dir->getPathname()}) doesn't exists, or is not writable");
        }

        $this->dir = $dir;
    }

    /**
     * @param string $parameter Which parameter to get the file for
     *
     * @return string
     */
    protected function getConfigFile($parameter)
    {
        switch ($parameter) {
            case 'accessToken':
                $filename = 'access_token.txt';
                break;

            case 'refreshToken':
                $filename = 'refresh_token.txt';
                break;

            default:
                throw new \InvalidArgumentException("Unknown parameter ($parameter)");
        }

        return $this->getDir()->getPathname() . DS . $this->getEnvironment() . '_' . $filename;
    }
}