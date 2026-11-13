<?php

namespace App\Models;

use CodeIgniter\Model;

/** CI3: Main_model */
class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $returnType    = 'object';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'first_name',
        'last_name',
        'email',
        'password',
        'created_at',
    ];

    /** @return object|false */
    public function login(string $email, string $password)
    {
        $row = $this->where('email', $email)
            ->where('password', md5($password))
            ->first();

        return $row ?: false;
    }
}
