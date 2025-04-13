<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список пользователей</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Список пользователей:</h2>

    <ul class="without_point">
    <?php foreach ($users as $user): ?>
        <li>
            <details>
                <summary><?= htmlspecialchars($user['username']) ?></summary>
                <div class="details">
                    <p>ID: <?= $user['id'] ?></p>
                    <p>Email: <?= htmlspecialchars($user['password']) ?></p>
                    <img class="image" src="data:image/png;base64,<?= base64_encode($user['image']) ?>" alt="Картинка">
                </div>
            </details>
        </li>
    <?php endforeach; ?>
    </ul>
    <table>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']) ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['password']) ?></td>
                <td><img class="image" src="data:;base64,<?= base64_encode($user['image']) ?>" alt="Картинка"></td>
                <td><a href="one_user.php?id=<?= $user['id'] ?>">Подробнее</a></td>
            </tr>
        <?php endforeach; ?>
        
    </table>
    <ul class="without_point">
        <?php foreach ($users as $user): ?>
        <li>
            <div class="row">
                ID: <?= htmlspecialchars($user['id']) ?> | Имя: <?= htmlspecialchars($user['username']) ?> | Email: <?= htmlspecialchars($user['password']) ?>
                <img class="image" src="data:;base64,<?= base64_encode($user['image']) ?>" alt="Картинка">
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>