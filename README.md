# Тестовое задание для banki.shop
API для подгрузки изображений к заданным параметрам.

# Stack
Yii 2, MySql.

# Роуты

Базовый адрес - http://89.191.225.149:3000;

Все параметры:
/bankishop/test/parameters

Параметры с типом:
/bankishop/test/parameters/type/{type}

Поиск параметра по id или title
/bankishop/test/parameters/search/{param_id?}/{title?}

Добавление изображений к переданному параметру:
/bankishop/test/parameters/icon/{parameter_id}

Удаление изображений:
bankishop/test/parameters/icon/delete/{icon_id}

# Задача

Есть некоторая таблица с параметрами (id | title | type).
Необходимо реализовать возможность для параметров, имеющих type = 2, загрузить до двух изображений icon и icon_gray.

Реализовать API в котором можно получить все параметры к которым можно подгрузить картинки со списком подгруженных картинок в формате json.
Список подгруженных картинок должен иметь исходное имя, путь для просмотра картинок и отметку для понимания что есть icon, а что icon_gray.
В списке параметров необходим поиск по id и title.

Возможность добавить одно или два изображения к параметру.
Возможность заменить загруженные изображения на другие.
Возможность удалить изображения из параметра.

Требования к загружаемым изображениям:

При сохранении имени файла должны использоваться только латинские символы, символы подчеркивания и дефиса; 
символы русского алфавита должны приводиться к нижнему регистру и быть транслитерированы в латинские.
Все файлы должны сохранятся в одну директорию. 
Если загружают файлы с одинаковыми именами, они не должны затирать друг друга.
Необходимо сохранять исходное имя файла.
