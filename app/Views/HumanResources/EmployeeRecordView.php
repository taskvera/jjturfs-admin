<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Employee Record View</title>
  <!-- Tailwind CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.2.7/dist/tailwind.min.css" rel="stylesheet">
  
  <!-- Font Awesome CDN -->
  <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
      integrity="sha512-..."
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
  />
  
  <!-- Favicon (optional) -->
  <link rel="icon" type="image/png" href="https://example.com/favicon.png" />
</head>
<body class="bg-gray-100 text-gray-800">

  <!--
    HEADER / NAVIGATION
  -->
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
          <input
            type="text"
            class="block w-full pl-10 pr-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Search..."
          />
        </div>
      </div>
      
      <!-- User Menu / Avatar (optional) -->
      <div class="flex items-center space-x-4">
        <button class="text-gray-600 hover:text-gray-800">
          <i class="fa-solid fa-bell"></i>
        </button>
        <button class="text-gray-600 hover:text-gray-800 hidden md:inline-flex">
          <i class="fa-solid fa-comments"></i>
        </button>
        <div class="relative">
          <button class="flex items-center space-x-2 focus:outline-none">
            <img
              src="https://via.placeholder.com/36"
              alt="User Avatar"
              class="w-9 h-9 rounded-full object-cover"
            />
            <span class="hidden sm:inline-block font-medium">John Admin</span>
            <i class="fa-solid fa-caret-down text-sm"></i>
          </button>
          <!-- Possible dropdown menu if needed -->
        </div>
      </div>
    </div>
  </header>
  
  <!--
    MAIN CONTENT AREA
  -->
  <div class="flex flex-col lg:flex-row">

    <!--
      SIDEBAR
    -->
    <aside class="w-full lg:w-64 bg-white shadow-md mt-4 lg:mt-0 lg:h-screen">
      <nav class="px-4 pt-4 pb-6 lg:py-8">
        <ul class="space-y-2">
          <li>
            <a
              href="#"
              class="flex items-center space-x-3 py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100"
            >
              <i class="fa-solid fa-home"></i>
              <span>Dashboard</span>
            </a>
          </li>
          <li>
            <a
              href="#"
              class="flex items-center space-x-3 py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100"
            >
              <i class="fa-solid fa-users"></i>
              <span>Employees</span>
            </a>
          </li>
          <li>
            <a
              href="#"
              class="flex items-center space-x-3 py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100"
            >
              <i class="fa-solid fa-briefcase"></i>
              <span>Departments</span>
            </a>
          </li>
          <li>
            <a
              href="#"
              class="flex items-center space-x-3 py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100"
            >
              <i class="fa-solid fa-chart-line"></i>
              <span>Reports</span>
            </a>
          </li>
        </ul>
      </nav>
    </aside>

    <!--
      EMPLOYEE RECORD VIEW
    -->
    <main class="flex-1 p-4 lg:p-8">

      <!-- Page Title / Breadcrumbs -->
      <div class="mb-6">
        <nav class="mb-2 text-sm" aria-label="Breadcrumb">
          <ol class="list-none p-0 inline-flex space-x-2">
            <li>
              <a href="#" class="text-blue-600 hover:underline">Employees</a>
            </li>
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
            <img
              src="https://via.placeholder.com/150"
              alt="Employee Photo"
              class="w-32 h-32 rounded-full object-cover"
            />
          </div>
          <!-- Main Info -->
          <div class="flex-1">
            <h2 class="text-xl font-bold mb-1">Jane Doe</h2>
            <p class="text-gray-600 mb-1 flex items-center space-x-1">
              <i class="fa-solid fa-briefcase text-blue-600"></i>
              <span>Lead Software Engineer</span>
            </p>
            <p class="text-gray-600 mb-1 flex items-center space-x-1">
              <i class="fa-solid fa-building text-blue-600"></i>
              <span>Engineering Department</span>
            </p>
            <p class="text-gray-600 mb-2 flex items-center space-x-1">
              <i class="fa-solid fa-location-dot text-blue-600"></i>
              <span>New York, USA</span>
            </p>
          </div>
          <!-- Action Buttons -->
          <div class="flex space-x-2">
            <button
              class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700"
            >
              <i class="fa-solid fa-pen mr-2"></i>
              Edit
            </button>
            <button
              class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded shadow hover:bg-red-600"
            >
              <i class="fa-solid fa-trash mr-2"></i>
              Remove
            </button>
          </div>
        </div>
      </section>

      <!-- Tabs for Employee Information -->
      <section class="bg-white rounded-md shadow p-6 mb-6">
        <!-- Tabs Header -->
        <div class="border-b border-gray-200 mb-6">
          <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="employeeTabs" data-tabs-toggle="#employeeTabContent">
            <li class="mr-2">
              <a
                href="#overview"
                class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active"
              >
                <i class="fa-solid fa-user mr-2"></i> Overview
              </a>
            </li>
            <li class="mr-2">
              <a
                href="#contact"
                class="inline-block p-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300"
              >
                <i class="fa-solid fa-address-book mr-2"></i> Contact
              </a>
            </li>
            <li class="mr-2">
              <a
                href="#employment"
                class="inline-block p-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300"
              >
                <i class="fa-solid fa-id-badge mr-2"></i> Employment
              </a>
            </li>
            <li class="mr-2">
              <a
                href="#documents"
                class="inline-block p-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300"
              >
                <i class="fa-solid fa-folder-open mr-2"></i> Documents
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
              Jane Doe is a senior member of the engineering team with a focus on backend systems and cloud infrastructure. She leads several strategic projects and oversees a small team of developers.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
              <!-- Stats / KPI Cards -->
              <div class="bg-gray-50 p-4 rounded-md shadow-sm">
                <h4 class="text-gray-500 uppercase text-xs font-semibold mb-2">Projects Led</h4>
                <p class="text-2xl font-bold text-blue-600">8</p>
              </div>
              <div class="bg-gray-50 p-4 rounded-md shadow-sm">
                <h4 class="text-gray-500 uppercase text-xs font-semibold mb-2">Team Size</h4>
                <p class="text-2xl font-bold text-green-600">5</p>
              </div>
            </div>
            <div>
              <h4 class="text-lg font-bold mb-2">Key Skills</h4>
              <ul class="list-disc list-inside text-gray-700 space-y-1">
                <li>Cloud Architecture (AWS, Azure)</li>
                <li>Microservices</li>
                <li>CI/CD Automation</li>
                <li>Agile Methodologies</li>
              </ul>
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
                  <span>jane.doe@example.com</span>
                </div>
              </div>
              <div>
                <label class="block font-medium text-gray-700 mb-1" for="phone">Phone</label>
                <div class="flex items-center space-x-2">
                  <i class="fa-solid fa-phone text-blue-600"></i>
                  <span>+1 555 123 4567</span>
                </div>
              </div>
              <div>
                <label class="block font-medium text-gray-700 mb-1" for="address">Address</label>
                <div class="flex items-start space-x-2">
                  <i class="fa-solid fa-location-dot text-blue-600 mt-1"></i>
                  <span>
                    123 Main Street<br>
                    New York, NY 10001
                  </span>
                </div>
              </div>
              <div>
                <label class="block font-medium text-gray-700 mb-1">Emergency Contact</label>
                <div class="flex items-center space-x-2">
                  <i class="fa-solid fa-people-roof text-blue-600"></i>
                  <span>John Doe (Spouse) - +1 555 765 4321</span>
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
                <p class="text-gray-700">EMP-0099</p>
              </div>
              <div>
                <label class="block font-medium text-gray-700 mb-1">Date of Joining</label>
                <p class="text-gray-700">March 15, 2020</p>
              </div>
              <div>
                <label class="block font-medium text-gray-700 mb-1">Role</label>
                <p class="text-gray-700">Lead Software Engineer</p>
              </div>
              <div>
                <label class="block font-medium text-gray-700 mb-1">Department</label>
                <p class="text-gray-700">Engineering</p>
              </div>
              <div>
                <label class="block font-medium text-gray-700 mb-1">Manager</label>
                <p class="text-gray-700">Mark Smith, Head of Engineering</p>
              </div>
              <div>
                <label class="block font-medium text-gray-700 mb-1">Employment Type</label>
                <p class="text-gray-700">Full-time</p>
              </div>
            </div>
          </div>

          <!-- Documents Tab -->
          <div id="documents" class="hidden p-4">
            <h3 class="text-lg font-bold mb-4">Documents</h3>
            <ul class="space-y-4">
              <li class="flex items-center justify-between bg-gray-50 p-4 rounded-md shadow-sm">
                <div class="flex items-center space-x-2">
                  <i class="fa-solid fa-file-pdf text-red-500 text-lg"></i>
                  <span>Resume - JaneDoe.pdf</span>
                </div>
                <button class="text-blue-600 hover:underline">
                  Download
                </button>
              </li>
              <li class="flex items-center justify-between bg-gray-50 p-4 rounded-md shadow-sm">
                <div class="flex items-center space-x-2">
                  <i class="fa-solid fa-file-word text-blue-500 text-lg"></i>
                  <span>Performance Review 2023.docx</span>
                </div>
                <button class="text-blue-600 hover:underline">
                  Download
                </button>
              </li>
              <li class="flex items-center justify-between bg-gray-50 p-4 rounded-md shadow-sm">
                <div class="flex items-center space-x-2">
                  <i class="fa-solid fa-file text-gray-500 text-lg"></i>
                  <span>Project Plan - Rev 2.txt</span>
                </div>
                <button class="text-blue-600 hover:underline">
                  Download
                </button>
              </li>
            </ul>
          </div>
        </div>
      </section>

      <!-- Additional Info or Footer -->
      <section class="bg-white rounded-md shadow p-6">
        <h3 class="text-lg font-bold mb-4">Additional Information</h3>
        <p class="text-gray-700">
          This section can be used to display more details or custom fields for the employee, 
          such as benefits enrollment, certifications, training history, or any other necessary info.
        </p>
      </section>
    </main>
  </div>

  <!--
    FOOTER
  -->
  <footer class="bg-white mt-8 border-t border-gray-300">
    <div class="max-w-7xl mx-auto px-4 py-4 text-sm text-gray-500 flex justify-between">
      <p>&copy; 2025 MyCompany. All rights reserved.</p>
      <p>Powered by <a href="#" class="text-blue-600 hover:underline">Awesome Tech</a></p>
    </div>
  </footer>

  <!--
    EXAMPLE TABS JS (Optional)
    If you want the tabs to function without a reload, you can include
    a bit of JavaScript. Below is a very minimal approach, which you can
    expand or replace with a more robust solution.
  -->
  <script>
    const tabs = document.querySelectorAll('[href^="#"]');
    const tabContents = document.querySelectorAll('#employeeTabContent > div');

    tabs.forEach((tab) => {
      tab.addEventListener('click', function (e) {
        e.preventDefault();
        // Remove active class from all
        tabs.forEach((t) => {
          t.classList.remove('text-blue-600', 'border-blue-600', 'active');
          t.classList.add('hover:text-gray-600');
        });
        tabContents.forEach((c) => c.classList.add('hidden'));

        // Activate current
        this.classList.add('text-blue-600', 'border-blue-600', 'active');
        this.classList.remove('hover:text-gray-600');
        const targetId = this.getAttribute('href');
        const targetContent = document.querySelector(targetId);
        targetContent.classList.remove('hidden');
      });
    });
  </script>
</body>
</html>
