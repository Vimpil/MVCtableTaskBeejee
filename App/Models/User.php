<?php

namespace App\Models;

use PDO;
use \Core\View;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class User extends \Core\Model
{

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT id, name FROM users');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getUser($username, $password)
    {
        // Called from \Core\Model
        $db = static::getDB();
		
        $stmt = $db->prepare("SELECT * FROM usrdata WHERE login=:login");
        $stmt->execute(array(
        ':login' => $username
        ));
        // Get user matching request
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data;
        

    }
    
}
