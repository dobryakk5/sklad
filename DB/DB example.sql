-- 1. Свойства боксов
SELECT id, name, code, property_type, link_iblock_id, multiple
FROM b_iblock_property WHERE iblock_id = 40 ORDER BY sort;

id |name                                   |code             |property_type|link_iblock_id|multiple|
---+---------------------------------------+-----------------+-------------+--------------+--------+
640|Тип объекта хранения                   |OBJECT_TYPE      |L            |             0|N       |
484|Статус                                 |STATUS           |L            |             0|N       |
480|Площадь                                |SQUARE           |N            |             0|N       |
492|Объем                                  |VOLUME           |N            |             0|N       |
493|Ширина дверного проема                 |DOORWAY_WIDTH    |N            |             0|N       |
479|Этаж                                   |FLOOR            |L            |             0|N       |
592|Активность ссылки на видеонаблюдение   |VIDEO_LINK_ACTIVE|L            |             0|N       |
591|Ссылка на видеонаблюдение              |VIDEO_LINK       |S            |             0|N       |
498|Координаты области бокса на карте этажа|MAP_COORDS       |S            |             0|N       |
497|Освещение                              |LIGHTING         |S            |             0|N       |
496|Категория                              |BOXING_CATEGORY  |L            |             0|N       |
495|Название для сайта                     |NAME_FOR_SITE    |S            |             0|N       |
494|Это ячейка                             |THIS_CELL        |S            |             0|N       |
482|Тип бокса                              |BOX_TYPE         |S            |             0|N       |
491|Код из 1С                              |CODE_1C          |S            |             0|N       |
490|Ставка депозита %                      |DEPOSIT          |N            |             0|N       |
489|Страховка, руб.                        |PRICE_INSURANCE  |S            |             0|N       |
488|Гарантийный депозит, руб.              |PRICE_GUARANTEE  |S            |             0|N       |
485|Дополнительные фото                    |GALLERY          |F            |             0|Y       |
481|Вид аренды                             |RENT_TYPE        |L            |             0|N       |
483|Номер бокса                            |BOX_NUMBER       |S            |             0|N       |

-- 2. Значения свойств одного бокса
SELECT ip.code, ipv.value, ipv.value_num, ipv.value_enum
FROM b_iblock_element ie
JOIN b_iblock_element_property ipv ON ipv.iblock_element_id = ie.id
JOIN b_iblock_property ip ON ip.id = ipv.iblock_property_id
WHERE ie.iblock_id = 40 AND ie.active = 'Y'
LIMIT 1;

code |value|value_num|value_enum|
-----+-----+---------+----------+
FLOOR|319  |         |       319|

-- 3. Свойства тарифов
SELECT id, name, code, property_type, link_iblock_id
FROM b_iblock_property WHERE iblock_id = 27 ORDER BY sort;

id |name                                                       |code                |property_type|link_iblock_id|
---+-----------------------------------------------------------+--------------------+-------------+--------------+
214|Цена за 1 месяц                                            |TARIF_PRICE_1       |S            |              |
215|Цена за 1 месяц (служебное свойство)                       |FILTER_PRICE_1      |N            |              |
216|Показывать кнопку "В корзину"                              |FORM_ORDER          |L            |              |
224|Цена за 3 месяца (служебное свойство)                      |FILTER_PRICE_2      |S            |              |
231|Окрашивать иконку в цвет темы                              |BACKGROUND          |L            |              |
230|Платежи                                                    |PAYMENTS            |S            |              |
229|Выводить только одну цену                                  |ONLY_ONE_PRICE      |L            |              |
228|Цена за 1 год  (служебное свойство)                        |FILTER_PRICE_DEFAULT|S            |              |
227|Цена за 1 год                                              |TARIF_PRICE_DEFAULT |S            |              |
226|Цена за 6 месяцев (служебное свойство)                     |FILTER_PRICE_3      |S            |              |
225|Цена за 6 месяцев                                          |TARIF_PRICE_3       |S            |              |
223|Цена за 3 месяца                                           |TARIF_PRICE_2       |S            |              |
222|Хит продаж                                                 |HIT                 |L            |              |
221|Иконка                                                     |ICON                |F            |              |
220|Показывать на главной                                      |SHOW_ON_INDEX_PAGE  |L            |              |
219|Срок действия лицензии                                     |DURATION            |S            |              |
218|Visa, Master Card,  МИР                                    |VISA                |S            |              |
217|Оборот                                                     |TURN                |L            |              |
232|Круглосуточная поддержка и консультации через тикет-систему|SUPPORT             |L            |              |
233|Уведомление по почте и смс                                 |NOTIFICATION        |L            |              |
234|Родительский контроль                                      |CONTROL             |L            |              |

-- 4. Поля сделки CRM
DESCRIBE b_crm_deal;

 -- 'sitemanager.b_crm_deal' doesn't exist

-- 5. Свойства секций (склады — адрес, координаты и т.д.)
SELECT * -- id, name, code, property_type
FROM b_iblock_property
WHERE iblock_id = 40 

ID |TIMESTAMP_X        |IBLOCK_ID|NAME                                   |ACTIVE|SORT|CODE             |DEFAULT_VALUE|PROPERTY_TYPE|ROW_COUNT|COL_COUNT|LIST_TYPE|MULTIPLE|XML_ID             |FILE_TYPE               |MULTIPLE_CNT|TMP_ID|LINK_IBLOCK_ID|WITH_DESCRIPTION|SEARCHABLE|FILTRABLE|IS_REQUIRED|VERSION|USER_TYPE|USER_TYPE_SETTINGS|HINT|
---+-------------------+---------+---------------------------------------+------+----+-----------------+-------------+-------------+---------+---------+---------+--------+-------------------+------------------------+------------+------+--------------+----------------+----------+---------+-----------+-------+---------+------------------+----+
479|2026-01-26 16:36:03|       40|Этаж                                   |Y     | 500|FLOOR            |             |L            |        1|       30|L        |N       |                   |                        |           5|      |             0|N               |N         |Y        |Y          |      1|         |a:0:{}            |    |
480|2026-01-26 16:36:03|       40|Площадь                                |Y     | 490|SQUARE           |             |N            |        1|       30|L        |N       |                   |                        |           5|      |             0|N               |N         |N        |Y          |      1|         |a:0:{}            |    |
481|2026-01-26 16:36:03|       40|Вид аренды                             |Y     | 500|RENT_TYPE        |             |L            |        1|       30|L        |N       |                   |                        |           5|      |             0|N               |N         |N        |N          |      1|         |a:0:{}            |    |
482|2026-01-26 16:36:03|       40|Тип бокса                              |Y     | 500|BOX_TYPE         |             |S            |        1|       30|L        |N       |                   |                        |           5|      |             0|N               |N         |N        |N          |      1|         |a:0:{}            |    |
483|2026-01-26 16:36:03|       40|Номер бокса                            |Y     | 500|BOX_NUMBER       |             |S            |        1|       30|L        |N       |                   |                        |           5|      |             0|N               |N         |N        |N          |      1|         |a:0:{}            |    |
484|2026-01-26 16:36:03|       40|Статус                                 |Y     | 100|STATUS           |             |L            |        1|       30|L        |N       |                   |                        |           5|      |             0|N               |N         |Y        |Y          |      1|         |a:0:{}            |    |
485|2026-01-26 16:36:03|       40|Дополнительные фото                    |Y     | 500|GALLERY          |             |F            |        1|       30|L        |Y       |                   |jpg, gif, bmp, png, jpeg|           5|      |             0|Y               |N         |N        |N          |      1|         |a:0:{}            |    |
488|2026-01-26 16:36:03|       40|Гарантийный депозит, руб.              |Y     | 500|PRICE_GUARANTEE  |             |S            |        1|       30|L        |N       |                   |                        |           5|      |             0|N               |N         |N        |N          |      1|         |a:0:{}            |    |
489|2026-01-26 16:36:03|       40|Страховка, руб.                        |Y     | 500|PRICE_INSURANCE  |             |S            |        1|       30|L        |N       |EXT_PRICE_INSURANCE|                        |           5|      |             0|N               |N         |N        |N          |      1|         |a:0:{}            |    |
490|2026-01-26 16:36:03|       40|Ставка депозита %                      |Y     | 500|DEPOSIT          |             |N            |        1|       30|L        |N       |EXT_DEPOSIT        |                        |           5|      |             0|N               |N         |N        |N          |      1|         |a:0:{}            |    |
491|2026-01-26 16:36:03|       40|Код из 1С                              |Y     | 500|CODE_1C          |             |S            |        1|       30|L        |N       |                   |                        |           5|      |             0|N               |N         |Y        |N          |      1|         |a:0:{}            |    |
492|2026-01-26 16:36:03|       40|Объем                                  |Y     | 495|VOLUME           |1            |N            |        1|       30|L        |N       |                   |                        |           5|      |             0|N               |N         |N        |N          |      1|         |a:0:{}            |    |
493|2026-01-26 16:36:03|       40|Ширина дверного проема                 |Y     | 500|DOORWAY_WIDTH    |             |N            |        1|       30|L        |N       |                   |                        |           5|      |             0|N               |N         |N        |N          |      1|         |a:0:{}            |    |
494|2026-01-26 16:36:03|       40|Это ячейка                             |Y     | 500|THIS_CELL        |             |S            |        1|       30|L        |N       |                   |                        |           5|      |             0|N               |N         |N        |N          |      1|         |a:0:{}            |    |
495|2026-01-26 16:36:03|       40|Название для сайта                     |Y     | 500|NAME_FOR_SITE    |             |S            |        1|       30|L        |N       |                   |                        |           5|      |             0|N               |N         |Y        |N          |      1|         |a:0:{}            |    |
496|2026-01-26 16:36:03|       40|Категория                              |Y     | 500|BOXING_CATEGORY  |             |L            |        1|       30|L        |N       |                   |                        |           5|      |             0|N               |N         |N        |N          |      1|         |a:0:{}            |    |
497|2026-01-26 16:36:03|       40|Освещение                              |Y     | 500|LIGHTING         |             |S            |        1|       30|L        |N       |                   |                        |           5|      |             0|N               |N         |N        |N          |      1|         |a:0:{}            |    |
498|2026-01-26 16:36:03|       40|Координаты области бокса на карте этажа|Y     | 500|MAP_COORDS       |             |S            |        1|       30|L        |N       |                   |                        |           5|      |             0|N               |N         |N        |N          |      1|         |a:0:{}            |    |
591|2026-01-26 16:36:03|       40|Ссылка на видеонаблюдение              |Y     | 500|VIDEO_LINK       |             |S            |        1|       50|L        |N       |                   |                        |           5|      |             0|N               |N         |N        |N          |      1|         |a:0:{}            |    |
592|2026-01-26 16:36:03|       40|Активность ссылки на видеонаблюдение   |Y     | 500|VIDEO_LINK_ACTIVE|             |L            |        1|       30|C        |N       |                   |                        |           5|      |             0|N               |N         |N        |N          |      1|         |a:0:{}            |    |
640|2026-01-26 16:36:03|       40|Тип объекта хранения                   |Y     |  50|OBJECT_TYPE      |             |L            |        1|       30|L        |N       |                   |                        |           5|      |             0|N               |Y         |Y        |N          |      1|         |a:0:{}            |    |


SELECT p.code, l.id, l.value, l.xml_id
FROM b_iblock_property_enum l
JOIN b_iblock_property p ON p.id = l.property_id
WHERE p.iblock_id = 40 AND p.code IN ('STATUS','FLOOR','OBJECT_TYPE','BOXING_CATEGORY','RENT_TYPE')
ORDER BY p.code, l.sort;

code           |id |value              |xml_id                              |
---------------+---+-------------------+------------------------------------+
BOXING_CATEGORY|407|Premium            |1332e768-f814-11ea-a22c-e0d55e4ff0fb|
BOXING_CATEGORY|348|Категория для сайта|174a463e-f756-11e9-a226-e0d55e4ff0fb|
BOXING_CATEGORY|404|A                  |f8a742ec-f813-11ea-a22c-e0d55e4ff0fb|
BOXING_CATEGORY|405|B                  |f8a742ed-f813-11ea-a22c-e0d55e4ff0fb|
BOXING_CATEGORY|406|C                  |f8a742ee-f813-11ea-a22c-e0d55e4ff0fb|
FLOOR          |319|1 Этаж             |floor-1                             |
FLOOR          |320|2 Этаж             |floor-2                             |
FLOOR          |321|3 Этаж             |floor-3                             |
FLOOR          |322|4 Этаж             |floor-4                             |
FLOOR          |336|Офисы Склад 7      |0d411336a8f79cac79bf7a21c12907e3    |
FLOOR          |335|Офисы Склад 5      |f7a3c3b02bb2dba250a075d983ce38e2    |
FLOOR          |425|6 Этаж             |floor-6                             |
FLOOR          |409|Контейнеры         |containers                          |
OBJECT_TYPE    |413|Антресольный бокс  |7cbd476492881518af1c99ab8731661b    |
OBJECT_TYPE    |414|Ячейка             |a26397867c2b2a317642aab516714d65    |
OBJECT_TYPE    |415|Контейнер          |af1d376e3baec2d79bacc35510079b18    |
OBJECT_TYPE    |416|Бокс               |73511f295db101f9f853e919b1ad75d2    |
RENT_TYPE      |340|Аренда бокса       |86d80935-dcbf-45b6-b569-2073737afb18|
RENT_TYPE      |339|Аренда ячейки      |040d7a45-e82a-4bb9-a80f-78c89b90aa03|
RENT_TYPE      |337|Аренда офиса       |2eeefe1e-fe88-4c72-8eb8-107136e3bb17|
RENT_TYPE      |402|Хранение шин       |76910cbc-ddfa-11ea-a22c-e0d55e4ff0fb|
RENT_TYPE      |338|Доставка           |b428d7d7-af73-11e9-a224-e0d55e4ff0fb|
RENT_TYPE      |410|Аренда контейнера  |containers                          |
STATUS         |346|Свободен           |5f0282e7-78f3-4a43-b2cd-ca484c9d7d3a|
STATUS         |341|Арендован          |a40a9a9a-0ac3-4a92-901a-1194e4a843d6|
STATUS         |343|Бесхоз             |1204225d-5c21-11e9-a220-e0d55e4ff0fb|
STATUS         |345|Осв. через 14 дней |1d45f702-9083-11e5-ae05-005056c00008|
STATUS         |342|Служебный          |7c52110b-4379-49c4-9e57-67f6b2e351e0|
STATUS         |401|Бокс удален        |94d684c5-b230-11ea-a22c-e0d55e4ff0fb|
STATUS         |344|Осв. через 7 дней  |b68491b4-907d-11e5-ae05-005056c00008|
STATUS         |347|Забронирован       |f77910cc-2961-4c00-b6a1-402c25203e68|

-- Секция бокса хранит тариф?
SELECT bs.id, bs.name, bs.code, bs.description
FROM b_iblock_section bs
WHERE bs.iblock_id = 40
LIMIT 5;

id|name                      |code                        |description                                                                                                                                                                                                                                                    |
--+--------------------------+----------------------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
63|Склад на ш. Энтузиастов   |sklad-na-sh-entuziastov     |Филиал шоссе Энтузиастов/МКАД - это 4 этажа и более 1500 теплых помещений для хранения вещей и товаров.<br>¶ <br>¶ Складской комплекс располагается на границе районов Реутов и Балашихи. <br>¶ Транспортный переулок располагается рядом со съездом с МКАД, по|
64|Склад на Молодогвардейской|sklad-na-molodogvardeyskoy  |<p>¶  Если вы находитесь в ЗАО Москвы и хотите воспользоваться услугой временного хранения вещей, обратитесь в наш склад на Молодогвардейской. Условия аренды бокса там такие же, как в других комплексах АльфаСклад:¶</p>¶<ul>¶ <li>доступ 24/7, </li>¶ <li>ох|
65|Склад на Нагатинской      |sklad-na-nagatinskoy        |<p>¶  Склад на Нагатинской – ТЦ «Конфетти» (ЮАО Москвы) предлагает услугу индивидуального хранения личных вещей. Комплекс принадлежит компании АльфаСклад. Ближайшие к нему станции метро — «Коломенская», «Нагатинская», «Верхние котлы».¶</p>¶<p>¶  В аренду |
66|Склад на Звенигородском ш.|sklad-na-zvenigorodskom-sh  |<p>¶  Для временного или сезонного хранения вещей, которые не умещаются у вас дома, предлагаем воспользоваться услугами нашего склада на Звенигородском шоссе. Поблизости находятся сразу 4 станции метро: «Беговая», «Улица 1905 года», «Выставочная», «МЦК Ше|
67|Склад на Верхнелихоборской|sklad-na-verkhnelikhoborskoy|<p>¶  Наш склад на Верхнелихоборской вмещает более 600 боксов разной площади для хранения личных вещей. Взяв в аренду один из них, вы можете поместить туда мебель, спортинвентарь, детскую коляску и прочие крупные вещи, которым не находится место в квартир|

-- UF-поля на секциях
SELECT * FROM b_user_field
WHERE ENTITY_ID = 'IBLOCK_40_SECTION';

ID |ENTITY_ID        |FIELD_NAME          |USER_TYPE_ID      |XML_ID|SORT|MULTIPLE|MANDATORY|SHOW_FILTER|SHOW_IN_LIST|EDIT_IN_LIST|IS_SEARCHABLE|SETTINGS                                                                                                                                                                                       |
---+-----------------+--------------------+------------------+------+----+--------+---------+-----------+------------+------------+-------------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
 41|IBLOCK_40_SECTION|UF_ADDRESS          |string            |      | 100|N       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:60;s:4:"ROWS";i:2;s:6:"REGEXP";s:0:"";s:10:"MIN_LENGTH";i:0;s:10:"MAX_LENGTH";i:0;s:13:"DEFAULT_VALUE";s:0:"";}                                                              |
 93|IBLOCK_40_SECTION|UF_AKC_ICON         |boolean           |      | 100|N       |N        |N          |Y           |Y           |N            |a:4:{s:13:"DEFAULT_VALUE";s:1:"1";s:7:"DISPLAY";s:8:"CHECKBOX";s:5:"LABEL";a:2:{i:0;s:0:"";i:1;s:0:"";}s:14:"LABEL_CHECKBOX";s:0:"";}                                                          |
 51|IBLOCK_40_SECTION|UF_ARTICLES         |iblock_element    |      | 100|Y       |N        |N          |Y           |Y           |N            |a:5:{s:7:"DISPLAY";s:4:"LIST";s:11:"LIST_HEIGHT";i:10;s:9:"IBLOCK_ID";i:33;s:13:"DEFAULT_VALUE";s:0:"";s:13:"ACTIVE_FILTER";s:1:"Y";}                                                          |
 47|IBLOCK_40_SECTION|UF_BUS_STATION      |string            |      | 100|Y       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:60;s:4:"ROWS";i:1;s:6:"REGEXP";s:0:"";s:10:"MIN_LENGTH";i:0;s:10:"MAX_LENGTH";i:0;s:13:"DEFAULT_VALUE";s:0:"";}                                                              |
 92|IBLOCK_40_SECTION|UF_CALC_DESCR       |string            |      | 100|N       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:60;s:4:"ROWS";i:2;s:6:"REGEXP";s:0:"";s:10:"MIN_LENGTH";i:0;s:10:"MAX_LENGTH";i:0;s:13:"DEFAULT_VALUE";s:0:"";}                                                              |
 95|IBLOCK_40_SECTION|UF_COLOR_METRO      |address           |      | 100|N       |N        |N          |Y           |Y           |N            |a:1:{s:8:"SHOW_MAP";s:1:"Y";}                                                                                                                                                                  |
125|IBLOCK_40_SECTION|UF_CONSULT_PHONE    |string            |      | 100|N       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:20;s:4:"ROWS";i:1;s:6:"REGEXP";s:0:"";s:10:"MIN_LENGTH";i:0;s:10:"MAX_LENGTH";i:0;s:13:"DEFAULT_VALUE";s:0:"";}                                                              |
109|IBLOCK_40_SECTION|UF_CONTAINERS       |boolean           |      | 100|N       |N        |I          |Y           |Y           |Y            |a:4:{s:13:"DEFAULT_VALUE";i:0;s:7:"DISPLAY";s:8:"CHECKBOX";s:5:"LABEL";a:2:{i:0;s:0:"";i:1;s:0:"";}s:14:"LABEL_CHECKBOX";s:0:"";}                                                              |
 45|IBLOCK_40_SECTION|UF_DESCR_DETAIL     |string            |      | 100|N       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:60;s:4:"ROWS";i:5;s:6:"REGEXP";s:0:"";s:10:"MIN_LENGTH";i:0;s:10:"MAX_LENGTH";i:0;s:13:"DEFAULT_VALUE";s:0:"";}                                                              |
139|IBLOCK_40_SECTION|UF_DISTRICT         |enumeration       |      | 100|N       |N        |N          |Y           |Y           |N            |a:4:{s:7:"DISPLAY";s:4:"LIST";s:11:"LIST_HEIGHT";i:1;s:16:"CAPTION_NO_VALUE";s:0:"";s:13:"SHOW_NO_VALUE";s:1:"Y";}                                                                             |
 49|IBLOCK_40_SECTION|UF_DOP_INFO         |string            |      | 100|N       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:60;s:4:"ROWS";i:5;s:6:"REGEXP";s:0:"";s:10:"MIN_LENGTH";i:0;s:10:"MAX_LENGTH";i:0;s:13:"DEFAULT_VALUE";s:0:"";}                                                              |
 43|IBLOCK_40_SECTION|UF_DOSTUP_TIME      |string            |      | 100|N       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:60;s:4:"ROWS";i:1;s:6:"REGEXP";s:0:"";s:10:"MIN_LENGTH";i:0;s:10:"MAX_LENGTH";i:0;s:13:"DEFAULT_VALUE";s:0:"";}                                                              |
141|IBLOCK_40_SECTION|UF_FAQ_ITEMS        |iblock_element    |      | 100|Y       |N        |N          |Y           |Y           |N            |a:5:{s:7:"DISPLAY";s:8:"CHECKBOX";s:11:"LIST_HEIGHT";i:1;s:9:"IBLOCK_ID";i:9;s:13:"DEFAULT_VALUE";s:0:"";s:13:"ACTIVE_FILTER";s:1:"N";}                                                        |
 48|IBLOCK_40_SECTION|UF_FEATURES         |iblock_element    |      | 100|Y       |N        |N          |Y           |Y           |N            |a:5:{s:7:"DISPLAY";s:8:"CHECKBOX";s:11:"LIST_HEIGHT";i:5;s:9:"IBLOCK_ID";i:38;s:13:"DEFAULT_VALUE";s:0:"";s:13:"ACTIVE_FILTER";s:1:"N";}                                                       |
110|IBLOCK_40_SECTION|UF_FILTER_ACTION    |customhtml        |      | 100|N       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:20;s:4:"ROWS";i:1;s:6:"REGEXP";s:0:"";s:10:"MIN_LENGTH";i:0;s:10:"MAX_LENGTH";i:0;s:13:"DEFAULT_VALUE";s:0:"";}                                                              |
 75|IBLOCK_40_SECTION|UF_FLOORS           |enumeration       |      | 100|N       |N        |N          |Y           |Y           |N            |a:4:{s:7:"DISPLAY";s:4:"LIST";s:11:"LIST_HEIGHT";i:7;s:16:"CAPTION_NO_VALUE";s:0:"";s:13:"SHOW_NO_VALUE";s:1:"Y";}                                                                             |
144|IBLOCK_40_SECTION|UF_HOW_USE          |string            |      | 100|N       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:100;s:4:"ROWS";i:10;s:6:"REGEXP";s:0:"";s:10:"MIN_LENGTH";i:0;s:10:"MAX_LENGTH";i:0;s:13:"DEFAULT_VALUE";s:0:"";}                                                            |
111|IBLOCK_40_SECTION|UF_LINK_REGION      |iblock_element    |      | 100|N       |N        |N          |Y           |Y           |N            |a:5:{s:7:"DISPLAY";s:4:"LIST";s:11:"LIST_HEIGHT";i:5;s:9:"IBLOCK_ID";i:1;s:13:"DEFAULT_VALUE";s:0:"";s:13:"ACTIVE_FILTER";s:1:"Y";}                                                            |
 53|IBLOCK_40_SECTION|UF_MAP              |BendersayYandexMap|      | 100|N       |N        |N          |Y           |Y           |N            |a:0:{}                                                                                                                                                                                         |
 76|IBLOCK_40_SECTION|UF_MAP_FLOOR_1      |file              |      | 100|N       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:20;s:10:"LIST_WIDTH";i:200;s:11:"LIST_HEIGHT";i:200;s:13:"MAX_SHOW_SIZE";i:0;s:16:"MAX_ALLOWED_SIZE";i:0;s:10:"EXTENSIONS";a:3:{s:3:"jpg";b:1;s:4:"jpeg";b:1;s:3:"png";b:1;}}|
 77|IBLOCK_40_SECTION|UF_MAP_FLOOR_2      |file              |      | 100|N       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:20;s:10:"LIST_WIDTH";i:200;s:11:"LIST_HEIGHT";i:200;s:13:"MAX_SHOW_SIZE";i:0;s:16:"MAX_ALLOWED_SIZE";i:0;s:10:"EXTENSIONS";a:3:{s:3:"jpg";b:1;s:4:"jpeg";b:1;s:3:"png";b:1;}}|
 78|IBLOCK_40_SECTION|UF_MAP_FLOOR_3      |file              |      | 100|N       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:20;s:10:"LIST_WIDTH";i:200;s:11:"LIST_HEIGHT";i:200;s:13:"MAX_SHOW_SIZE";i:0;s:16:"MAX_ALLOWED_SIZE";i:0;s:10:"EXTENSIONS";a:3:{s:3:"jpg";b:1;s:4:"jpeg";b:1;s:3:"png";b:1;}}|
 79|IBLOCK_40_SECTION|UF_MAP_FLOOR_4      |file              |      | 100|N       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:20;s:10:"LIST_WIDTH";i:200;s:11:"LIST_HEIGHT";i:200;s:13:"MAX_SHOW_SIZE";i:0;s:16:"MAX_ALLOWED_SIZE";i:0;s:10:"EXTENSIONS";a:3:{s:3:"jpg";b:1;s:4:"jpeg";b:1;s:3:"png";b:1;}}|
 80|IBLOCK_40_SECTION|UF_MAP_FLOOR_5      |file              |      | 100|N       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:20;s:10:"LIST_WIDTH";i:200;s:11:"LIST_HEIGHT";i:200;s:13:"MAX_SHOW_SIZE";i:0;s:16:"MAX_ALLOWED_SIZE";i:0;s:10:"EXTENSIONS";a:3:{s:3:"jpg";b:1;s:4:"jpeg";b:1;s:3:"png";b:1;}}|
 46|IBLOCK_40_SECTION|UF_METRO            |string            |      | 100|Y       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:60;s:4:"ROWS";i:1;s:6:"REGEXP";s:0:"";s:10:"MIN_LENGTH";i:0;s:10:"MAX_LENGTH";i:0;s:13:"DEFAULT_VALUE";s:0:"";}                                                              |
 94|IBLOCK_40_SECTION|UF_NAME_CALC        |string            |      | 100|N       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:60;s:4:"ROWS";i:1;s:6:"REGEXP";s:0:"";s:10:"MIN_LENGTH";i:0;s:10:"MAX_LENGTH";i:0;s:13:"DEFAULT_VALUE";s:0:"";}                                                              |
 96|IBLOCK_40_SECTION|UF_ONE              |string            |      | 100|N       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:20;s:4:"ROWS";i:1;s:6:"REGEXP";s:0:"";s:10:"MIN_LENGTH";i:0;s:10:"MAX_LENGTH";i:0;s:13:"DEFAULT_VALUE";s:0:"";}                                                              |
137|IBLOCK_40_SECTION|UF_ONLINE_MANAGER   |boolean           |      | 100|N       |N        |N          |Y           |Y           |N            |a:4:{s:13:"DEFAULT_VALUE";i:0;s:7:"DISPLAY";s:5:"RADIO";s:5:"LABEL";a:2:{i:0;s:0:"";i:1;s:0:"";}s:14:"LABEL_CHECKBOX";s:0:"";}                                                                 |
 44|IBLOCK_40_SECTION|UF_PHONE            |string            |      | 100|N       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:60;s:4:"ROWS";i:1;s:6:"REGEXP";s:0:"";s:10:"MIN_LENGTH";i:0;s:10:"MAX_LENGTH";i:0;s:13:"DEFAULT_VALUE";s:0:"";}                                                              |
 64|IBLOCK_40_SECTION|UF_PHOTOGALLERY     |iblock_section    |      | 100|N       |N        |N          |Y           |Y           |N            |a:5:{s:7:"DISPLAY";s:4:"LIST";s:11:"LIST_HEIGHT";i:8;s:9:"IBLOCK_ID";i:43;s:13:"DEFAULT_VALUE";s:0:"";s:13:"ACTIVE_FILTER";s:1:"N";}                                                           |
 81|IBLOCK_40_SECTION|UF_PRICE_ON_MAP     |url               |      | 100|N       |N        |N          |Y           |Y           |N            |a:5:{s:5:"POPUP";s:1:"Y";s:4:"SIZE";i:20;s:10:"MIN_LENGTH";i:0;s:10:"MAX_LENGTH";i:0;s:13:"DEFAULT_VALUE";s:0:"";}                                                                             |
143|IBLOCK_40_SECTION|UF_PRICES           |string            |      | 100|N       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:100;s:4:"ROWS";i:10;s:6:"REGEXP";s:0:"";s:10:"MIN_LENGTH";i:0;s:10:"MAX_LENGTH";i:0;s:13:"DEFAULT_VALUE";s:0:"";}                                                            |
 42|IBLOCK_40_SECTION|UF_RECEPTION        |string            |      | 100|N       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:60;s:4:"ROWS";i:1;s:6:"REGEXP";s:0:"";s:10:"MIN_LENGTH";i:0;s:10:"MAX_LENGTH";i:0;s:13:"DEFAULT_VALUE";s:0:"";}                                                              |
136|IBLOCK_40_SECTION|UF_RECEPTION_NAME   |string            |      | 100|N       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:20;s:4:"ROWS";i:1;s:6:"REGEXP";s:0:"";s:10:"MIN_LENGTH";i:0;s:10:"MAX_LENGTH";i:0;s:13:"DEFAULT_VALUE";s:0:"";}                                                              |
 88|IBLOCK_40_SECTION|UF_SEO_DESCR_RENTAL |string            |      | 100|N       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:100;s:4:"ROWS";i:2;s:6:"REGEXP";s:0:"";s:10:"MIN_LENGTH";i:0;s:10:"MAX_LENGTH";i:0;s:13:"DEFAULT_VALUE";s:0:"";}                                                             |
 89|IBLOCK_40_SECTION|UF_SEO_H1_RENTAL    |string            |      | 100|N       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:100;s:4:"ROWS";i:2;s:6:"REGEXP";s:0:"";s:10:"MIN_LENGTH";i:0;s:10:"MAX_LENGTH";i:0;s:13:"DEFAULT_VALUE";s:0:"";}                                                             |
 87|IBLOCK_40_SECTION|UF_SEO_KEYWORDS_RENT|string            |      | 100|N       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:100;s:4:"ROWS";i:2;s:6:"REGEXP";s:0:"";s:10:"MIN_LENGTH";i:0;s:10:"MAX_LENGTH";i:0;s:13:"DEFAULT_VALUE";s:0:"";}                                                             |
 86|IBLOCK_40_SECTION|UF_SEO_TITLE_RENTAL |string            |      | 100|N       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:100;s:4:"ROWS";i:2;s:6:"REGEXP";s:0:"";s:10:"MIN_LENGTH";i:0;s:10:"MAX_LENGTH";i:0;s:13:"DEFAULT_VALUE";s:0:"";}                                                             |
108|IBLOCK_40_SECTION|UF_TEXT_BLOCK       |string            |      | 100|N       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:70;s:4:"ROWS";i:8;s:6:"REGEXP";s:0:"";s:10:"MIN_LENGTH";i:0;s:10:"MAX_LENGTH";i:0;s:13:"DEFAULT_VALUE";s:0:"";}                                                              |
140|IBLOCK_40_SECTION|UF_WAREHOUSE_TYPE   |enumeration       |      | 100|Y       |N        |N          |Y           |Y           |N            |a:4:{s:7:"DISPLAY";s:8:"CHECKBOX";s:11:"LIST_HEIGHT";i:6;s:16:"CAPTION_NO_VALUE";s:0:"";s:13:"SHOW_NO_VALUE";s:1:"Y";}                                                                         |
107|IBLOCK_40_SECTION|UF_YANDEX_REVIEW    |string            |      | 100|N       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:20;s:4:"ROWS";i:1;s:6:"REGEXP";s:0:"";s:10:"MIN_LENGTH";i:0;s:10:"MAX_LENGTH";i:0;s:13:"DEFAULT_VALUE";s:0:"";}                                                              |
 70|IBLOCK_40_SECTION|UF_YMAP_RATING      |double            |      | 100|N       |N        |N          |Y           |Y           |N            |a:5:{s:9:"PRECISION";i:1;s:4:"SIZE";i:20;s:9:"MIN_VALUE";d:0;s:9:"MAX_VALUE";d:0;s:13:"DEFAULT_VALUE";s:0:"";}                                                                                 |
 68|IBLOCK_40_SECTION|UF_YMAP_REVIEWS     |double            |      | 100|N       |N        |N          |Y           |Y           |N            |a:5:{s:9:"PRECISION";i:0;s:4:"SIZE";i:20;s:9:"MIN_VALUE";d:0;s:9:"MAX_VALUE";d:0;s:13:"DEFAULT_VALUE";s:0:"";}                                                                                 |
 67|IBLOCK_40_SECTION|UF_YMAP_URL         |string            |      | 100|N       |N        |N          |Y           |Y           |N            |a:6:{s:4:"SIZE";i:80;s:4:"ROWS";i:1;s:6:"REGEXP";s:0:"";s:10:"MIN_LENGTH";i:0;s:10:"MAX_LENGTH";i:0;s:13:"DEFAULT_VALUE";s:0:"";}                                                              |
 69|IBLOCK_40_SECTION|UF_YMAP_VOTES       |double            |      | 100|N       |N        |N          |Y           |Y           |N            |a:5:{s:9:"PRECISION";i:0;s:4:"SIZE";i:20;s:9:"MIN_VALUE";d:0;s:9:"MAX_VALUE";d:0;s:13:"DEFAULT_VALUE";s:0:"";}                                                                                 |

-- Значения UF для одной секции (подставь реальный section_id)
SELECT * FROM b_uts_iblock_40_section LIMIT 3;

VALUE_ID|UF_ADDRESS                                         |UF_RECEPTION|UF_DOSTUP_TIME|UF_PHONE          |UF_DESCR_DETAIL                                             |UF_METRO                                                                         |UF_BUS_STATION                                                              |UF_FEATURES                                                                           |UF_DOP_INFO                                                                                        |UF_ARTICLES                         |UF_MAP                              |UF_PHOTOGALLERY|UF_YMAP_URL                                                             |UF_YMAP_REVIEWS|UF_YMAP_VOTES|UF_YMAP_RATING|UF_FLOORS|UF_MAP_FLOOR_1|UF_MAP_FLOOR_2|UF_MAP_FLOOR_3|UF_MAP_FLOOR_4|UF_MAP_FLOOR_5|UF_PRICE_ON_MAP|UF_SEO_TITLE_RENTAL                                   |UF_SEO_KEYWORDS_RENT                                                                                                                                                                                                       |UF_SEO_DESCR_RENTAL                                                                                          |UF_SEO_H1_RENTAL                |UF_CALC_DESCR                         |UF_AKC_ICON|UF_NAME_CALC          |UF_COLOR_METRO|UF_ONE                                      |UF_YANDEX_REVIEW                                                                                                        |UF_TEXT_BLOCK                                                                                                                                                                                                                                                  |UF_CONTAINERS|UF_FILTER_ACTION|UF_LINK_REGION|UF_CONSULT_PHONE|UF_RECEPTION_NAME|UF_ONLINE_MANAGER|UF_DISTRICT|UF_WAREHOUSE_TYPE                |UF_FAQ_ITEMS|UF_PRICES|UF_HOW_USE|
--------+---------------------------------------------------+------------+--------------+------------------+------------------------------------------------------------+---------------------------------------------------------------------------------+----------------------------------------------------------------------------+--------------------------------------------------------------------------------------+---------------------------------------------------------------------------------------------------+------------------------------------+------------------------------------+---------------+------------------------------------------------------------------------+---------------+-------------+--------------+---------+--------------+--------------+--------------+--------------+--------------+---------------+------------------------------------------------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+-------------------------------------------------------------------------------------------------------------+--------------------------------+--------------------------------------+-----------+----------------------+--------------+--------------------------------------------+------------------------------------------------------------------------------------------------------------------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+-------------+----------------+--------------+----------------+-----------------+-----------------+-----------+---------------------------------+------------+---------+----------+
      63|Балашиха, мкр-н Никольско-Архангельский, ПСЗ, д. 2а|08:30–20:30 |КРУГЛОСУТОЧНО |+7 (495) 154–40–98|Более 1500 складских Боксов (комнат) от 1 кв. м до 60 кв. м.|a:3:{i:0;s:20:"Новокосино";i:1;s:24:"Первомайская";i:2;s:33:"шоссе Энтузиастов";}|a:2:{i:0;s:35:"ж/д станция Стройка";i:1;s:52:"Остановка Платформа Стройка";}|a:8:{i:0;i:214;i:1;i:212;i:2;i:211;i:3;i:224;i:4;i:231;i:5;i:219;i:6;i:208;i:7;i:218;}|Вы можете заказать дополнительные услуги по доставке и упаковке вещей, а также по аренде стеллажей.|a:1:{i:0;i:158;}                    |55.77903016154265,37.865413437445056|            109|https://yandex.ru/maps/org/khraneniye_veshchey_v_alfasklad/1374078280/  |          202.0|        194.0|           4.8|       37|         96315|        721456|        401389|        400447|              |1550 р/м2      |Боксы в аренду на Шоссе Энтузиастов (ВАО) - АльфаСклад|боксы в аренду на шоссе энтузиастов, боксы на шоссе энтузиастов, боксы в аренду в балашихе, боксы в балашихе, боксы в аренду балашиха, боксы балашиха, боксы в аренду реутов, боксы реутов, боксы в аренду мкад, боксы мкад|Арендовать бокс на Шоссе Энтузиастов / МКАД. Онлайн калькулятор для подбора бокса. Онлайн заказ аренды бокса.|Боксы на Шоссе Энтузиастов (ВАО)|&nbsp;                                |          1|Шоссе Энтузиастов/МКАД|#FFD702|;     |Стоимость боксов на первом этаже выше на 15%|<iframe src="https://yandex.ru/sprav/widget/rating-badge/1374078280" width="150" height="50" frameborder="0"></iframe>  |<p>Если вы находитесь в Балашихе, и вам удобно сдать вещи на хранение там, обратитесь на наш склад на шоссе Энтузиастов/МКАД. Это ВАО Москвы, ближайшие станции метро — «Новокосино», «Первомайская», «Шоссе Энтузиастов». </p>¶¶<p>АльфаСклад сдает в аренду б|            0|                |             1|                |                 |                0|         50|a:3:{i:0;i:51;i:1;i:52;i:2;i:54;}|            |         |          |
      64|Молодогвардейская ул., д. 61, стр. 3               |08:30–20:30 |КРУГЛОСУТОЧНО |+7 (495) 154–40–98|Около 1000 помещений от 1 до 60 кв.м.                       |a:3:{i:0;s:20:"Молодёжная";i:1;s:20:"Кунцевская";i:2;s:1:" ";}                   |a:1:{i:0;s:45:"Молодогвардейская улица";}                                   |a:8:{i:0;i:212;i:1;i:211;i:2;i:206;i:3;i:224;i:4;i:222;i:5;i:213;i:6;i:229;i:7;i:208;}|Вы можете заказать дополнительные услуги по доставке и упаковке вещей, а также по аренде стеллажей.|a:1:{i:0;i:162;}                    |55.73371777443598,37.3918122204873  |            110|https://yandex.ru/maps/org/khraneniye_veshchey_v_alfasklad/172915720922/|           47.0|         51.0|           4.4|       36|        739107|        721458|        400660|              |              |1690 р/м2      |Боксы в аренду на Молодогвардейской (ЗАО) - АльфаСклад|боксы в аренду на молодогвардейской                                                                                                                                                                                        |Арендовать бокс на Молодогвардейской. Онлайн калькулятор для подбора бокса. Онлайн заказ аренды бокса.       |Боксы на Молодогвардейской (ЗАО)|                                      |          0|Молодогвардейская     |#0078BE|;     |Стоимость боксов на первом этаже выше на 15%|<iframe src="https://yandex.ru/sprav/widget/rating-badge/172915720922" width="150" height="50" frameborder="0"></iframe>|                                                                                                                                                                                                                                                               |            0|                |             1|                |                 |                0|         47|a:2:{i:0;i:51;i:1;i:52;}         |            |         |          |
      65|Нагатинская ул., д. 16, ТЦ&nbsp«Конфетти»          |08:30–20:30 |КРУГЛОСУТОЧНО |+7 (495) 154–40–98|Более 700 помещений от 1 до 60 кв.м.                        |a:3:{i:0;s:22:"Коломенская";i:1;s:22:"Нагатинская";i:2;s:32:"МЦК Верхние котлы";}|a:2:{i:0;s:40:"2-й Нагатинский проезд";i:1;s:40:"7-й троллейбусный парк";}  |a:8:{i:0;i:214;i:1;i:207;i:2;i:211;i:3;i:222;i:4;i:231;i:5;i:208;i:6;i:205;i:7;i:218;}|Вы можете заказать дополнительные услуги по доставке и упаковке вещей, а также по аренде стеллажей |a:3:{i:0;i:158;i:1;i:161;i:2;i:159;}|55.67890851780649,37.642575864955205|            111|https://yandex.ru/maps/org/khraneniye_veshchey_v_alfasklad/186930539873/|           46.0|         24.0|           4.3|       37|         96318|         96319|         96320|        104519|              |1895 р/м2      |Боксы в аренду на Нагатинской - АльфаСклад            |боксы в аренду на нагатинской, боксы в аренду на коломенской, боксы на нагатинской, боксы на коломенской¶                                                                                                                  |Арендовать бокс на Нагатинской. Онлайн калькулятор для подбора бокса. Онлайн заказ аренды бокса.             |Боксы на Нагатинской            |Склад заканчивает заполнение. Звоните!|          0|Нагатинская           |#999999|;     |Стоимость боксов на первом этаже выше на 15%|                                                                                                                        |                                                                                                                                                                                                                                                               |            0|                |             1|                |                 |                0|         48|a:2:{i:0;i:51;i:1;i:52;}         |            |         |          |

      
SELECT ie.id, ie.name, ie.preview_picture, ie.detail_picture
FROM b_iblock_element ie
WHERE ie.iblock_id = 40 AND ie.active = 'Y'
LIMIT 3;

id  |name             |preview_picture|detail_picture|
----+-----------------+---------------+--------------+
5750|S1 1F36 36 кв м  |           6635|              |
5752|S3 OF19,0 19 кв м|           6637|              |
5753|S4 OF23 23 кв м  |           6638|              |

SHOW TABLES LIKE '%crm%';  - я проверил все пустые

Tables_in_sitemanager (%crm%)   |
--------------------------------+
b_form_crm                      |
b_form_crm_field                |
b_form_crm_link                 |
select * FROM  b_sale_order_converter_crm_error



select * FROM  b_form_crm
Field    |Type        |Null|Key|Default|Extra         |
---------+------------+----+---+-------+--------------+
ID       |int(18)     |NO  |PRI|       |auto_increment|
NAME     |varchar(255)|NO  |   |       |              |
ACTIVE   |char(1)     |YES |   |Y      |              |
URL      |varchar(255)|NO  |   |       |              |
AUTH_HASH|varchar(32) |YES |   |       |              |

select * FROM  b_form_crm_field

Field    |Type        |Null|Key|Default|Extra         |
---------+------------+----+---+-------+--------------+
ID       |int(18)     |NO  |PRI|       |auto_increment|
LINK_ID  |int(18)     |NO  |MUL|0      |              |
FIELD_ID |int(18)     |YES |   |0      |              |
FIELD_ALT|varchar(100)|YES |   |       |              |
CRM_FIELD|varchar(255)|NO  |   |       |              |

select * FROM  b_form_crm_link 
Field    |Type   |Null|Key|Default|Extra         |
---------+-------+----+---+-------+--------------+
ID       |int(18)|NO  |PRI|       |auto_increment|
FORM_ID  |int(18)|NO  |MUL|0      |              |
CRM_ID   |int(18)|NO  |   |0      |              |
LINK_TYPE|char(1)|NO  |   |M      |              |


-- 1. Как цена привязана к боксу?
-- У боксов нет свойства PRICE — значит цена в тарифе iblock 27?
-- Как элемент iblock 27 связан с секцией склада?
SELECT ie.id, ie.name, ie.iblock_section_id, ie.code
FROM b_iblock_element ie
WHERE ie.iblock_id = 27
LIMIT 5;

id |name                   |iblock_section_id|code|
---+-----------------------+-----------------+----+
104|Старт                  |               27|    |
105|Оптимальный            |               27|    |
106|Абсолютная безопасность|               27|    |
101|Первый                 |               28|    |
102|Кибербезопасность      |               28|    |

-- 2. Есть ли STOCK_ID на секциях (для связи с 1С)?
-- Смотрим UF_ONE — в данных там пусто, может это он?
SELECT VALUE_ID, UF_ONE, UF_LINK_REGION, UF_WAREHOUSE_TYPE
FROM b_uts_iblock_40_section
LIMIT 5;

VALUE_ID|UF_ONE                                      |UF_LINK_REGION|UF_WAREHOUSE_TYPE                |
--------+--------------------------------------------+--------------+---------------------------------+
      63|Стоимость боксов на первом этаже выше на 15%|             1|a:3:{i:0;i:51;i:1;i:52;i:2;i:54;}|
      64|Стоимость боксов на первом этаже выше на 15%|             1|a:2:{i:0;i:51;i:1;i:52;}         |
      65|Стоимость боксов на первом этаже выше на 15%|             1|a:2:{i:0;i:51;i:1;i:52;}         |
      66|                                            |             1|a:2:{i:0;i:51;i:1;i:52;}         |
      67|Стоимость боксов на первом этаже выше на 15%|             1|a:3:{i:0;i:51;i:1;i:52;i:2;i:54;}|
      
      
-- 3. Структура b_file — для построения URL фотографий
SELECT id, subdir, file_name, content_type
FROM b_file
WHERE id IN (6635, 6637, 6638);

id  |subdir    |file_name                           |content_type|
----+----------+------------------------------------+------------+
6635|iblock/bad|baddf5dd5b060498833f26439f92e822.jpg|image/jpeg  |
6637|iblock/33e|33e61225a5309d1dc866567b035f3b81.jpg|image/jpeg  |
6638|iblock/c09|c09dcbf01462df96dd50bfea92256705.jpg|image/jpeg  |