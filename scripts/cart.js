$(document).ready(() => {
    $("button.btn-update").click(function (event) {
        let productId= $(this).closest("tr").attr('id');
        let selectedQuantity = $(this).closest("tr").find("option:selected").val();
        var request = $.ajax({
            async: true,
            url: "updateCart.php",
            type: "GET",
            data: {
                productId: productId,
                selectedQuantity: selectedQuantity
            },
            success: (data) =>
            {
                if("successful" in data && data.successful == true)
                {
                    alert('the item quantity was successfully updated');
                }
                else if(data.error == 'Not enough supplies')
                {
                    alert(data.error + ". Max supplies available is " + data.supplies);
                }
                else
                {
                    alert(data.error);
                }
            }
        });
    });

    $("button.btn-delete").click(function (event) {
        let productId= $(this).closest("tr").attr('id');
        let selectedQuantity = $(this).closest("tr").find("option:selected").val();
        var request = $.ajax({
            async: true,
            url: "deleteItemFromCart.php",
            type: "GET",
            data: {
                productId: productId,
                selectedQuantity: selectedQuantity
            },
            success: (data) =>
            {
                if("successful" in data && data.successful == true)
                {
                    price = parseFloat($(this).closest("tr").children(':nth-child(3)').html());
                    quantity = data.quantity;
                    totalPriceElement = $("#totalPrice");
                    totalPrice = parseFloat(totalPriceElement.html().substring("Total price: ".length));
                    totalPrice -= price * quantity;
                    totalPriceElement.html("Total price: " + totalPrice);
                    $(this).closest("tr").remove();
                    if($("#items").find("tr").length == 2)
                    {
                        $("#items").parent().html("No items left in the shopping cart");
                        $("#items").remove();
                    }
                }
                else
                {
                    alert(data.error);
                }
            }
        });
    });

});




