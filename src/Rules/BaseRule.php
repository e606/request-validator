<?php

namespace Progsmile\Validator\Rules;

abstract class BaseRule
{
    const CONFIG_ALL = 'all';
    const CONFIG_DATA = 'data';
    const CONFIG_ORM = 'orm';
    const CONFIG_FIELD_RULES = 'fieldRules';

    private $config;

    protected $params;

    /**
     * BaseRule constructor.
     *
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Returns all config array, or specific one.
     *
     * @param string $type
     *
     * @return array
     */
    protected function getConfig($type = self::CONFIG_ALL)
    {
        if ($type == self::CONFIG_ALL) {
            return $this->config;
        }

        return isset($this->config[$type]) ? $this->config[$type] : [];
    }

    /**
     * If field has specific rule.
     *
     * @param $rule
     *
     * @return bool
     */
    public function hasRule($rule)
    {
        return strpos($this->getConfig(self::CONFIG_FIELD_RULES), $rule) !== false;
    }

    /**
     * Check if variable is not required - to prevent error messages from another validators.
     *
     * @param string $type | 'var' or 'file'
     *
     * @return bool
     */
    protected function isNotRequiredAndEmpty($type = 'var')
    {
        $condition = false;

        if ($type == 'var') {
            $condition = strlen($this->params[1]) == 0;
        } elseif ($type == 'file') {
            $fieldsName = $this->params[0];

            //when file field is not required and empty
            $condition = isset($_FILES[$fieldsName]['name']) && $_FILES[$fieldsName]['name'] == '';
        }

        return !$this->hasRule('required') && $condition;
    }

    /**
     * Set params to validator.
     *
     * @param $params
     *
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Returns params.
     *
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Returns pure class name.
     *
     * @return string
     */
    public function getRuleName()
    {
        $classPath = explode('\\', get_class($this));

        return array_pop($classPath);
    }

    /**
     * Returns error message from rule.
     *
     * @return string
     */
    abstract public function getMessage();

    /**
     * Will the process to check if it is valid or not.
     *
     * @return bool Return the result if valid or not
     */
    abstract public function isValid();
}
