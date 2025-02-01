<!-- app/Views/LoginView.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>J&amp;J Turf Management LLC - Staff Login</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>

</head>
<body class="bg-green-50 min-h-screen flex items-center justify-center p-4">
  <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-md">
    <!-- Logo and Title -->
    <div class="flex flex-col items-center mb-6">
      <i class="fas fa-leaf text-green-500 text-6xl animate-spin-slow"></i>
      <h1 class="mt-4 text-2xl font-bold text-green-700">J&amp;J Turf Management LLC</h1>
      <p class="text-gray-600 mt-1">Staff Login</p>
    </div>

    <!-- Error Message (if any) -->
    <?php if (!empty($error)): ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
        <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
      </div>
    <?php endif; ?>

    <!-- Login Form -->
    <form action="/login" method="post" class="space-y-4">
      <div>
        <label for="email" class="block text-gray-700">Email</label>
        <input
          type="email"
          name="username"
          id="email"
          placeholder="your.email@example.com"
          required
          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500"
        />
      </div>
      <div>
        <label for="password" class="block text-gray-700">Password</label>
        <input
          type="password"
          name="password"
          id="password"
          placeholder="********"
          required
          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500"
        />
      </div>
      <button
        type="submit"
        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-md transition-colors duration-200"
      >
        Login
      </button>
    </form>

    <!-- Trouble Logging In Link -->
    <div class="mt-4 text-center">
      <a href="#" id="trouble-link" class="text-green-600 hover:underline">Having trouble logging in?</a>
    </div>
  </div>

  <!-- Modal for Trouble Logging In -->
  <div id="trouble-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
      <h2 class="text-xl font-bold text-green-700 mb-4">Trouble Logging In?</h2>
      <p class="text-gray-700 mb-4">
        If you're having trouble logging in, please contact your system administrator or call our support line at
        <strong>(555) 123-4567</strong>.
      </p>
      <button id="close-modal" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded">
        Close
      </button>
    </div>
  </div>

  <!-- Modal JavaScript -->
  <script>
    const troubleLink = document.getElementById('trouble-link');
    const troubleModal = document.getElementById('trouble-modal');
    const closeModal = document.getElementById('close-modal');

    troubleLink.addEventListener('click', function(e) {
      e.preventDefault();
      troubleModal.classList.remove('hidden');
    });

    closeModal.addEventListener('click', function() {
      troubleModal.classList.add('hidden');
    });
  </script>
</body>
</html>
