<?php

class DB
{
    protected static $instance = null;
    protected function __construct() {}
    protected function __clone() {}

    public static function instance()
    {
        if (self::$instance === null) {
            $opt = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            );
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHAR;
		try {
            self::$instance = new PDO($dsn, DB_USER, DB_PASS, $opt);
        } catch (\PDOException $e) {
            die('The Database Details Are Incorrect');
        }
        }
        return self::$instance;
    }

    public static function __callStatic($method, $args)
    {
        return call_user_func_array(array(self::instance(), $method), $args);
    }

    // Chain Prrepared
    public static function run($sql, $args = [])
    {
        if (!$args) {
            return self::instance()->query($sql);
        }
        $stmt = self::instance()->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }
    
    // select - multiple rows fetchall() eg ('tablename', '*/col', '[]/''', )
    public static function all($table, $columns = '*', $where = null, $orderby = '', $limit = '')
    {
        // Quick Fetch No WHERE
        if (!is_array($where)) {
            $stmt = self::run("SELECT $columns FROM $table $orderby $limit");
            return  $stmt->fetchAll();
        }
        // setup where
        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            $whereDetails .= $i == 0 ? "$key = ?" : " AND $key = ?";
            $i++;
        }
        // get values
        $values = array_values($where);
        $stmt = self::run("SELECT $columns FROM $table WHERE $whereDetails $orderby $limit", $values);
        return  $stmt->fetchAll();
    }
    
    // select - eg ('tablename', '*/col', '[]/''', )
    public static function column($table, $columns = '*', $where = null, $orderby = '', $limit = '')
    {
        // Quick Fetch No WHERE
        if (!is_array($where)) {
            $stmt = self::run("SELECT $columns FROM $table $orderby $limit");
            return  $stmt->fetchColumn();
        }
        // setup where
        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            $whereDetails .= $i == 0 ? "$key = ?" : " AND $key = ?";
            $i++;
        }
        // get values
        $values = array_values($where);
        $stmt = self::run("SELECT $columns FROM $table WHERE $whereDetails $orderby $limit", $values);
        return  $stmt->fetchColumn();
    }
    
    // select single row fetch()
    public static function select($table, $columns = '*', $where = null, $orderby = '', $limit = '')
    {
        // Quick Fetch No WHERE
        if (!is_array($where)) {
            $stmt = self::run("SELECT $columns FROM $table $orderby $limit");
            return  $stmt->fetch(PDO::FETCH_ASSOC);
        }
        // setup where
        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            $whereDetails .= $i == 0 ? "$key = ?" : " AND $key = ?";
            $i++;
        }
        // get values
        $values = array_values($where);
        $stmt = self::run("SELECT $columns FROM $table WHERE $whereDetails $orderby $limit", $values);
        return  $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // select single row fetch()
    public static function raw($table, $columns = '*', $where = null, $orderby = '', $limit = '')
    {
        // Quick Fetch No WHERE
        if (!is_array($where)) {
            $stmt = self::run("SELECT $columns FROM $table $orderby $limit");
            return  $stmt;
        }
        // setup where
        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            $whereDetails .= $i == 0 ? "$key = ?" : " AND $key = ?";
            $i++;
        }
        // get values
        $values = array_values($where);
        $stmt = self::run("SELECT $columns FROM $table WHERE $whereDetails $orderby $limit", $values);
        return  $stmt;
    }
  
    // update = careful merge removes duplicate array key ???
    public static function update($table, $data, $where)
    {
        //merge data and where together
        $collection = array_merge($data, $where);
        //collect the values from collection
        $values = array_values($collection);
        //setup fields
        $fieldDetails = null;
        foreach ($data as $key => $value) {
            $fieldDetails .= "$key = ?,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');
        //setup where 
        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            $whereDetails .= $i == 0 ? "$key = ?" : " AND $key = ?";
            $i++;
        }
        $stmt = self::run("UPDATE $table SET $fieldDetails WHERE $whereDetails", $values);
        return $stmt->rowCount();
    }

    // insert
    public static function insert($table, $data)
    {
        //add columns into comma seperated string
        $columns = implode(',', array_keys($data));
        //get values
        $values = array_values($data);
        $placeholders = array_map(function ($val) {
            return '?';
        }, array_keys($data));
        //convert array into comma seperated string
        $placeholders = implode(',', array_values($placeholders));
        $id = self::run("INSERT INTO $table ($columns) VALUES ($placeholders)", $values);
        return self::lastInsertId();
    }

    // delete
    public static function delete($table, $where, $limit = false)
    {
        //collect the values from collection
        $values = array_values($where);
        //setup where 
        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            $whereDetails .= $i == 0 ? "$key = ?" : " AND $key = ?";
            $i++;
        }
        //if limit is a number use a limit on the query
        if (is_numeric($limit)) {
            $limit = "LIMIT $limit";
            $stmt = self::run("DELETE FROM $table WHERE $whereDetails $limit", $values);
            return $stmt->rowCount();
        } else {
            $stmt = self::run("DELETE FROM $table WHERE $whereDetails", $values);
            return $stmt->rowCount();
        }
    }
    
    // delete wher IN ()
    public static function deleteByIds($table, $where, $ids, $col = 'id')
    {
        if (is_array($where)) {
            //collect the values for prepared statement?
            $values = array_values($where);
            //setup where
            $whereDetails = null;
            $i = 0;
            foreach ($where as $key => $value) {
                $whereDetails .= $i == 0 ? "$key = ?" : " AND $key = ?";
                $i++;
            }
            $stmt = self::run("DELETE FROM $table WHERE $whereDetails AND $col IN ($ids)", [$values]);
            return $stmt->rowCount();
        } else {
            $stmt = self::run("DELETE FROM $table WHERE $where IN ($ids)");
            return $stmt->rowCount();
        }
    }
    
    // truncate table
    public static function truncate($table)
    {
        $stmt = DB::run("TRUNCATE TABLE $table");
        return $stmt->rowCount();
    }
}