<?php

require_once 'Model.php';

class User extends Model
{
    protected static $table = 'users';

    public $id;
    public $username;
    public $password;
    public $role;
    public $created_at;
    public $updated_at;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /** ✅ Get all users */
    public static function all()
    {
        $results = parent::all();
        return $results
            ? array_map(fn($user) => new self($user), $results)
            : null;
    }

    /** ✅ Find by ID */
    public static function find($id)
    {
        $result = parent::find($id);
        return $result ? new self($result) : null;
    }

    /** ✅ Create a new user (hash password before insert) */
    public static function create(array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $result = parent::create($data);
        return $result ? new self($result) : null;
    }

    /** ✅ Update user (rehash password if changed) */
    public function update(array $data)
    {
        if (isset($data['password']) && !empty($data['password'])) {
            // Check if it's already a bcrypt hash (starts with $2y$ or $2a$)
            if (!preg_match('/^\$2y\$/', $data['password'])) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }
        } else {
            unset($data['password']); // don't overwrite existing password with empty
        }

        $result = parent::updateById($this->id, $data);
        if ($result) {
            foreach ($data as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }
        return $result;
    }


    /** ✅ Save current state to DB */
    public function save()
    {
        $data = [
            'username' => $this->username,
            'password' => $this->password,
            'role' => $this->role,
        ];
        return $this->update($data);
    }

    /** ✅ Delete user */
    public function delete()
    {
        return parent::deleteById($this->id);
    }

    /** ✅ Find by username (for login) */
    public static function findByUsername($username)
    {
        $results = parent::where('username', '=', $username);
        return $results ? new self($results[0]) : null;
    }

    /** ✅ Verify login credentials */
    public static function authenticate($username, $password)
    {
        $user = self::findByUsername($username);

        if ($user && password_verify($password, $user->password)) {
            return $user;
        }

        return null;
    }
}
