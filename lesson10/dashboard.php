<html lang="en">
<head>
    <title>dashboard</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px 20px;
        }
    </style>
</head>

<body>
    <?php
    include_once('config.php');
    $sql="SELECT * FROM users";
    $getusers=$conn->prepare($sql);
    $getusers->execute();
    $users=$getusers->fetchAll();

    ?>

    <br><br>

    <table>
        <thead>
            <th>ID</th>
            <th>Name</th>
            <th>Surname</th>
            <th>Email</th>
        </thead>
<tbody>
        <?php
        foreach($users as $user){
            ?>
            <tr>
            
            <td><?= $user['id'] ?></td>
            <td><?= $user['name'] ?></td>
            <td><?= $user['surname'] ?></td>
            <td><?= $user['email'] ?></td>
            
        </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
    <a href="index.php">add user</a>
</body>
</html>