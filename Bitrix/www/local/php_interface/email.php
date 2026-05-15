<?php
use Bitrix\Highloadblock\HighloadBlockTable as HLBT;

class EmailConfirm
{

    private const MY_HL_BLOCK_ID = 5;

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

    public function add( $ver_code) {

        if (! $this->hashExist($ver_code)) {
            $result = $this->entity_data_class::add(array(
                'UF_EHASH' => $ver_code,
            ));
        }
    }

    public function hashExist($hash) {
        $rsData = $this->entity_data_class::getList(array(
            'order' => array("ID" => "DESC"),
            'filter' => array("UF_EHASH" => $hash),
            'select' => array('ID', 'UF_EHASH')
        ));
        if($el = $rsData->fetch()){
            return $el["UF_EHASH"];
        } else {
            return false;
        }
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