<?php

namespace bot;
/**
 *
 */


class db
{

    protected $dbh;

    public $className = 'stdClass';



    public function __construct()
    {
        $config = include __DIR__ . '/../config.php';

        $this->dbh = new \PDO($config['driver'] . ':host=' . $config['host'] . ';dbname=' . $config['dbname'] , $config['user'] , $config['password']);

        //var_dump($this->dbh);

    }


    public function execute($sql, $params=[] )
    {
        $sth = $this->dbh->prepare($sql);
        return $sth->execute($params);

    }

    public function query($sql)
    {
        $sth = $this->dbh->prepare($sql);
        $res = $sth->execute();
        if (false !== $res){
            return $sth->fetchAll(\PDO::FETCH_ASSOC);
        }
        return [];
    }

}
