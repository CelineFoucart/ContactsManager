<?php

namespace Tests;

use \PDO;
use PHPUnit\Framework\TestCase;
use Tests\Database\helpers\DatabaseFake;
use Phinx\Config\Config;
use Phinx\Migration\Manager;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;

class DatabaseTestCase extends TestCase
{
    /**
     * @var PDO
     */
    protected PDO $pdo;
    /**
     * @var Manager
     */
    protected Manager $manager;

    protected function setUp(): void
    {
        $this->pdo = DatabaseFake::getPdo();
        $this->manager = $this->getManager($this->pdo);
        $this->manager->migrate('test');
        $this->manager->seed('test');
    }

    protected function getManager(\PDO $pdo): Manager
    {
        $configArray = require 'phinx.php';
        $configArray['environments']['test'] = [
            'adapter' => 'sqlite',
            'connection' => $pdo
        ];
        $config = new Config($configArray);
        return new Manager($config, new StringInput(' '), new NullOutput());
    }
}
