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
        $stmt = $db->prepare('SELECT COUNT(*) FROM tasks');
        $stmt->execute();
                
        $count = $stmt->fetchColumn() ;
        
		return $count;
        
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
		//$this->debug_to_console("insertTable");
		
		
		$db = static::getDB();
        $stmt = $db->prepare("INSERT INTO `tasks`(`id`, `name`, `email`, `task`, `status`, `editedTask`) VALUES (:newId, :name, :email, :task, :status, '0')");
        $stmt->execute(array(
        ':newId' => $newId,
        ':name' => $name,
        ':email' => $email,
        ':task' => $task,
        ':status' => $status,
        ));
        
    }
    
    
    public static function updateRowStatus ($newId, $status)
    {
		$db = static::getDB();
        $stmt = $db->prepare("UPDATE tasks SET status=:status WHERE id=:newId");
        $stmt->execute(array(
        ':status' => $status,
        ':newId' => $newId,
        ));
        
        // UPDATE tasks SET `status`='1' WHERE id=1231 
    }
    
    public static function  updateRowTask($id, $task){


		
		$db = static::getDB();
        $stmt = $db->prepare("UPDATE tasks SET task=:task, editedTask='1' WHERE id=:id");
        $stmt->execute(array(
		':task' => $task,
        ':id' => $id,        
        ));
		

  	}
    
    
    
    
}
