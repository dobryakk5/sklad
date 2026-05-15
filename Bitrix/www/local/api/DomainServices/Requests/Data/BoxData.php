<?php


namespace Api\DomainServices\Requests\Data;


use Api\DomainServices\Common\Data\DataAbstract;

class BoxData extends DataAbstract
{
    public $destination = null;     // [шаг 1] enum: 1 - для личных, 2 - для бизнеса
    public $reason = null;          // [шаг 2] причина использования, enum
    public $thingsAmount = null;    // [шаг 3] объем вещей для личных целей, enum
    public $itemsAmount = null;     // [шаг 3] объем материалов/вещей/итд для бизнеса, enum
    public $rooms = null;           // [шаг 3] ремонтируемые команты, [enum]
    public $volume = null;          // [шаг 3] насколько большой объем + [шаг 3-2] объем вещей
    public $typeToStore = null;     // [шаг 3-1] что хранить, enum: 1 - некоторые, 2 - все
    public $boxesCount = null;      // [шаг 3-2] число коробок,
    public $workplacesCount = null; // [шаг 3] количество рабочих мест для перевозки
    public $frequency = null;       // [шаг 4] частота посещение, enum
    public $name = null;            // ‘string’,
    public $phone = null;           // ‘string’,
    public $email = null;           // ‘string’

    /**
     * Собирает набор данных для формы
     * @return array
     */
    public function getDataForForm()
    {
        $data = [
            'NAME' => $this->getName(),
            'PHONE' => $this->getPhone(),
            'EMAIL' => $this->getEmail(),
            'OPROSNIK' => '',
        ];

        $oprosnikFields = [
            'destination' => $this->getDestination(),
            'reason' => $this->getReason(),
            'rooms' => $this->getRooms(),
            'typeToStore' => $this->getTypeToStore(),
            'thingsAmount' => $this->getThingsAmount(),
            'itemsAmount' => $this->getItemsAmount(),
            'volume' => $this->getVolume(),
            'boxesCount' => $this->getBoxesCount(),
            'workplacesCount' => $this->getWorkplacesCount(),
            'frequency' => $this->getFrequency(),
        ];

        $boxesCountAnswerId = 360;
        $volumeAnswerId = 354;
        $workplacesCountAnswerId = 366;

        $oprosnikResult = '';

        foreach ($oprosnikFields as $index => $field) {
            if (empty($field)) {
                continue;
            }

            $value = 'Y';

            if ($index == 'boxesCount') {
                $value = $field;
                $field = $boxesCountAnswerId;
            } elseif ($index == 'volume') {
                $value = $field;
                $field = $volumeAnswerId;
            } elseif ($index == 'workplacesCount') {
                $value = $field;
                $field = $workplacesCountAnswerId;
            }

            // Если для вопроса пришел массив ответов, то необходимо перебрать ответы и разместить их
            // под одним общим вопросом
            if (is_array($field)) {
                $questionHeader = '';
                $questionAnswers = '';

                foreach ($field as $fieldItem) {
                    $element = \CIBlockElement::GetByID($fieldItem);
                    $answer = $element->GetNext();

                    if (empty($answer)) {
                        continue;
                    }

                    // Если заголовок вопроса не задан, то по первому ответу найдем вопрос и запишем заголовок
                    if (empty($questionHeader)) {
                        $section = \CIBlockSection::GetByID($answer["IBLOCK_SECTION_ID"]);
                        if ($question = $section->getNext()) {
                            $questionHeader = "---------------------\r\n".$question["NAME"]."\r\n";
                        }
                    }

                    // Добавляем текст ответа
                    $questionAnswers .= $answer["NAME"]." -> " . $value . "\r\n";
                }

                if (!empty($questionHeader) && !empty($questionAnswers)) {
                    $oprosnikResult .= $questionHeader;
                    $oprosnikResult .= $questionAnswers;
                }
            // Если для вопроса пришел простой ответ, не массив, то просто компонуем текст
            } else {
                $element = \CIBlockElement::GetByID($field);
                $answer = $element->GetNext();

                if (empty($answer)) {
                    continue;
                }

                $section = \CIBlockSection::GetByID($answer["IBLOCK_SECTION_ID"]);
                if ($question = $section->getNext()) {
                    $oprosnikResult .= "---------------------\r\n".$question["NAME"]."\r\n";
                    $oprosnikResult .= $answer["NAME"]." -> " . $value . "\r\n";
                }
            }
        }

        $data['OPROSNIK'] = $oprosnikResult;

        return $data;
    }

    /**
     * @return mixed
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param mixed $destination
     */
    public function setDestination($destination): void
    {
        $this->destination = $destination;
    }

    /**
     * @return mixed
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @param mixed $reason
     */
    public function setReason($reason): void
    {
        $this->reason = $reason;
    }

    /**
     * @return mixed
     */
    public function getThingsAmount()
    {
        return $this->thingsAmount;
    }

    /**
     * @param mixed $thingsAmount
     */
    public function setThingsAmount($thingsAmount): void
    {
        $this->thingsAmount = $thingsAmount;
    }

    /**
     * @return null
     */
    public function getItemsAmount()
    {
        return $this->itemsAmount;
    }

    /**
     * @param null $itemsAmount
     */
    public function setItemsAmount($itemsAmount): void
    {
        $this->itemsAmount = $itemsAmount;
    }

    /**
     * @return mixed
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * @param mixed $rooms
     */
    public function setRooms($rooms): void
    {
        $this->rooms = $rooms;
    }

    /**
     * @return mixed
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * @param mixed $volume
     */
    public function setVolume($volume): void
    {
        $this->volume = $volume;
    }

    /**
     * @return mixed
     */
    public function getTypeToStore()
    {
        return $this->typeToStore;
    }

    /**
     * @param mixed $typeToStore
     */
    public function setTypeToStore($typeToStore): void
    {
        $this->typeToStore = $typeToStore;
    }

    /**
     * @return mixed
     */
    public function getBoxesCount()
    {
        return $this->boxesCount;
    }

    /**
     * @param mixed $boxesCount
     */
    public function setBoxesCount($boxesCount): void
    {
        $this->boxesCount = $boxesCount;
    }

    /**
     * @return null
     */
    public function getWorkplacesCount()
    {
        return $this->workplacesCount;
    }

    /**
     * @param null $workplacesCount
     */
    public function setWorkplacesCount($workplacesCount): void
    {
        $this->workplacesCount = $workplacesCount;
    }

    /**
     * @return mixed
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * @param mixed $frequency
     */
    public function setFrequency($frequency): void
    {
        $this->frequency = $frequency;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * Валидирует заданные данные
     */
    public function validate()
    {
        $emptyChecks = [
            'destination',
            'reason',
            'frequency',
            'name',
            'phone',
            'email',
        ];

        $this->checkEmpty($emptyChecks);
    }

}