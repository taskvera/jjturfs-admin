<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Employees Index</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>

</head>
<body class="bg-gray-100 text-gray-800">
  <div class="max-w-7xl mx-auto p-4">
    <h1 class="text-3xl font-bold mb-4">Employees</h1>

    <div class="bg-white rounded shadow p-4">
      <?php if (empty($employees)): ?>
        <p class="text-gray-600">No employees found.</p>
      <?php else: ?>
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
              <tr class="border-b hover:bg-gray-50">
                <td class="py-2 px-4"><?php echo htmlspecialchars($emp['id']); ?></td>
                <td class="py-2 px-4 font-semibold"><?php echo htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']); ?></td>
                <td class="py-2 px-4"><?php echo htmlspecialchars($emp['email']); ?></td>
                <td class="py-2 px-4"><?php echo htmlspecialchars($emp['position']); ?></td>
                <td class="py-2 px-4"><?php echo htmlspecialchars($emp['department']); ?></td>
                <td class="py-2 px-4"><?php echo htmlspecialchars($emp['location']); ?></td>
                <td class="py-2 px-4">
                  <!-- Example action buttons -->
                  <a href="#"
                     class="text-blue-600 hover:text-blue-800 inline-flex items-center mr-2">
                     <i class="fa-solid fa-eye mr-1"></i> View
                  </a>
                  <a href="#"
                     class="text-green-600 hover:text-green-800 inline-flex items-center mr-2">
                     <i class="fa-solid fa-pen mr-1"></i> Edit
                  </a>
                  <a href="#"
                     class="text-red-600 hover:text-red-800 inline-flex items-center">
                     <i class="fa-solid fa-trash mr-1"></i> Delete
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
