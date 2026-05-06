workspace "Потоки данных в /exchange/index.php" "Обмен Б24/CRM/1С ↔ сайт Bitrix" {

    !identifiers hierarchical

    model {
        b24 = softwareSystem "Б24 / CRM / 1С" "Внешний источник HTTP-запросов: key + action + data." {
            tags "ExternalSystem"
        }

        bitrix = softwareSystem "Сайт Bitrix" "Сайт alfasklad.ru на Bitrix. Принимает команды обмена и синхронизирует сущности." {
            exchange = container "/exchange/index.php" "Входная точка обмена. Проверяет key, логирует запрос, маршрутизирует action в нужную функцию." "PHP / Bitrix" {
                tags "Gateway"

                start = component "start()" "Роутер входящих action. Проверяет key, пишет /exchange/rest_log.txt и вызывает нужный обработчик." "PHP method" {
                    tags "Function"
                }

                getKey = component "GetKey()" "Формирует дневной ключ md5(date('d.m.Y') . 'alfasklad')." "PHP static method" {
                    tags "Function"
                }

                addContact = component "AddContact()" "Создаёт или обновляет пользователя сайта и профиль покупателя." "PHP method" {
                    tags "Function"
                }

                updateContact = component "UpdateContact()" "Обновляет баланс и штрафы пользователя: UF_USER_BALANCE, UF_PENALTIES." "PHP method" {
                    tags "Function"
                }

                addOrder = component "AddOrder()" "Создаёт или обновляет заказ Bitrix Sale из сделки Б24." "PHP method" {
                    tags "Function"
                }

                addContract = component "AddContract()" "Создаёт или обновляет договор в ИБ 52, а также опись в ИБ 58." "PHP method" {
                    tags "Function"
                }

                addInvoice = component "AddInvoice()" "Создаёт или обновляет счёт в ИБ 53, цену каталога и детализацию в ИБ 59." "PHP method" {
                    tags "Function"
                }

                deleteActions = component "DeleteOrder / DeleteInvoice / DeleteContract / DeleteContact" "Удаление заказов, счетов, договоров и деактивация пользователя." "PHP methods" {
                    tags "Function"
                }

                invoiceStatus = component "invoiceStatus2Id()" "Маппинг статусов счета: N→354, P→355, 1→356, D/F/3→400." "PHP static method" {
                    tags "Function"
                }

                productLookup = component "getProductByID()" "Ищет товар/бокс по XML_ID в ИБ 40 и 48." "PHP method" {
                    tags "Function"
                }

                contractLookup = component "getContractByGuid()" "Ищет договор в ИБ 52 по CONTRACT_GUID." "PHP method" {
                    tags "Function"
                }

                invoiceLookup = component "getContractInvoiceByGuid()" "Ищет счет по паре CONTRACT_GUID + INVOICE_GUID." "PHP method" {
                    tags "Function"
                }

                userLookup = component "getUserByEmail / getUserByPassport / getUserByInnAndKpp / getUserByPhone" "Функции поиска пользователя и профиля покупателя." "PHP methods" {
                    tags "Function"
                }
            }

            bUser = container "b_user" "Пользователи сайта Bitrix." "Bitrix DB table" {
                tags "Database"
            }

            saleProfile = container "sale profile" "Профили покупателя и значения свойств профиля." "Bitrix Sale tables" {
                tags "Database"
            }

            saleOrders = container "Заказы Bitrix Sale" "Заказы, корзина и свойства заказа." "Bitrix Sale" {
                tags "Database"
            }

            ibContracts = container "ИБ 52 — Договоры" "Инфоблок договоров. Хранит BOX, USER, STATUS, CONTRACT_GUID, PAID_DATE_TO, BALANCE и другие свойства." "Bitrix IBlock" {
                tags "Database"
            }

            ibInvoices = container "ИБ 53 — Счета" "Инфоблок счетов. Хранит USER, PROFILE_TYPE, INVOICE_GUID, CONTRACT_GUID, STATUS, NUMBER, DATE_FROM, DATE_TO." "Bitrix IBlock + Catalog product" {
                tags "Database"
            }

            ibInventory = container "ИБ 58 — Опись вещей" "Опись вещей по договору. Связь с пользователем и договором." "Bitrix IBlock" {
                tags "Database"
            }

            ibInvoiceProducts = container "ИБ 59 — Детализация счета" "Строки детализации счета: PRODUCT, INVOICE, PRICE." "Bitrix IBlock" {
                tags "Database"
            }

            ibProducts = container "ИБ 40 / 48 — Товары и боксы" "Каталог боксов и товаров, поиск по XML_ID." "Bitrix IBlock / Catalog" {
                tags "Database"
            }

            logs = container "/logs/*.txt и /exchange/rest_log.txt" "Файлы логирования входящих запросов и результатов обработки." "Filesystem" {
                tags "Log"
            }

            pushService = container "PushesService" "Отправляет push по новому неоплаченному счету." "PHP service" {
                tags "Service"
            }

            autopayCron = container "cron/autopay.php" "Отдельный cron для автоплатежей. Автоплатеж вынесен из AddInvoice." "PHP cron" {
                tags "Service"
            }
        }

        b24 -> bitrix.exchange "Отправляет HTTP-запросы с key, action и data"
        b24 -> bitrix.exchange.start "HTTP-запрос попадает в start()"

        bitrix.exchange.start -> bitrix.exchange.getKey "Проверяет входящий key"
        bitrix.exchange.start -> bitrix.logs "Пишет сырой входящий запрос"
        bitrix.exchange.start -> bitrix.exchange.addContact "Маршрутизирует action=addcontact"
        bitrix.exchange.start -> bitrix.exchange.updateContact "Маршрутизирует action=updateContact"
        bitrix.exchange.start -> bitrix.exchange.addOrder "Маршрутизирует action=addOrder"
        bitrix.exchange.start -> bitrix.exchange.addContract "Маршрутизирует action=addContract"
        bitrix.exchange.start -> bitrix.exchange.addInvoice "Маршрутизирует action=addInvoice"
        bitrix.exchange.start -> bitrix.exchange.deleteActions "Маршрутизирует DeleteOrder/DeleteInvoice/DeleteContract/DeleteContact"

        bitrix.exchange.addContact -> bitrix.exchange.userLookup "Ищет существующего пользователя"
        bitrix.exchange.userLookup -> bitrix.bUser "Ищет пользователя по email"
        bitrix.exchange.userLookup -> bitrix.saleProfile "Ищет профиль покупателя"
        bitrix.exchange.addContact -> bitrix.bUser "Создаёт или обновляет пользователя"
        bitrix.exchange.addContact -> bitrix.saleProfile "Создаёт или обновляет профиль покупателя"
        bitrix.exchange.addContact -> bitrix.logs "Пишет лог контакта"

        bitrix.exchange.updateContact -> bitrix.bUser "Обновляет UF_USER_BALANCE и UF_PENALTIES"
        bitrix.exchange.updateContact -> bitrix.logs "Пишет лог обновления контакта"

        bitrix.exchange.addOrder -> bitrix.exchange.addContact "Сначала создаёт или обновляет пользователя"
        bitrix.exchange.addOrder -> bitrix.exchange.productLookup "Ищет товары заказа по XML_ID"
        bitrix.exchange.productLookup -> bitrix.ibProducts "Читает товары и боксы из ИБ 40/48"
        bitrix.exchange.addOrder -> bitrix.saleOrders "Создаёт или обновляет заказ и корзину"
        bitrix.exchange.addOrder -> bitrix.logs "Пишет лог сделки/заказа"

        bitrix.exchange.addContract -> bitrix.exchange.addContact "Сначала создаёт или обновляет пользователя для договора"
        bitrix.exchange.addContract -> bitrix.exchange.productLookup "Ищет бокс по внешнему коду"
        bitrix.exchange.addContract -> bitrix.exchange.contractLookup "Ищет договор по GUID"
        bitrix.exchange.contractLookup -> bitrix.ibContracts "Читает договоры по CONTRACT_GUID"
        bitrix.exchange.addContract -> bitrix.ibContracts "Создаёт или обновляет договор"
        bitrix.exchange.addContract -> bitrix.ibInventory "Создаёт или обновляет опись INVENTORY"
        bitrix.exchange.addContract -> bitrix.logs "Пишет лог договора"

        bitrix.exchange.addInvoice -> bitrix.exchange.addContact "Сначала создаёт или обновляет пользователя для счета"
        bitrix.exchange.addInvoice -> bitrix.exchange.invoiceStatus "Преобразует статус счета"
        bitrix.exchange.addInvoice -> bitrix.exchange.invoiceLookup "Ищет счет по CONTRACT_GUID + INVOICE_GUID"
        bitrix.exchange.invoiceLookup -> bitrix.ibInvoices "Читает счета по паре GUID договора и GUID счета"
        bitrix.exchange.addInvoice -> bitrix.ibInvoices "Создаёт или обновляет счет и цену каталога"
        bitrix.exchange.addInvoice -> bitrix.ibInvoiceProducts "Пересоздаёт детализацию счета"
        bitrix.exchange.addInvoice -> bitrix.ibProducts "Ищет товары детализации счета по PRODUCT_XML_ID"
        bitrix.exchange.addInvoice -> bitrix.pushService "Отправляет push по новому неоплаченному счету"
        bitrix.exchange.addInvoice -> bitrix.logs "Пишет лог счета"

        bitrix.exchange.deleteActions -> bitrix.saleOrders "DeleteOrder удаляет заказ"
        bitrix.exchange.deleteActions -> bitrix.ibInvoices "DeleteInvoice удаляет счет"
        bitrix.exchange.deleteActions -> bitrix.ibContracts "DeleteContract удаляет договор"
        bitrix.exchange.deleteActions -> bitrix.bUser "DeleteContact деактивирует пользователя ACTIVE=N"
        bitrix.exchange.deleteActions -> bitrix.logs "Пишет логи удаления"

        bitrix.autopayCron -> bitrix.ibInvoices "Находит неоплаченные счета для автоплатежа"
        bitrix.autopayCron -> bitrix.saleOrders "Создаёт заказ на оплату счета"
    }

    views {
        systemContext bitrix "SystemContext" "Контекст обмена Б24/CRM/1С с сайтом Bitrix" {
            include *
            autoLayout lr
        }

        container bitrix "Containers" "Основные контейнеры и хранилища, участвующие в обмене" {
            include *
            autoLayout lr
        }

        component bitrix.exchange "ExchangeComponents" "Функции файла /exchange/index.php" {
            include *
            autoLayout tb
        }

        dynamic bitrix.exchange "AddOrderFlow" "Поток addOrder: создание/обновление заказа" {
            1: b24 -> bitrix.exchange.start "action=addOrder"
            2: bitrix.exchange.start -> bitrix.exchange.getKey "Проверка key"
            3: bitrix.exchange.start -> bitrix.logs "Логирование запроса"
            4: bitrix.exchange.start -> bitrix.exchange.addOrder "Маршрутизация"
            5: bitrix.exchange.addOrder -> bitrix.exchange.addContact "Создать/обновить пользователя"
            6: bitrix.exchange.addContact -> bitrix.bUser "Запись пользователя"
            7: bitrix.exchange.addContact -> bitrix.saleProfile "Запись профиля"
            8: bitrix.exchange.addOrder -> bitrix.exchange.productLookup "Поиск товаров по XML_ID"
            9: bitrix.exchange.productLookup -> bitrix.ibProducts "Чтение ИБ 40/48"
            10: bitrix.exchange.addOrder -> bitrix.saleOrders "Создание/обновление заказа"
            11: bitrix.exchange.addOrder -> bitrix.logs "Логирование результата"
            autoLayout lr
        }

        dynamic bitrix.exchange "AddContractFlow" "Поток addContract: создание/обновление договора" {
            1: b24 -> bitrix.exchange.start "action=addContract"
            2: bitrix.exchange.start -> bitrix.exchange.getKey "Проверка key"
            3: bitrix.exchange.start -> bitrix.logs "Логирование запроса"
            4: bitrix.exchange.start -> bitrix.exchange.addContract "Маршрутизация"
            5: bitrix.exchange.addContract -> bitrix.exchange.addContact "Создать/обновить пользователя"
            6: bitrix.exchange.addContract -> bitrix.exchange.productLookup "Поиск бокса"
            7: bitrix.exchange.productLookup -> bitrix.ibProducts "Чтение ИБ 40/48"
            8: bitrix.exchange.addContract -> bitrix.exchange.contractLookup "Поиск договора по GUID"
            9: bitrix.exchange.contractLookup -> bitrix.ibContracts "Чтение ИБ 52"
            10: bitrix.exchange.addContract -> bitrix.ibContracts "Создание/обновление договора"
            11: bitrix.exchange.addContract -> bitrix.ibInventory "Создание/обновление описи"
            12: bitrix.exchange.addContract -> bitrix.logs "Логирование результата"
            autoLayout lr
        }

        dynamic bitrix.exchange "AddInvoiceFlow" "Поток addInvoice: создание/обновление счета" {
            1: b24 -> bitrix.exchange.start "action=addInvoice"
            2: bitrix.exchange.start -> bitrix.exchange.getKey "Проверка key"
            3: bitrix.exchange.start -> bitrix.logs "Логирование запроса"
            4: bitrix.exchange.start -> bitrix.exchange.addInvoice "Маршрутизация"
            5: bitrix.exchange.addInvoice -> bitrix.exchange.addContact "Создать/обновить пользователя"
            6: bitrix.exchange.addInvoice -> bitrix.exchange.invoiceStatus "Маппинг статуса счета"
            7: bitrix.exchange.addInvoice -> bitrix.exchange.invoiceLookup "Поиск по CONTRACT_GUID + INVOICE_GUID"
            8: bitrix.exchange.invoiceLookup -> bitrix.ibInvoices "Чтение ИБ 53"
            9: bitrix.exchange.addInvoice -> bitrix.ibInvoices "Создание/обновление счета и цены"
            10: bitrix.exchange.addInvoice -> bitrix.ibInvoiceProducts "Пересоздание строк счета"
            11: bitrix.exchange.addInvoice -> bitrix.ibProducts "Поиск товаров детализации"
            12: bitrix.exchange.addInvoice -> bitrix.pushService "Push по новому неоплаченному счету"
            13: bitrix.exchange.addInvoice -> bitrix.logs "Логирование результата"
            autoLayout lr
        }

        dynamic bitrix.exchange "DeleteFlow" "Поток удаления и деактивации" {
            1: b24 -> bitrix.exchange.start "action=DeleteOrder/DeleteInvoice/DeleteContract/DeleteContact"
            2: bitrix.exchange.start -> bitrix.exchange.getKey "Проверка key"
            3: bitrix.exchange.start -> bitrix.logs "Логирование запроса"
            4: bitrix.exchange.start -> bitrix.exchange.deleteActions "Маршрутизация"
            5: bitrix.exchange.deleteActions -> bitrix.saleOrders "Удаление заказа"
            6: bitrix.exchange.deleteActions -> bitrix.ibInvoices "Удаление счета"
            7: bitrix.exchange.deleteActions -> bitrix.ibContracts "Удаление договора"
            8: bitrix.exchange.deleteActions -> bitrix.bUser "Деактивация пользователя"
            9: bitrix.exchange.deleteActions -> bitrix.logs "Логирование результата"
            autoLayout lr
        }

        styles {
            element "ExternalSystem" {
                background "#0B4DBA"
                color "#ffffff"
                shape RoundedBox
            }

            element "Gateway" {
                background "#1168BD"
                color "#ffffff"
                shape RoundedBox
            }

            element "Function" {
                background "#E8F1FF"
                color "#0B2E63"
                shape Component
            }

            element "Database" {
                background "#F3F6FA"
                color "#1F2937"
                shape Cylinder
            }

            element "Log" {
                background "#FFF7E6"
                color "#5C3B00"
                shape Folder
            }

            element "Service" {
                background "#EAF7EA"
                color "#145214"
                shape RoundedBox
            }

            relationship "Relationship" {
                color "#4B5563"
                thickness 2
            }
        }

        theme default
    }
}