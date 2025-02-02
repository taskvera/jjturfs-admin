<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Employee Record View</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
  <!-- Favicon (optional) -->
  <link rel="icon" type="image/png" href="https://example.com/favicon.png" />
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
      <!-- Breadcrumbs and Title -->
      <div class="mb-6">
        <nav class="mb-2 text-sm" aria-label="Breadcrumb">
          <ol class="list-none p-0 inline-flex space-x-2">
            <li><a href="/employees" class="text-blue-600 hover:underline">Employees</a></li>
            <li>/</li>
            <li class="text-gray-500">Employee Record</li>
          </ol>
        </nav>
        <h1 class="text-2xl font-bold mb-2">Employee Record</h1>
        <p class="text-gray-600">Detailed view of employee information and status.</p>
      </div>
      
      <!-- Employee Card -->
      <section class="bg-white rounded-md shadow p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
          <!-- Profile Picture -->
          <div class="mb-4 md:mb-0 flex-shrink-0">
            <img src="<?php echo htmlspecialchars($employee['photo'] ?? 'https://via.placeholder.com/150'); ?>" alt="Employee Photo" class="w-32 h-32 rounded-full object-cover"/>
          </div>
          <!-- Main Info -->
          <div class="flex-1">
            <h2 class="text-xl font-bold mb-1">
              <?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?>
            </h2>
            <p class="text-gray-600 mb-1 flex items-center space-x-1">
              <i class="fa-solid fa-briefcase text-blue-600"></i>
              <span><?php echo htmlspecialchars($employee['position']); ?></span>
            </p>
            <p class="text-gray-600 mb-1 flex items-center space-x-1">
              <i class="fa-solid fa-building text-blue-600"></i>
              <span><?php echo htmlspecialchars($employee['department']); ?></span>
            </p>
            <p class="text-gray-600 mb-2 flex items-center space-x-1">
              <i class="fa-solid fa-location-dot text-blue-600"></i>
              <span><?php echo htmlspecialchars($employee['location']); ?></span>
            </p>
          </div>
          <!-- Action Buttons -->
          <div class="flex space-x-2">
            <a href="/employees/edit/<?php echo (int)$employee['id']; ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700 transition duration-300">
              <i class="fa-solid fa-pen mr-2"></i> Edit
            </a>
            <a href="/employees/delete/<?php echo (int)$employee['id']; ?>" class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded shadow hover:bg-red-600 transition duration-300">
              <i class="fa-solid fa-trash mr-2"></i> Remove
            </a>
          </div>
        </div>
      </section>
      
      <!-- Tabs for Employee Information -->
      <section class="bg-white rounded-md shadow p-6 mb-6">
        <!-- Tabs Header -->
        <div class="border-b border-gray-200 mb-6">
          <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="employeeTabs" data-tabs-toggle="#employeeTabContent">
            <li class="mr-2">
              <a href="#overview" class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active">
                <i class="fa-solid fa-user mr-2"></i> Overview
              </a>
            </li>
            <li class="mr-2">
              <a href="#contact" class="inline-block p-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300">
                <i class="fa-solid fa-address-book mr-2"></i> Contact
              </a>
            </li>
            <li class="mr-2">
              <a href="#employment" class="inline-block p-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300">
                <i class="fa-solid fa-id-badge mr-2"></i> Employment
              </a>
            </li>
            <li class="mr-2">
              <a href="#documents" class="inline-block p-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300">
                <i class="fa-solid fa-folder-open mr-2"></i> Documents
              </a>
            </li>
            <li class="mr-2">
              <a href="#permissions" class="inline-block p-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300">
                <i class="fa-solid fa-lock mr-2"></i> Permissions
              </a>
            </li>
          </ul>
        </div>
        
        <!-- Tabs Content -->
        <div id="employeeTabContent">
          <!-- Overview Tab -->
          <div id="overview" class="p-4">
            <h3 class="text-lg font-bold mb-2">Quick Overview</h3>
            <p class="text-gray-600 mb-4">
              <?php echo htmlspecialchars($employee['overview'] ?? 'No overview available.'); ?>
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
              <!-- Stats / KPI Cards -->
              <div class="bg-gray-50 p-4 rounded-md shadow-sm">
                <h4 class="text-gray-500 uppercase text-xs font-semibold mb-2">Projects Led</h4>
                <p class="text-2xl font-bold text-blue-600"><?php echo htmlspecialchars($employee['projects_led'] ?? '0'); ?></p>
              </div>
              <div class="bg-gray-50 p-4 rounded-md shadow-sm">
                <h4 class="text-gray-500 uppercase text-xs font-semibold mb-2">Team Size</h4>
                <p class="text-2xl font-bold text-green-600"><?php echo htmlspecialchars($employee['team_size'] ?? '0'); ?></p>
              </div>
            </div>
            <div>
              <h4 class="text-lg font-bold mb-2">Key Skills</h4>
              <?php if (!empty($employee['skills'])): ?>
                <ul class="list-disc list-inside text-gray-700 space-y-1">
                  <?php foreach ($employee['skills'] as $skill): ?>
                    <li><?php echo htmlspecialchars($skill); ?></li>
                  <?php endforeach; ?>
                </ul>
              <?php else: ?>
                <p class="text-gray-600">No skills listed.</p>
              <?php endif; ?>
            </div>
          </div>
          
          <!-- Contact Tab -->
          <div id="contact" class="hidden p-4">
            <h3 class="text-lg font-bold mb-4">Contact Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block font-medium text-gray-700 mb-1" for="email">Email</label>
                <div class="flex items-center space-x-2">
                  <i class="fa-solid fa-envelope text-blue-600"></i>
                  <span><?php echo htmlspecialchars($employee['email']); ?></span>
                </div>
              </div>
              <div>
                <label class="block font-medium text-gray-700 mb-1" for="phone">Phone</label>
                <div class="flex items-center space-x-2">
                  <i class="fa-solid fa-phone text-blue-600"></i>
                  <span><?php echo htmlspecialchars($employee['phone'] ?? 'N/A'); ?></span>
                </div>
              </div>
              <div>
                <label class="block font-medium text-gray-700 mb-1" for="address">Address</label>
                <div class="flex items-start space-x-2">
                  <i class="fa-solid fa-location-dot text-blue-600 mt-1"></i>
                  <span><?php echo nl2br(htmlspecialchars($employee['address'] ?? 'N/A')); ?></span>
                </div>
              </div>
              <div>
                <label class="block font-medium text-gray-700 mb-1">Emergency Contact</label>
                <div class="flex items-center space-x-2">
                  <i class="fa-solid fa-people-roof text-blue-600"></i>
                  <span><?php echo htmlspecialchars($employee['emergency_contact'] ?? 'N/A'); ?></span>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Employment Tab -->
          <div id="employment" class="hidden p-4">
            <h3 class="text-lg font-bold mb-4">Employment Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block font-medium text-gray-700 mb-1">Employee ID</label>
                <p class="text-gray-700"><?php echo htmlspecialchars($employee['employee_code'] ?? 'N/A'); ?></p>
              </div>
              <div>
                <label class="block font-medium text-gray-700 mb-1">Date of Joining</label>
                <p class="text-gray-700"><?php echo htmlspecialchars($employee['date_of_joining'] ?? 'N/A'); ?></p>
              </div>
              <div>
                <label class="block font-medium text-gray-700 mb-1">Role</label>
                <p class="text-gray-700"><?php echo htmlspecialchars($employee['position']); ?></p>
              </div>
              <div>
                <label class="block font-medium text-gray-700 mb-1">Department</label>
                <p class="text-gray-700"><?php echo htmlspecialchars($employee['department']); ?></p>
              </div>
              <div>
                <label class="block font-medium text-gray-700 mb-1">Manager</label>
                <p class="text-gray-700"><?php echo htmlspecialchars($employee['manager'] ?? 'N/A'); ?></p>
              </div>
              <div>
                <label class="block font-medium text-gray-700 mb-1">Employment Type</label>
                <p class="text-gray-700"><?php echo htmlspecialchars($employee['employment_type'] ?? 'N/A'); ?></p>
              </div>
            </div>
          </div>
          
          <!-- Documents Tab -->
          <div id="documents" class="hidden p-4">
            <h3 class="text-lg font-bold mb-4">Documents</h3>
            <?php if (!empty($employee['documents'])): ?>
              <ul class="space-y-4">
                <?php foreach ($employee['documents'] as $doc): ?>
                  <li class="flex items-center justify-between bg-gray-50 p-4 rounded-md shadow-sm">
                    <div class="flex items-center space-x-2">
                      <?php if (stripos($doc['filename'], '.pdf') !== false): ?>
                        <i class="fa-solid fa-file-pdf text-red-500 text-lg"></i>
                      <?php elseif (stripos($doc['filename'], '.doc') !== false || stripos($doc['filename'], '.docx') !== false): ?>
                        <i class="fa-solid fa-file-word text-blue-500 text-lg"></i>
                      <?php else: ?>
                        <i class="fa-solid fa-file text-gray-500 text-lg"></i>
                      <?php endif; ?>
                      <span><?php echo htmlspecialchars($doc['filename']); ?></span>
                    </div>
                    <a href="<?php echo htmlspecialchars($doc['url']); ?>" class="text-blue-600 hover:underline">Download</a>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              <p class="text-gray-600">No documents available.</p>
            <?php endif; ?>
          </div>
          
         <!-- Permissions Tab -->
<div id="permissions" class="hidden p-4">
  <h3 class="text-lg font-bold mb-4">Permissions</h3>
  <!-- Sub-Tabs Header -->
  <div class="border-b border-gray-200 mb-4">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="permissionsSubTabs">
      <li class="mr-2">
        <a href="#menu-options" class="inline-block p-3 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active">
          Menu Options
        </a>
      </li>
      <li class="mr-2">
        <a href="#crud-options" class="inline-block p-3 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300">
          CRUD Options
        </a>
      </li>
    </ul>
  </div>
  <!-- Sub-Tabs Content -->
  <div id="permissionsSubTabContent">
    <!-- Menu Options Sub-Tab -->
    <div id="menu-options" class="p-4">
      <h4 class="text-md font-bold mb-2">Menu Options Permissions</h4>
      <p class="text-gray-600 mb-4">Control which menu items the user can see.</p>
      <!-- Hidden input for employee_id -->
      <form id="menu-options-form">
        <input type="hidden" name="employee_id" value="<?php echo (int)$employee['id']; ?>">
        <div class="space-y-4">
          <?php 
            // Retrieve the saved menu options; if none, use an empty array.
            $savedMenus = !empty($employee['permissions']['menu_options']) ? $employee['permissions']['menu_options'] : [];
          ?>
          <div class="flex items-center">
            <input type="checkbox" id="menu-dashboard" name="menu[]" value="dashboard"
              class="h-5 w-5 text-blue-600 border-gray-300 rounded transition duration-300 ease-in-out"
              <?php echo in_array('dashboard', $savedMenus) ? 'checked' : ''; ?>>
            <label for="menu-dashboard" class="ml-3 text-gray-700">Dashboard</label>
          </div>
          <div class="flex items-center">
            <input type="checkbox" id="menu-employees" name="menu[]" value="employees"
              class="h-5 w-5 text-blue-600 border-gray-300 rounded transition duration-300 ease-in-out"
              <?php echo in_array('employees', $savedMenus) ? 'checked' : ''; ?>>
            <label for="menu-employees" class="ml-3 text-gray-700">Employees</label>
          </div>
          <div class="flex items-center">
            <input type="checkbox" id="menu-departments" name="menu[]" value="departments"
              class="h-5 w-5 text-blue-600 border-gray-300 rounded transition duration-300 ease-in-out"
              <?php echo in_array('departments', $savedMenus) ? 'checked' : ''; ?>>
            <label for="menu-departments" class="ml-3 text-gray-700">Departments</label>
          </div>
          <div class="flex items-center">
            <input type="checkbox" id="menu-reports" name="menu[]" value="reports"
              class="h-5 w-5 text-blue-600 border-gray-300 rounded transition duration-300 ease-in-out"
              <?php echo in_array('reports', $savedMenus) ? 'checked' : ''; ?>>
            <label for="menu-reports" class="ml-3 text-gray-700">Reports</label>
          </div>
          <div class="flex items-center">
            <input type="checkbox" id="menu-settings" name="menu[]" value="settings"
              class="h-5 w-5 text-blue-600 border-gray-300 rounded transition duration-300 ease-in-out"
              <?php echo in_array('settings', $savedMenus) ? 'checked' : ''; ?>>
            <label for="menu-settings" class="ml-3 text-gray-700">Settings</label>
          </div>
        </div>
        <button type="button" id="save-menu-options" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700 transition duration-300 ease-in-out">
          <i class="fa-solid fa-save mr-2"></i> Save Menu Options
        </button>
      </form>
      <div id="menu-options-msg" class="mt-2 text-green-600 opacity-0 transition-opacity duration-300">Menu options saved successfully!</div>
    </div>
    
    <!-- CRUD Options Sub-Tab -->
    <div id="crud-options" class="hidden p-4">
      <h4 class="text-md font-bold mb-2">CRUD Options Permissions</h4>
      <p class="text-gray-600">Control which CRUD actions the user is allowed to perform.</p>
      <!-- (You can add similar controls here for CRUD options) -->
    </div>
  </div>
</div>
          
        </div>
      </section>
      
      <!-- Additional Information Section -->
      <section class="bg-white rounded-md shadow p-6">
        <h3 class="text-lg font-bold mb-4">Additional Information</h3>
        <p class="text-gray-700">
          This section can be used to display more details or custom fields for the employee, 
          such as benefits enrollment, certifications, training history, or any other necessary info.
        </p>
      </section>
      
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
    // Main Tabs
    const mainTabs = document.querySelectorAll('#employeeTabs a');
    const mainTabContents = document.querySelectorAll('#employeeTabContent > div');

    mainTabs.forEach((tab) => {
      tab.addEventListener('click', function (e) {
        e.preventDefault();
        mainTabs.forEach((t) => {
          t.classList.remove('text-blue-600', 'border-blue-600', 'active');
          t.classList.add('hover:text-gray-600');
        });
        mainTabContents.forEach((c) => c.classList.add('hidden'));
        this.classList.add('text-blue-600', 'border-blue-600', 'active');
        this.classList.remove('hover:text-gray-600');
        const targetId = this.getAttribute('href');
        const targetContent = document.querySelector(targetId);
        targetContent.classList.remove('hidden');
      });
    });

    // Permissions Sub-Tabs
    const subTabs = document.querySelectorAll('#permissionsSubTabs a');
    const subTabContents = document.querySelectorAll('#permissionsSubTabContent > div');

    subTabs.forEach((tab) => {
      tab.addEventListener('click', function(e) {
        e.preventDefault();
        subTabs.forEach((t) => {
          t.classList.remove('text-blue-600', 'border-blue-600', 'active');
          t.classList.add('hover:text-gray-600');
        });
        subTabContents.forEach((c) => c.classList.add('hidden'));
        this.classList.add('text-blue-600', 'border-blue-600', 'active');
        this.classList.remove('hover:text-gray-600');
        const targetId = this.getAttribute('href');
        const targetContent = document.querySelector(targetId);
        targetContent.classList.remove('hidden');
      });
    });

    // AJAX Save for Menu Options
    document.getElementById('save-menu-options').addEventListener('click', function() {
      const form = document.getElementById('menu-options-form');
      const formData = new FormData(form);
      
      fetch('/employees/save-permissions', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const msg = document.getElementById('menu-options-msg');
          msg.textContent = "Menu options saved successfully!";
          msg.classList.remove('opacity-0');
          setTimeout(() => {
            msg.classList.add('opacity-0');
          }, 2000);
        } else {
          alert("Error: " + (data.error || "Unable to save menu options."));
        }
      })
      .catch(error => {
        alert("Error: " + error);
      });
    });
  </script>
</body>
</html>
