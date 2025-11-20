<div class="p-4 mt-14 sm:ml-64">
  <h1 class="mb-4 text-xl font-semibold text-gray-900 dark:text-white">Dashboard</h1>
  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 flex flex-col items-center">
      <span class="text-gray-500 dark:text-gray-400 text-sm">Total Bookings</span>
      <span class="text-2xl font-bold text-blue-700 dark:text-blue-400 mt-2">120</span>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 flex flex-col items-center">
      <span class="text-gray-500 dark:text-gray-400 text-sm">Total Hotels</span>
      <span class="text-2xl font-bold text-green-700 dark:text-green-400 mt-2">8</span>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 flex flex-col items-center">
      <span class="text-gray-500 dark:text-gray-400 text-sm">Total Users</span>
      <span class="text-2xl font-bold text-yellow-700 dark:text-yellow-400 mt-2">45</span>
    </div>
  </div>

  <!-- ApexCharts Bookings Trend -->
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
    <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Bookings Trend</h2>
    <div id="bookingsChart"></div>
  </div>
</div>

<script>
  var options = {
    chart: {
      type: 'line',
      height: 300,
      toolbar: { show: false }
    },
    series: [{
      name: 'Bookings',
      data: [10, 41, 35, 51, 49, 62, 69, 91, 148, 120, 110, 130]
    }],
    xaxis: {
      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    },
    colors: ['#2563eb'],
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth' },
    grid: {
      borderColor: '#e7e7e7',
      row: {
        colors: ['#f3f3f3', 'transparent'], // alternating row colors
        opacity: 0.5
      },
    },
    tooltip: {
      theme: 'dark'
    }
  };

  var chart = new ApexCharts(document.querySelector("#bookingsChart"), options);
  chart.render();
</script>