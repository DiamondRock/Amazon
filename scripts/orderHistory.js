$(document).ready(() =>{
    $("#orders tbody tr").on("click", function(event) {
        let orderId= $(this).attr('id');
        document.location = 'showOrder.php?id=' + orderId;
    });
});