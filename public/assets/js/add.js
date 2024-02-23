
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function () {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
    .forEach(function (form) {
    form.addEventListener('submit', function (event) {
    if (!form.checkValidity()) {
    event.preventDefault()
    event.stopPropagation()
}

    form.classList.add('was-validated')
}, false)
})
})()

    let delete_form = document.querySelectorAll("a[href*='delete_']");
    delete_form.forEach((item) => {
    item.addEventListener("click", (e) => {
        e.preventDefault();
        $.confirm({
            icon: 'fa fa-question',
            title: 'Confirmation',
            content: 'Voulez-vous supprimer cet enregistrement ? <br> Ce processus ne peut pas être annulé.',
            useBootstrap: false,
            boxWidth: '500px',
            // autoClose: 'Annuler|10000',
            theme: 'modern',
            closeIcon: true,
            animation: 'zoom',
            type: 'red',
            buttons: {
                Supprimer: {
                    btnClass: 'btn__confirm__delete',
                    action: function () {
                        window.location = item.href
                    }
                },
                Annuler: function () {
                }
            }
        });
    })
})

    new TomSelect("#client_id",{
        create: true,
        sortField: {
            field: "text",
            direction: "asc"
        }
    });