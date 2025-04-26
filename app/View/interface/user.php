<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Primary Meta Tags -->
    <title>User Management - Kiwkiw MVC</title>
    <meta name="description" content="User management interface for Kiwkiw MVC framework">
    <meta name="keywords" content="User Management, PHP Framework, MVC, Database">
    <meta name="author" content="Chandra Tri Antomo">
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
    
    <style>
        ::-webkit-scrollbar {
            width: 5px;
        }
        ::-webkit-scrollbar-track {
            background: rgb(17 24 39 / 0.8);
        }
        ::-webkit-scrollbar-thumb {
            background: rgb(17 24 39);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-900 to-gray-800 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-gray-900/20 backdrop-blur-lg border-b border-gray-800 fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <span class="text-2xl font-bold bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">
                        Kiwkiw MVC
                    </span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="https://github.com/Chandra2004/Kiwkiw-Native.git" 
                       target="_blank" 
                       class="text-gray-400 hover:text-cyan-400 transition-all flex items-center">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-32 pb-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-12">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">
                User Management
            </h1>
        </div>

        <!-- User List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (!empty($model['userData'])) : ?>
                <?php foreach ($model['userData'] as $user) : ?>
                    <a href="<?= $model['base_url']?>/user/information/<?= $user['id'] ?>" 
                       class="bg-gray-800/50 p-6 rounded-xl border border-gray-700/50 hover:border-cyan-400/30 transition-all group">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-100 mb-1 group-hover:text-cyan-400 transition-colors">
                                    <?= htmlspecialchars($user['username']) ?>
                                </h3>
                                <p class="text-gray-400 text-sm">
                                    Member since <?= date('M Y', strtotime($user['created_at'])) ?>
                                </p>
                            </div>
                            <div class="bg-cyan-400/10 p-3 rounded-lg">
                                <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="col-span-full bg-gray-800/50 p-8 rounded-2xl border border-gray-700/50 text-center">
                    <div class="text-cyan-400 mb-4">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-100 mb-2">No Users Found</h3>
                    <p class="text-gray-400">Create your first user to get started</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-gray-800 mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center text-gray-500">
                <p class="text-sm">
                    © 2024 Kiwkiw MVC • 
                    <a href="https://www.instagram.com/chandratriantomo.2077/" 
                       target="_blank" 
                       class="hover:text-cyan-400 transition-all">
                        Crafted by Chandra Tri A
                    </a>
                </p>
            </div>
        </div>
    </footer>
</body>
</html>