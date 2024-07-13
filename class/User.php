<?php

class User {
    public $id;
    public $name;
    public $email;
    public $password;
    public $role;

    private $con;
    private $tableName;

    public function __construct($db)
    {
        $this->con = $db;
        $this->tableName = "users";
    }

    public function allUser()
    {
        $stmt = "SELECT id, name, email, role, created_at FROM ".$this->tableName . ";";
        $this->con->query($stmt);

        if ($this->con->execute())
        {
            if ($this->con->rowCount() > 0)
            {
                $data = $this->con->getAll();
                return $data;
            }
            return [];
        }
        return false;
    }

    public function getByID($id)
    {
        $this->id = $id;

        $stmt = "SELECT id, name, email, created_at FROM ".$this->tableName . " WHERE id = :id;";
        $this->con->query($stmt);

        $this->con->bind(':id', $this->id);

        if ($this->con->execute())
        {
            if ($this->con->rowCount() > 0)
            {
                $data = $this->con->get();
                return $data;
            }
            return [];
        }
        return false;
    }

    public function createUser($name, $email, $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = "INSERT INTO ".$this->tableName." (name, email, password) VALUES (:name, :email, :password);";
        $this->con->query($stmt);

        $this->con->bind(':name', $this->name);
        $this->con->bind(':email', $this->email);
        $this->con->bind(':password', $this->password);

        if ($this->con->execute())
        {
            return true;
        }
        return false;
    }

    public function updateUser($id, $name, $password, $role)
    {
        $this->id = $id;
        $this->name = $name;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->role = $role;

        $stmt = "UPDATE ".$this->tableName." SET name = :name, password = :password, role = :role WHERE id = :id;";
        $this->con->query($stmt);

        $this->con->bind(':id', $this->id);
        $this->con->bind(':name', $this->name);
        $this->con->bind(':password', $this->password);
        $this->con->bind(':role', $this->role);

        if ($this->con->execute())
        {
            if ($this->con->rowCount() > 0) {
                return true;
            }
            return null;
        }
        return false;
    }

    public function deleteUser($id)
    {
        $this->id = $id;

        $stmt = "DELETE FROM ".$this->tableName." WHERE id = :id;";
        $this->con->query($stmt);

        $this->con->bind(':id', $this->id);

        if ($this->con->execute())
        {
            if ($this->con->rowCount() > 0) {
                return true;
            }
            return null;
        }
        return false;
    }
}
