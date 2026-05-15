<?php

namespace Api\Models;

/**
 * Абстрактная модель
 * Class Model
 * @package Api\Models
 */
abstract class Model
{
    protected $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * Метод возвращает ID записи
     *
     * @return int
     */
    public function getId()
    {
        return (int) $this->fields['ID'];
    }

    public function getName()
    {
        return (string) $this->fields['NAME'] ?? '';
    }

    public function getSectionId()
    {
        return (int) $this->fields['IBLOCK_SECTION_ID'] ?? null;
    }
}