<?php
namespace Core;

use JsonSerializable;

class Entity implements JsonSerializable {
    /**
     * Map of properties and their values.
     *
     * @var array
     */
    protected $_fields = [];

    /**
     * Map of original property values before modifications.
     *
     * @var array
     */
    protected $_original = [];

    /**
     * Map of properties that have been modified.
     *
     * @var array
     */
    protected $_dirty = [];

    /**
     * Flag indicating whether the entity is new and doesn't exist in the database.
     *
     * @var bool
     */
    protected $_isNew = true;

    /**
     * Constructor.
     *
     * @param array $properties Properties to populate.
     * @param array $options Options for initialization (e.g. markClean, markNew).
     */
    public function __construct(array $properties = [], array $options = []) {
        $options += [
            'markClean' => false,
            'markNew' => true
        ];
        
        $this->_isNew = $options['markNew'];
        
        if (!empty($properties)) {
            $this->set($properties, [
                'setter' => !$options['markClean']
            ]);
        }
        
        if ($options['markClean']) {
            $this->clean();
        }
    }

    /**
     * Get or set whether the entity is new.
     *
     * @param bool|null $new True/false to set, null to get.
     * @return bool
     */
    public function isNew(?bool $new = null): bool {
        if ($new !== null) {
            $this->_isNew = $new;
        }
        return $this->_isNew;
    }

    /**
     * Set a field value or multiple field values.
     *
     * @param string|array $property The property name, or an array of properties.
     * @param mixed $value The value to set if $property is a string.
     * @param array $options Options for setting properties (e.g., whether to use setter methods).
     * @return $this
     */
    public function set($property, $value = null, array $options = []) {
        $options += ['setter' => true];
        
        if (is_array($property)) {
            foreach ($property as $field => $val) {
                $this->set($field, $val, $options);
            }
            return $this;
        }

        $old = isset($this->_fields[$property]) ? $this->_fields[$property] : null;
        
        if ($options['setter']) {
            $setter = $this->_accessor($property, 'set');
            if ($setter) {
                $value = $this->{$setter}($value);
            }
        }

        $this->_fields[$property] = $value;
        
        if ($value !== $old) {
            $this->_dirty[$property] = true;
        }

        return $this;
    }

    /**
     * Get a property value.
     *
     * @param string $property Property name.
     * @return mixed
     */
    public function get(string $property) {
        $getter = $this->_accessor($property, 'get');
        if ($getter) {
            return $this->{$getter}(isset($this->_fields[$property]) ? $this->_fields[$property] : null);
        }
        return isset($this->_fields[$property]) ? $this->_fields[$property] : null;
    }

    /**
     * Check if a property is set and not null.
     *
     * @param string $property Property name.
     * @return bool
     */
    public function has(string $property): bool {
        return $this->get($property) !== null;
    }

    /**
     * Unset a property.
     *
     * @param string $property Property name.
     * @return $this
     */
    public function unsetProperty(string $property) {
        unset($this->_fields[$property]);
        unset($this->_dirty[$property]);
        return $this;
    }

    /**
     * Check if a property is dirty, or get all dirty properties.
     *
     * @param string|null $property Property name, or null.
     * @param bool|null $isDirty Optional boolean to set dirty status.
     * @return bool|array
     */
    public function dirty(?string $property = null, ?bool $isDirty = null) {
        if ($property === null) {
            return array_keys($this->_dirty);
        }
        if ($isDirty !== null) {
            if ($isDirty === false) {
                unset($this->_dirty[$property]);
            } else {
                $this->_dirty[$property] = true;
            }
            return $isDirty;
        }
        return isset($this->_dirty[$property]);
    }

    /**
     * Clean the dirty status of all properties.
     *
     * @return void
     */
    public function clean() {
        $this->_dirty = [];
        $this->_original = $this->_fields;
    }

    /**
     * Convert the entity properties to an array recursively.
     *
     * @return array
     */
    public function toArray(): array {
        $result = [];
        foreach (array_keys($this->_fields) as $property) {
            $value = $this->get($property);
            if ($value instanceof Entity) {
                $result[$property] = $value->toArray();
            } elseif (is_array($value)) {
                $result[$property] = array_map(function ($item) {
                    return $item instanceof Entity ? $item->toArray() : $item;
                }, $value);
            } else {
                $result[$property] = $value;
            }
        }
        return $result;
    }

    /**
     * JSON serialization support.
     *
     * @return array
     */
    public function jsonSerialize(): array {
        return $this->toArray();
    }

    /**
     * Magic getter.
     *
     * @param string $property Property name.
     * @return mixed
     */
    public function __get(string $property) {
        return $this->get($property);
    }

    /**
     * Magic setter.
     *
     * @param string $property Property name.
     * @param mixed $value Property value.
     */
    public function __set(string $property, $value) {
        $this->set($property, $value);
    }

    /**
     * Magic isset check.
     *
     * @param string $property Property name.
     * @return bool
     */
    public function __isset(string $property): bool {
        return $this->has($property);
    }

    /**
     * Magic unset.
     *
     * @param string $property Property name.
     */
    public function __unset(string $property) {
        $this->unsetProperty($property);
    }

    /**
     * Find getter/setter method for a property.
     *
     * @param string $property Property name.
     * @param string $type Either 'get' or 'set'.
     * @return string|null
     */
    protected function _accessor(string $property, string $type): ?string {
        $camel = str_replace(' ', '', ucwords(str_replace('_', ' ', $property)));
        $method = '_' . $type . $camel;
        return method_exists($this, $method) ? $method : null;
    }
}
