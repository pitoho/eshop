<?php
class User
{
    public $id;
    public $login;
    public $password;
    public $hash;
    public $email; 

    public function __construct(array $item = [])
    {
        if ($item) {
            $this->login = $item['login'];
            $this->password = $item['password']; 
            $this->hash = hash('sha256', $this->password );
            $this->email = $item['email'];
            $this->id = $item['id'] ?? null;
        }
    }
}