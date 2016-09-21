<?php

namespace App\System;

class Config
{
    private $configArray  = [];
    private $configFolder = null;

    public function getConfigFolder()
    {
        return $this->configFolder;
    }

    public function setConfigFolder($configFolderParam)
    {
        if (!$configFolderParam) {
            throw new \Exception("Empty config folder path");
        }
        $configFolder = realpath($configFolderParam);
        if ($configFolder != false && is_dir($configFolder) && is_readable($configFolder)) {
            $this->configArray  = [];
            $this->configFolder = $configFolder.DIRECTORY_SEPARATOR;
        } else {
            throw new \Exception("Config directory read error: ".$configFolderParam);
        }
    }

    public function includeConfigFile($path)
    {
        if (!$path) {
            throw new \Exception("Error include CNF");
        }
        $file = realpath($path);
        if ($file != false && is_file($file) && is_readable($file)) {
            $_basename                     = explode('.php', basename($file))[0];
            $this->configArray[$_basename] = include $file;
        } else {
            //@TODO
            throw new \Exception("Config file read error: ".$path);
        }
    }

    public function __get($name)
    {
        if (!isset($this->configArray[$name])) {
            $this->includeConfigFile($this->configFolder.$name.'.php');
        }
        if (array_key_exists($name, $this->configArray)) {
            return $this->configArray[$name];
        }
        return null;
    }
}
