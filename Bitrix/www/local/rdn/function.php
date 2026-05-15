<?
\Bitrix\Main\Loader::includeModule('catalog');
\Bitrix\Main\Loader::includeModule('iblock');
/**
 * Exchange_1C
 *
 * класс занимается обработкой информации пришедшей из 1С
 * состоит из статичных функций, каждая из которых отвечает за обработку своих данных (склады, боксы, товары и т.п.)
 */
class Exchange_1C
{
	
	static function get_filials($iblock_inf, $data_filials)
	{
		foreach($data_filials as $filial)
		{
			$arFields = array();
			$bs = new CIBlockSection;
			$res_fil=CIBlockSection::GetList(array(),array("IBLOCK_ID"=>$iblock_inf['ID'],"XML_ID"=> $filial['XML_ID']),true,$arSelect=Array("UF_*"))->GetNext();
			if(isset($res_fil) && empty($res_fil))
			//{
				//$arFields = array("UF_ADDRESS"=>$filial['a_adress']);
				//$res = $bs->Update($res_fil["ID"], $arFields); 
			//}
			//else
			{
				$params = Array(
							"max_len" => "100", // обрезает символьный код до 100 символов
							"change_case" => "L", // буквы преобразуются к нижнему регистру
							"replace_space" => "_", // меняем пробелы на нижнее подчеркивание
							"replace_other" => "_", // меняем левые символы на нижнее подчеркивание
							"delete_repeat_replace" => "true", // удаляем повторяющиеся нижние подчеркивания
							"use_google" => "false", // отключаем использование google
						); 

				$SECTION_CODE = CUtil::translit($filial['name'], "ru" , $params);
				$rs = CUserFieldEnum::GetList(array(), array("USER_FIELD_ID" => 75, "VALUE"=> $filial['count_levels']))->Fetch();
				
				$arFields = array("IBLOCK_ID" =>$iblock_inf['ID'], "NAME"=>$filial['name'], "XML_ID"=>$filial['XML_ID'], "CODE" =>$SECTION_CODE, "UF_ADDRESS"=>$filial['a_adress'], "UF_FLOORS" => $rs["ID"]);
				$res = $bs->Add($arFields);	

				if(!$res)
				{
					AddMessage2Log($bs->LAST_ERROR);
					return false;
				}
			}
			
		}
		return true;
	}
	
	static function get_group_prices($data_group_prices)
	{
		foreach($data_group_prices as $group_prices)
		{
			$arFields = array();
			$res_prices = CCatalogGroup::GetList(array(),array('XML_ID'=> $group_prices['XML_ID']))->GetNext();
			if(empty($res_prices))
			{
				$arFields = array("NAME"=> $group_prices['name'], 
								  "XML_ID"=> $group_prices['XML_ID'],
								  "NAME_LANG"=> $group_prices['name'],
								  "USER_GROUP" => array(2),
								  "USER_GROUP_BUY" => array(2),
								  "USER_LANG" => array(
														"ru" => $group_prices['name']
													)
								);
				$res = CCatalogGroup::Add($arFields);
				if(!$res)
				{
					return false;
				}
			}
		}
	return true;		
	}

	static function get_levels($iblock_inf, $data_levels)
	{
		$CODE = 'FLOOR';
		$property = CIBlockProperty::GetByID($CODE, $iblock_inf['ID'])->GetNext();
		$PROPERTY_ID = $property['ID'];
		$property_enums = CIBlockPropertyEnum::GetList(array(), array("IBLOCK_ID"=>$iblock_inf['ID'], "CODE"=>$CODE));
		while($enum_fields = $property_enums->GetNext())
		{
			$temp[]['name'] = $enum_fields["VALUE"];
		}
		$diff = array_diff_assoc($data_levels, $temp); 
		$ibpenum = new CIBlockPropertyEnum;
		if(count($diff) > 0)
		{
			foreach($diff as $key)
			{
				if(!$PropID = $ibpenum->Add(Array('PROPERTY_ID'=>$PROPERTY_ID, 'VALUE'=>$key['name'])))
				{
					return false;
				}
			}		
		}
		return true;		
	}
	//TODO
	//слить get_rents,get_status,get_boxing_category в одну функцию
	
	static function get_rents($iblock_inf, $data_rents)
	{
		$CODE = 'RENT_TYPE';
		$property = CIBlockProperty::GetByID($CODE, $iblock_inf['ID'])->GetNext();
		$PROPERTY_ID = $property['ID'];
		foreach($data_rents as $rents)
		{
			$ibpenum = new CIBlockPropertyEnum;
			$property_enums = CIBlockPropertyEnum::GetList(array(), array("IBLOCK_ID"=>$iblock_inf['ID'], "CODE"=>$CODE, "XML_ID" => $rents['XML_ID']))->GetNext();
			if(empty($property_enums))
			{
				if(!$PropID = $ibpenum->Add(Array('PROPERTY_ID'=>$PROPERTY_ID, 'VALUE'=>$rents['name'], 'XML_ID'=> $rents['XML_ID'])))
				{
					return false;
				}
			}
		}
		return true;
	}

	static function get_status($iblock_inf, $data_status_boxes)
	{
		$CODE = 'STATUS';
		$property = CIBlockProperty::GetByID($CODE, $iblock_inf['ID'])->GetNext();
		$PROPERTY_ID = $property['ID'];
		foreach($data_status_boxes as $status_boxes)
		{
			$ibpenum = new CIBlockPropertyEnum;
			$property_enums = CIBlockPropertyEnum::GetList(array(), array("IBLOCK_ID"=>$iblock_inf['ID'], "CODE"=>$CODE, "XML_ID" => $status_boxes['XML_ID']))->GetNext();
			if(empty($property_enums))
			{
				if(!$PropID = $ibpenum->Add(Array('PROPERTY_ID'=>$PROPERTY_ID, 'VALUE'=>$status_boxes['name'], 'XML_ID'=> $status_boxes['XML_ID'])))
				{
					return false;
				}
			}
		}
		return true;
	}
	
	static function get_boxing_category($iblock_inf, $data_boxing_category)
	{
		$CODE = 'BOXING_CATEGORY';
		$property = CIBlockProperty::GetByID($CODE, $iblock_inf['ID'])->GetNext();
		$PROPERTY_ID = $property['ID'];
		foreach($data_boxing_category as $boxing_category)
		{
			$ibpenum = new CIBlockPropertyEnum;
			$property_enums = CIBlockPropertyEnum::GetList(array(), array("IBLOCK_ID"=>$iblock_inf['ID'], "CODE"=>$CODE, "XML_ID" => $boxing_category['XML_ID']))->GetNext();
			if(empty($property_enums))
			{
				if(!$PropID = $ibpenum->Add(Array('PROPERTY_ID'=>$PROPERTY_ID, 'VALUE'=>$boxing_category['name'], 'XML_ID'=> $boxing_category['XML_ID'])))
				{
					return false;
				}
			}
		}
		return true;
	}
	
	static function get_full_boxes($iblock_inf, $data_full_boxes)
	{
		$arSelect = Array("ID", "NAME", "IBLOCK_ID", "PROPERTY_*");
		foreach($data_full_boxes as $full_boxes)
		{
			$el = new CIBlockElement;
			$arBoxFields = array();
			$arTemp = array();
			$arTemp = array(
				"CODE_1C"=> $full_boxes['code'], 
				"SQUARE"=> $full_boxes['size'], 
				'BOX_NUMBER'=> $full_boxes['name'],
				"WIDTH"=> $full_boxes['width'], 
				"HEIGHT"=> $full_boxes['height'], 
				"LENGTH"=> $full_boxes['length'], 
				"PLACE"=> $full_boxes['place'], 
			);
			$arFilter = Array("IBLOCK_ID"=> $iblock_inf['ID'], "XML_ID"=>$full_boxes["XML_ID"]);
			$res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect)->GetNext();
			if(!empty($res))
			{
				$arBoxFields = array('NAME'=> $full_boxes['description']);
				$res = $el->Update($res['ID'], $arBoxFields);
				CIBlockElement::SetPropertyValuesEx($res['ID'], $iblock_inf['ID'], $arTemp);
			}
			else
			{
				$IBLOCK_SECTION_ID = self::get_section_for_boxes($iblock_inf['ID'], $full_boxes['filial']);
				
				$arBoxFields = array(
				"IBLOCK_ID"=>$iblock_inf['ID'],
				"IBLOCK_SECTION_ID"=>$IBLOCK_SECTION_ID,
				"XML_ID"=> $full_boxes['XML_ID'], 
				"NAME"=>$full_boxes['description'],
				"PROPERTY_VALUES"=> $arTemp,
				);
				$temp_ID = $el->Add($arBoxFields);
				if(!$temp_ID)
				{
					AddMessage2Log($el->LAST_ERROR);
					continue;
				}
				CCatalogProduct::Add(array("ID"=> $temp_ID, "AVAILABLE"=> "Y", "TYPE"=> \Bitrix\Catalog\ProductTable::TYPE_PRODUCT, "MEASURE"=> 6));
			}
		}
		return true;
	}
	
	static function get_change_boxes($iblock_inf, $data_change_boxes)
	{
		$arSelect = Array("ID", "NAME", "IBLOCK_ID", "PROPERTY_*", 'IBLOCK_SECTION_ID');
		foreach($data_change_boxes as $change_boxes)
		{
			$el = new CIBlockElement;
			$arBoxFields = array();
			$arTemp = array();
			$arTemp = array(
				"CODE_1C"=> $change_boxes['code'], 
				"VOLUME"=> $change_boxes['space'],
				"SQUARE"=> $change_boxes['ph_size'], 
				'BOX_NUMBER'=> $change_boxes['name'], 
				"BOX_TYPE"=> $change_boxes['type_box'], 
				"DOORWAY_WIDTH"=> $change_boxes['doorway_width'], 
				"THIS_CELL"=> $change_boxes['this_cell'], 
				"NAME_FOR_SITE"=> $change_boxes['name_for_site'], 
				"LIGHTING"=> $change_boxes['lighting'], 
				"PRICE_GUARANTEE"=> $change_boxes['price_guarantee'], 
				"DEPOSIT"=> $change_boxes['deposit'],
				"PRICE_INSURANCE"=> $change_boxes['insurance'],
				"WIDTH"=> $change_boxes['width'], 
				"HEIGHT"=> $change_boxes['height'], 
				"LENGTH"=> $change_boxes['length'], 
				"PLACE"=> $change_boxes['place'], 
			);
			
			//проходимся по свойствам типа список FLOOR, RENT_TYPE, STATUS, BOXING_CATEGORY
			$idFLOOR = self::get_id_proplist("FLOOR", $iblock_inf['ID'], $change_boxes['level']);
			if($idFLOOR)
			{
				$arTemp['FLOOR'] = $idFLOOR;
			}
			$idRENT_TYPE = self::get_id_proplist("RENT_TYPE", $iblock_inf['ID'], $change_boxes['rents_xmlid']);
			if($idRENT_TYPE)
			{
				$arTemp['RENT_TYPE'] = $idRENT_TYPE;
			}
			$idSTATUS = self::get_id_proplist("STATUS", $iblock_inf['ID'], $change_boxes['status_xmlid']);
			if($idSTATUS)
			{
				$arTemp['STATUS'] = $idSTATUS;
				//AddMessage2Log('Статус текущего бокса ('.$change_boxes["XML_ID"].') - '.$idSTATUS.' ('.$change_boxes['status_xmlid'].')');
			}
			else
			{
				AddMessage2Log('Для бокса XML, которого = '.$change_boxes["XML_ID"]." Не удалось записать статус - ".$change_boxes['status_name'].' ('.$change_boxes['status_xmlid'].')');
			}
			$idSTATUS = self::get_id_proplist("BOXING_CATEGORY", $iblock_inf['ID'], $change_boxes['boxing_category_xml_id']);
			if($idSTATUS)
			{
				$arTemp['BOXING_CATEGORY'] = $idSTATUS;
			}

			$IBLOCK_SECTION_ID = self::get_section_for_boxes($iblock_inf['ID'], $change_boxes['filial']);
			
			$res_prices = CCatalogGroup::GetList(array(),array('XML_ID'=> $change_boxes['price_xmlid']))->GetNext();
			$arFilter = Array("IBLOCK_ID"=> $iblock_inf['ID'], "XML_ID"=>$change_boxes["XML_ID"]);
			$res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect)->GetNext();
			if(!empty($res))
			{	
				$arBoxFields = array('NAME'=> $change_boxes['name']." ".$change_boxes['type_box']." ".$change_boxes['ph_size']." кв м");
				if($change_boxes['delete_mark'] === "Да")
				{
					$arBoxFields['ACTIVE'] = 'N';
				}
				elseif($change_boxes['delete_mark'] === "Нет")
				{
					if($arTemp['STATUS'] == 346)
					{ //id статуса Свободен
						$arBoxFields['ACTIVE'] = 'Y';
					}
				}
				// Привязывает бокс/ячейку к складу если не привязана 29.03.2022
                // https://webpult.bitrix24.ru/company/personal/user/16/tasks/task/view/2786/
                if(!$res['IBLOCK_SECTION_ID']){
                    $arBoxFields['IBLOCK_SECTION_ID'] = $IBLOCK_SECTION_ID;
                }
				$el_res = $el->Update($res['ID'], $arBoxFields);
				//debug
				if(!$el_res)
				{
					AddMessage2Log('Ошибка при добавлении элемента '.$res['ID'].' xml_id '.$change_boxes["XML_ID"]);
				}
				CIBlockElement::SetPropertyValuesEx($res['ID'], $iblock_inf['ID'], $arTemp);
				$temp_ID = $res['ID'];
			}
			else
			{
			    $arBoxFields = array(
				"IBLOCK_ID"=>$iblock_inf['ID'],
				"IBLOCK_SECTION_ID"=>$IBLOCK_SECTION_ID,
				"XML_ID"=> $change_boxes['XML_ID'], 
				"NAME"=> $change_boxes['name']." ".$change_boxes['type_box']." ".$change_boxes['ph_size']." кв м",
				"PROPERTY_VALUES"=> $arTemp,
				);
				$temp_ID = $el->Add($arBoxFields);
				if(!$temp_ID)
				{
					AddMessage2Log($el->LAST_ERROR);
					continue;
				}
				CCatalogProduct::Add(array("ID"=> $temp_ID, "AVAILABLE"=> "Y", "TYPE"=> \Bitrix\Catalog\ProductTable::TYPE_PRODUCT, "MEASURE"=> 6));
			}
			
			if(!empty($res_prices))
			{
				$r_prices = self::set_element_prices($temp_ID, $res_prices['ID'], $change_boxes['price']);
				if(!$r_prices)
				{
					AddMessage2Log('Ошибка при добавлении цены для товара '.$temp_ID.' с xml_id цены '. $change_boxes['price_xmlid']);
				}
			}	
		}
		return true;
	}
	
	private static function get_id_proplist($nameCode, $idIblock, $val )
	{
		if($nameCode == "FLOOR")
		{
			$res_enum = CIBlockPropertyEnum::GetList(Array("SORT"=>"ASC", "VALUE"=>"ASC"), Array("IBLOCK_ID"=>$idIblock, "CODE"=> $nameCode, "VALUE"=>$val))->GetNext();
		}
		else
		{
			$res_enum = CIBlockPropertyEnum::GetList(Array("SORT"=>"ASC", "VALUE"=>"ASC"), Array("IBLOCK_ID"=>$idIblock, "CODE"=> $nameCode, "XML_ID"=>$val))->GetNext();
		}
		if(isset($res_enum['ID']) && !empty($res_enum['ID']))
		{
			return $res_enum['ID'];
		}
		else
		{
			return false;
		}
	}

	private static function get_section_for_boxes($iblock_id_temp, $xml_id_temp)
	{
		$res_fil=CIBlockSection::GetList(array(),array("IBLOCK_ID"=>$iblock_id_temp,"XML_ID"=> $xml_id_temp),true, Array("ID","IBLOCK_ID"))->GetNext();
		if(isset($res_fil) && !empty($res_fil))
		{
			$IBLOCK_SECTION_ID = $res_fil['ID'];
		}
		else{
			$IBLOCK_SECTION_ID = false;
		}
		return $IBLOCK_SECTION_ID;
	}

	private static function set_element_prices($arElementID, $id_prices, $price)
	{
		$arFieldsPrice = Array(
					"PRODUCT_ID" => $arElementID, //Код продукта
					"CATALOG_GROUP_ID" => $id_prices, //Код типа цены
					"PRICE" => $price, //Цена
					"CURRENCY" => "RUB", // Валюта
				);
				
				$dbPrice = \Bitrix\Catalog\Model\Price::getList([
					"filter" => array(
						"PRODUCT_ID" => $arElementID,
						"CATALOG_GROUP_ID" => $id_prices
				)]);
				
				
				if ($arPrice = $dbPrice->fetch()) 
				{
					$result = \Bitrix\Catalog\Model\Price::update($arPrice["ID"], $arFieldsPrice);                        
					if ($result->isSuccess())
					{
						return true; //цена обновлена
					} 
					else 
					{
						return false; //ошибка при обновлении цены
					}
				}
				else
				{
					$result = \Bitrix\Catalog\Model\Price::add($arFieldsPrice);
					if ($result->isSuccess())
					{
						return true; //цена добавлена;
					} 
					else
					{
						return false; //ошибка при добавлении цены
					}
				}
	}

}
?>