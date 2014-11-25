<?php
class searchCourse
{
    /**
     * Every model needs a database connection, passed to the model
     * @param object $db A PDO database connection
     */
    function __construct($db) {
        try {
            $this->db = $db;
        } catch (PDOException $e) {
            exit('Database connection could not be established.');
        }
    }

    /**
     * Get simple "stats". This is just a simple demo to show
     * how to use more than one model in a controller (see application/controller/songs.php for more)
     */

    function searchCourse($data) {
        try {
            $sql = "SELECT * FROM `coursedatabase` WHERE `year` = '1032' AND (`code` LIKE ? OR `cname` LIKE ? OR `professor` LIKE ? OR `time` LIKE ?);";
            $query = $this->db->prepare($sql);
            $query->execute(array('%'.$data.'%', '%'.$data.'%', '%'.$data.'%', '%'.$data.'%'));
            $result = $query->fetchAll();
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $result;
    }
}