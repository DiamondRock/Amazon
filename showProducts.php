<h1>Product List</h1>
<div class='content'>
    <?php
    include "master.php";
    require_once "connectDB.php";
    if (isset($dbConnError))
    {
        die("Couldn't connect to database! Please try again.");
    }
    else
    {
        $errorInDb = false;
    }
    try
    {
        if(isset($_GET['search']))
        {
            $search = $_GET['search'];
            $category = $_GET['category'];
        }
        ?>
        <div id="searchAndFilterDiv">
            <form action="products.php" method="get">

            <?php
            try
            {
                $query="select name from categories order by name asc";
                $result = executeQuery($conn, $query, [], [], false);
                if($result->rowCount() > 0)
                {
                    echo '<select name="category" id="category">';
                    echo "<option>Select a category...</option>";
                    while($row = $result->fetch(PDO::FETCH_BOTH))
                    {
                        if(isset($category) && $row[0] == $category)
                        {
                            echo "<option selected=\"selected\">". $row[0]. "</option>";
                        }
                        else
                        {
                            echo "<option>". $row[0]. "</option>";
                        }
                    }
                    echo '</select>';
                }
            }
            catch (Exception $e)
            {

            }

            ?>
            <input type="text" name="search" id="search" placeholder="Search" value="<? if(isset($search)){echo $search;}?>"/>
            <input type="submit" value="Search" class="btn btn-primary"/>
            </form>
        </div>
        <?
        if(isset($search))
        {
            if($category != "Select a category...")
            {
                if(!empty($search))
                {
                    $query="select p.id as id, p.name as name, description, price, supplies, i.path as picturePath from products as p left join images as i on p.pictureId=i.id join categories as c on p.categoryId=c.id where p.deleted = 0 and c.name=:categoryName and  p.name like concat('%',:search,'%') ORDER BY p.name ASC";
                    $params = [$category, $search];
                    $paramsNamesInQuery = [":categoryName", ":search"];
                }
                else
                {
                    $query="select p.id as id, p.name as name, description, price, supplies, i.path as picturePath from products as p left join images as i on p.pictureId=i.id join categories as c on p.categoryId=c.id where p.deleted = 0 and c.name=:categoryName ORDER BY p.name ASC";
                    $params = [$category];
                    $paramsNamesInQuery = [":categoryName"];
                }
            }
            else
            {
                if(!empty($search))
                {
                    $query="select p.id as id, p.name as name, description, price, supplies, i.path as picturePath from products as p left join images as i on p.pictureId=i.id where p.deleted = 0 and p.name like concat('%',:search,'%') ORDER BY p.name ASC";
                    $params = [$search];
                    $paramsNamesInQuery = [":search"];
                }
                else
                {
                    $query="select p.id as id, name, description, price, supplies, i.path as picturePath from products as p left join images as i on p.pictureId=i.id where p.deleted = 0 ORDER BY p.name ASC";
                    $params = [];
                    $paramsNamesInQuery = [];
                }
            }
        }
        else
        {
            $query="select p.id as id, name, description, price, supplies, i.path as picturePath from products as p left join images as i on p.pictureId=i.id where p.deleted = 0 ORDER BY name ASC";
            $params = [];
            $paramsNamesInQuery = [];
        }
        $result = executeQuery($conn, $query, $params, $paramsNamesInQuery, false);

        if($result->rowCount() == 0)
        {
            echo 'No products available';
        }
        else
        {
            if(isset($_GET["page"]))
            {
                $pageNum = filter_var($_GET["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
                if(!is_numeric($pageNum))
                {
                    die('Invalid page number!');
                }
            }
            else
            {
                $pageNum = 1;
            }
            $totalPagesNum = ceil($result->rowCount()/$GLOBALS['itemsPerPage']);
            $pagePosition = (($pageNum - 1) * $GLOBALS['itemsPerPage']); //get starting position to fetch the records
            $rows = fetchNRows($result, $GLOBALS['itemsPerPage'], $pagePosition,PDO::FETCH_BOTH);
            foreach ($rows as $row)
            {
                ?>
                    <div class='product_wrapper'>
                        <a href="<? if($_SESSION['userType'] == 'admin'){echo "updateProduct.php";} else{echo "showProduct.php";}?>?id=<?php echo $row['id']; ?>">
                        <div class='image'><img src="<?php if(empty($row['picturePath'])){ echo $GLOBALS['noImageAvailablePath'];} else{ echo $row['picturePath']; } ?>"/></div>
                        <div class='name' title="<?echo htmlentities($row['name']); ?>"><?php echo htmlentities($row['name']); ?></div>
                        <div class='price'><b>Price: </b><?php echo $row['price']; ?></div>
                        <?
                        if($_SESSION['userType'] == 'admin')
                        {
                            ?>


                            <div class='supplies'><b>Supplies: </b><?php echo $row['supplies']; ?></div>
                            <div class='deleteButton' id='<?php echo $row['id']; ?>'>
                                <p class="btn  btn-delete"
                                   type="button" value="Delete">Delete</p>
                            </div>
                            <?
                        }
                        ?>
                    </a>
                </div>
                <?
            }
            echo "<div style=\"clear:both;\"></div>";
            echo '<div id="paginationDiv">';
            $pageURLParams = "";
            if(isset($search))
            {
                $pageURLParams = "category=$category&search=$search";
            }
            echo paginate($GLOBALS['itemsPerPage'], $pageNum, $result->rowCount(), $totalPagesNum, "products.php", $pageURLParams);
            echo '</div>';
        }
    }
    catch (Exception $e)
    {
        echo "Error in database. Please try again.";
        echo $e->getMessage();
    }
    ?>


</div>		


