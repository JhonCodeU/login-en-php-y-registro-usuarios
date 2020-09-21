<?php session_start();

if(isset($_SESSION['usuario'])){
    header('location: index.php');
}
$errors = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user =  filter_var(strtolower($_POST['usuario']), FILTER_SANITIZE_STRING);
    $pass = $_POST['password'];
    $pass = hash('sha512',$pass);

    try {
        $conexion = new PDO('mysql:host=localhost;dbname=login_practica', 'root', '');
    } catch (PDOException $e) {
        echo "Error:" . $e->getMessage();
    }

    $statement = $conexion->prepare('SELECT * FROM usuarios WHERE user = :user AND pass = :pass');
	$statement->execute(array(
			':user' => $user,
			':pass' => $pass
		));

    $resultado = $statement->fetch();
	if ($resultado !== false) {
		$_SESSION['usuario'] = $user;
        header('Location: index.php');
        //echo 'Datos correctos';
	} else {
		$errors = '<li>Datos incorrectos</li>';
	}
}

require('views/login.view.php');

