# Основной сайт

Корень основного сайта:

```text
/home/bitrix/www
```

Это подтверждается nginx `root "/home/bitrix/www/"`.

---

# Главные зоны проекта

## 1. Локальные кастомизации сайта

```text
/home/bitrix/www/local/php_interface/
```

Что нашли внутри:

```text
client.php
email.php
init.php
payment.php
yookassa.php
lib/
```

### Что это похоже значит

* `init.php` — главный файл локальных обработчиков и кастомной логики сайта
* `payment.php` — локальная логика, связанная с оплатами
* `yookassa.php` — клиент/обертка для работы с API ЮKassa
* `lib/` — вспомогательные классы

### Что уже нашли важного

В `local/php_interface/init.php` есть:

* `OnSaleOrderSaved -> SetOrderDescription`
* `OnSalePayOrder -> saleOrderHandler`
* `OnSalePayOrder -> saleOrderSend`
* `OnSaleAfterPsServiceProcessRequest`
* кусок, где фигурирует `getServiceId() == 7` — вероятно ЮKassa

Это больше похоже на:

* доработки заказа,
* реакции на успешную оплату,
* постобработку платежей,
* внутренние интеграции после оплаты.

---

## 2. Основной php_interface Битрикса

```text
/home/bitrix/www/bitrix/php_interface/
```

Что нашли внутри:

```text
classes/
handlers.php
init.php
leads/
include/
functions.php
```

Это уже очень важная зона.

---

# Ключевые файлы и их роль

## A. `bitrix/php_interface/handlers.php`

```text
/home/bitrix/www/bitrix/php_interface/handlers.php
```

Что нашли:

* `OnSaleOrderSaved -> \Enum\Rest::NewOrderSendToB24`
* `OnAfterUserAdd -> \Enum\Rest::OnAfterUserAddHandler`
* `OnAfterUserAdd -> \Enum\Rest::OnBeforeUserAddHandler`
* `OnAfterUserUpdate -> \Enum\Rest::OnAfterUserUpdateHandler`
* `OnSaleOrderPaid -> \Enum\Rest::OnSaleOrderPaidHandler`

### Это сейчас самый важный файл по интеграции с CRM/B24

Именно отсюда видно:

* новый заказ уходит в B24 на `OnSaleOrderSaved`
* успешная оплата уходит отдельно на `OnSaleOrderPaid`

---

## B. `bitrix/php_interface/classes/enum/Rest.php`

```text
/home/bitrix/www/bitrix/php_interface/classes/enum/Rest.php
```

Это главный найденный класс интеграции сайта с B24.

### Что внутри нашли

* `NewOrderSendToB24(\Bitrix\Main\Event $event)`
* `OnSaleOrderPaidHandler(\Bitrix\Main\Event $event)`
* `SendB24($data, $method)`

### Что делает

#### `NewOrderSendToB24()`

* срабатывает на `OnSaleOrderSaved`
* собирает полный состав заказа:

  * товары
  * свойства заказа
  * скидки
  * тип плательщика
  * способы оплаты и доставки
  * пользователя
* отправляет данные в B24

#### `OnSaleOrderPaidHandler()`

* срабатывает на `OnSaleOrderPaid`
* отправляет в B24 только:

  * `ORDER_ID`
* вызывает:

  * `'/enum.payedDeal/'`

### Вывод по роли файла

Это основной мост:
**сайт → B24**

---

## C. `bitrix/php_interface/init.php`

```text
/home/bitrix/www/bitrix/php_interface/init.php
```

Что нашли важного:

* `OnSaleOrderSaved -> calculateBoxPriceInOrder`
* `OnSaleOrderSaved -> SetDealCategoryIdAfterSaveNewOrder`
* `OnSaleOrderSaved -> ClearBasketAfterSaveNewOrder`

### Что это похоже значит

Этот файл отвечает за системные доработки поведения заказа внутри сайта:

* пересчет цены бокса
* выставление свойства `DEAL_CATEGORY_ID`
* очистка корзины после сохранения заказа

#### Особенно важно

`SetDealCategoryIdAfterSaveNewOrder()`:

* анализирует корзину
* если в заказе счет или пополнение баланса — ставит `DEAL_CATEGORY_ID`

То есть тут есть связь заказа с CRM-логикой, но **не сама отправка**.

---

## D. `bitrix/php_interface/leads/`

```text
/home/bitrix/www/bitrix/php_interface/leads/
```

Там нашли:

* `handlers.php`
* `classes.php`
* `constants.php`
* `log.txt`

### Что это делает

Это отдельная логика лидов, в основном по:

* веб-формам
* инфоблокам
* отправке лидов в CRM

### Что нашли важного

В `leads/handlers.php`:

* `OnBeforeIBlockElementAdd -> LeadSender::Send`
* `form:onBeforeResultAdd -> LeadSender::SendFromWebForm`

В `leads/classes.php`:

* `LeadSender::Brain(...)`
* вызов:

  * `crm.lead.add.json`

### Но важно

`SendOrderUserData()` там есть, **но хук на него закомментирован**.
Значит эта папка **не является основным местом бага с оплатой**.

---

# Второстепенные найденные точки

## 3. REST-папка

```text
/home/bitrix/www/local/rest/payment.php
```

По grep не было видно связи с CRM/сделками.
Пока выглядит как неосновная точка.

---

## 4. 1C-обмен

```text
/home/bitrix/www/local/rdn/1c_exchange.php
```

Это уже отдельная история — обмен с 1С.
К текущему багу с ЮKassa и CRM напрямую не привязывается.

---

# Дубликат файла

Нашли еще:

```text
/home/bitrix/www/s/bitrix/php_interface/classes/enum/Rest.php
```

Но `ls -li` показал одинаковый inode с основным:

```text
/home/bitrix/www/bitrix/php_interface/classes/enum/Rest.php
/home/bitrix/www/s/bitrix/php_interface/classes/enum/Rest.php
```

### Что это значит

Это не две разные версии, а один и тот же файл через жесткую ссылку.
Правка одного изменит второй.

---

# Логическая карта проекта по найденному

## Внутренняя логика магазина

* `local/php_interface/init.php`
* `bitrix/php_interface/init.php`

## Интеграция с B24 / CRM

* `bitrix/php_interface/handlers.php`
* `bitrix/php_interface/classes/enum/Rest.php`

## Лиды с форм / инфоблоков

* `bitrix/php_interface/leads/handlers.php`
* `bitrix/php_interface/leads/classes.php`

## ЮKassa

* `local/php_interface/yookassa.php`
* куски в `local/php_interface/init.php`

## Оплаты после факта успешной оплаты

* `local/php_interface/init.php`
* `bitrix/php_interface/classes/enum/Rest.php::OnSaleOrderPaidHandler`

---

# Самые важные места на будущее

Если потом снова искать проблемы по заказам/оплатам/CRM, я бы смотрел в таком порядке:

## 1. Отправка заказа в CRM/B24

```text
/home/bitrix/www/bitrix/php_interface/handlers.php
/home/bitrix/www/bitrix/php_interface/classes/enum/Rest.php
```

## 2. События заказа и оплаты на сайте

```text
/home/bitrix/www/local/php_interface/init.php
/home/bitrix/www/bitrix/php_interface/init.php
```

## 3. Лиды с форм

```text
/home/bitrix/www/bitrix/php_interface/leads/
```

## 4. ЮKassa

```text
/home/bitrix/www/local/php_interface/yookassa.php
```

---

# Короткий итог

Для этой системы структура по сути такая:

```text
/home/bitrix/www
├── local/php_interface/
│   ├── init.php              # локальные обработчики сайта, оплаты, post-payment логика
│   ├── payment.php           # локальная платежная логика
│   ├── yookassa.php          # клиент API ЮKassa
│   └── lib/                  # вспомогательные классы
├── local/rest/
│   └── payment.php           # второстепенный REST-код по оплатам
├── local/rdn/
│   └── 1c_exchange.php       # обмен с 1С
└── bitrix/php_interface/
    ├── handlers.php          # главные event handler'ы, в т.ч. отправка заказа в B24
    ├── init.php              # доработки заказа внутри Bitrix
    ├── classes/enum/Rest.php # основной мост сайт -> B24
    └── leads/
        ├── handlers.php      # лиды из форм/iblock
        ├── classes.php       # crm.lead.add
        └── constants.php
```

---------------------------------------------

Да. Ниже короткая техкарта по найденному проекту.

# Техкарта проекта: где что лежит

## Корень основного сайта

```text
/home/bitrix/www
```

Это основной Bitrix-сайт.

---

## 1. Заказы, оплаты, CRM/B24

### Главная точка интеграции с CRM/B24

```text
/home/bitrix/www/bitrix/php_interface/handlers.php
/home/bitrix/www/bitrix/php_interface/classes/enum/Rest.php
```

### Что там происходит

`handlers.php`

* `OnSaleOrderSaved -> \Enum\Rest::NewOrderSendToB24`
* `OnSaleOrderPaid -> \Enum\Rest::OnSaleOrderPaidHandler`

`Rest.php`

* `NewOrderSendToB24()` — отправляет **полные данные нового заказа** в B24
* `OnSaleOrderPaidHandler()` — отправляет в B24 только **ORDER_ID** как факт оплаты
* `SendB24()` — общий вебхук-отправитель

### Когда сюда лезть

* заказ уходит в CRM слишком рано
* создаются лишние сделки/лиды
* надо понять, что именно сайт шлет в B24
* надо менять логику передачи заказа после оплаты

### Что уже найдено по багу

Основной источник мусора в CRM:

```php
OnSaleOrderSaved -> NewOrderSendToB24
```

---

## 2. Локальная логика сайта по оплатам и заказам

### Основной файл

```text
/home/bitrix/www/local/php_interface/init.php
```

### Что там найдено

* `OnSaleOrderSaved -> SetOrderDescription`
* `OnSalePayOrder -> saleOrderHandler`
* `OnSalePayOrder -> saleOrderSend`
* `OnSaleAfterPsServiceProcessRequest`
* логика с `getServiceId() == 7` — очень похоже на ЮKassa

### Что это значит

Этот файл отвечает за:

* локальные доработки заказа
* действия после успешной оплаты
* внутренние платежные сценарии
* автоплатежи / recurring token

### Когда сюда лезть

* заказ неправильно описывается
* после оплаты не срабатывает нужный код
* надо понять local-логику оплаты
* надо проверить ID платежной системы

---

## 3. Внутренняя логика заказа внутри Bitrix

### Файл

```text
/home/bitrix/www/bitrix/php_interface/init.php
```

### Что там найдено

* `OnSaleOrderSaved -> calculateBoxPriceInOrder`
* `OnSaleOrderSaved -> SetDealCategoryIdAfterSaveNewOrder`
* `OnSaleOrderSaved -> ClearBasketAfterSaveNewOrder`

### Что это значит

Этот файл отвечает за:

* пересчет цены товара/бокса в заказе
* установку свойства `DEAL_CATEGORY_ID`
* очистку корзины после сохранения заказа

### Когда сюда лезть

* неправильно считается цена
* не та категория сделки
* странное поведение заказа после сохранения
* надо понять, какие свойства заказа проставляются до CRM

---

## 4. ЮKassa

### Файл

```text
/home/bitrix/www/local/php_interface/yookassa.php
```

### Что там

* обертка над API ЮKassa
* `HttpClient`
* вызовы в `https://api.yookassa.ru`

### Что это значит

Это не место создания сделок в CRM.
Это клиент к API платежного шлюза.

### Когда сюда лезть

* ошибки общения с ЮKassa
* не создается/не подтверждается платеж
* проблемы с запросами в API ЮKassa

---

## 5. Лиды с форм сайта

### Папка

```text
/home/bitrix/www/bitrix/php_interface/leads/
```

### Основные файлы

```text
handlers.php
classes.php
constants.php
```

### Что там найдено

`handlers.php`

* `OnBeforeIBlockElementAdd -> LeadSender::Send`
* `form:onBeforeResultAdd -> LeadSender::SendFromWebForm`

`classes.php`

* `LeadSender`
* отправка в:

  ```text
  crm.lead.add.json
  ```

### Что это значит

Это отдельная логика:

* лиды из форм
* лиды из инфоблоков
* отправка заявок в CRM

### Важно

`SendOrderUserData()` там есть, но хук на него закомментирован.
То есть это **не основной источник бага по оплатам**.

### Когда сюда лезть

* не доходят лиды из форм
* задваиваются лиды с форм
* надо править поля лида, UTM, комментарии, источник

---

## 6. Локальные файлы по платежам

### Файлы

```text
/home/bitrix/www/local/php_interface/payment.php
/home/bitrix/www/local/rest/payment.php
```

### Что сейчас известно

Прямой связи с CRM по grep не нашли.

### Когда сюда лезть

* если проблема не в B24-интеграции, а в локальной записи оплаты
* если надо понять, как сайт пишет внутренние payment-данные

---

## 7. Обмен с 1С

### Файл

```text
/home/bitrix/www/local/rdn/1c_exchange.php
```

### Что это

Отдельная зона обмена с 1С.

### Когда сюда лезть

* выгрузка/загрузка в 1С
* CommerceML/обмен заказами/оплатами с 1С
* но не первая точка для бага с ЮKassa → CRM

---

## 8. Дубликат файла Rest.php

Есть еще:

```text
/home/bitrix/www/s/bitrix/php_interface/classes/enum/Rest.php
```

Но это тот же файл, а не копия.
Вы проверили inode — он одинаковый.

### Что это значит

Правите:

```text
/home/bitrix/www/bitrix/php_interface/classes/enum/Rest.php
```

и второй меняется автоматически.

---

# Куда лезть по типу бага

## Если заказ слишком рано попадает в CRM

Сначала:

```text
/bitrix/php_interface/handlers.php
/bitrix/php_interface/classes/enum/Rest.php
```

## Если проблема именно после успешной оплаты

Сначала:

```text
/local/php_interface/init.php
/bitrix/php_interface/classes/enum/Rest.php
```

## Если не та категория сделки

Сначала:

```text
/bitrix/php_interface/init.php
```

## Если не доходят лиды с форм

Сначала:

```text
/bitrix/php_interface/leads/handlers.php
/bitrix/php_interface/leads/classes.php
```

## Если проблема в работе с ЮKassa API

Сначала:

```text
/local/php_interface/yookassa.php
/local/php_interface/init.php
```

## Если вопрос про 1С-обмен

Сначала:

```text
/local/rdn/1c_exchange.php
```

---

# Самые важные файлы проекта

```text
/home/bitrix/www/bitrix/php_interface/handlers.php
/home/bitrix/www/bitrix/php_interface/classes/enum/Rest.php
/home/bitrix/www/bitrix/php_interface/init.php
/home/bitrix/www/local/php_interface/init.php
/home/bitrix/www/local/php_interface/yookassa.php
/home/bitrix/www/bitrix/php_interface/leads/classes.php
/home/bitrix/www/bitrix/php_interface/leads/handlers.php
```

---

# По текущему багу итог

## Причина

Заказ отправляется в B24 на событии:

```php
OnSaleOrderSaved
```

## Конкретно

```text
/home/bitrix/www/bitrix/php_interface/handlers.php
/home/bitrix/www/bitrix/php_interface/classes/enum/Rest.php
```

## Не первопричина

* `yookassa.php`
* `leads/*`
* `OnSalePayOrder` из `local/php_interface/init.php`

---

# Схема проекта в одну картинку

```text
/home/bitrix/www
├── local/php_interface/
│   ├── init.php              # локальные обработчики заказов/оплат
│   ├── payment.php           # локальная платежная логика
│   ├── yookassa.php          # API-клиент ЮKassa
│   └── lib/                  # вспомогательные классы
├── local/rest/
│   └── payment.php           # доп. REST-логика по оплатам
├── local/rdn/
│   └── 1c_exchange.php       # обмен с 1С
└── bitrix/php_interface/
    ├── handlers.php          # события: заказ, оплата, пользователь, отправка в B24
    ├── init.php              # системные доработки заказа
    ├── classes/enum/Rest.php # основной мост сайт -> B24
    └── leads/
        ├── handlers.php      # лиды из форм/iblock
        ├── classes.php       # crm.lead.add
        └── constants.php
```

