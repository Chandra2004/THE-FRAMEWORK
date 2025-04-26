<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Primary Meta Tags -->
    <title>User Details - Kiwkiw MVC</title>
    <meta name="description" content="User detail information page for Kiwkiw MVC framework">
    <meta name="keywords" content="User Details, PHP Framework, MVC, Database">
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
        <div class="max-w-3xl mx-auto">
            <div class="mb-8">
                <a href="<?= $model['base_url'] ?>/user" class="text-cyan-400 hover:text-cyan-300 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Users
                </a>
            </div>

            <!-- User Card -->
            <div class="bg-gray-800/50 p-8 rounded-2xl border border-gray-700/50">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">
                        User Details
                    </h1>
                    <span class="bg-cyan-400/10 text-cyan-400 px-4 py-2 rounded-full text-sm">
                        ID: <?= htmlspecialchars($model['user']['id']) ?>
                    </span>
                </div>

                <!-- User Info -->
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Username</p>
                            <p class="text-gray-100 font-medium"><?= htmlspecialchars($model['user']['username']) ?></p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Account Created</p>
                            <p class="text-gray-100 font-medium">
                                <?= date('M d, Y H:i', strtotime($model['user']['created_at'])) ?>
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Password Hash</p>
                            <p class="text-gray-100 font-mono text-sm break-all">
                                <?= htmlspecialchars($model['user']['password']) ?>
                            </p>
                        </div>
                    </div>

                    <!-- Security Badge -->
                    <div class="mt-8 p-4 bg-gray-900/50 rounded-lg border border-cyan-400/20 flex items-center gap-4">
                        <div class="bg-cyan-400/10 p-3 rounded-lg">
                            <svg class="w-8 h-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-gray-100 font-medium mb-1">Security Note</h3>
                            <p class="text-gray-400 text-sm">
                                Passwords are securely hashed using bcrypt algorithm and cannot be retrieved in plain text.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
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