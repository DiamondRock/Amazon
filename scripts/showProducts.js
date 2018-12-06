$(document).ready(() => {
    $(".product_wrapper a").click(function(event)
    {
        if ($(event.target).is('.btn-delete'))
        {
            event.preventDefault();
        }
    });
    $("p.btn-delete").click(function (event) {
        let productId= $(this).parent().attr('id');
        var request = $.ajax({
            async: true,
            url: "deleteFromProducts.php",
            type: "POST",
            data: {
                productId: productId,
            },
            success: (data) =>
            {
                if("successful" in data && data.successful == true)
                {
                    alert("The item was successfully removed!");
                    $(this).closest(".product_wrapper").remove();
                }
                else
                {
                    alert(data.error);
                }
            }
        });
    });

});




