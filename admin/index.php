<?php
include("../config.php");
include("../funcs.php");

if (isset($_POST['login'])) {
    $password = $_POST['password'];
    $user = $_POST['username'];
    if ($user == $admin_name && $password == $pass) {
        $_SESSION['admin'] = 1;
        msg('control.php', 'success', '<i class="fas fa-check"></i> Успешен вход в системата');
    } else {
        $_SESSION['admin'] = 0;
        msg('index.php', 'danger', '<i class="fas fa-exclamation-triangle"></i> Грешно потребителско име или парола.');
    }
}

?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>eyeZ &bull; Вход</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
        <link rel="stylesheet" href="assets/admin.css">
    </head>
    <body>
    <div class="container text-center">
        <form method="post" action="index.php" class="form-signin">

            <h3 class="m-3"><i class="far fa-eye"></i> eyeZ</h3>
            <h1 class="h3 mb-3 font-weight-normal">Администрация</h1>

            <label for="inputUser" class="sr-only">Потребителско име</label>
            <input type="text" name="username" id="inputUser" class="form-control" placeholder="Потребителско име" required autofocus>
            <label for="inputPassword" class="sr-only">Парола</label>
            <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Парола" required>
            <button class="btn btn-lg btn-primary btn-block" name="login" type="submit">Вход</button>

            <?php echo showMessage(); ?>

            <p class="mt-3 mb-3 text-muted"><i class="far fa-copyright"></i> eyeZ by WEBOcean.INFO <?php echo date("Y"); ?></p>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
<?php echo deleteSession('alert'); ?>
