<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'projeto-security';

$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);


if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['description'])) {
    $description = $_POST['description'];
    $sql = "INSERT INTO tarefas (id_usuario, descricao) VALUES ('$user_id', '$description')";

    if (mysqli_query($conn, $sql)) {
        header('Location: tarefas.php');
        exit;
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}

if (isset($_POST['delete'])) {
    $id = $_POST['delete'];
    $sql = "DELETE FROM tarefas WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        header('Location: tarefas.php');
        exit;
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}

if (isset($_POST['complete'])) {
    $id = $_POST['complete'];
    $completed = $_POST['completed'] ? '1' : '0';
    $sql = "UPDATE tarefas SET concluida = '$completed' WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        header('Location: tarefas.php');
        exit;
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}

$sql = "SELECT id, descricao, concluida FROM tarefas WHERE id_usuario = '$user_id'";
$result = mysqli_query($conn, $sql);
$tarefas = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tarefas</title>
</head>

<body>
    <h1>Tarefas</h1>
    <div style="position: absolute; top: 10px; right: 10px;">
    <?php if (isset($nome_usuario)): ?>
        <p>Olá, <?php echo $nome_usuario; ?>! <a href="logout.php">Sair</a></p>
    <?php endif; ?>
</div>
    <div style="float:right;">
        <form method="post" action="logout.php">
            <button type="submit">Logout</button>
        </form>
    </div>
    <form method="post">
        <label for="description">Descrição:</label>
        <input type="text" name="description" id="description">
        <button type="submit">Adicionar</button>
    </form>
    <br>
    <table style="border-collapse: collapse; border: 1px solid black;">
    <thead>
        <tr>
            <th style="border: 1px solid black;">Descrição</th>
            <th style="border: 1px solid black;">Concluída</th>
            <th style="border: 1px solid black;">Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tarefas as $tarefa): ?>
            <tr>
                <td style="border: 1px solid black;"><?php echo $tarefa['descricao']; ?></td>
                <td style="border: 1px solid black;">
                    <form method="post">
                        <input type="hidden" name="complete" value="<?php echo $tarefa['id']; ?>">
                        <input type="checkbox" name="completed" onchange="this.form.submit()" <?php if ($tarefa['concluida']) echo 'checked'; ?>>
                    </form>
                </td>
                <td style="border: 1px solid black;">
                    <form method="post">
                        <input type="hidden" name="delete" value="<?php echo $tarefa['id']; ?>">
                        <button type="submit">Excluir</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>