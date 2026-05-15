<?php
namespace Api\DomainServices\Common\Data;

use Api\Exceptions\ValidationException;

/**
 * Адстрактный класс данных запросов
 * Class DataAbstract
 * @package Api\DomainServices\Requests\Data
 */
class DataAbstract
{
    /**
     * Задаём параметры из заданного массива и запускает их валидацию
     * @param $data
     */
    public function setFromArray($data)
    {
        foreach ($data as $field => $value) {
            if (property_exists(get_class($this), $field)) {
                $this->$field = $value;
            }
        }
        // Валидируем заданные данные
        $this->validate();
    }

    /**
     * Выполняет валидацию полей данных
     * @return bool
     */
    public function validate() {
        return true;
    }

    /**
     * Выполняет проверку полей на пустоту
     * @param $fields
     * @throws ValidationException
     */
    protected function checkEmpty($fields)
    {
        $callback = function ($fieldValue) {
            return !empty($fieldValue);
        };
        $this->commonCheck($fields, $callback, "Не заполнено поле #FIELD#");
    }

    /**
     * Выполняет проверку на целочисленность заданых полей
     * @param $fields
     * @throws ValidationException
     */
    protected function checkInteger($fields)
    {
        $callback = function ($fieldValue) {
            return is_integer($fieldValue);
        };
        $this->commonCheck($fields, $callback, "Поле #FIELD# должно быть целым числом");
    }

    protected function checkNumeric($fields)
    {
        $callback = function ($fieldValue) {
            return is_numeric($fieldValue);
        };
        $this->commonCheck($fields, $callback, "Поле #FIELD# должно быть числом");
    }

    protected function checkBoolean($fields)
    {
        $callback = function ($fieldValue) {
            return is_bool($fieldValue);
        };
        $this->commonCheck($fields, $callback, "Поле #FIELD# должно быть логическим значением");
    }

    protected function checkArray($fields)
    {
        $callback = function ($fieldValue) {
            return is_array($fieldValue);
        };
        $this->commonCheck($fields, $callback, "Поле #FIELD# должно быть массивом");
    }

    private function commonCheck($fields, $callback, $error)
    {
        foreach ($fields as $field) {
            if (!$callback($this->$field)) {
                $errorMes = str_replace('#FIELD#', $field, $error);
                throw new ValidationException($errorMes);
            }
        }
    }
}