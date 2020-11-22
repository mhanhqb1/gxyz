$(document).ready(function () {
    $('#dataTable').DataTable({
        "columnDefs": [
            {"orderable": false, "targets": 0}
        ]
    });
    
    $("#checkAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
    
    $('.img-btn-check').on('click', function(){
        var param = $(this).attr('data-param');
        var val = $(this).attr('data-val');
        var ids = $('.img-check:checkbox:checked').map(function() {
            return this.value;
        }).get();
        ids = ids.join(",");
        $.ajax({
            url: BASE_URL + '/ajaxUpdateImages',
            method: 'POST',
            data: {
                field: param,
                val: val,
                ids: ids,
                _token: CSRF_TOKEN
            }
        }).done(function (response) {
            window.location.reload();
        });
    });
    
    $('.video-btn-check').on('click', function(){
        var param = $(this).attr('data-param');
        var val = $(this).attr('data-val');
        var ids = $('.img-check:checkbox:checked').map(function() {
            return this.value;
        }).get();
        ids = ids.join(",");
        $.ajax({
            url: BASE_URL + '/ajaxUpdateVideos',
            method: 'POST',
            data: {
                field: param,
                val: val,
                ids: ids,
                _token: CSRF_TOKEN
            }
        }).done(function (response) {
            window.location.reload();
        });
    });
});


