$('.favorito').on('click', function (e) {
    e.preventDefault();
    var $this = $(this),
            url = $(this).data('url'),
            idMarcador = $this.data('id');
    $this.addClass('disabled');

    $.post(url, {id: idMarcador}).done(function (respuesta) {
        if (respuesta.actualizado) {
            $this.toggleClass('activo')
        }
        $this.removeClass('disabled');
    }).fail(function () {
        $this.removeClass('disabled');
    });
});


$('body').on('submit', 'form[name="etiqueta"][data-ajax="true"]', function (event) {
    event.preventDefault();
    var $form = $(this),
            $btnSubmit = $form.find('button[type="submit"]'),
            $container = $form.closest('.modal-body'),
            $etiquetaSelect = $('#marcador_etiquetas'),
            url = $form.attr('action');

    $btnSubmit.addClass('disabled');

    var data = {};
    $.each($form.serializeArray(), function () {
        data[this.name] = this.value;
    });

    $.post(url, data)
            .done(function (response) {
                $container.html('');
                $container.append(response.form.content);
                $btnSubmit.removeClass('disabled');
            })
            .fail(function () {
                $btnSubmit.removeClass('disabled');
            });
});