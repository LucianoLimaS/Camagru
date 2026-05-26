<?php
namespace Core;

abstract class Table {
    /**
     * @var \PDO
     */
    protected $db;

    /**
     * The database table name.
     *
     * @var string
     */
    protected $_table;

    /**
     * The table primary key.
     *
     * @var string
     */
    protected $_primaryKey = 'id';

    /**
     * Fully qualified class name of the associated Entity.
     *
     * @var string
     */
    protected $_entityClass;

    /**
     * Associations definitions.
     *
     * @var array
     */
    protected $_associations = [
        'belongsTo' => [],
        'hasMany' => []
    ];

    /**
     * Constructor.
     *
     * @param array $config Configuration options.
     */
    public function __construct(array $config = []) {
        $this->db = Database::getInstance()->getConnection();

        // 1. Resolve table name
        if (isset($config['table'])) {
            $this->_table = $config['table'];
        } elseif (!$this->_table) {
            $className = (new \ReflectionClass($this))->getShortName();
            // UsersTable -> users
            $this->_table = strtolower(preg_replace('/Table$/', '', $className));
        }

        // 2. Resolve primary key
        if (isset($config['primaryKey'])) {
            $this->_primaryKey = $config['primaryKey'];
        }

        // 3. Resolve entity class
        if (isset($config['entityClass'])) {
            $this->_entityClass = $config['entityClass'];
        } elseif (!$this->_entityClass) {
            $className = (new \ReflectionClass($this))->getShortName();
            $singular = rtrim(preg_replace('/Table$/', '', $className), 's');
            $this->_entityClass = 'App\\Models\\Entity\\' . $singular;
        }

        $this->initialize($config);
    }

    /**
     * Initialization hook.
     *
     * @param array $config Configuration options.
     * @return void
     */
    public function initialize(array $config) {
        // Overridden in subclasses
    }

    /**
     * Set the table name.
     *
     * @param string $table Table name.
     * @return void
     */
    public function setTable(string $table) {
        $this->_table = $table;
    }

    /**
     * Set the primary key column name.
     *
     * @param string $key Primary key name.
     * @return void
     */
    public function setPrimaryKey(string $key) {
        $this->_primaryKey = $key;
    }

    /**
     * Set the fully qualified entity class name.
     *
     * @param string $class Entity class name.
     * @return void
     */
    public function setEntityClass(string $class) {
        $this->_entityClass = $class;
    }

    /**
     * Get the database table name.
     *
     * @return string
     */
    public function getTableName(): string {
        return $this->_table;
    }

    /**
     * Get the primary key.
     *
     * @return string
     */
    public function getPrimaryKey(): string {
        return $this->_primaryKey;
    }

    /**
     * Get the fully qualified entity class name.
     *
     * @return string
     */
    public function getEntityClass(): string {
        return $this->_entityClass;
    }

    /**
     * Get the PDO database connection.
     *
     * @return \PDO
     */
    public function getConnection() {
        return $this->db;
    }

    /**
     * Create a new entity instance.
     *
     * @param array $data Data to populate.
     * @param array $options Options (e.g. markClean, markNew).
     * @return Entity
     */
    public function newEntity(array $data = [], array $options = []): Entity {
        $class = $this->_entityClass;
        if (!class_exists($class)) {
            $class = Entity::class;
        }
        return new $class($data, $options);
    }

    /**
     * Patch an existing entity with new data.
     *
     * @param Entity $entity The entity.
     * @param array $data New data.
     * @return Entity
     */
    public function patchEntity(Entity $entity, array $data): Entity {
        $entity->set($data);
        return $entity;
    }

    /**
     * Create a query builder instance for this table.
     *
     * @param string $type The query type (defaults to 'all').
     * @param array $options Query options.
     * @return Query
     */
    public function find(string $type = 'all', array $options = []): Query {
        return new Query($this);
    }

    /**
     * Fetch a single entity by primary key.
     *
     * @param mixed $id Primary key value.
     * @param array $options Options (e.g., contain).
     * @return Entity
     * @throws \Exception if record not found.
     */
    public function get($id, array $options = []): Entity {
        $query = $this->find()->where([$this->getPrimaryKey() => $id]);
        if (!empty($options['contain'])) {
            $query->contain($options['contain']);
        }
        $entity = $query->first();
        if (!$entity) {
            throw new \Exception("Record not found in table '" . $this->getTableName() . "' with ID '" . $id . "'");
        }
        return $entity;
    }

    /**
     * Save an entity (INSERT or UPDATE).
     *
     * @param Entity $entity The entity to save.
     * @return Entity|false Saved entity or false on failure.
     */
    public function save(Entity $entity) {
        $now = date('Y-m-d H:i:s');
        if ($entity->isNew()) {
            if (!$entity->has('created')) {
                $entity->set('created', $now);
            }
            if (in_array($this->_table, ['users', 'images']) && !$entity->has('updated')) {
                $entity->set('updated', $now);
            }
            
            $fields = [];
            $placeholders = [];
            $values = [];
            
            foreach ($entity->toArray() as $field => $val) {
                if (is_array($val) || $val instanceof Entity) {
                    continue;
                }
                $fields[] = "`$field`";
                $placeholders[] = ":$field";
                $values[$field] = $val;
            }
            
            $sql = "INSERT INTO `" . $this->getTableName() . "` (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
            $stmt = $this->db->prepare($sql);
            
            foreach ($values as $field => $val) {
                $stmt->bindValue(':' . $field, $val);
            }
            
            if (!$stmt->execute()) {
                return false;
            }
            
            $id = $this->db->lastInsertId();
            if ($id) {
                $entity->set($this->getPrimaryKey(), (int)$id);
            }
            
            $entity->isNew(false);
            $entity->clean();
            return $entity;
        } else {
            if (in_array($this->_table, ['users', 'images'])) {
                $entity->set('updated', $now);
            }
            
            $dirtyFields = $entity->dirty();
            if (empty($dirtyFields)) {
                return $entity;
            }
            
            $updates = [];
            $values = [];
            
            foreach ($dirtyFields as $field) {
                $val = $entity->get($field);
                if (is_array($val) || $val instanceof Entity) {
                    continue;
                }
                $updates[] = "`$field` = :$field";
                $values[$field] = $val;
            }
            
            if (empty($updates)) {
                return $entity;
            }
            
            $pk = $this->getPrimaryKey();
            $sql = "UPDATE `" . $this->getTableName() . "` SET " . implode(', ', $updates) . " WHERE `$pk` = :pk_val";
            $stmt = $this->db->prepare($sql);
            
            foreach ($values as $field => $val) {
                $stmt->bindValue(':' . $field, $val);
            }
            $stmt->bindValue(':pk_val', $entity->get($pk));
            
            if (!$stmt->execute()) {
                return false;
            }
            
            $entity->clean();
            return $entity;
        }
    }

    /**
     * Delete an entity.
     *
     * @param Entity $entity The entity to delete.
     * @return bool
     */
    public function delete(Entity $entity): bool {
        $pk = $this->getPrimaryKey();
        $id = $entity->get($pk);
        if (!$id) {
            return false;
        }
        $sql = "DELETE FROM `" . $this->getTableName() . "` WHERE `$pk` = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    /**
     * Define a belongsTo association.
     *
     * @param string $associatedName Name of the association (e.g. Users).
     * @param array $options Options for configuration.
     * @return void
     */
    public function belongsTo(string $associatedName, array $options = []) {
        $options += [
            'className' => null,
            'foreignKey' => null,
            'bindingKey' => 'id'
        ];
        
        if (!$options['className']) {
            $options['className'] = 'App\\Models\\Table\\' . $associatedName . 'Table';
        }
        
        if (!$options['foreignKey']) {
            $singular = strtolower(rtrim($associatedName, 's'));
            $options['foreignKey'] = $singular . '_id';
        }
        
        $this->_associations['belongsTo'][$associatedName] = $options;
    }

    /**
     * Define a hasMany association.
     *
     * @param string $associatedName Name of the association (e.g. Images).
     * @param array $options Options for configuration.
     * @return void
     */
    public function hasMany(string $associatedName, array $options = []) {
        $options += [
            'className' => null,
            'foreignKey' => null,
            'bindingKey' => 'id'
        ];
        
        if (!$options['className']) {
            $options['className'] = 'App\\Models\\Table\\' . $associatedName . 'Table';
        }
        
        if (!$options['foreignKey']) {
            $singular = strtolower(rtrim($this->_table, 's'));
            $options['foreignKey'] = $singular . '_id';
        }
        
        $this->_associations['hasMany'][$associatedName] = $options;
    }

    /**
     * Eagerly load associations for a list of entities.
     *
     * @param Entity[] $entities List of entities.
     * @param array $contain Associations list to load.
     * @return Entity[]
     */
    public function eagerLoad(array $entities, array $contain): array {
        foreach ($contain as $assocName) {
            if (isset($this->_associations['belongsTo'][$assocName])) {
                $assoc = $this->_associations['belongsTo'][$assocName];
                $assocTable = TableRegistry::get($assocName);
                
                $foreignKeys = [];
                foreach ($entities as $ent) {
                    $fkVal = $ent->get($assoc['foreignKey']);
                    if ($fkVal !== null) {
                        $foreignKeys[] = $fkVal;
                    }
                }
                
                if (empty($foreignKeys)) {
                    continue;
                }
                
                $foreignKeys = array_unique($foreignKeys);
                $associatedEntities = $assocTable->find()
                    ->where([$assoc['bindingKey'] => $foreignKeys])
                    ->all();
                    
                $indexed = [];
                $assocPk = $assocTable->getPrimaryKey();
                foreach ($associatedEntities as $assocEnt) {
                    $indexed[$assocEnt->get($assocPk)] = $assocEnt;
                }
                
                $propertyName = strtolower(rtrim($assocName, 's'));
                foreach ($entities as $ent) {
                    $fkVal = $ent->get($assoc['foreignKey']);
                    $ent->set($propertyName, isset($indexed[$fkVal]) ? $indexed[$fkVal] : null, ['setter' => false]);
                    $ent->clean();
                }
            } elseif (isset($this->_associations['hasMany'][$assocName])) {
                $assoc = $this->_associations['hasMany'][$assocName];
                $assocTable = TableRegistry::get($assocName);
                
                $bindingKeys = [];
                foreach ($entities as $ent) {
                    $bkVal = $ent->get($assoc['bindingKey']);
                    if ($bkVal !== null) {
                        $bindingKeys[] = $bkVal;
                    }
                }
                
                if (empty($bindingKeys)) {
                    continue;
                }
                
                $bindingKeys = array_unique($bindingKeys);
                $associatedEntities = $assocTable->find()
                    ->where([$assoc['foreignKey'] => $bindingKeys])
                    ->all();
                    
                $grouped = [];
                foreach ($associatedEntities as $assocEnt) {
                    $fkVal = $assocEnt->get($assoc['foreignKey']);
                    $grouped[$fkVal][] = $assocEnt;
                }
                
                $propertyName = strtolower($assocName);
                foreach ($entities as $ent) {
                    $bkVal = $ent->get($assoc['bindingKey']);
                    $associatedList = isset($grouped[$bkVal]) ? $grouped[$bkVal] : [];
                    $ent->set($propertyName, $associatedList, ['setter' => false]);
                    $ent->clean();
                }
            }
        }
        return $entities;
    }
}
