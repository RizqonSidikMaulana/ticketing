<?php

namespace App\Config;

use Exception;
use PDO;

/**
 * Class Config
 */
class Config
{
    /**
     * @param string $key
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function getParameter(string $key)
    {
        if (!$key) {
            throw new Exception("Parameter '$key' is missing.");
        }

        $parameterLists = require __DIR__ . '/../params.php';

        return $parameterLists[$key];
    }

    public function dbConnection()
    {
        $param = $this->getParameter('db');
        
        return new PDO(
            $param['db_driver'].':host='.$param['host'].';dbname='.$param['db_name'],
            $param['username'],
            $param['password']);
    }
}