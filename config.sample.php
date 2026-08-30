<?php
declare(strict_types=1);

// このファイルを config.php としてコピーし、値を書き換えてから使ってください。
// config.php はリポジトリには含めません（.gitignore済み）。

return [
    'db_host' => 'localhost',
    'db_name' => 'your_database_name',
    'db_user' => 'your_database_user',
    'db_pass' => 'your_database_password',

    // 管理者共通パスワードのハッシュ。以下のコマンドで生成してください：
    //   php -r "echo password_hash('好きなパスワード', PASSWORD_DEFAULT), PHP_EOL;"
    'admin_password_hash' => '$2y$10$xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
];
