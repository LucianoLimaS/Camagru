<?php
namespace Core;

class TableRegistry {
    /**
     * Cache of Table instances.
     *
     * @var Table[]
     */
    protected static $instances = [];

    /**
     * Get a Table instance by its alias.
     *
     * @param string $alias Table alias (e.g. 'Users', 'Images').
     * @param array $config Configuration options for instantiation.
     * @return Table
     * @throws \Exception if the table class does not exist.
     */
    public static function get(string $alias, array $config = []): Table {
        if (isset(self::$instances[$alias])) {
            return self::$instances[$alias];
        }

        $className = 'App\\Models\\Table\\' . $alias . 'Table';
        if (!class_exists($className)) {
            throw new \Exception("Table class '" . $className . "' could not be found for alias '" . $alias . "'.");
        }

        self::$instances[$alias] = new $className($config);
        return self::$instances[$alias];
    }

    /**
     * Manually set a Table instance.
     *
     * @param string $alias Table alias.
     * @param Table $table Table instance.
     * @return void
     */
    public static function set(string $alias, Table $table) {
        self::$instances[$alias] = $table;
    }

    /**
     * Clear all cached instances.
     *
     * @return void
     */
    public static function clear() {
        self::$instances = [];
    }
}
