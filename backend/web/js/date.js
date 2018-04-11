$(function() {
    $('#reservation').daterangepicker({

    });

    $('#search').click(function(){
        console.log($('#reservation').val());
        $("#_form").submit();
    });
});