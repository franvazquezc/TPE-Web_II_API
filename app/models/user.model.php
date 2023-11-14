<?php
require_once 'model.php';

class UserModel extends Model {

    public function getByUserName($userName) {
        $query = $this->db->prepare('SELECT * FROM users WHERE user_name = ?');
        $query->execute([$userName]);

        return $query->fetch(PDO::FETCH_OBJ);
    }
}