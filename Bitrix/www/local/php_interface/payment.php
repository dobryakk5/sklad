<?php
use Bitrix\Highloadblock\HighloadBlockTable as HLBT;

class Payment
{
    private const MY_HL_BLOCK_ID = 6;
    private $entity_data_class;
    public function __construct()
    {
        $this->entity_data_class = $this->initHLBT();
    }

    private function initHLBT()
    {
        CModule::IncludeModule('highloadblock');
        if (empty(self::MY_HL_BLOCK_ID) || self::MY_HL_BLOCK_ID < 1) {
            return false;
        }
        $hlblock = HLBT::getById(self::MY_HL_BLOCK_ID)->fetch();
        $entity = HLBT::compileEntity(self::MY_HL_BLOCK_ID);
        $entity_data_class = $entity->getDataClass();
        return $entity_data_class;
    }

    public function add( $json) {

        $result = $this->entity_data_class::add(array(
                'UF_JSON_DATA' => $json,
                'UF_IS_SEND' => 'N'
        ));
    }


    public function getItem() {
        $data = [];
        $rsData = $this->entity_data_class::getList(array(
            'order' => array("ID" => "ASC"),
            'filter' => array("UF_IS_SEND" => 'N'),
            'select' => array('ID', 'UF_JSON_DATA'),
            //"limit" => 1,

        ));
        while($el = $rsData->fetch()){
            $data[] =  $el;
        }

        return $data;
    }
    public function updateItem($elId) {
        $result = $this->entity_data_class::update($elId, array(
            'UF_IS_SEND' => 'Y'
        ));
    }

    public function updateItemError($elId, $error) {
        $result = $this->entity_data_class::update($elId, array(
            'UF_DESC' => $error
        ));
    }
    
/*
    public function getDataByUserId($user_id) {
        $rsData = $this->entity_data_class::getList(array(
            'order' => array("ID" => "DESC"),
            'filter' => array("UF_CLIENT_ID" => $user_id),
            'select' => array('*')
        ));
        $data = [];
        if($el = $rsData->fetch()){
            $data =  $el;
            $data['UF_DATE_CONFIRM'] = $el['UF_DATE_CONFIRM']->toString();
            return $data;
        } else {
            return false;
        }
    }

    public function updateUserData($elId, $ver_code, $email) {
        $result = $this->entity_data_class::update($elId, array(
            'UF_VER_CODE' => $ver_code,
            'UF_EMAIL' => $email,
            'UF_DATE_CONFIRM' => \Bitrix\Main\Type\DateTime::createFromTimestamp(time())
        ));
    }

    public function getJsonData($hash) {
        $r = $this->getDataByUserId($user_id);
        $data = [];
        if(! empty($r)) {
            $data['id'] = $r['UF_CLIENT_ID'];
            $data['DateTime'] = $r['UF_DATE_CONFIRM'];
            $data['VerCode'] = $r['UF_VER_CODE'];
            $data['email'] = $r['UF_EMAIL'];
        } else {
            $data['error'] = 'User not found';
        }
        return json_encode($data);
    }
*/
}