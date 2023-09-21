<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['add_to_wishlist'])){

    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];

    $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if(mysqli_num_rows($check_wishlist_numbers) > 0){
        $message[] = 'Уже добавлено в избранное';
    }elseif(mysqli_num_rows($check_cart_numbers) > 0){
        $message[] = 'Уже добавлено в корзину';
    }else{
        mysqli_query($conn, "INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_image')") or die('query failed');
        $message[] = 'Товар добавлен в избранное';
    }

}

if(isset($_POST['add_to_cart'])){

    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if(mysqli_num_rows($check_cart_numbers) > 0){
        $message[] = 'Уже добавлено в корзину';
    }else{

        $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

        if(mysqli_num_rows($check_wishlist_numbers) > 0){
            mysqli_query($conn, "DELETE FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
        }

        mysqli_query($conn, "INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
        $message[] = 'Товар добавлен в корзину';
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Меню | Кафе «Олимпия»</title>

   <!-- шрифт awesome cdn ссылка -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- ссылка на файл css -->
   <link rel="stylesheet" href="css/style.css">

   <link type="image/png" sizes="16x16" rel="icon" href="images/icons8-мешок-муки-16.png">
   <link type="image/png" sizes="32x32" rel="icon" href="images/icons8-мешок-муки-32.png">
   <link type="image/png" sizes="96x96" rel="icon" href="images/icons8-мешок-муки-96.png">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>Меню</h3>
    <p> <a href="home.php">Главная</a> / Меню </p>
</section>

<section class="products">

   <h1 class="title">ПИЦЦА</h1>

   <div class="box-container">
    <?php
    $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE `name` LIKE '%ПИЦЦА%'") or die('query failed');
    if(mysqli_num_rows($select_products) > 0){
        while($fetch_products = mysqli_fetch_assoc($select_products)){
            $name = $fetch_products['name'];
            $name = preg_replace('/\s*Состав.*$/u', '', $name);
            ?>
            <form action="" method="POST" class="box">
                <a href="view_page.php?pid=<?php echo $fetch_products['id']; ?>" class="fas fa-eye"></a>
                <div class="price"><?php echo $fetch_products['price']; ?> р.</div>
                <img src="images/<?php echo $fetch_products['image']; ?>" alt="" class="image">
                <div class="name"><?php echo $name; ?></div>
                <input type="number" name="product_quantity" value="1" min="0" class="qty">
                <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                <input type="hidden" name="product_name" value="<?php echo $name; ?>">
                <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                <input type="submit" value="Добавить в избранное" name="add_to_wishlist" class="option-btn">
                <input type="submit" value="Добавить в корзину" name="add_to_cart" class="btn">
            </form>
            <?php
        }
    }else{
        echo '<p class="empty">Товары еще не добавлены!</p>';
    }
    ?>
</div>


</section>

<section class="products">

   <h1 class="title">РИС, ЛАПША И СПАГЕТТИ</h1>

   <div class="box-container">
    <?php
    $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE `name` LIKE '%ЖАРЕНЫЙ РИС%' OR `name` LIKE '%ЛАПША%' OR `name` LIKE '%СПАГЕТТИ%'") or die('query failed');
    if(mysqli_num_rows($select_products) > 0){
        while($fetch_products = mysqli_fetch_assoc($select_products)){
            $name = $fetch_products['name'];
            $name = preg_replace('/\s*Состав.*$/u', '', $name);
            ?>
            <form action="" method="POST" class="box">
                <a href="view_page.php?pid=<?php echo $fetch_products['id']; ?>" class="fas fa-eye"></a>
                <div class="price"><?php echo $fetch_products['price']; ?> р.</div>
                <img src="images/<?php echo $fetch_products['image']; ?>" alt="" class="image">
                <div class="name"><?php echo $name; ?></div>
                <input type="number" name="product_quantity" value="1" min="0" class="qty">
                <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                <input type="hidden" name="product_name" value="<?php echo $name; ?>">
                <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                <input type="submit" value="Добавить в избранное" name="add_to_wishlist" class="option-btn">
                <input type="submit" value="Добавить в корзину" name="add_to_cart" class="btn">
            </form>
            <?php
        }
    }else{
        echo '<p class="empty">Товары еще не добавлены!</p>';
    }
    ?>
</div>


</section>

<section class="products">

   <h1 class="title">ПОКЕ</h1>

   <div class="box-container">
    <?php
    $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE `name` LIKE '%ПОКЕ%'") or die('query failed');
    if(mysqli_num_rows($select_products) > 0){
        while($fetch_products = mysqli_fetch_assoc($select_products)){
            $name = $fetch_products['name'];
            $name = preg_replace('/\s*Состав.*$/u', '', $name);
            ?>
            <form action="" method="POST" class="box">
                <a href="view_page.php?pid=<?php echo $fetch_products['id']; ?>" class="fas fa-eye"></a>
                <div class="price"><?php echo $fetch_products['price']; ?> р.</div>
                <img src="images/<?php echo $fetch_products['image']; ?>" alt="" class="image">
                <div class="name"><?php echo $name; ?></div>
                <input type="number" name="product_quantity" value="1" min="0" class="qty">
                <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                <input type="hidden" name="product_name" value="<?php echo $name; ?>">
                <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                <input type="submit" value="Добавить в избранное" name="add_to_wishlist" class="option-btn">
                <input type="submit" value="Добавить в корзину" name="add_to_cart" class="btn">
            </form>
            <?php
        }
    }else{
        echo '<p class="empty">Товары еще не добавлены!</p>';
    }
    ?>
</div>


</section>

<section class="products">

   <h1 class="title">НАПИТКИ</h1>

   <div class="box-container">
    <?php
    $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE `price` BETWEEN 100 AND 180") or die('query failed');
    if(mysqli_num_rows($select_products) > 0){
        while($fetch_products = mysqli_fetch_assoc($select_products)){
            $name = $fetch_products['name'];
            $name = preg_replace('/\s*Состав.*$/u', '', $name);
            ?>
            <form action="" method="POST" class="box">
                <a href="view_page.php?pid=<?php echo $fetch_products['id']; ?>" class="fas fa-eye"></a>
                <div class="price"><?php echo $fetch_products['price']; ?> р.</div>
                <img src="images/<?php echo $fetch_products['image']; ?>" alt="" class="image">
                <div class="name"><?php echo $name; ?></div>
                <input type="number" name="product_quantity" value="1" min="0" class="qty">
                <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                <input type="hidden" name="product_name" value="<?php echo $name; ?>">
                <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                <input type="submit" value="Добавить в избранное" name="add_to_wishlist" class="option-btn">
                <input type="submit" value="Добавить в корзину" name="add_to_cart" class="btn">
            </form>
            <?php
        }
    }else{
        echo '<p class="empty">Товары еще не добавлены!</p>';
    }
    ?>
</div>


</section>





<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>