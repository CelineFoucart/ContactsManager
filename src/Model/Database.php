<?php

namespace App\Model;

use \PDO;

/**
 * Creates a new connexion to the database.
 */
class Database
{ 
	
	protected $dbname;
    protected $user;
    protected $password;
    protected $host;
    protected $pdo; 
    protected $datatype = "mysql";

    public function __construct(string $dbname, string $user, string $password, string $host = 'localhost')
    {
        $this->dbname = $dbname;
        $this->user = $user;
        $this->password = $password;
        $this->host = $host;
        $this->pdo = $this->connect();
    }

    /**
     * create a new instance of PDO
     * 
     * @return PDO
     */
	private function connect(): PDO 
	{
		$pdo = new PDO($this->datatype.':host='.$this->host.';dbname='. $this->dbname .';charset=utf8', $this->user, $this->password); 
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		return $pdo;
	}

	/**
	 * get value of $pdo
	 * 
	 * @return PDO
	 */
	public function getPDO(): PDO
	{
		return $this->pdo;
	}

	/**
	 * return the last insert id
	 *
	 * @return int
	 */
	public function getLastInsertId(): int
	{
		return (int)$this->pdo->lastInsertId();
	}
}