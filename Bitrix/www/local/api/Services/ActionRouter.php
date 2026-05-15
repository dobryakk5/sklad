<?php

namespace Api\Services;

use Api\DomainServices\Orders\OrdersService;
use Api\Exceptions\EntityNotFoundException;
use Api\Exceptions\ValidationException;
use Api\Helpers\RequestBodyParser;
use Api\Services\Actions\Auth;
use Api\Services\Actions\Features;
use Api\Services\Actions\ForgotPassword;
use Api\Services\Actions\PromotionDetails;
use Api\Services\Actions\Promotions;
use Api\Services\Actions\Pushes\PushSubscribe;
use Api\Services\Actions\Pushes\PushTest;
use Api\Services\Actions\Pushes\PushUnSubscribe;
use Api\Services\Actions\Requests\RequestBox;
use Api\Services\Actions\Requests\RequestCalculateRent;
use Api\Services\Actions\Requests\RequestDelivery;
use Api\Services\Actions\Requests\RequestFast;
use Api\Services\Actions\Requests\RequestSpaceVolumeStorageRent;
use Api\Services\Actions\Service\GetBoxAndStorages;
use Api\Services\Actions\Service\SpaceCalcPosition;
use Api\Services\Actions\Storages\StorageCalculate;
use Api\Services\Actions\Storages\StorageDetails;
use Api\Services\Actions\Storages\Storages;
use Api\Services\Actions\Storages\StoragesShort;
use Api\Services\Actions\User\Boxes\SendRequestForBox;
use Api\Services\Actions\User\Contract\GetContractDebt;
use Api\Services\Actions\User\Contract\GetContractDetail;
use Api\Services\Actions\User\Boxes\GetBoxes;
use Api\Services\Actions\User\Contract\DepositPayOnline;
use Api\Services\Actions\User\Contract\GetContractPayUrl;
use Api\Services\Actions\User\Contract\SendContractDocsEmail;
use Api\Services\Actions\User\Contract\SendDocumentUpd;
use Api\Services\Actions\User\ChangeSettings;
use Api\Services\Actions\User\Boxes\BoxInventory;
use Api\Services\Actions\User\Contract\SetAutoDebit;
use Api\Services\Actions\User\GetProfile;
use Api\Services\Actions\User\Boxes\BoxInventoryPatch;
use Api\Services\Actions\User\GetUserInfo;
use Api\Services\Actions\User\InfoItemDetails;
use Api\Services\Actions\User\InfoItems;
use Bitrix\Main\HttpRequest;

/**
 * Роутер действия. Для реквеста определяет своё возможное действие и выполняет его.
 * Class ActionRouter
 * @package Api\Services
 */
class ActionRouter
{
    // Доступные методы запросов
    const METHOD_POST = 'POST',
        METHOD_GET = 'GET',
        METHOD_DELETE = 'DELETE',
        METHOD_PUT = 'PUT',
        METHOD_PATCH = 'PATCH';

    // Карта маршрутов
    private $routesMap;

    function __construct()
    {

        // Индексы в массиве это регулярные выражения. Символы, которые требуют экранирования, нужно экранировать
        $this->routesMap = [
            "push\/test" => [
                'method' => self::METHOD_GET,
                'urlParams' => [
                ],
                'classAction' => PushTest::class
            ],
            "features" => [
                'method' => self::METHOD_GET,
                'urlParams' => [],
                'classAction' => Features::class
            ],
            "service\/space_calc\/stores" => [
                'method' => self::METHOD_GET,
                'urlParams' => [
                    1 => 'space',
                ],
                'classAction' => GetBoxAndStorages::class
            ],
            "service\/prices\/storages" => [
                'method' => self::METHOD_GET,
                'urlParams' => [],
                'classAction' => Storages::class
            ],
            "request\/fast" => [
                'method' => self::METHOD_POST,
                'urlParams' => [],
                'classAction' => RequestFast::class
            ],
            "storages" => [
                'method' => self::METHOD_GET,
                'urlParams' => [],
                'classAction' => StoragesShort::class
            ],
            "storages\/(\d+)\/(" . StorageDetails::TYPE_SHORT ."){1}" => [
                'method' => self::METHOD_GET,
                'urlParams' => [
                    1 => 'storageId',
                    2 => 'descriptionType'
                ],
                'classAction' => StorageDetails::class
            ],
            "storages\/(\d+)\/(" . StorageDetails::TYPE_FULL ."){1}" => [
                'method' => self::METHOD_GET,
                'urlParams' => [
                    1 => 'storageId',
                    2 => 'descriptionType'
                ],
                'classAction' => StorageDetails::class
            ],
            "storages\/calculate" => [
                'method' => self::METHOD_POST,
                'urlParams' => [],
                'classAction' => StorageCalculate::class
            ],
            "request\/delivery" => [
                'method' => self::METHOD_POST,
                'urlParams' => [],
                'classAction' => RequestDelivery::class
            ],
            "request\/calculate" => [
                'method' => self::METHOD_POST,
                'urlParams' => [],
                'classAction' => RequestCalculateRent::class
            ],
            "auth" => [
                'method' => self::METHOD_POST,
                'urlParams' => [],
                'classAction' => Auth::class
            ],
            "restore" => [
                'method' => self::METHOD_POST,
                'urlParams' => [],
                'classAction' => ForgotPassword::class
            ],
            "news" => [
                'method' => self::METHOD_GET,
                'urlParams' => [],
                'classAction' => Promotions::class
            ],
            "news\/(\d+)" => [
                'method' => self::METHOD_GET,
                'urlParams' => [
                    1 => 'promotionId',
                ],
                'classAction' => PromotionDetails::class
            ],
            "service\/space_calc\/positions" => [
                'method' => self::METHOD_GET,
                'urlParams' => [],
                'classAction' => SpaceCalcPosition::class
            ],
            "user\/contract\/(\d+)\/document\/(\d+)\/upd" => [
                'method' => self::METHOD_POST,
                'urlParams' => [
                    1 => 'contractId',
                    2 => 'documentId',
                ],
                'classAction' => SendDocumentUpd::class
            ],
            "user\/boxes\/(\d+)" => [
                'methods' => [
                    self::METHOD_GET,
                    self::METHOD_POST,
                    self::METHOD_PATCH,
                    self::METHOD_PUT
                ],
                'urlParams' => [
                    1 => 'boxId',
                ],
                'classAction' => [
                    self::METHOD_GET => BoxInventory::class,
                    self::METHOD_POST => BoxInventoryPatch::class,
                    self::METHOD_PATCH => BoxInventoryPatch::class,
                    self::METHOD_PUT => BoxInventoryPatch::class
                ]
            ],
            "user\/information" => [
                'method' => self::METHOD_GET,
                'urlParams' => [],
                'classAction' => InfoItems::class
            ],
            "user\/information\/(\d+)" => [
                'method' => self::METHOD_GET,
                'urlParams' => [
                    1 => 'infoItemId',
                ],
                'classAction' => InfoItemDetails::class
            ],
            "user\/settings" => [
                'method' => self::METHOD_POST,
                'urlParams' => [],
                'classAction' => ChangeSettings::class
            ],
            "user" => [
                'method' => self::METHOD_GET,
                'urlParams' => [],
                'classAction' => GetProfile::class // /user/boxes
            ],
            "user\/boxes" => [
                'method' => self::METHOD_GET,
                'urlParams' => [],
                'classAction' => GetBoxes::class
            ],
            "user\/info" => [
                'method' => self::METHOD_GET,
                'urlParams' => [],
                'classAction' => GetUserInfo::class
            ],
            "request\/box" => [
                'method' => self::METHOD_POST,
                'urlParams' => [],
                'classAction' => RequestBox::class
            ],
            "user\/contract\/(\d+)\/pay-email" => [
                'method' => self::METHOD_POST,
                'urlParams' => [
                    1 => 'contractId',
                ],
                'classAction' => SendContractDocsEmail::class
            ],
            "user\/contract\/(\d+)\/deposit\/pay" => [
                'method' => self::METHOD_POST,
                'urlParams' => [
                    1 => 'contractId',
                ],
                'classAction' => DepositPayOnline::class
            ],
            "user\/contract\/(\d+)" => [
                'method' => self::METHOD_GET,
                'urlParams' => [
                    1 => 'contractId',
                ],
                'classAction' => GetContractDetail::class
            ],
            "user\/contract\/(\d+)\/pay" => [
                'method' => self::METHOD_POST,
                'urlParams' => [
                    1 => 'contractId',
                ],
                'classAction' => GetContractPayUrl::class
            ],
            "user\/contract\/(\d+)\/auto" => [
                'methods' => [
                    self::METHOD_POST,
                    self::METHOD_PATCH
                ],
                'urlParams' => [
                    1 => 'contractId',
                ],
                'classAction' => [
                    self::METHOD_POST => SetAutoDebit::class,
                    self::METHOD_PATCH => SetAutoDebit::class,
                ],
            ],
            "request\/order" => [
                'method' => self::METHOD_POST,
                'urlParams' => [],
                'classAction' => RequestSpaceVolumeStorageRent::class
            ],
            "user\/boxes\/(\d+)\/request" => [
                'method' => self::METHOD_POST,
                'urlParams' => [
                    1 => 'boxId',
                ],
                'classAction' => SendRequestForBox::class
            ],
            "user\/contract\/(\d+)\/debt" => [
                'method' => self::METHOD_GET,
                'urlParams' => [
                    1 => 'contractId',
                ],
                'classAction' => GetContractDebt::class
            ],
            "push\/subscribe" => [
                'method' => self::METHOD_POST,
                'urlParams' => [],
                'classAction' => PushSubscribe::class
            ],
            "push\/unsubscribe" => [
                'method' => self::METHOD_POST,
                'urlParams' => [],
                'classAction' => PushUnSubscribe::class
            ],
        ];
    }

    /**
     * Маршрутизирует пришедший запрос
     * @param HttpRequest $request
     * @return array
     */
    public function route(HttpRequest $request)
    {
        // Url запроса
        $url = $request->getRequestedPage();
        $url = str_replace('/index.php', '', $url);
        // Текущий метод запроса
        $method = $request->getRequestMethod();

        // GET параметры
        $getParams = $request->getQueryList()->toArray();
        // POST параметры
        $postParams = $request->getPostList()->toArray();
        // Параметры из тела запроса
        $bodyParams = json_decode(file_get_contents('php://input'), true);
        $bodyParams = $bodyParams ? $bodyParams : [];
        if (in_array($method, [self::METHOD_PATCH, self::METHOD_PUT])) {
            $rawBody = file_get_contents("php://input");
            //AddMessage2Log($rawBody);
            $resParse = RequestBodyParser::parse($rawBody);
            $filesFromBody = $_FILES;
            $bodyParams = array_merge($bodyParams, $resParse);
            $files = $filesFromBody;
        } else {
            $files = $request->getFileList();
        }
        // Совпадения при проверке регулярного выражения
        $matches = [];

        $classAction = null;
        $urlParams = [];
        file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/local/Api/log.txt', $url . " " . $method . " : "."\n", FILE_APPEND);

        foreach ($this->routesMap as $route => $params) {
            //Собираем регулярку
            $regexRoute = '/^\/api\/'. $route . '$/imu';
                                                                                                                                
            // Если Url подходит по регулярку, то берём заданные параметры из карты
            if (preg_match($regexRoute, $url, $matches)) {
                if (!empty($params['methods'])) {
                    if (!in_array($method, $params['methods'])) {
                        continue;
                    }
                    $classAction = $params['classAction'][$method];
                } else {
                    if ($method != $params['method']) {
                        continue;
                    }
                    $classAction = $params['classAction'];
                }

                $urlParams = [];

                if (count($matches)) {
                    foreach ($params['urlParams'] as $index => $var) {
                        $value = null;

                        if (array_key_exists($index, $matches)) {
                            $value = $matches[$index];
                        }
                        $urlParams[$var] = $value;
                    }
                }
            }
        }
        //file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/local/Api/log.txt', '$classAction'. print_r($classAction, true)."\n", FILE_APPEND);
        // Если класс действия задан, то создаём объект действия и выполняем его
        if ($classAction !== null) {
            $action = new $classAction($urlParams, $getParams, $postParams, $bodyParams, $files);
            //file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/local/Api/log.txt', '$action'. print_r($action, true)."\n", FILE_APPEND);

            //AddMessage2Log($url . " " . $method . " : " . print_r([
                    //'urlParams' => $urlParams,
                    //'getParams' => $getParams,
                    //'postParams' => $postParams,
                    //'bodyParams' => $bodyParams,
                    //'files' => $files
                //], true));
            try {
                $action->init();
                $actionResult = $action->execute();
                /**
                 * @var ActionResult $actionResult
                 */
                $result = $actionResult->getResult();
                $httpCode = $actionResult->getApiCode();

            } catch (ValidationException $e) {
                $httpCode = 400;
                $result = [
                    'code' => 400,
                    'message' => $e->getMessage()
                ];
            } catch (EntityNotFoundException $e) {
                $httpCode = 404;
                $result = [
                    'code' => 404,
                    'message' => $e->getMessage()
                ];
            } catch (\Exception $e) {
                AddMessage2Log($e->getMessage() . "\r\n" . $e->getTraceAsString());
                $httpCode = 500;
                $result = [
                    'code' => 500,
                    'message' => 'Неизвестная ошибка'
                ];
            }
        } else {
            // Если класс действия не был получен, то отдаём 404 ошибку
            $httpCode = 404;
            $result = [
                'code' => 404,
                'message' => 'Method Not Exists',
            ];
        }
        \CHTTP::setStatus($httpCode);
        return $result;
    }
}