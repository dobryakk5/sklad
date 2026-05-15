<?php

namespace Api\Services\Actions;

/**
 * Абстрактный класс api действий
 * Class ActionAbstract
 * @package Api\Services\Actions
 */
abstract class ActionAbstract
{
    // Параметры url
    protected $urlParams;

    // GET параметры
    protected $getParams;

    // POST параметры
    protected $postParams;

    // Параметры из тела зпроса
    protected $bodyParams;

    // Обработанные параметры
    protected $data = [];
    // Необработанные параметры
    protected $params = [];
    // Список необходимых параметров, которые будут собраны из параметров запроса
    protected $needParams = [];

    public function __construct($urlParams, $getParams, $postParams, $bodyParams, $files)
    {
        $this->urlParams = $urlParams;
        $this->getParams = $getParams;
        $this->postParams = $postParams;
        $this->bodyParams = $bodyParams;
        $this->files = $files;
        $this->params = array_merge($postParams, $getParams, $bodyParams);
    }

    /**
     * Выполняет конкретное дейсвтие
     * @return mixed
     */
    abstract public function execute();

    /**
     * Инициализация действия
     */
    public function init()
    {
        $data = $this->processParams($this->params);
        $this->data = $data;
    }

    /**
     * Обработка параметров перед сохранением
     * @param $params
     * @return array
     */
    protected function processParams($params): array
    {
        return $this->getNeedData($params, $this->needParams);
    }

    /**
     * Из набора данных получает необходимые параметры
     * @param $params
     * @param $needParams
     * @return array
     */
    protected function getNeedData($params, $needParams)
    {
        $data = [];

        foreach ($needParams as $needParam) {
            if (array_key_exists($needParam, $params)) {
                $value = $params[$needParam];
            } else {
                $value = null;
            }
            $data[$needParam] = $value;
        }
        return $data;
    }
}