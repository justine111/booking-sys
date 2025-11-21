<?php
require_once __DIR__ . '/../../../controller/dashboard-controller.php';
$dashboardController = new dashboard_controller();
$dashboardData = $dashboardController->getDashboardStats();

// Extract data for easy access
$stats = $dashboardData['data']['stats'] ?? [];
$revenue = $dashboardData['data']['revenue'] ?? [];
$bookingsTrend = $dashboardData['data']['bookingsTrend'] ?? [];
$recentBookings = $dashboardData['data']['recentBookings'] ?? [];
$topHotels = $dashboardData['data']['topHotels'] ?? [];

// Define months for chart data
$allMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
$monthsShort = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

// Prepare bookings trend data for chart
$bookingsChartData = array_fill(0, 12, 0);
foreach ($bookingsTrend as $trend) {
  $monthIndex = array_search($trend['month'], $allMonths);
  if ($monthIndex !== false) {
    $bookingsChartData[$monthIndex] = (int)$trend['bookings_count'];
  }
}
?>

<div class="p-4 mt-14 sm:ml-64">
  <div class="flex justify-between items-center mb-6">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Overview</h1>
      <p class="text-gray-500 dark:text-gray-400">Welcome back! Here's what's happening today.</p>
    </div>
    <div class="flex items-center space-x-4">
      <div class="relative">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
          <i class="fas fa-search text-gray-500"></i>
        </div>
        <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Search...">
      </div>
      <button id="theme-toggle" class="p-2.5 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
        <i class="fas fa-moon text-gray-500 dark:text-yellow-400"></i>
      </button>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
      <div class="flex justify-between items-start">
        <div>
          <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Bookings</p>
          <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2"><?= number_format($stats['totalBookings'] ?? 0) ?></p>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Active bookings</p>
        </div>
        <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
          <i class="fas fa-calendar-check text-blue-500 text-xl"></i>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border-l-4 border-green-500">
      <div class="flex justify-between items-start">
        <div>
          <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Hotels</p>
          <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2"><?= number_format($stats['totalHotels'] ?? 0) ?></p>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Properties listed</p>
        </div>
        <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
          <i class="fas fa-hotel text-green-500 text-xl"></i>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
      <div class="flex justify-between items-start">
        <div>
          <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</p>
          <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2"><?= number_format($stats['totalUsers'] ?? 0) ?></p>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Registered users</p>
        </div>
        <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
          <i class="fas fa-users text-yellow-500 text-xl"></i>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border-l-4 border-red-500">
      <div class="flex justify-between items-start">
        <div>
          <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Occupancy Rate</p>
          <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2"><?= number_format($stats['occupancyRate'] ?? 0, 2) ?>%</p>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Current occupancy</p>
        </div>
        <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
          <i class="fas fa-chart-pie text-red-500 text-xl"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts Section -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Bookings Trend Chart -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Bookings Trend</h2>
        <div class="flex space-x-2">
          <button class="text-xs px-3 py-1 bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 rounded-lg font-medium">Monthly</button>
          <button class="text-xs px-3 py-1 text-gray-500 dark:text-gray-400 rounded-lg font-medium">Weekly</button>
        </div>
      </div>
      <div id="bookingsChart"></div>
    </div>

    <!-- Revenue Chart -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Revenue Overview</h2>
        <div class="flex space-x-2">
          <button class="text-xs px-3 py-1 bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 rounded-lg font-medium">2025</button>
          <button class="text-xs px-3 py-1 text-gray-500 dark:text-gray-400 rounded-lg font-medium">2024</button>
        </div>
      </div>
      <div id="revenueChart"></div>
    </div>
  </div>

  <!-- Additional Info Section -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Recent Bookings -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 lg:col-span-2">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Bookings</h2>
        <a href="#" class="text-sm text-primary-600 dark:text-primary-400 font-medium">View All</a>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
              <th scope="col" class="px-4 py-3">Guest</th>
              <th scope="col" class="px-4 py-3">Hotel</th>
              <th scope="col" class="px-4 py-3">Check-in</th>
              <th scope="col" class="px-4 py-3">Status</th>
              <th scope="col" class="px-4 py-3">Amount</th>
            </tr>
          </thead>
          <tbody>
            <?php if (count($recentBookings) > 0): ?>
              <?php foreach ($recentBookings as $booking): ?>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                  <td class="px-4 py-3 font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($booking['client_name']) ?></td>
                  <td class="px-4 py-3"><?= htmlspecialchars($booking['hotel_name'] ?: 'N/A') ?></td>
                  <td class="px-4 py-3"><?= date('d M Y', strtotime($booking['check_in_date'])) ?></td>
                  <td class="px-4 py-3">
                    <?php
                    $statusClass = 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
                    $statusText = $booking['status'] ?: 'Unknown';

                    if (stripos($statusText, 'confirm') !== false) {
                      $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                    } elseif (stripos($statusText, 'pending') !== false) {
                      $statusClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                    } elseif (stripos($statusText, 'cancel') !== false) {
                      $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                    }
                    ?>
                    <span class="<?= $statusClass ?> text-xs font-medium px-2.5 py-0.5 rounded-full"><?= htmlspecialchars($statusText) ?></span>
                  </td>
                  <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">$<?= number_format($booking['total_amount'], 2) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr class="bg-white dark:bg-gray-800">
                <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No recent bookings found</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Top Hotels -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
      <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Top Performing Hotels</h2>
      <div class="space-y-4">
        <?php if (count($topHotels) > 0): ?>
          <?php
          $colors = ['blue', 'green', 'yellow', 'purple'];
          foreach ($topHotels as $index => $hotel):
            $color = $colors[$index % count($colors)];
          ?>
            <div class="flex items-center">
              <div class="w-10 h-10 rounded-lg bg-<?= $color ?>-100 dark:bg-<?= $color ?>-900/30 flex items-center justify-center mr-3">
                <i class="fas fa-hotel text-<?= $color ?>-500"></i>
              </div>
              <div class="flex-1">
                <p class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($hotel['hotel_name']) ?></p>
                <p class="text-xs text-gray-500 dark:text-gray-400"><?= $hotel['bookings_count'] ?> bookings this year</p>
              </div>
              <span class="bg-<?= $color ?>-100 text-<?= $color ?>-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-<?= $color ?>-900 dark:text-<?= $color ?>-300">$<?= number_format($hotel['total_revenue'], 0) ?></span>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No data available</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
  // Theme toggle functionality
  document.getElementById('theme-toggle').addEventListener('click', function() {
    document.documentElement.classList.toggle('dark');
    const icon = this.querySelector('i');
    if (document.documentElement.classList.contains('dark')) {
      icon.classList.remove('fa-moon');
      icon.classList.add('fa-sun');
    } else {
      icon.classList.remove('fa-sun');
      icon.classList.add('fa-moon');
    }
  });

  // Prepare PHP data for JavaScript
  const bookingsChartData = <?= json_encode($bookingsChartData) ?>;
  const monthsShort = <?= json_encode($monthsShort) ?>;

  // Bookings Trend Chart
  var bookingsOptions = {
    chart: {
      type: 'line',
      height: 300,
      toolbar: {
        show: false
      },
      zoom: {
        enabled: false
      }
    },
    series: [{
      name: 'Bookings',
      data: bookingsChartData
    }],
    xaxis: {
      categories: monthsShort
    },
    colors: ['#3b82f6'],
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'smooth',
      width: 3
    },
    grid: {
      borderColor: '#e5e7eb',
      strokeDashArray: 5,
      xaxis: {
        lines: {
          show: false
        }
      }
    },
    tooltip: {
      theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
    },
    markers: {
      size: 5,
      hover: {
        size: 7
      }
    }
  };

  var bookingsChart = new ApexCharts(document.querySelector("#bookingsChart"), bookingsOptions);
  bookingsChart.render();

  // Revenue Chart (using dummy data for now, can be replaced with actual revenue by month)
  var revenueOptions = {
    chart: {
      type: 'bar',
      height: 300,
      toolbar: {
        show: false
      }
    },
    series: [{
      name: 'Revenue',
      data: [12500, 13100, 11900, 14200, 13800, 15200, 16800, 18500, 21000, 19500, 18700, 20300]
    }],
    xaxis: {
      categories: monthsShort
    },
    colors: ['#10b981'],
    dataLabels: {
      enabled: false
    },
    grid: {
      borderColor: '#e5e7eb',
      strokeDashArray: 5,
      xaxis: {
        lines: {
          show: false
        }
      }
    },
    tooltip: {
      theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
      y: {
        formatter: function(value) {
          return "$" + value.toLocaleString();
        }
      }
    }
  };

  var revenueChart = new ApexCharts(document.querySelector("#revenueChart"), revenueOptions);
  revenueChart.render();

  // Update charts on theme change
  document.getElementById('theme-toggle').addEventListener('click', function() {
    setTimeout(() => {
      bookingsChart.updateOptions({
        tooltip: {
          theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
        }
      });
      revenueChart.updateOptions({
        tooltip: {
          theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
        }
      });
    }, 100);
  });
</script>