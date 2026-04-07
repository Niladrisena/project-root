<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enterprise Login | ERP SaaS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex h-screen">

    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white shadow-[10px_0_15px_-3px_rgba(0,0,0,0.1)] z-10">
        <div class="w-full max-w-md">
            <div class="mb-10 text-center lg:text-left">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Welcome back</h1>
                <p class="text-gray-500 mt-2 text-sm">Please enter your corporate credentials to access the ERP.</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-md">
                    <p class="text-sm text-red-700"><?= $error ?></p>
                </div>
            <?php endif; ?>
            <?php if (Session::get('flash_error')): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-md">
                    <p class="text-sm text-red-700"><?= Session::get('flash_error'); Session::set('flash_error', null); ?></p>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('/auth/login') ?>" method="POST" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Corporate Email</label>
                    <input type="email" name="email" id="email" required 
                        class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="you@company.com">
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <a href="<?= base_url('/auth/forgot-password') ?>" class="text-sm font-medium text-blue-600 hover:text-blue-500">Forgot password?</a>
                    </div>
                    <input type="password" name="password" id="password" required 
                        class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="••••••••">
                </div>

                <div class="flex items-center">
                    <input id="remember_me" name="remember_me" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900">Remember me for 30 days</label>
                </div>

                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                    Sign In to Workspace
                </button>
            </form>
        </div>
    </div>

    <div class="hidden lg:flex lg:w-1/2 bg-blue-900 relative overflow-hidden items-center justify-center">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-800 to-indigo-900 opacity-90"></div>
        <div class="relative z-10 p-12 text-white max-w-lg text-center">
            <h2 class="text-4xl font-bold mb-6 leading-tight">Manage Your Enterprise, Seamlessly.</h2>
            <p class="text-lg text-blue-200 mb-8">Access advanced project management, automated HR pipelines, and real-time financial analytics in one secure dashboard.</p>
            <div class="flex justify-center space-x-4 opacity-75">
                <span class="px-3 py-1 bg-white/10 rounded-full text-sm backdrop-blur-sm">SOC2 Compliant</span>
                <span class="px-3 py-1 bg-white/10 rounded-full text-sm backdrop-blur-sm">End-to-End Encrypted</span>
            </div>
        </div>
    </div>

</body>
</html>