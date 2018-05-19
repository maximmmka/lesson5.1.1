<?php
require 'c:/php/com/vendor/autoload.php';
$api = new \Yandex\Geo\Api();
if (!empty($_GET['address'])) {
    $api->setQuery($_GET['address']);
}
$api
    ->setLimit(1) // кол-во результатов
    ->setLang(\Yandex\Geo\Api::LANG_US) // локаль ответа
    ->load();
$response = $api->getResponse();
$response->getFoundCount(); // кол-во найденных адресов
$response->getQuery(); // исходный запрос
$response->getLatitude(); // широта для исходного запроса
$response->getLongitude(); // долгота для исходного запроса
$collection = $response->getList();
foreach ($collection as $item) {
    $address = $item->getAddress(); // вернет адрес
    $latitude = $item->getLatitude(); // широта
    $longitude = $item->getLongitude(); // долгота
    $item->getData(); // необработанные данные
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>определение координат</title>
</head>

<body>
<h1>Определение координат по адресу</h1>

<form class="form" method="GET">
    <div class="input">
        <label>Адрес: <input class="input" placeholder="введите адрес для поиска" type="text" name="address"></label>
    </div>
    <input class="send" type="submit" value="Найти">
</form>

<?php if (isset($address)): ?>
    <h2><?=$address; ?></h2>
<?php endif; ?>

<?php if (isset($latitude)): ?>
    <p>широта: <?=$latitude; ?></p>
<?php endif; ?>

<?php if (isset($longitude)): ?>
    <p>долгота: <?=$longitude; ?></p>
<?php endif; ?>


<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<div id="map" style="width: 600px; height: 400px"></div>
<script type="text/javascript">

    ymaps.ready(init);
    function init() {
        var myMap = new ymaps.Map("map", {
                center: [<?=$latitude; ?>, <?=$longitude; ?>],
                zoom: 10

            }, {
                searchControlProvider: 'yandex#search'
            }),
            myGeoObject = new ymaps.GeoObject({
                // Описание геометрии.
                geometry: {
                    type: "Point",
                    coordinates: [<?=$latitude; ?>, <?=$longitude; ?>]
                },
                // Свойства.
                properties: {
                    // Контент метки.
                    iconContent: 'Это здесь',
                    hintContent: 'Найдено'
                }
            }, {
                // Опции.
                // Иконка метки будет растягиваться под размер ее содержимого.
                preset: 'islands#blackStretchyIcon',
                // Метку можно перемещать.
                draggable: true
            }),
            myPieChart = new ymaps.Placemark([
                [<?=$latitude; ?>, <?=$longitude; ?>]
                //55.847, 37.6
            ]);
        myMap.geoObjects
            .add(myGeoObject)
            .add(myPieChart)
    }
</script>
</body>
</html>