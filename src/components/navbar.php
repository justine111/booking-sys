<header class="fixed w-full z-20 top-0 start-0">
  <nav class="bg-white border-gray-200 border-b px-4 lg:px-6 py-2.5 dark:bg-gray-800">
    <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">
      <a href="#" class="flex items-center">
        <img src="<?= $basePath ?>/src/assets/img/logo.gif" class="mr-3 h-8 sm:h-9" alt="Logo" />
        <div>
          <span class="self-center text-xl text-orange-500 font-semibold whitespace-nowrap">StayScape</span>
          <p class="text-xs text-green-600 font-semibold leading-none">Tacloban City</p>
        </div>
      </a>
      <div class="flex items-center lg:order-2">
        <a href="#" class="text-white bg-orange-600 hover:bg-gray-50 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 mr-2">Log in</a>
        <a href="#" class="text-white bg-orange-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 mr-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">Get started</a>
      </div>
      <div class="hidden justify-between items-center w-full lg:flex lg:w-auto lg:order-1" id="mobile-menu-2">
        <ul class="flex flex-col mt-4 gap-2 font-medium lg:flex-row lg:space-x-8 lg:mt-0">
          <li>
            <a href="#" 
            class="block py-2 text-gray-700 border border-gray-100 hover:bg-orange-500 lg:hover:bg-transparent lg:border-0">Booking</a>
          </li>
          <li>
            <a href="#" 
            class="block py-2 text-gray-700 border border-gray-100 hover:bg-orange-500 lg:hover:bg-transparent lg:border-0">Services</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>