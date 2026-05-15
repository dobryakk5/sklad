<?php

namespace Api\Helpers;

/**
 * Parses a raw HTTP request using regexp
 * To enable parsing for Multipart/Form-data requests you can configure [[Request::parsers]] using this class:
 * 'request' => [
 *     'parsers' => [
 *         'multipart/form-data' => 'app\components\FormParser',
 *     ]
 * ]
 * Спс гуглу
 * @author Uniser <uniserpl@gmail.com>
 * @since 2.0
 */
class RequestBodyParser
{
    public $asArray = true;

    public static function parse($rawBody)
    {
        try {
            // Считываем разделитель из полной версии CONTENT_TYPE
            preg_match('/boundary=(.*)$/', $_SERVER["CONTENT_TYPE"], $matches);

            // Если разделителя нет, то парсим стандартной ф-ей
            if (!count($matches)) {
                parse_str(urldecode($rawBody), $a_data);
                return $a_data;
            }

            $boundary = $matches[1];

            // разбиваем контент на фрагменты и удаляем начало
            $a_blocks = preg_split("/-+$boundary/", $rawBody);
            array_pop($a_blocks);

            $a_data = [];
            // анализируем каждый блок
            foreach ($a_blocks as $id => $block) {
                if (empty($block))
                    continue;
                if (preg_match("/Content-Disposition:\s(\S+?);\s*(.*?)\r\n(Content-Type:\s(\S+?)\r\n)?\r\n(.*)\r\n/s", $block, $matches)) {
                    if ($matches[1] !== 'form-data')
                        throw new \Exception('Не могу определить расположение:'.$matches[1]);

                    if (preg_match_all('/\b([^\s=]+)="([^"]*)"(;|$)/', $matches[2],$vars)) {
                        $params = [];
                        foreach($vars[1] as $index=>$name)
                            $params[$name] = $vars[2][$index];
                        if (!isset($params['name']))
                            throw new \Exception('Не могу получить имя переменной:'.$matches[2]);

                        $count = strlen($params['name']);

                        $endName = substr($params['name'], $count - 2);

                        if (empty($matches[4])) { // Content-type не задан
                            if ($endName == '[]') {
                                $tempName = substr($params['name'], 0, $count - 2);
                                $a_data[$tempName][] = $matches[5];
                            } else {
                                $a_data[$params['name']] = $matches[5];
                            }
                        } else {
                            // Возможно, понадобятся фильтры

                            // Файлы
                            $filename = isset($params['filename']) ? $params['filename'] : '';

                            // Принудительно
                            $fileArray = [
                                'error'    => UPLOAD_ERR_OK,
                                'name'     => $filename, //basename($filename),
                                'type'     => $matches[4],
                                'size'     => strlen($matches[5]),
                                'tmp_name' => $matches[5]
                            ];
                            if ($endName == '[]') {
                                $tempName = substr($params['name'], 0, $count - 2);
                                $_FILES[$tempName][] = $fileArray;
                            } else {
                                $_FILES[$params['name']] = $fileArray;
                            }
                        }
                    } else
                        throw new \Exception('Не могу получить имя переменной:'.$matches[2]);

                } else // Здесь всё кроме multipart/form-data
                    throw new \Exception('Неизвестный формат:'.substr($block,0,50).'...');
            }
            return $a_data;
        } catch (\Exception $e) {
            return null;
        }
    }
}