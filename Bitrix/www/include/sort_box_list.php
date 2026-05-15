<style>
.sort-box-list {
	margin-top: 30px;
	/*
	border-bottom: 1px solid #f1f1f1;
	padding-bottom: 30px;
	*/
}
.sort-box-list .sort-item {
	position: relative;
	display: inline-block;
	color: #999;
	margin-left: 10px;
}
.sort-box-list .sort-item.active {
	color: #333;
	margin-right: 25px;
}
.sort-box-list .sort-item.active span::after {
	content: "";
	position: absolute;
	top: 50%;
	margin-top: -6px;
	right: 0;
	margin-right: -15px;
	width: 7px;
	height: 13px;
	background: url('/bitrix/templates/aspro-priority/images/svg/header_icons.svg') 0 -65px no-repeat;
}
.sort-box-list .sort-item.active.desc span::after {
	-webkit-transform: rotate(270deg);
	-moz-transform: rotate(270deg);
	-ms-transform: rotate(270deg);
	-o-transform: rotate(270deg);
	transform: rotate(270deg);
}
.sort-box-list .sort-item.active.asc span::after {
	-webkit-transform: rotate(90deg);
	-moz-transform: rotate(90deg);
	-ms-transform: rotate(90deg);
	-o-transform: rotate(90deg);
	transform: rotate(90deg);
}

.only_cells {
	margin-top: 30px;
	text-align: left;
}
@media(min-width: 992px) {
	.only_cells {
		margin-left: -32px;
	}
}
@media(max-width: 448px) {
	.only_cells {
		margin-top: 30px;
		text-align: left;

        display: inline-grid!important;

	}
}
.only_cells .checkbox_container {
    flex-basis: 40px;
}
.only_cells .checkbox_container .value {
	/*
    position: absolute;
    right: 0;
    margin-right: 20px;
    margin-top: 4px;
	*/
}
.only_cells .checkbox_container .value input[type="checkbox"],
.only_cells .checkbox_container .value input[type="radio"]
{
    display: none;
}
.only_cells .checkbox_container .value input[type="checkbox"] + label,
.only_cells .checkbox_container .value input[type="radio"] + label
{
    z-index: 1;
    cursor: pointer;
}
.only_cells .checkbox_container .value input[type="checkbox"] + label::before,
.only_cells .checkbox_container .value input[type="radio"] + label::before
{
    content: "";
    color: #000;
    display: inline-block;
	font-size: 15px;
	line-height: 20px;
    position: relative;
    text-align: center;
    text-indent: 0px;
    width: 25px;
    height: 25px;
    background: #ffffff;
	border: 1px solid #d0d0d0;
    vertical-align: middle;
    cursor: pointer;
    z-index: 1;
    margin-right: 10px;
}
.only_cells .checkbox_container .value input[type="checkbox"]:checked + label::before,
.only_cells .checkbox_container .value input[type="radio"]:checked + label::before
{
    content: "\2713";
    font-size: 20px;
    color: #ef5a54;
    line-height: 25px;
}
.only_cells {
    display: inline-flex;
}
.checkbox_container {
    margin-right: 25px;
}
.only_cells .checkbox_container .value input[type="radio"]:disabled + label,
.only_cells .checkbox_container .value input[type="radio"]:disabled + label::before {
    cursor: default;
    color: #cccccc;
}


</style>

<?
$sort_default = "catalog_PRICE_1";
$order_default = "asc";

if(array_key_exists("sort", $_REQUEST) && !empty($_REQUEST["sort"])){
	setcookie("boxListSort", $_REQUEST["sort"], 0, SITE_DIR);
	$_COOKIE["boxListSort"] = $_REQUEST["sort"];
}
if(array_key_exists("order", $_REQUEST) && !empty($_REQUEST["order"])){
	setcookie("boxListOrder", $_REQUEST["order"], 0, SITE_DIR);
	$_COOKIE["boxListOrder"] = $_REQUEST["order"];
}

$sort = !empty($_COOKIE["boxListSort"]) ? $_COOKIE["boxListSort"] : $sort_default;
$order = !empty($_COOKIE["boxListOrder"]) ? $_COOKIE["boxListOrder"] : $order_default;

//исправляем несоответсвие имеющейся сортировки и типа размера бокса
global $BOX_LIST_propSize;
if(($BOX_LIST_propSize == "SQUARE") and ($sort == "property_VOLUME")) {
	$sort = "property_SQUARE";
	$_COOKIE["boxListSort"] = $sort;
} elseif(($BOX_LIST_propSize == "VOLUME") and ($sort == "property_SQUARE")) {
	$sort = "property_VOLUME";
	$_COOKIE["boxListSort"] = $sort;
}

//Записываем сортировку в глоб. переменную
global $BOX_LIST_SORT;
global $BOX_LIST_ORDER;
$BOX_LIST_SORT = $sort;
$BOX_LIST_ORDER = $order;


if($order == "asc") {
	$newOrder = "desc";
} else {
	$newOrder = "asc";
}
?>
<div class="row">
	<div class="col-md-12"> <? /* col-md-8 col-xs-12 */?>
		<div class="row">
			<div class="col-md-4 col-xs-12">
				<div class="sort-box-list">
					Сортировать: <a class="sort-item <?if($sort == "property_".$BOX_LIST_propSize) {?><?=$order?> active<?}?>" href="<?=$APPLICATION->GetCurPageParam('sort=property_'.$BOX_LIST_propSize.'&order='.$newOrder, array('sort', 'order'))?>"><span>по размеру бокса</span></a>
								 <a class="sort-item <?if($sort == "catalog_PRICE_1") {?><?=$order?> active<?}?>" href="<?=$APPLICATION->GetCurPageParam('sort=catalog_PRICE_1&order='.$newOrder, array('sort', 'order'))?>"><span>по цене</span></a>
				</div>
			</div>
			<div class="col-md-8 col-xs-12">
				<div class="only_cells">
                    <div class="checkbox_container">
                        <div class="value">
                            <input type="radio" name="object_type" value="all" <?if($_COOKIE["boxListFilterOnly"] == ""){?>checked="checked"<?}?>><label>Все</label>
                        </div>
                    </div>
                    <div class="checkbox_container">
                        <div class="value">
                            <input type="radio" name="object_type" value="cells" <?if($_COOKIE["boxListFilterOnly"] == "cells"){?>checked="checked"<?}?> disabled><label>Только ячейки</label>
                        </div>
                    </div>

                    <div class="checkbox_container">
                        <div class="value">
                            <input type="radio" name="object_type" value="containers" <?if($_COOKIE["boxListFilterOnly"] == "containers"){?>checked="checked"<?}?>><label>Только контейнеры</label>
                        </div>
                    </div>

                    <div class="checkbox_container">
                        <div class="value">
                            <input type="radio" name="object_type" value="antresolbox" <?if($_COOKIE["boxListFilterOnly"] == "antresolbox"){?>checked="checked"<?}?>><label>Только антресольные боксы</label>
                        </div>
                    </div>
				</div>				
			</div>
		</div>	
	</div>
</div>
