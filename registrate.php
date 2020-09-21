<?php session_start();

if(isset($_SESSION['usuario'])){
    header('location: index.php');
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user =  filter_var(strtolower($_POST['usuario']), FILTER_SANITIZE_STRING);
    $pass = $_POST['password'];
    $pass2 = $_POST['password2'];


    $errors = '';

    if(empty($user) or empty($pass) or empty($pass2)){
        $errors .= '<li>Por favor rellena todos los datos correctamente</li>';
    }else{
        		// Comprobamos que el usuario no exista ya.
		try {
			$conexion = new PDO('mysql:host=localhost;dbname=login_practica', 'root', '');
		} catch (PDOException $e) {
			echo "Error:" . $e->getMessage();
		}

		$statement = $conexion->prepare('SELECT * FROM usuarios WHERE usuario = :usuario LIMIT 1');
		$statement->execute(array(':usuario' => $user));

		// El metodo fetch nos va a devolver el resultado o false en caso de que no haya resultado.
        $resultado = $statement->fetch();
        
        print_r($resultado);
		// Si resultado es diferente a false entonces significa que ya existe el usuario.
		if ($resultado != false) {
			$errores .= '<li>El nombre de usuario ya existe</li>';
		}

		// Hasheamos nuestra contraseña para protegerla un poco.
		# OJO: La seguridad es un tema muy complejo, aqui solo estamos haciendo un hash de la contraseña,
		# pero esto no asegura por completo la informacion encriptada.
		$password = hash('sha512', $pass);
        $password2 = hash('sha512', $pass2);
        
        //echo "$user .$password .$password2s";

		// Comprobamos que las contraseñas sean iguales.
		if ($password != $password2) {
			$errors .= '<li>Las contraseñas no son iguales</li>';
		}  
    }

    if($errors == ''){
        $statement = $conexion->prepare('INSERT INTO usuarios (id, user, pass) VALUES(null, :user, :pass)');
        $statement-> execute(array(
            ':user' => $user,
            ':pass'=> $password
        ));

        echo "Hola mundo";
        header('Location: login.php');
    }
}

require 'views/registrate.view.php';
