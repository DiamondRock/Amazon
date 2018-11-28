$(document).ready(() => {
    $("input.btn-delete").click(function (event) {
        let productId= $(this).parent().attr('id');
        var request = $.ajax({
            async: true,
            url: "deleteFromProducts.php",
            type: "GET",
            data: {
                productId: productId,
            },
            success: (data) =>
            {
                if("successful" in data && data.successful == true)
                {
                    alert("The item was successfully removed!");
                    $(this).parent().parent().remove();
                }
                else
                {
                    alert(data.error);
                }
            }
        });
    });

});




