<?php

namespace Api\Services;

class ActionResult
{
    private $params = [];
    private $errors = [];
    private $apiCode = 200;

    /**
     * Получает массив результата
     * @return array
     */
    public function getResult()
    {
        return $this->params;
    }

    /**
     * Получает код Api результата выполнения действия
     * @return int
     */
    public function getApiCode()
    {
        return $this->apiCode;
    }

    /**
     * Задаёт api код результата выполнения действия
     * @param $code
     */
    public function setApiCode($code)
    {
        $this->apiCode = $code;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }
}