<?php
namespace Api\DomainServices\Common\Traits;

/**
 * Трейт работы с битрикс формами
 * Trait Form
 * @package Api\DomainServices\Common\Traits
 */
trait Form
{
    /**
     * Получает Id формы по её символьному идентификатору
     * @param $formSID
     * @return mixed
     */
    private function getFormIdBySID($formSID)
    {
        $dbRes = \CForm::GetBySID($formSID);
        $form = $dbRes->Fetch();
        $formId = $form['ID'];
        return $formId;
    }

    /**
     * Собирает нужный формат данных для заданных данных для заданной формы
     * @param $formSID
     * @param $needFields
     * @return array
     */
    private function collectCorrectFormData($formId, $needFields)
    {
        $fields = getFieldsForFormById($formId);
        $arValues = [];
        foreach ($needFields as $needField => $value) {
            if (array_key_exists($needField, $fields)) {
                $key = $fields[$needField]['FIELD_NAME'];
                if ($fields[$needField]['FIELD_TYPE'] == 'checkbox') {
                    $arValues[$key] = [$fields[$needField]['VALUE']];
                } else if ($fields[$needField]['FIELD_TYPE'] == 'dropdown') {
                    $valId = $fields[$needField]['ANSWER_VALUES'][$value];
                    $arValues[$key] = $valId;
                } else {
                    $arValues[$key] = $value;
                }
            }
        }
        return $arValues;
    }

    /**
     * Сохраняет результат формы и создает событие заполнения формы
     * @param $formId
     * @param $arValues
     * @param int $userId
     * @param string $checkRights
     * @return bool
     * @throws \Exception
     */
    private function saveResult($formId, $arValues, $userId = 0, $checkRights = 'N')
    {
        $result = \CFormResult::Add($formId, $arValues, $checkRights, $userId);
        if (!$result) {
            throw new \Exception('Неизвестная ошибка при сохранение результата формы');
        }
        \CFormResult::Mail($result);
        return $result;
    }
}