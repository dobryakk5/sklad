<?
$arDisplays = array("block", "list", "table");
if(array_key_exists("display", $_REQUEST) || (array_key_exists("display", $_SESSION)) || $arParams["DEFAULT_LIST_TEMPLATE"]){
	if($_REQUEST["display"] && (in_array(trim($_REQUEST["display"]), $arDisplays))){
		$display = trim($_REQUEST["display"]);
		$_SESSION["display"]=trim($_REQUEST["display"]);
	}
	elseif($_SESSION["display"] && (in_array(trim($_SESSION["display"]), $arDisplays))){
		$display = $_SESSION["display"];
	}
	elseif($arSection["DISPLAY"]){
		$display = $arSection["DISPLAY"];
	}
	else{
		$display = $arParams["DEFAULT_LIST_TEMPLATE"];
	}
}
else{
	$display = "block";
}
$template = "catalog_".$display;
?>

<div class="filters-wrap">
    <div class="row">
			<?
			$arAvailableSort = array();
			$arSorts = $arParams["SORT_BUTTONS"];

			if(in_array("POPULARITY", $arSorts)){
				$arAvailableSort["SHOWS"] = array("SHOWS", "desc");
			}
			if(in_array("NAME", $arSorts)){
				$arAvailableSort["NAME"] = array("NAME", "asc");
			}
			if(in_array("PRICE", $arSorts)){
				$arSortPrices = $arParams["SORT_PRICES"];
				if($arSortPrices == "MINIMUM_PRICE" || $arSortPrices == "MAXIMUM_PRICE"){
					$arAvailableSort["PRICE"] = array("PROPERTY_".$arSortPrices, "desc");
				}
				else{
					if($arSortPrices == "REGION_PRICE")
					{
						global $arRegion;
						if($arRegion)
						{
							if(!$arRegion["PROPERTY_SORT_REGION_PRICE_VALUE"] || $arRegion["PROPERTY_SORT_REGION_PRICE_VALUE"] == "component")
							{
								$price = CCatalogGroup::GetList(array(), array("NAME" => $arParams["SORT_REGION_PRICE"]), false, false, array("ID", "NAME"))->GetNext();
								$arAvailableSort["PRICE"] = array("CATALOG_PRICE_".$price["ID"], "desc");
							}
							else
							{
								$arAvailableSort["PRICE"] = array("CATALOG_PRICE_".$arRegion["PROPERTY_SORT_REGION_PRICE_VALUE"], "desc");
							}
						}
						else
						{
							$price_name = ($arParams["SORT_REGION_PRICE"] ? $arParams["SORT_REGION_PRICE"] : "BASE");
							$price = \CCatalogGroup::GetList(array(), array("NAME" => $price_name), false, false, array("ID", "NAME"))->GetNext();
							$arAvailableSort["PRICE"] = array("CATALOG_PRICE_".$price["ID"], "desc");
						}
					}
					else
					{
						$price = CCatalogGroup::GetList(array(), array("NAME" => $arParams["SORT_PRICES"]), false, false, array("ID", "NAME"))->GetNext();
						$arAvailableSort["PRICE"] = array("CATALOG_PRICE_".$price["ID"], "desc");
					}
				}
			}
			if(in_array("QUANTITY", $arSorts)){
				$arAvailableSort["CATALOG_AVAILABLE"] = array("QUANTITY", "desc");
			}
			$sort = "SHOWS";
			if((array_key_exists("sort", $_REQUEST) && array_key_exists(ToUpper($_REQUEST["sort"]), $arAvailableSort)) || (array_key_exists("sort", $_SESSION) && array_key_exists(ToUpper($_SESSION["sort"]), $arAvailableSort)) || $arParams["ELEMENT_SORT_FIELD"]){
				if($_REQUEST["sort"]){
					$sort = ToUpper($_REQUEST["sort"]);
					$_SESSION["sort"] = ToUpper($_REQUEST["sort"]);
				}
				elseif($_SESSION["sort"]){
					$sort = ToUpper($_SESSION["sort"]);
				}
				else{
					$sort = ToUpper($arParams["ELEMENT_SORT_FIELD"]);
				}
			}
			$sort_order=$arAvailableSort[$sort][1];
			if((array_key_exists("order", $_REQUEST) && in_array(ToLower($_REQUEST["order"]), Array("asc", "desc"))) || (array_key_exists("order", $_REQUEST) && in_array(ToLower($_REQUEST["order"]), Array("asc", "desc")) ) || $arParams["ELEMENT_SORT_ORDER"]){
				if($_REQUEST["order"]){
					$sort_order = $_REQUEST["order"];
					$_SESSION["order"] = $_REQUEST["order"];
				}
				elseif($_SESSION["order"]){
					$sort_order = $_SESSION["order"];
				}
				else{
					$sort_order = ToLower($arParams["ELEMENT_SORT_ORDER"]);
				}
			}
            $titleSelected = '';
			?>
        <div class="col-md-7 col-sm-5 col-xs-5 ordering-wrap">

            <div class="filter-action font_xs showen pull-left hidden-lg hidden-md"><span class="with_dropdown">Фильтр</span></div>

                <div class="select-outer">
                    <select class="sort">
                        <?foreach($arAvailableSort as $key => $val):?>
                            <?$newSort = $sort_order == 'desc' ? 'asc' : 'desc';
                            $current_url = $APPLICATION->GetCurPageParam('sort='.$key.'&order='.$newSort, 	array('sort', 'order'));
                            $url = str_replace('+', '%2B', $current_url);
                            $titleSelected = ($sort == $key && !$titleSelected ? GetMessage('SECT_SORT_'.$key) : $titleSelected);?>
                            <option <?=($sort == $key ? "selected='selected'" : "")?>  value="<?=$url?>" class="ordering"><span><?=GetMessage('SECT_SORT_'.$key)?><i class="arr icons_fa"></i></span></option>
                        <?endforeach;?>
                    </select>

                    <div class="sort_desktop with_dropdown font_xs">
                        <span class="selected"><?=$titleSelected;?></span>
                        <div class="dropdown">
                            <div class="wrap">
                                <?foreach($arAvailableSort as $key => $val):?>
                                <?$newSort = $sort_order == 'desc' ? 'asc' : 'desc';
                                $current_url = $APPLICATION->GetCurPageParam('sort='.$key.'&order='.$newSort, 	array('sort', 'order'));
                                $url = str_replace('+', '%2B', $current_url);?>
                                            <div class="ordering<?=($sort == $key ? " selected" : "")?>"><a href="<?=$url?>"><?=GetMessage('SECT_SORT_'.$key)?><i class="arr icons_fa"></i></a></div>
                                <?endforeach;?>
                            </div>
                        </div>
                    </div>
                <?
                if($sort == "PRICE"){
                    $sort = $arAvailableSort["PRICE"][0];
                }
                if($sort == "CATALOG_AVAILABLE"){
                    $sort = "CATALOG_QUANTITY";
                }
                ?>
            </div>
        </div>

        <div class="col-md-5 col-sm-7 col-xs-7 display-type pull-right text-right">

            <?foreach($arDisplays as $displayType):?>
                <?
                $current_url = '';
                $current_url = $APPLICATION->GetCurPageParam('display='.$displayType, 	array('display'));
                $url = str_replace('+', '%2B', $current_url);
                switch ($displayType){
                    case 'block':
                        $ico = 'table';
                        break;
                    case 'list':
                        $ico = 'list';
                        break;
                    case 'table':
                        $ico = 'price';
                        break;
                    default:
                        $ico = 'table';
                        break;

                }

                ?>
                <a rel="nofollow" title="<?=GetMessage("SECT_DISPLAY_".strtoupper($displayType))?>" href="<?=$url;?>" class="view-button view-<?=$displayType?> <?=($display == $displayType ? 'cur' : '')?>">
                    <?=CPriority::showIconSvg(SITE_TEMPLATE_PATH.'/images/include_svg/display_'.$ico.'.svg');?>
                </a>


            <?endforeach;?>
        </div>

	<!--/noindex-->
    </div>
    <div class="clearfix"></div>
</div>