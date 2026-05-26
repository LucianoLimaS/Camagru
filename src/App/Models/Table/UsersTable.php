<?php
namespace App\Models\Table;

use Core\Table;

class UsersTable extends Table {
    /**
     * Initialize table and associations.
     *
     * @param array $config Configuration options.
     * @return void
     */
    public function initialize(array $config) {
        $this->setTable('users');
        $this->setPrimaryKey('id');
        $this->setEntityClass(\App\Models\Entity\User::class);

        $this->hasMany('Images');
        $this->hasMany('Likes');
        $this->hasMany('Comments');
    }
}
