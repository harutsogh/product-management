<?php

namespace App\Validators;

use App\Validators\Traits\Authorize;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * Class BaseValidator
 * @package App\Validators
 */
abstract class BaseValidator
{
    use Authorize;

    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @var array
     */
    protected array $rules = [];

    /**
     * @var array
     */
    protected array $params = [];

    /**
     * @var array
     */
    protected array $messages = [];

    /**
     * @param string $key
     * @return array|\ArrayAccess|mixed
     */
    public function get(string $key)
    {
        return Arr::get($this->data, $key);
    }

    /**
     * @param string $param
     * @return array|\ArrayAccess|mixed
     */
    public function param(string $param)
    {
        return Arr::get($this->params, $param);
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams(array $params): self
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @param string $param
     * @param $value
     * @return $this
     */
    public function addParam(string $param, $value): self
    {
        $this->params[$param] = $value;

        return $this;
    }

    /**
     * @param string $field
     * @param string $rule
     * @param array $arguments
     * @return $this
     */
    public function addRule(string $field, string $rule, array $arguments = []): self
    {
        $this->rules[$field][] = $this->formatRule($rule, $arguments);

        return $this;
    }

    /**
     * @param string $field
     * @param string $rule
     * @return $this
     */
    public function removeRule(string $field, string $rule): self
    {
        foreach ($this->rules[$field] as $index => $ruleOptions) {
            if ($this->deFormatRule($ruleOptions) === $rule) {
                unset($this->rules[$field][$index]);
                $this->rules[$field] = array_values($this->rules[$field]);
                break;
            }
        }

        return $this;
    }

    /**
     * @param string $rule
     * @param array $arguments
     * @return $this
     */
    public function appendRule(string $rule, array $arguments = []): self
    {
        foreach ($this->rules as $field => $rules) {
            $this->addRule($field, $rule, $arguments);
        }

        return $this;
    }

    /**
     * @param string $field
     * @param string $rule
     * @param string $message
     * @return $this
     */
    public function addMessage(string $field, string $rule, string $message): self
    {
        $this->messages["$field.$rule"] = $message;

        return $this;
    }

    /**
     * @param string $method
     * @return bool
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validate(string $method): bool
    {
        $action = ucfirst(Str::camel($method));

        $this->callValidate($action);
        $this->callAuthorize($action);

        return true;
    }

    /**
     * @param string $action
     * @return bool
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function callValidate(string $action): bool
    {
        $validation = 'validate' . $action;

        if (!method_exists($this, $validation)) {
            return false;
        }

        $rules = $this->{$validation}();

        $this->rules = is_array($rules) ? array_merge($rules, $this->rules) : $this->rules;

        $validator = Validator::make(
            $this->data,
            $this->rules,
            $this->messages
        );
        $validator->validate();
        return true;
    }

    /**
     * @param string $rule
     * @param array $arguments
     * @return string
     */
    protected function formatRule(string $rule, array $arguments = []): string
    {
        if (empty($arguments)) {
            return $rule;
        }
        $arguments = implode(',', $arguments);

        return sprintf('%s:%s', $rule, $arguments);
    }

    /**
     * @param string $rule
     * @return string
     */
    protected function deFormatRule(string $rule): string
    {
        return explode(':', $rule)[0];
    }
}
