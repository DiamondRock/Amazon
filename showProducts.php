<?php

if(isset($_GET['action']) && $_GET['action']=="add")
{
    die("sth");
}

?>
<h1>Product List</h1>
<?php
if(isset($message)){
    echo "<h2>$message</h2>";
}
?>
<div class='content'>
    <?php

    $query="SELECT p.id as id, name, description, price, supplies, i.path as picturePath FROM products as p, images as i where p.deleted = 0 and p.pictureId=i.id ORDER BY name ASC";
    $params = [];
    $paramsNamesInQuery = [];
    $result = executeQuery($conn, $query, $params, $paramsNamesInQuery, false);
    if($result->rowCount() == 0)
    {
        echo 'No products available';
    }
    else
    {
        while ($row = $result->fetch(PDO::FETCH_BOTH))
        {
            ?>
            <div class='product_wrapper'>
                <div class='image'><img src="<?php echo $row['picturePath']; ?>"/></div>
                <div class='name'><?php echo $row['name']; ?></div>
                <div class='price'><b>Price:</b><?php echo $row['price']; ?></div>
                <div class='supplies'><b>Supplies:</b><?php echo $row['supplies']; ?></div>
                <div class='updateButton'><input class="btn" type="button" value="Update" onClick="document.location.href='updateProducts.php?id=<?php echo $row['id']; ?>'" /></div>
                <div class='deleteButton' id='<?php echo $row['id'];?>'><input class="btn btn-delete" type="button" value="Delete"/></div>
            </div>

            <?
        }
    }


    ?>
</div>		


