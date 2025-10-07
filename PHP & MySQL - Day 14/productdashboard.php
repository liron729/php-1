<?php 
	include_once('config.php'); 

	if(isset($_SESSION['submit']))
	{
		$title = $_POST['title'];
        $description = $_POST['description'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];
	}

    if(empty($title) || empty($description) || empty($quantity) || empty($price))
    {
        echo "You need to fill all the fields.";
    }
    else
    { 
        $sql = "SELECT title FROM products WHERE title=:title";
        $tempSQL = $conn->prepare($sql);
        $tempSQL->bindParam(':title', $title);
        $tempSQL->execute();
    }
    if($tempSQL->rowCount() > 0)
    {
        echo "This product already exists.";
        header( "refresh:2; url=addproduct.php" );
    }
    else
    {
        $sql = "INSERT INTO products (title, description, quantity, price) VALUES (:title, :description, :quantity, :price)";
        $insertSQL = $conn->prepare($sql);

        $insertSQL->bindParam(':title', $title);
        $insertSQL->bindParam(':description', $description);
        $insertSQL->bindParam(':quantity', $quantity);
        $insertProduct->bindParam(':price', $price);

        $insertProduct->execute();

        echo "Product added successfully.";
    }
  $sql = "SELECT * FROM products";
  $selectproducts = $conn->prepare($sql);
  $selectproducts->execute();

  $products_data = $selectproducts->fetchAll();

?>

<?php include("header.php"); ?>

<style>
		
  table
  {
    border: 1px solid black;
  }

  tr,td,th
  {
    border: 1px solid black;
    
  }
  table,tr,td
  {
    border-collapse: collapse;
  }
  td
  {
    padding: 10px;
  }

</style>

<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Welcome, <i> <?php echo $_SESSION['username']; ?> </i></a>
  <ul class="navbar-nav px-3">
    <li class="nav-item text-nowrap">
      <a class="nav-link" href="logout.php">Sign out</a>
    </li>
  </ul>
</nav>

<div class="container-fluid">
  <div class="row">
    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
      <div class="sidebar-sticky">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link active" href="dashboard.php">
              <span data-feather="home"></span>
              Dashboard <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item">
          <?php foreach ($products_data as $products_data) { ?>

            <a class="nav-link" href="profile.php?id=<?= $user_data['id'];?>">
            <?php  } ?>
              <span data-feather="file"></span>
              Edit Profile
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
      </div>  

      <div>
        <?php 

        include_once('config.php');

        $getproducts = $conn->prepare("SELECT * FROM products");

        $getproducts->execute();

        $products = $getproducts->fetchAll();

        ?>

        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>title</th>
              <th>description</th>
              <th>quantity</th>
              <th>price</th>
              <th>Update</th>
            </tr>
          </thead>
          <?php
            foreach ($products as $product ) {
          ?>
          <tbody>
            <tr> 
              <td> <?= $user['id'] ?> </td>
              <td> <?= $user['title'] ?> </td>
              <td> <?= $user['description']  ?> </td> 
              <td> <?= $user['quantity']  ?> </td> 
              <td> <?= $user['price']  ?> </td>
              <td> <?= "<a href='deleteproduct.php?id=$product[id]'> Delete</a>| <a href='product   q.php?id=$product[id]'> Update </a>"?></td>
            </tr>
          
            <?php 
              }
            ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>

<?php include("footer.php"); ?>