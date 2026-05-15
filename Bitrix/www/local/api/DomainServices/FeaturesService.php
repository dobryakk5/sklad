<?php

namespace Api\DomainServices;


use Api\Models\BusinessFeature;
use Api\Services\ActionResult;

class FeaturesService
{
    public function getFeatures($limit = null, $lastId = null)
    {
        $features = BusinessFeature::getAfterId($lastId, $limit);
        return $features;
    }
}