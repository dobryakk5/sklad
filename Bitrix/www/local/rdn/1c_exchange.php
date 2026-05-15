<?
define('BX_SESSION_ID_CHANGE', false);
define('BX_SKIP_POST_UNQUOTE', true);
define('NO_AGENT_CHECK', true);
define("STATISTIC_SKIP_ACTIVITY_CHECK", true);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
require($_SERVER["DOCUMENT_ROOT"] . "/local/rdn/function.php");

if ($_GET["mode"] == "checkauth" && $USER->IsAuthorized()) {
	if (
		(COption::GetOptionString("main", "use_session_id_ttl", "N") == "Y")
		&& (COption::GetOptionInt("main", "session_id_ttl", 0) > 0)
		&& !defined("BX_SESSION_ID_CHANGE")
	) {
		echo "failure\n", GetMessage("CC_BSC1_ERROR_SESSION_ID_CHANGE");
	} else {
		echo "success\n";
		echo session_name() . "\n";
		echo session_id() . "\n";
		echo bitrix_sessid_get() . "\n";
		echo "timestamp=" . time() . "\n";
	}
}

if (
	isset($_GET["mode"])
	&& $_GET["mode"] !== "checkauth"
	&& (check_bitrix_sessid()) //we got valid token from 1C
) {
	$errors = array();
	$errors['code'] = 'false';
	$data_from_1c = json_decode($_POST['data_from_1c'], true);

	if (! empty($data_from_1c)) {
		//            file_put_contents(__DIR__ .'/log_2.txt', print_r($data_from_1c), FILE_APPEND);

		if (!is_dir(__DIR__ . '/logs')) {
			mkdir(__DIR__ . '/logs');
		}

		file_put_contents(__DIR__ . '/logs/log-' . date('Y-m-d') . '.txt', date(DATE_RFC822) . "\n" . print_r($data_from_1c, true) . "\n" . print_r(json_encode($_GET), true), FILE_APPEND);
	}


	$catalog_xml_id = $data_from_1c['catalog_xml_id'];
	$res_iblock = CIBlock::GetList(array(), array("XML_ID" => $catalog_xml_id))->GetNext();

	if (isset($data_from_1c['catalog_name']) && !empty($data_from_1c['catalog_name'])) {
		$ib = new CIBlock;
		$arFieldsIblock = array("NAME" => $data_from_1c['catalog_name']);
		if (!$ib->Update($res_iblock['ID'], $arFieldsIblock)) {
			AddMessage2Log($ib->LAST_ERROR);
		}
	}



	if ($_GET["mode"] === 'box') {


		if ($_GET["type"] === 'full') {
			if (isset($data_from_1c['filials']) && !empty($data_from_1c['filials'])) {
				//file_put_contents(__DIR__ .'/log_filials.txt', date("Y-m-d H:i:s")."\n", FILE_APPEND);
				//file_put_contents(__DIR__ .'/log_filials.txt', print_r($data_from_1c['filials']), FILE_APPEND);

				if (!Exchange_1C::get_filials($res_iblock, $data_from_1c['filials'])) {
					$errors = message_errors('Ошибка при записи Филиалов складов', $errors);
					AddMessage2Log('Ошибка при записи Филиалов складов');
				}
			}

			if (isset($data_from_1c['type_prices']) && !empty($data_from_1c['type_prices'])) {
				if (!Exchange_1C::get_group_prices($data_from_1c['type_prices'])) {
					$errors = message_errors('Ошибка при записи типов цен', $errors);
					AddMessage2Log('Ошибка при записи типов цен');
				}
			}

			if (isset($data_from_1c['levels']) && !empty($data_from_1c['levels'])) {
				if (!Exchange_1C::get_levels($res_iblock, $data_from_1c['levels'])) {
					$errors = message_errors('Ошибка при записи этажей', $errors);
					AddMessage2Log('Ошибка при записи этажей');
				}
			}

			if (isset($data_from_1c['rents']) && !empty($data_from_1c['rents'])) {
				if (!Exchange_1C::get_rents($res_iblock, $data_from_1c['rents'])) {
					$errors = message_errors('Ошибка при записи видов аренды', $errors);
					AddMessage2Log('Ошибка при записи видов аренды');
				}
			}

			if (isset($data_from_1c['status_boxes']) && !empty($data_from_1c['status_boxes'])) {
				if (!Exchange_1C::get_status($res_iblock, $data_from_1c['status_boxes'])) {
					$errors = message_errors('Ошибка при записи статусов боксов', $errors);
					AddMessage2Log('Ошибка при записи статусов боксов');
				}
			}

			if (isset($data_from_1c['boxing_category']) && !empty($data_from_1c['boxing_category'])) {
				if (!Exchange_1C::get_boxing_category($res_iblock, $data_from_1c['boxing_category'])) {
					$errors = message_errors('Ошибка при записи категорий боксов', $errors);
					AddMessage2Log('Ошибка при записи категорий боксов');
				}
			}

			if (isset($data_from_1c['boxes']) && !empty($data_from_1c['boxes'])) {
				if (!Exchange_1C::get_full_boxes($res_iblock, $data_from_1c['boxes'])) {
					$errors = message_errors('Ошибка при записи общей информации по боксам', $errors);
					AddMessage2Log('Ошибка при записи общей информации по боксам');
				}
			}

			print_r(json_encode($errors));

			//$json_str = file_get_contents('php://input');
			// Получить массив
			//$json_obj = json_decode($json_str,true);
			//AddMessage2Log($data_from_1c);
			//AddMessage2Log($catalog_xml_id);

		} elseif ($_GET["type"] === 'change') {

			if (isset($data_from_1c['boxes_ps']) && !empty($data_from_1c['boxes_ps'])) {
				if (!Exchange_1C::get_change_boxes($res_iblock, $data_from_1c['boxes_ps'])) {
					$errors = message_errors('Ошибка при записи изменений по боксам', $errors);
					AddMessage2Log('Ошибка при записи изменений по боксам');
				}
			}

			print_r(json_encode($errors));
			//AddMessage2Log($data_from_1c);

		}
	} elseif ($_GET["mode"] === 'catalog') {
	}
	//then change default to the secure one
	//COption::SetOptionString("catalog", "DEFAULT_SKIP_SOURCE_CHECK", "N");
	//AddMessage2Log("нормас");
}

function message_errors($string_message, $temp = array())
{
	$temp['code'] = 'true';
	$temp['message'][] = $string_message;
	return $temp;
}
