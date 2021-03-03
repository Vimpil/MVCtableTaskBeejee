<?php

namespace App\Models;

use PDO;
use \Core\View;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class TableRows extends \Core\Model
{

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    
    
    public static function getAllTable()
    {
    
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM tasks');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    }
    
    
    public static function countTable()
    {
    
        $db = static::getDB();
        $stmt = $db->query('SELECT COUNT(*) FROM tasks;');
		$stmt->execute();
		
        return $total_records;
        
    }
    
    
    public static function getTablePage($tableHead,$ascDesc, $start_from, $record_per_page)
    {
        $db = static::getDB();
        
        $stmt = $db->query("SELECT * FROM tasks ORDER BY $tableHead $ascDesc LIMIT $start_from, $record_per_page");
		$stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
        public static function insterTable ($newId,$name, $email, $task, $status)
    {
		$db = static::getDB();
        $stmt = $db->query("INSERT INTO `tasks`(`id`, `name`, `email`, `task`, `status`, `editedTask`) VALUES ('$newId','$name','$email','$task','$status', '0')");
        $stmt->execute();
        
    }
    
    
    
    
}
