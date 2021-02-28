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
        $stmt = $db->query('SELECT id, name, email, task, status FROM tasks');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
}
