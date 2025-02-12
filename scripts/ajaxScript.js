$(document).ready(function() {
    $('#submitBtn').click(function(event) {
        event.preventDefault(); // Останавливает стандартную отправку формы
        
        var calcData = $('#Calc').serialize(); // Данные калькулятора
        var orderData = new FormData($('#Order')[0]); // Данные формы заказа

        
        // Добавляем данные калькулятора в форму заказа
        for (var pair of (new URLSearchParams(calcData))) {
            orderData.append(pair[0], pair[1]);
        }

        // Отправляем AJAX-запрос
        $.ajax({
            url: $('#Order').attr('action'),
            type: 'POST',
            data: orderData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Данные успешно отправлены');
                alert('Ваш заказ успешно отправлен!'); // Уведомление пользователю
                window.location.href = "https://example.com";               //!!! ЗАМЕНИ НА URL первой страницы
            },
            error: function(xhr, status, error) {
                console.error('Ошибка при отправке данных');
                alert('Ошибка при отправке заказа. Попробуйте снова.');
            }
        });
    });
});
