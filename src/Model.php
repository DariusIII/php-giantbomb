<?php

/**
 * This file is part of the GiantBomb PHP API created by Davide Borsatto.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright (c) 2015, Davide Borsatto
 */
namespace dborsatto\GiantBomb;

/**
 * Class Model.
 *
 * @author Davide Borsatto <davide.borsatto@gmail.com>
 */
class Model
{
    /**
     * The model name.
     *
     * @var string
     */
    protected $name = null;

    /**
     * The model values.
     *
     * @var array
     */
    protected $values = array();

    /**
     * Class constructor.
     *
     * @param string $name
     * @param array  $values
     */
    public function __construct($name, $values)
    {
        $this->name = $name;
        $this->values = $values;
    }

    /**
     * Returns all model values.
     *
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * Returns a single value.
     *
     * @param string $value
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($value, $default = null)
    {
        if (!$this->isValidValue($value)) {
            throw new Exception(sprintf('Value %s is not a valid key, exceping one of %s', $value, implode(', ', array_keys($this->values))));
        }

        return $this->values[$value] ? $this->values[$value] : $default;
    }

    /**
     * Magic function to allow methods like $model->getName().
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (0 === strpos($name, 'get')) {
            $key = $this->convertValueString(substr($name, 3));

            return $this->get($key);
        }

        return $this->get($name);
    }

    /**
     * Magic function to access a model value.
     *
     * @param string $value
     *
     * @return mixed
     */
    public function __get($value)
    {
        return $this->get($value);
    }

    /**
     * Checks if the requested value is valid.
     *
     * @param string $value
     *
     * @return bool
     */
    protected function isValidValue($value)
    {
        return isset($this->values[$value]);
    }

    /**
     * Converts a value from CamelCase to pascal_case.
     *
     * @param string $value
     *
     * @return string
     */
    protected function convertValueString($value)
    {
        return strtolower(preg_replace('/(?<=\\w)(?=[A-Z])/', '_$1', $value));
    }
}
