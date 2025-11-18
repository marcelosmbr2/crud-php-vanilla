<?php 
$title = 'Home';
ob_start(); 
?>

<div class="max-w-4xl mx-auto p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-semibold text-gray-900">Lista de Usuários</h1>
        <a href="/users/create" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 transition">Create User</a>
    </div>
    <div class="bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($users as $user): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $user['id'] ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $user['name'] ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $user['email'] ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <a href="/users/edit/<?= $user['id'] ?>" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
$content = ob_get_clean();
require __DIR__ . '/layouts/layout.php'
?>