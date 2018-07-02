<?php
header('content-type:text/html;charset=utf-8');

class PDOMysql
{
    private static $config = array(); // 设置参数， 配置信息
    private static $link = null; // 保存连接标识符
    private static $pconnect = false; // 是否开启长连接
    public static $dbVersion = null; // 是否开启长连接
    public static $connected = false; // 是否连接成功
    private static $PDOStatement = null; // 保存PDOStatement对象
    public static $queryStr = null; // 保存最后的操作
    private static $error = null; // 保存错误信息
    public static $lastInsertId = null; // 保存最后插入的数据的ID
    public static $numRows = 0; // 保存最后受影响的行数

    public function __construct($dbConfig = '')
    {
        $this->checkConfig($dbConfig);
        $this->connect();
    }

    /**
     * check config file is valid or not
     */
    private function checkConfig($dbConfig)
    {
        if (!class_exists('PDO')) {
            self::threw_exception('Not Support PDO!!!!');
        }
        if (!is_array($dbConfig)) {
            $dbConfig = array(
                'hostname' => DB_HOST,
                'username' => DB_USER,
                'password' => DB_PWD,
                'database' => DB_NAME,
                'port' => DB_PORT,
                'dbms' => DB_TYPE,
                'dsn' => DB_TYPE . ":host=" . DB_HOST . ";dbname=" . DB_NAME,
            );
        }
        if (empty($dbConfig['hostname'])) {
            self::throw_exception('Config File Not Found!!!');
        }
        self::$config = $dbConfig;
        if (empty(self::$config['params'])) {
            self::$config['params'] = array();
        }
    }

    /**
     * connect DB
     */
    private function connect()
    {
        if (!isset(self::$link)) {
            $configs = self::$config;
            if (self::$pconnect) {
                $configs['params'][constant("PDO::ATTR_PERSISTENT")] = true;
            }
            try {
                self::$link = new PDO($configs['dsn'], $configs['username'], $configs['password'], $configs['params']);
            } catch (PDOException $e) {
                self::throw_exception($e->getMessage());
            }
            if (!self::$link) {
                self::throw_exception('PDO Connection Failed!!!!');
                return false;
            }
            self::$link->exec('SET NAMES ' . DB_CHARSET);
            self::$dbVersion = self::$link->getAttribute(constant("PDO::ATTR_SERVER_VERSION"));
            self::$connected = true;
            unset($configs);
        }
    }

    /**
     * self define exception notice
     */
    public static function throw_exception($errMsg)
    {
        echo '<br>' . $errMsg . '<hr>';
    }

    /**
     * get all data
     */
    public static function getAll($sql = null)
    {
        if ($sql != null) {
            self::query($sql);
        }
        $result = self::$PDOStatement->fetchAll(constant("PDO::FETCH_ASSOC"));
        return $result;
    }

    public static function query($sql = '')
    {
        $link = self::$link;
        if (!$link) {
            return false;
        }
        // 判断之前是否有结果集，如果有，释放之
        if (!empty(self::$PDOStatement)) {
            self::free();
        }
        self::$queryStr = $sql;
        self::$PDOStatement = $link->prepare(self::$queryStr);
        $res = self::$PDOStatement->execute();
        self::haveErrorThrowException();
        return $res;
    }

    /**
     * free PDOStatement
     */
    private static function free()
    {
        self::$PDOStatement = null;
    }

    private static function haveErrorThrowException()
    {
        $obj = empty(self::$PDOStatement) ? self::$link : self::$PDOStatement;
        $arrError = $obj->errorInfo();
        if ($arrError[0] != '00000') {
            self::$error = 'SQLSTATE: ' . $arrError[0] . '<br>SQL ERROR: ' . $arrError[1] . '<br>ERROR SQL: ' . self::$queryStr;
            self::throw_exception(self::$error);
            return false;
        }
        if (self::$queryStr == '') {
            self::throw_exception('No SQL Statement!!!');
            return false;
        }
    }

    /**
     * get one row data
     */
    public static function getRow($sql = null)
    {
        if ($sql != null) {
            self::query($sql);
        }
        $res = self::$PDOStatement->fetch(constant("PDO::FETCH_ASSOC"));
        return $res;
    }

    /**
     * Create, Delete, Update, return affected number of rows
     */
    public static function execute($sql = null)
    {
        $link = self::$link;
        if (!$link) {
            return false;
        }
        self::$queryStr = $sql;
        if (!empty(self::$PDOStatement)) {
            self::free();
        }
        $res = $link->exec(self::$queryStr);
        self::haveErrorThrowException();
        if ($res) {
            self::$lastInsertId = $link->lastInsertId();
            self::$numRows = $res;
            return self::$numRows;
        } else {
            return false;
        }
    }

    /**
     * find data by id
     */
    public static function findById($tabName, $priId, $fields = '*')
    {
        $sql = 'SELECT %s FROM %s WHERE id=%d';
        return self::getRow(sprintf($sql, self::parseFields($fields), $tabName, $priId));
    }

    /**
     * parse fields
     */
    public static function parseFields($fields = null)
    {
        if (is_array($fields)) {
            array_walk($fields, array('PDOMysql', 'addSpecialChar'));
            $fieldsStr = implode(',', $fields);
        } elseif (is_string($fields) && !empty($fields)) {
            if (strpos($fields, '`') === false) {
                $fields = explode(',', $fields);
                array_walk($fields, array('PDOMysql', 'addSpecialChar'));
                $fieldsStr = implode(',', $fields);
            } else {
                $fieldsStr = $fields;
            }
        } else {
            $fields = '*';
        }
        return $fieldsStr;
    }

    /**
     * use '`' for quote sql
     */
    public static function addSpecialChar(&$value)
    {
        if ($value === '*' || strpos($value, '.') !== false || strpos($value, '`') !== false) {

        } elseif (strpos($value, '`') === false) {
            $value = '`' . trim($value) . '`';
        }
        return $value;
    }

    public static function find($tables, $fields = '*', $where = null, $group = null, $having = null, $order = null, $limit = null)
    {
        $sql = 'SELECT ' . self::parseFields($fields) . ' FROM ' . $tables
        . self::parseWhere($where)
        . self::parseGroup($group)
        . self::parseHaving($having)
        . self::parseOrder($order)
        . self::parseLimit($limit);
        $dataAll = self::getAll($sql);
        return count($dataAll) == 1 ? $dataAll[0] : $dataAll;
    }

    public static function parseWhere($where)
    {
        $whereStr = '';
        if (is_string($where) && !empty($where)) {
            $whereStr = $where;
        }
        return empty($whereStr) ? '' : ' WHERE ' . $whereStr;
    }

    public static function parseGroup($group)
    {
        $groupStr = '';
        if (is_array($group)) {
            $groupStr = ' GROUP BY ' . implode(',', $group);
        } elseif (is_string($group) && !empty($group)) {
            $groupStr .= ' GROUP BY ' . $group;
        }
        return empty($groupStr) ? '' : $groupStr;
    }

    public static function parseHaving($having)
    {
        $havingStr = '';
        if (is_string($having) && !empty($having)) {
            $havingStr = ' HAVING ' . $having;
        }
        return $havingStr;
    }

    public static function parseOrder($order)
    {
        $orderStr = '';
        if (is_array($order)) {
            $orderStr .= ' ORDER BY ' . join(',', $order);
        } elseif (is_string($order) && !empty($order)) {
            $orderStr .= ' ORDER BY ' . $order;
        }
        return $orderStr;
    }

    public static function parseLimit($limit)
    {
        $limitStr = '';
        if (is_array($limit)) {
            if (count($limit) > 1) {
                $limitStr .= ' LIMIT ' . $limit[0] . ',' . $limit[0];
            } else {
                $limitStr .= ' LIMIT ' . $limit[0];
            }
        } elseif (is_string($limit) && !empty($limit)) {
            $limitStr .= ' LIMIT ' . $limit;
        }
        return $limitStr;
    }

    /**
     * add data
     */
    public static function add($data, $table)
    {
        $keys = array_keys($data);
        array_walk($keys, array('PDOMysql', 'addSpecialChar'));
        $fieldStr = join(',', $keys);
        $values = "'" . join("','", array_values($data)) . "'";
        $sql = "INSERT {$table}({$fieldStr}) VALUES ({$values})";
        // echo $sql;
        return self::execute($sql);
    }

    /**
     * update data
     */
    public static function update($data, $table, $where = null, $order = null, $limit = null)
    {
        $sets = '';
        foreach ($data as $key => $value) {
            $sets .= $key . "='" . $value . "',";
        }
        // echo $set;
        $sets = rtrim($sets, ',');
        $sql = "UPDATE {$table} SET {$sets} " . self::parseWhere($where) . self::parseOrder($order) . self::parseLimit($limit);
        return self::execute($sql);
    }

    /**
     * delete data
     */
    public static function delete($table, $where = null, $order = null, $limit = 0)
    {
        $sql = "DELETE FROM {$table} " . self::parseWhere($where) . self::parseOrder($order) . self::parseLimit($limit);
        return self::execute($sql);
    }

    /**
     * get last executed sql statement
     */
    public static function getLastSQL()
    {
        $link = self::$link;
        if (!$link) {
            return false;
        }
        return self::$queryStr;
    }

    /**
     * get last inserted data id
     */
    public static function getLastInsertId()
    {
        $link = self::$link;
        if (!$link) {
            return false;
        }
        return self::$lastInsertId;
    }

    /**
     * get db version
     */
    public static function getDBVersion()
    {
        $link = self::$link;
        if (!$link) {
            return false;
        }
        return self::$dbVersion;
    }

    /**
     * show all tables name
     */
    public static function showTables()
    {
        $tables = array();
        if (self::query('SHOW TABLES')) {
            $result = self::getAll();
            foreach ($result as $key => $value) {
                $tables[$key] = current($value);
            }
        }
        return $tables;
    }

    /**
     * close db
     */
    public static function close()
    {
        self::$link = null;
    }
}
require_once 'config.php';
$pdo = new PDOMysql();
// var_dump($pdo);
// $sql = 'SELECT * FROM user WHERE id=1';
// $sql = 'INSERT user (username, password, email) VALUES ("jessica", "1234", "jessica@jessica.com")';
// $res = $pdo->getAll($sql);
// $res = $pdo->execute($sql);
// $tabName = 'user';
// $priId = '10';
// $fields = 'username, email';
// $res = $pdo->findById($tabName, $priId, $fields);
// $tables = 'user';
// $res = $pdo->find($tables);
// $res = $pdo->find($tables, 'username, email', 'id>=10');
// $data = array(
//     'username' => 'xxxx',
//     'password' => '1234',
//     'email' => 'xxx@xxx.com',
// );
// $res = $pdo->add($data, 'user');
// $data = array(
//     'username' => 'Lee11',
//     'password' => '1234',
//     'email' => 'Lee@lee.com',
// );
// $res = $pdo->update($data, 'user', 'id=8');
// $res = $pdo->delete('user', 'id>17');
$res = $pdo->showTables();
print_r($res);
