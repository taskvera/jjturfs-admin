<?php
// app/Models/UserModel.php
namespace App\Models;

class UserModel {
    /**
     * Find a user by credentials.
     * (This is a stub. In a real application, connect to your database here.)
     */
    public function findUserByCredentials($username, $password) {
        if ($username === 'admin' && $password === 'password') {
            return ['id' => 1, 'username' => $username];
        }
        return null;
    }
}
