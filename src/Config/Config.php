<?php
namespace Connfetti\INI\Config;

use Connfetti\IO\Base\IOFactory;
use Connfetti\IO\Reader\INIReader;

class Config
{
    public static $VERSION = '0.1.0';

    private $cfgfile;
    private $cfgpath;

    private $cfgdata;

    public function __construct(string $file, string $path = './')
    {
        $this->cfgfile = $file;
        $this->cfgpath = $path;

        $this->init();
    }

    public function __set($name, $value)
    {
        throw new \Exception("Config is readonly!");
    }

    public function __get($name)
    {
        if(isset($this->cfgdata->$name)) {
            return $this->cfgdata->$name;
        }
        return null;
    }

    public function __isset($name)
    {
        return isset($this->cfgdata->$name);
    }

    private function init()
    {
        /** @var INIReader $oReader */
        $oReader = IOFactory::createReader(IOFactory::FILE_INI, $this->cfgpath . "/" . $this->cfgfile);
        $this->cfgdata = $this->setPropertiesByCfg($this->setCfgArray($oReader->getContent()));
    }

    private function setCfgArray($cfgdata)
    {
        $tree = array();
        foreach($cfgdata as $prop => $iniset) {
            $propparts = explode(".", $iniset[0]);
            $val = $iniset[1];

            foreach(array_reverse($propparts) as $part) {
                $val = array($part => $val);
            }
            $tree = array_merge_recursive($tree, $val);
        }

        return $tree;
    }

    private function setPropertiesByCfg($cfgtree_ar)
    {
        if(!is_array($cfgtree_ar)) {
            return $cfgtree_ar;
        }

        $obj = new \stdClass();
        if(count($cfgtree_ar) > 0) {
            foreach($cfgtree_ar as $node => $item) {
                $obj->$node = $this->setPropertiesByCfg($item);
            }
            return $obj;
        } else {
            return null;
        }
    }
}
