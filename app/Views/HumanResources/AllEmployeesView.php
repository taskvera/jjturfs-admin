<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Employees & Vendors Index</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
</head>
<body class="bg-gray-100 text-gray-800">

  <!-- HEADER / NAVIGATION -->
  <header class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
      <!-- Logo / Brand -->
      <div class="flex items-center space-x-2">
        <i class="fa-solid fa-building text-blue-600 text-2xl"></i>
        <span class="text-xl font-bold">MyCompany</span>
      </div>
      <!-- Search Bar -->
      <div class="hidden md:flex items-center space-x-2">
        <div class="relative">
          <span class="absolute inset-y-0 left-0 flex items-center pl-3">
            <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
          </span>
          <input type="text"
                 class="block w-full pl-10 pr-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                 placeholder="Search..." />
        </div>
      </div>
      <!-- User Menu / Avatar -->
      <div class="flex items-center space-x-4">
        <button class="text-gray-600 hover:text-gray-800">
          <i class="fa-solid fa-bell"></i>
        </button>
        <button class="text-gray-600 hover:text-gray-800 hidden md:inline-flex">
          <i class="fa-solid fa-comments"></i>
        </button>
        <div class="relative">
          <button class="flex items-center space-x-2 focus:outline-none">
            <img src="https://via.placeholder.com/36" alt="User Avatar" class="w-9 h-9 rounded-full object-cover" />
            <span class="hidden sm:inline-block font-medium">John Admin</span>
            <i class="fa-solid fa-caret-down text-sm"></i>
          </button>
        </div>
      </div>
    </div>
  </header>

  <!-- MAIN CONTENT AREA -->
  <div class="flex flex-col lg:flex-row">
    <!-- SIDEBAR -->
    <aside class="w-full lg:w-64 bg-white shadow-md mt-4 lg:mt-0 lg:h-screen">
      <nav class="px-4 pt-4 pb-6 lg:py-8">
        <ul class="space-y-2">
          <li>
            <a href="/dashboard" class="flex items-center space-x-3 py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100">
              <i class="fa-solid fa-home"></i>
              <span>Dashboard</span>
            </a>
          </li>
          <li>
            <a href="/employees" class="flex items-center space-x-3 py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100">
              <i class="fa-solid fa-users"></i>
              <span>Employees</span>
            </a>
          </li>
          <li>
            <a href="/vendors" class="flex items-center space-x-3 py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100">
              <i class="fa-solid fa-truck"></i>
              <span>Vendors</span>
            </a>
          </li>
          <li>
            <a href="/departments" class="flex items-center space-x-3 py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100">
              <i class="fa-solid fa-briefcase"></i>
              <span>Departments</span>
            </a>
          </li>
          <li>
            <a href="/reports" class="flex items-center space-x-3 py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100">
              <i class="fa-solid fa-chart-line"></i>
              <span>Reports</span>
            </a>
          </li>
        </ul>
      </nav>
    </aside>

    <!-- MAIN SECTION -->
    <main class="flex-1 p-4 lg:p-8">
      <!-- Breadcrumbs -->
      <nav class="mb-4 text-sm" aria-label="Breadcrumb">
        <ol class="list-none p-0 inline-flex space-x-2">
          <li>
            <a href="/dashboard" class="text-blue-600 hover:underline">Dashboard</a>
          </li>
          <li>/</li>
          <li class="text-gray-500">Employees & Vendors</li>
        </ol>
      </nav>

      <h1 class="text-3xl font-bold mb-6">Employees & Vendors</h1>

      <!-- Top-Level Tabs: Employees and Vendors -->
      <div class="mb-6">
        <ul class="flex border-b">
          <li class="mr-4">
            <a href="#employeesTab" id="employeesTabLink" class="inline-block py-2 px-4 text-blue-600 border-b-2 border-blue-600 active transition duration-300">Employees</a>
          </li>
          <li>
            <a href="#vendorsTab" id="vendorsTabLink" class="inline-block py-2 px-4 text-gray-600 hover:text-blue-600 transition duration-300">Vendors</a>
          </li>
        </ul>
      </div>

      <!-- Employees Tab Content -->
      <div id="employeesTab" class="tab-content">
        <?php if (empty($employees)): ?>
          <div class="bg-white rounded shadow p-4">
            <p class="text-gray-600">No employees found.</p>
          </div>
        <?php else: ?>
          <div class="bg-white rounded shadow p-4 overflow-x-auto">
            <table class="min-w-full border-collapse">
              <thead>
                <tr class="bg-gray-50 border-b">
                  <th class="py-2 px-4 text-left font-medium text-gray-600">ID</th>
                  <th class="py-2 px-4 text-left font-medium text-gray-600">Name</th>
                  <th class="py-2 px-4 text-left font-medium text-gray-600">Email</th>
                  <th class="py-2 px-4 text-left font-medium text-gray-600">Position</th>
                  <th class="py-2 px-4 text-left font-medium text-gray-600">Department</th>
                  <th class="py-2 px-4 text-left font-medium text-gray-600">Location</th>
                  <th class="py-2 px-4 text-left font-medium text-gray-600">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($employees as $emp): ?>
                  <tr class="border-b hover:bg-gray-50 transition-colors duration-200">
                    <td class="py-2 px-4"><?php echo htmlspecialchars($emp['id']); ?></td>
                    <td class="py-2 px-4 font-semibold"><?php echo htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']); ?></td>
                    <td class="py-2 px-4"><?php echo htmlspecialchars($emp['email']); ?></td>
                    <td class="py-2 px-4"><?php echo htmlspecialchars($emp['position']); ?></td>
                    <td class="py-2 px-4"><?php echo htmlspecialchars($emp['department']); ?></td>
                    <td class="py-2 px-4"><?php echo htmlspecialchars($emp['location']); ?></td>
                    <td class="py-2 px-4">
                      <a href="/employees/<?php echo (int)$emp['id']; ?>" class="text-blue-600 hover:text-blue-800 inline-flex items-center mr-2">
                        <i class="fa-solid fa-eye mr-1"></i> View
                      </a>
                      <a href="/employees/edit/<?php echo (int)$emp['id']; ?>" class="text-green-600 hover:text-green-800 inline-flex items-center mr-2">
                        <i class="fa-solid fa-pen mr-1"></i> Edit
                      </a>
                      <a href="/employees/delete/<?php echo (int)$emp['id']; ?>" class="text-red-600 hover:text-red-800 inline-flex items-center">
                        <i class="fa-solid fa-trash mr-1"></i> Delete
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>

      <!-- Vendors Tab Content -->
      <div id="vendorsTab" class="tab-content hidden">
        <?php if (empty($vendors)): ?>
          <div class="bg-white rounded shadow p-4">
            <p class="text-gray-600">No vendors found.</p>
          </div>
        <?php else: ?>
          <div class="bg-white rounded shadow p-4 overflow-x-auto">
            <table class="min-w-full border-collapse">
              <thead>
                <tr class="bg-gray-50 border-b">
                  <th class="py-2 px-4 text-left font-medium text-gray-600">ID</th>
                  <th class="py-2 px-4 text-left font-medium text-gray-600">Name</th>
                  <th class="py-2 px-4 text-left font-medium text-gray-600">Email</th>
                  <th class="py-2 px-4 text-left font-medium text-gray-600">Contact</th>
                  <th class="py-2 px-4 text-left font-medium text-gray-600">Location</th>
                  <th class="py-2 px-4 text-left font-medium text-gray-600">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($vendors as $vendor): ?>
                  <tr class="border-b hover:bg-gray-50 transition-colors duration-200">
                    <td class="py-2 px-4"><?php echo htmlspecialchars($vendor['id']); ?></td>
                    <td class="py-2 px-4 font-semibold"><?php echo htmlspecialchars($vendor['name']); ?></td>
                    <td class="py-2 px-4"><?php echo htmlspecialchars($vendor['email']); ?></td>
                    <td class="py-2 px-4"><?php echo htmlspecialchars($vendor['contact']); ?></td>
                    <td class="py-2 px-4"><?php echo htmlspecialchars($vendor['location']); ?></td>
                    <td class="py-2 px-4">
                      <a href="/vendors/<?php echo (int)$vendor['id']; ?>" class="text-blue-600 hover:text-blue-800 inline-flex items-center mr-2">
                        <i class="fa-solid fa-eye mr-1"></i> View
                      </a>
                      <a href="/vendors/edit/<?php echo (int)$vendor['id']; ?>" class="text-green-600 hover:text-green-800 inline-flex items-center mr-2">
                        <i class="fa-solid fa-pen mr-1"></i> Edit
                      </a>
                      <a href="/vendors/delete/<?php echo (int)$vendor['id']; ?>" class="text-red-600 hover:text-red-800 inline-flex items-center">
                        <i class="fa-solid fa-trash mr-1"></i> Delete
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
      
    </main>
  </div>

  <!-- FOOTER -->
  <footer class="bg-white mt-8 border-t border-gray-300">
    <div class="max-w-7xl mx-auto px-4 py-4 text-sm text-gray-500 flex justify-between">
      <p>&copy; 2025 MyCompany. All rights reserved.</p>
      <p>Powered by <a href="#" class="text-blue-600 hover:underline">Awesome Tech</a></p>
    </div>
  </footer>
  
  <!-- Tabs JS -->
  <script>
    // Top-Level Tabs: Employees and Vendors
    const employeesTabLink = document.getElementById("employeesTabLink");
    const vendorsTabLink = document.getElementById("vendorsTabLink");

    employeesTabLink.addEventListener("click", function(e){
      e.preventDefault();
      document.getElementById("employeesTab").classList.remove("hidden");
      document.getElementById("vendorsTab").classList.add("hidden");
      employeesTabLink.classList.add("text-blue-600", "border-b-2", "border-blue-600", "active");
      vendorsTabLink.classList.remove("text-blue-600", "border-b-2", "border-blue-600", "active");
    });

    vendorsTabLink.addEventListener("click", function(e){
      e.preventDefault();
      document.getElementById("vendorsTab").classList.remove("hidden");
      document.getElementById("employeesTab").classList.add("hidden");
      vendorsTabLink.classList.add("text-blue-600", "border-b-2", "border-blue-600", "active");
      employeesTabLink.classList.remove("text-blue-600", "border-b-2", "border-blue-600", "active");
    });
  </script>
</body>
</html>
