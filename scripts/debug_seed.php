<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$emails = [
    'test@example.com',
    'org-user@example.com',
];

foreach ($emails as $email) {
    echo "EMAIL={$email}\n";

    $user = Illuminate\Support\Facades\DB::table('users')
        ->where('email', '=', $email)
        ->first();

    if (!$user) {
        echo "  NO_USER\n";
        continue;
    }

    $orgId = $user->organization_id;
    echo "  USER_ID=" . (string) $user->id . PHP_EOL;
    echo "  ORG_ID=" . (string) $orgId . PHP_EOL;

    $modelType = 'App\\Models\\User';

    $roles = Illuminate\Support\Facades\DB::table('model_has_roles')
        ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
        ->where('model_has_roles.model_type', '=', $modelType)
        ->where('model_has_roles.model_id', '=', (int) $user->id)
        ->select('roles.name')
        ->get();

    $roleNames = [];
    foreach ($roles as $r) {
        $roleNames[] = $r->name;
    }

    echo "  ROLES=" . json_encode($roleNames) . PHP_EOL;
}

