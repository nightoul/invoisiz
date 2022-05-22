$(document).ready(function (){
    $('.ajax-editable').on('change', function (){
        let email = $(this).val();
        if (email.includes('@')) {
            $.ajax({
                method: 'POST',
                url: `/invoisiz/www/client/editwithajax`,
                data: { clientId: $(this).data('client-id'), clientEmail: email }
            });
        } else {
            Swal.fire('Invalid email!')
        }
    })
})
