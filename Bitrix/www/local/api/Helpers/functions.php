<?php

if (!function_exists('dd')) {
    function dd(...$args)
    {
        echo "<pre>";
        var_dump(...$args);
        echo "</pre>";
        die;
    }
}

if (!function_exists('dumpD')) {
    function dumpD(...$args)
    {
        echo "<pre>";
        var_dump(...$args);
        echo "</pre>";
    }
}

function getFieldsForFormById($FORM_ID)
{
    $FIELDS = [];
    if (\CForm::GetDataByID($FORM_ID, $form, $questions, $answers, $dropdown, $multiselect)) {
        $FIELDS = array_merge($FIELDS, $questions, $dropdown, $multiselect);

        foreach ($answers as $key => $answerList) {
            if (isset($FIELDS[$key])) {

                if (count($answerList) > 1) {
                    $FIELDS[$key]['OPTIONS'] = [];
                    foreach ($answerList as $answer) {
                        $FIELDS[$key]['OPTIONS'][$answer['ID']] = $answer['MESSAGE'];
                    }
                }

                $answer = $answerList[0];

                $FIELDS[$key]['FIELD_TYPE'] = $answer['FIELD_TYPE'];
                $FIELDS[$key]['FIELD_NAME'] = 'form_' . $answer['FIELD_TYPE'] . '_' . $answer['ID'];

                if ($answer['FIELD_TYPE'] == 'checkbox') {
                    $FIELDS[$key]['FIELD_NAME'] = 'form_' . $answer['FIELD_TYPE'] . '_' . $FIELDS[$key]['SID'];
                    $FIELDS[$key]['VALUE'] = $answer['ID'];
                }
                if ($answer['FIELD_TYPE'] == 'radio') {
                    $FIELDS[$key]['FIELD_NAME'] = 'form_' . $answer['FIELD_TYPE'] . '_' . $FIELDS[$key]['SID'];
                    $FIELDS[$key]['VALUE'] = $answer['ID'];
                }
                if  ($answer['FIELD_TYPE'] == 'dropdown') {
                    $FIELDS[$key]['FIELD_NAME'] = 'form_' . $answer['FIELD_TYPE'] . '_' . $key;
                    foreach ($answerList as $answ) {
                        $FIELDS[$key]['ANSWER_VALUES'][$answ['VALUE']] = $answ['ID'];
                    }
                }
            }
        }
    }
    return $FIELDS;
}