<?
if ($arResult['ITEMS']) {
	$arSectionsIDs = array();

	foreach ($arResult['ITEMS'] as $key => $arItem) {
		$arResult['ITEMS'][$key]['DETAIL_PAGE_URL'] = CPriority::FormatNewsUrl($arItem);
		CPriority::getFieldImageData($arResult['ITEMS'][$key], array('PREVIEW_PICTURE'));
		if ($SID = $arItem['DISPLAY_PROPERTIES']['LINK_SKLAD']['VALUE']) {
			$arSectionsIDs[] = $SID;
		}
	}

	array_unique($arSectionsIDs);

	if ($arSectionsIDs) {
		$arResult['BOXES'] = CCache::CIBLockSection_GetList(
			array(
				'SORT' => 'ASC',
				'NAME' => 'ASC',
				'CACHE' => array(
					'TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']),
					'GROUP' => array('ID'),
					'MULTI' => 'N'
				)
			),
			array('IBLOCK_ID' => STORAGES_CATALOG_IBLOCK, 'ID' => $arSectionsIDs),
			false,
			array('ID', 'NAME', 'UF_ADDRESS')
		);
	}

	foreach ($arResult['ITEMS'] as $key => $arItem) {
		// if ($arResult['ITEMS'][$key]['ADDRESS'] = $arResult['BOXES'][$arItem['DISPLAY_PROPERTIES']['LINK_SKLAD']['VALUE']]['UF_ADDRESS']) {
		// 	$arResult['ITEMS'][$key]['ADDRESS'] = trim(str_replace(['г.Москва,', 'г. Москва,'], '', $arResult['ITEMS'][$key]['ADDRESS']));
		// 	if (mb_strpos($arResult['ITEMS'][$key]['ADDRESS'], 'ул.') === 0) {
		// 		$arResult['ITEMS'][$key]['ADDRESS'] = 'Склад на ' . $arResult['ITEMS'][$key]['ADDRESS'];
		// 	} else {
		// 		$arResult['ITEMS'][$key]['ADDRESS'] = 'Склад в г.' . $arResult['ITEMS'][$key]['ADDRESS'];
		// 	}
		// }
	}

	$arResult['COUNT_ELEMENTS'] = CCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'), array());
}
