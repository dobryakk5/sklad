<?php


namespace Api\Services\Actions\User\Boxes;


use Api\DomainServices\Storages\StorageService;
use Api\DomainServices\User\BoxesService;
use Api\DomainServices\User\DocumentsService;
use Api\Models\Box;
use Api\Models\Contract;
use Api\Models\Storage;
use Api\Services\ActionResult;
use Api\Services\Actions\User\ActionWithUserAbstract;

class GetBoxes extends ActionWithUserAbstract
{
    protected $needParams = [
        'limit',
        'lastId'
    ];

    public function execute()
    {
        /**
         * @var Contract $contract
         * @var Box $box
         * @var Storage $storage
         */

        $data = $this->data;
        $limit = $data['limit'] !== null ? (int) $data['limit'] : null;
        $lastId = $data['lastId'] !== null ? (int) $data['lastId'] : null;
        $result = array();

        // Получить действующие договоры
        $docsService = new DocumentsService();
        $contracts = $docsService->getUserContracts($this->user->getId());

        if (!empty($contracts)) {
            // Взять айдишники боксов из договоров
            $boxIds = array();
            foreach ($contracts as $contract) {
                $boxIds[] = $contract->getBoxId();
            }

            $boxes = BoxesService::getBoxesByIdList($boxIds, $limit, $lastId);

            // Каждый договор заново запишем в массив с ID бокса в качестве индекса
            foreach ($contracts as $key => $contract) {
                $contracts[$contract->getBoxId()] = $contract;
                unset($contracts[$key]);
            }

            // Получим массив записей "ID бокса => ID склада"
            $storagesIds = array();
            foreach ($boxes as $box) {
                $storagesIds[$box->getId()] = $box->getSectionId();
            }

            // Получим список складов одним запросом
            $storageService = new StorageService();
            $storages = $storageService->getStorages($storagesIds, null, null);

            // Перезапишем массив складов, используя в качестве ключей ID склада
            foreach ($storages as $key => $storage) {
                $storages[$storage->getId()] = $storage;
                unset($storages[$key]);
            }

            // Создадим массив вида "ID бокса => объект склада"
            $storagesMapped = array();
            foreach ($storagesIds as $key => $storagesId) {
                if ($storages[$storagesId]) {
                    $storagesMapped[$key] = $storages[$storagesId];
                }
            }


            foreach ($boxes as $box) {
                $boxId = (int) $box->getId();
                $contract = $contracts[$boxId];
                $storage = $storagesMapped[$boxId];

                $documentBalance = $contract->getBalance();
                if ($documentBalance != 0) {
                    $documentBalance = $documentBalance * (-1);
                }

                $result[] = [
                    'id' => $boxId,
                    'videoUrl' => (string) $box->getVideoUrl(),
                    'addressStore' => $storage->getAddress(),
                    'number' => (string) $box->getNumber(),
                    'floor' => (int) $box->getFloor(),
                    'space' => (float) $box->getSquare(),
                    'volume' => (float) $box->getVolume(),
                    'documentNumber' => (string) $contract->getNumber(),
                    'payedTill' => $contract->getPaidDateTo(true),
                    'documentBalance' => $documentBalance,
                ];
            }
        }

        $apiCode = 200;
        $actionResult = new ActionResult();
        $actionResult->setParams($result);
        $actionResult->setApiCode($apiCode);
        return $actionResult;
    }

}