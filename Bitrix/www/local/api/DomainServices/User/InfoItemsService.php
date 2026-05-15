<?php


namespace Api\DomainServices\User;


use Api\Models\InfoItem;

class InfoItemsService
{
    public function getInfoItems($limit = null, $lastId = null)
    {
        return InfoItem::getInfoItemsList($limit, $lastId);
    }

    public function getInfoItem(int $id)
    {
        return InfoItem::getInfoItem($id);
    }
}