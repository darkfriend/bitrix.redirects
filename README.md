Модуль "Редиректы" для 1С-Битрикс
====

Модуль позволяет добавить в битрикс поддержку редиректов.

[Модуль в Маркетплейс Битрикс](http://marketplace.1c-bitrix.ru/solutions/dev2fun.redirects/#tab-install-link)

## Чем полезен модуль

* Совершает редирект со статусом 301 или 302
* Удобный интерфейс управления редиректами

## Несколько примеров

### Как сделать редирект с `//` на одну `/`?

1. в поле `Старый URL` указываем `//`
2. в поле `Новый URL` указываем `/`
3. в поле `Идентификатор сайта` текущий идентификатор сайта (например `s1`)

### Как сделать редирект с `/` на без слэша?

1. в поле `Старый URL` указываем `/`
2. в поле `Новый URL` указываем ` ` (пробел)
3. в поле `Идентификатор сайта` текущий идентификатор сайта (например `s1`)

### Как сделать редирект на сторонний домен?

1. в поле `Старый URL` указываем адрес с которого делаем редирект начиная с `/example_path/`
2. в поле `Новый URL` указываем `https://site.ru/example/`
3. в поле `Идентификатор сайта` текущий идентификатор сайта (например `s1`)

## Установка и обновление

Рекомендуется установка и обновление через [маркетплейс Битрикса](http://marketplace.1c-bitrix.ru/solutions/dev2fun.redirects/#tab-install-link).

> Возможна установка и обновление из репозитория github, но требуется немного поработать ручками и головой.

### Установка из github

1. Узнать вашу текущую кодировку сайта (utf8 или win1251)
2. Скачать репозиторий и перенести папку с модулем из нужной кодировки в `bitrix/modules` (папку `migrations` не надо переносить)
3. Переходите на страницу со списком доступных решений (`/bitrix/admin/partner_modules.php`)
4. Устанавливаете модуль
5. Настраиваете редиректы

### Обновление из github

1. Узнать вашу текущую кодировку сайта (utf8 или win1251)
2. Узнать текущую установленную версию (например `1.0.0`)
3. Скачать репозиторий и перенести папку с модулем из нужной кодировки в `bitrix/modules` 
4. Папку `migrations` нужно перенести в любое доступное место по url
5. Запускаете файлы миграций **по очередно** начиная с версии на 1 выше вашей текущей (если ваша текущая версия `1.0.0`, то следующая версия будут 1.1.0, значит запускаете файл `1.1.0.php`)
6. При успехе выполнения миграции будет выведено `номер_версии - DONE` (например: `1.1.0 - DONE`)


## Техническая поддержка

Поддержку решения осуществляет @darkfriend от команды [dev2fun](http://dev2fun.com)
Вы можете найти меня по этому нику в [telegram](https://t.me/darkfriend) или написав на почту support@dev2fun.com

## Поддержка выпуска обновлений

|   |  |
| ------------- | ------------- |
| Yandex.Money  | 410011413398643  |
| Webmoney WMR (rub)  | R218843696478  |
| Webmoney WMU (uah)  | U135571355496  |
| Webmoney WMZ (usd)  | Z418373807413  |
| Webmoney WME (eur)  | E331660539346  |
| Webmoney WMX (btc)  | X740165207511  |
| Webmoney WML (ltc)  | L718094223715  |
| Webmoney WMH (bch)  | H526457512792  |
| PayPal  | [@darkfriend](https://www.paypal.me/darkfriend)  |
| Payeer  | P93175651  |
| Bitcoin  | 15Veahdvoqg3AFx3FvvKL4KEfZb6xZiM6n  |
| Litecoin  | LRN5cssgwrGWMnQruumfV2V7wySoRu7A5t  |
| Ethereum  | 0xe287Ac7150a087e582ab223532928a89c7A7E7B2  |
| BitcoinCash  | bitcoincash:qrl8p6jxgpkeupmvyukg6mnkeafs9fl5dszft9fw9w  |