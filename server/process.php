<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Сбор данных из формы
    $name = htmlspecialchars($_POST['name']);
    $phone = htmlspecialchars($_POST['phone']);
    $city = htmlspecialchars($_POST['city']);
    $message = htmlspecialchars($_POST['message']);
    $total = isset($_POST['total']) ? htmlspecialchars($_POST['total']) : 'Не указано';

    // Сбор дополнительных данных
    $service1 = isset($_POST['service1']) ? $_POST['service1'] : 'Не указано';
    $service2 = isset($_POST['service2']) ? $_POST['service2'] : 'Не указано';
    $service3 = isset($_POST['service3']) ? $_POST['service3'] : 'Не указано';
    $service4 = isset($_POST['service4']) ? $_POST['service4'] : 'Не указано';
    $service5 = isset($_POST['service5']) ? $_POST['service5'] : 'Не указано';
    $service6 = isset($_POST['service6']) ? 'Выбрано' : 'Не выбрано';
    $service7 = isset($_POST['service7']) ? $_POST['service7'] : 'Не указано';
    $out_of_town = isset($_POST['out_of_town']) ? $_POST['out_of_town'] : 'Не указано';
    $consent = isset($_POST['consent']) ? 'Дано согласие' : 'Не дано согласие';

    // Получаем файлы
    $files = $_FILES['files'];

    // Email получателя
    $to = 'mashaborodkina@mail.ru';  
    $subject = 'Новое сообщение с сайта';

    // Генерируем уникальный `boundary`
    $boundary = md5(time());

    // Заголовки письма
    $headers = "From: mashaborodkina@mail.ru\r\n";  
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

    // Начало тела письма
    $messageContent = "--$boundary\r\n";
    $messageContent .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $messageContent .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $messageContent .= "Имя: $name\r\n";
    $messageContent .= "Телефон: $phone\r\n";
    $messageContent .= "Город: $city\r\n";
    $messageContent .= "Сообщение: $message\r\n";
    $messageContent .= "Итоговая сумма: $total\r\n";
    $messageContent .= "Услуга 1: $service1\r\n";
    $messageContent .= "Услуга 2: $service2\r\n";
    $messageContent .= "Услуга 3: $service3\r\n";
    $messageContent .= "Услуга 4: $service4\r\n";
    $messageContent .= "Услуга 5: $service5\r\n";
    $messageContent .= "Услуга 6: $service6\r\n";
    $messageContent .= "Услуга 7: $service7\r\n";
    $messageContent .= "Выезд за город: $out_of_town\r\n";
    $messageContent .= "Согласие на обработку данных: $consent\r\n";
    $messageContent .= "\r\n";

    // Проверяем, есть ли загруженные файлы
    if (!empty($_FILES["files"]["name"][0])) {
        for ($i = 0; $i < count($_FILES["files"]["name"]); $i++) {
            // Проверяем, загружен ли файл без ошибок
            if ($_FILES["files"]["error"][$i] === UPLOAD_ERR_OK) {
                $fileContent = file_get_contents($_FILES["files"]["tmp_name"][$i]);
                $fileName = basename($_FILES["files"]["name"][$i]);

                // Добавляем каждый файл как вложение
                $messageContent .= "--$boundary\r\n";
                $messageContent .= "Content-Type: application/octet-stream; name=\"$fileName\"\r\n";
                $messageContent .= "Content-Transfer-Encoding: base64\r\n";
                $messageContent .= "Content-Disposition: attachment; filename=\"$fileName\"\r\n\r\n";
                $messageContent .= chunk_split(base64_encode($fileContent)) . "\r\n";
            }
        }
    }

    // Завершаем письмо
    $messageContent .= "--$boundary--\r\n";

    // Отправка письма
    if (mail($to, $subject, $messageContent, $headers)) {
        echo 'Письмо успешно отправлено.';
    } else {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        echo 'Ошибка при отправке письма. Подробности: ';
        print_r(error_get_last());
    }
}
?>