<?php 

namespace App\Model;

use \App\Model\Database;
use \App\Model\Exception\ConfigException;

class ModelFactory {

	public static $factory;
    public static $configs = [];
	
	private $pdo;

	public static function getInstance(array $configs = [])
	{
		if (self::$factory === null) {
			self::$factory = new ModelFactory();
		}
        if (empty(self::$configs)) {
            self::$configs = $configs;
        }
		return self::$factory;
	}

	public function createPDO()
	{
		if ($this->pdo === null) {
			try {
				$dbname = self::$configs['dbname'];
                $user = self:: $configs['user'];
                $password = self::$configs['password'];
                $host = self::$configs['server'];
				$pdo = new Database($dbname, $user, $password, $host);
				$this->pdo = $pdo->getPDO();
			} catch (ConfigException $e) {
				$e->getMessage();
			}
		}
		return $this->pdo;
	}

	public function getManager($tableName)
	{
		$pdo = $this->createPDO();
        $tableName = ucfirst($tableName);
        $className = "App\Model\Manager\\" . $tableName . "Manager";
		return new $className($pdo);
	}

}
