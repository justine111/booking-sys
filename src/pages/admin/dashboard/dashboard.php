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
    stroke: { curve: 'smooth' }
  };

  var chart = new ApexCharts(document.querySelector("#bookingsChart"), options);
  chart.render();
</script>
  <!---div class="grid grid-cols-3 gap-4 mb-4">
    <div class="flex items-center justify-center h-24 rounded-sm bg-gray-50 dark:bg-gray-800">
      <p class="text-2xl text-gray-400 dark:text-gray-500">
        
        <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
        </svg>
      </p>
    </div>
    <div class="flex items-center justify-center h-24 rounded-sm bg-gray-50 dark:bg-gray-800">
      <p class="text-2xl text-gray-400 dark:text-gray-500">
        <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
        </svg>
      </p>
    </div>
    <div class="flex items-center justify-center h-24 rounded-sm bg-gray-50 dark:bg-gray-800">
      <p class="text-2xl text-gray-400 dark:text-gray-500">
        <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
        </svg>
      </p>
    </div>
  </div>
  <div class="flex items-center justify-center h-48 mb-4 rounded-sm bg-gray-50 dark:bg-gray-800">
    <p class="text-2xl text-gray-400 dark:text-gray-500">
      <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
      </svg>
    </p>
  </div>
  <div class="grid grid-cols-2 gap-4 mb-4">
    <div class="flex items-center justify-center rounded-sm bg-gray-50 h-28 dark:bg-gray-800">
      <p class="text-2xl text-gray-400 dark:text-gray-500">
        <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
        </svg>
      </p>
    </div>
    <div class="flex items-center justify-center rounded-sm bg-gray-50 h-28 dark:bg-gray-800">
      <p class="text-2xl text-gray-400 dark:text-gray-500">
        <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
        </svg>
      </p>
    </div>
    <div class="flex items-center justify-center rounded-sm bg-gray-50 h-28 dark:bg-gray-800">
      <p class="text-2xl text-gray-400 dark:text-gray-500">
        <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
        </svg>
      </p>
    </div>
    <div class="flex items-center justify-center rounded-sm bg-gray-50 h-28 dark:bg-gray-800">
      <p class="text-2xl text-gray-400 dark:text-gray-500">
        <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
        </svg>
      </p>
    </div>
  </div>
  <div class="flex items-center justify-center h-48 mb-4 rounded-sm bg-gray-50 dark:bg-gray-800">
    <p class="text-2xl text-gray-400 dark:text-gray-500">
      <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
      </svg>
    </p>
  </div>
  <div class="grid grid-cols-2 gap-4">
    <div class="flex items-center justify-center rounded-sm bg-gray-50 h-28 dark:bg-gray-800">
      <p class="text-2xl text-gray-400 dark:text-gray-500">
        <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
        </svg>
      </p>
    </div>
    <div class="flex items-center justify-center rounded-sm bg-gray-50 h-28 dark:bg-gray-800">
      <p class="text-2xl text-gray-400 dark:text-gray-500">
        <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
        </svg>
      </p>
    </div>
    <div class="flex items-center justify-center rounded-sm bg-gray-50 h-28 dark:bg-gray-800">
      <p class="text-2xl text-gray-400 dark:text-gray-500">
        <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
        </svg>
      </p>
    </div>
    <div class="flex items-center justify-center rounded-sm bg-gray-50 h-28 dark:bg-gray-800">
      <p class="text-2xl text-gray-400 dark:text-gray-500">
        <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
        </svg>
      </p>
    </div>
  </div----->
</div>