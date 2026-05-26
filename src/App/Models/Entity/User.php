<?php
namespace App\Models\Entity;

use Core\Entity;

class User extends Entity {
    /**
     * Hash the password automatically when setting it.
     *
     * @param string $password Raw password.
     * @return string Hashed password.
     */
    protected function _setPassword(string $password): string {
        if (strpos($password, '$2y$') === 0 && strlen($password) === 60) {
            return $password;
        }
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Virtual field: is_active.
     *
     * @return bool
     */
    protected function _getIsActive(): bool {
        return (bool)$this->get('active');
    }
}
