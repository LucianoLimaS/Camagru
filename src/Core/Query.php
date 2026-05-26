<?php
namespace Core;

class Query {
    /**
     * @var Table
     */
    protected $table;

    /**
     * @var array
     */
    protected $select = ['*'];

    /**
     * @var array
     */
    protected $where = [];

    /**
     * @var array
     */
    protected $order = [];

    /**
     * @var int|null
     */
    protected $limit = null;

    /**
     * @var int|null
     */
    protected $offset = null;

    /**
     * @var array
     */
    protected $contain = [];

    /**
     * Query parameters for binding.
     *
     * @var array
     */
    public $params = [];

    /**
     * Counter for generating unique parameter names.
     *
     * @var int
     */
    protected $paramCounter = 0;

    /**
     * Constructor.
     *
     * @param Table $table The Table instance.
     */
    public function __construct(Table $table) {
        $this->table = $table;
    }

    /**
     * Set fields to select.
     *
     * @param array $fields List of fields.
     * @return $this
     */
    public function select(array $fields) {
        $this->select = $fields;
        return $this;
    }

    /**
     * Add WHERE conditions.
     *
     * @param array $conditions Conditions in format ['field' => $val, 'field >' => $val].
     * @return $this
     */
    public function where(array $conditions) {
        foreach ($conditions as $field => $val) {
            $this->where[] = [$field, $val];
        }
        return $this;
    }

    /**
     * Add ORDER BY options.
     *
     * @param string|array $order Order options.
     * @return $this
     */
    public function order($order) {
        if (is_array($order)) {
            $this->order = array_merge($this->order, $order);
        } else {
            $this->order[] = $order;
        }
        return $this;
    }

    /**
     * Set LIMIT.
     *
     * @param int $limit Maximum number of records.
     * @return $this
     */
    public function limit(int $limit) {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Set OFFSET.
     *
     * @param int $offset Offset.
     * @return $this
     */
    public function offset(int $offset) {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Set associations to eagerly load.
     *
     * @param array|string $associations Associations to load.
     * @return $this
     */
    public function contain($associations) {
        if (is_string($associations)) {
            $associations = [$associations];
        }
        $this->contain = array_merge($this->contain, $associations);
        return $this;
    }

    /**
     * Build the raw SQL statement and populate the parameters array.
     *
     * @return string
     */
    public function sql(): string {
        $sql = "SELECT " . implode(', ', $this->select) . " FROM `" . $this->table->getTableName() . "`";
        
        $whereClauses = [];
        $this->params = [];
        $this->paramCounter = 0;

        foreach ($this->where as $cond) {
            $fieldExpr = trim($cond[0]);
            $value = $cond[1];

            $operator = '=';
            $field = $fieldExpr;

            // Check if there is space in the field name (e.g., "id >" or "name LIKE")
            if (strpos($fieldExpr, ' ') !== false) {
                list($field, $operator) = explode(' ', $fieldExpr, 2);
                $field = trim($field);
                $operator = trim($operator);
            }

            if (is_array($value)) {
                $placeholders = [];
                foreach ($value as $val) {
                    $paramName = "p" . $this->paramCounter++;
                    $placeholders[] = ":" . $paramName;
                    $this->params[$paramName] = $val;
                }
                $whereClauses[] = "`$field` IN (" . implode(', ', $placeholders) . ")";
            } elseif ($value === null) {
                if ($operator === '=' || $operator === 'IS') {
                    $whereClauses[] = "`$field` IS NULL";
                } else {
                    $whereClauses[] = "`$field` IS NOT NULL";
                }
            } else {
                $paramName = "p" . $this->paramCounter++;
                $whereClauses[] = "`$field` $operator :$paramName";
                $this->params[$paramName] = $value;
            }
        }

        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }

        if (!empty($this->order)) {
            $sql .= " ORDER BY " . implode(', ', $this->order);
        }

        if ($this->limit !== null) {
            $sql .= " LIMIT " . (int)$this->limit;
        }

        if ($this->offset !== null) {
            $sql .= " OFFSET " . (int)$this->offset;
        }

        return $sql;
    }

    /**
     * Execute the query and return all matching entities.
     *
     * @return Entity[]
     */
    public function all(): array {
        $sql = $this->sql();
        $db = $this->table->getConnection();
        $stmt = $db->prepare($sql);
        
        foreach ($this->params as $param => $val) {
            $stmt->bindValue(':' . $param, $val);
        }
        
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $entities = [];
        foreach ($rows as $row) {
            $entities[] = $this->table->newEntity($row, [
                'markClean' => true,
                'markNew' => false
            ]);
        }
        
        if (!empty($entities) && !empty($this->contain)) {
            $entities = $this->table->eagerLoad($entities, $this->contain);
        }
        
        return $entities;
    }

    /**
     * Execute the query and return the first matching entity or null.
     *
     * @return Entity|null
     */
    public function first() {
        $query = clone $this;
        $query->limit(1);
        $results = $query->all();
        return !empty($results) ? $results[0] : null;
    }

    /**
     * Execute a count query and return the total number of matching rows.
     *
     * @return int
     */
    public function count(): int {
        $countQuery = clone $this;
        $countQuery->select = ['COUNT(*)'];
        $countQuery->order = [];
        $countQuery->limit = null;
        $countQuery->offset = null;
        
        $sql = $countQuery->sql();
        $db = $this->table->getConnection();
        $stmt = $db->prepare($sql);
        
        foreach ($countQuery->params as $param => $val) {
            $stmt->bindValue(':' . $param, $val);
        }
        
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }
}
