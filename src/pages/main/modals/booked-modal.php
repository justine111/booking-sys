<div id="book-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
  <div class="relative p-4 w-full max-w-2xl max-h-full">
    <div class="relative bg-white rounded-xl shadow-sm dark:bg-gray-700">
      <!-- Modal header -->
      <div class="flex items-start justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
        <div>
          <h3 class="text-md font-semibold text-gray-900 flex items-start gap-1">
            <i data-lucide="house-plus" class="w-5 h-5 text-orange-600"></i>
            Ask for room reservation
          </h3>
          <p class="text-gray-600 text-sm">Fill in the details to reserve the room</p>
        </div>
        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="book-modal">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
      </div>

      <!-- Modal body -->
      <form class="p-4" method="post" id="reservation-form">
        <?php
        $unitId = $_GET['id'];
        ?>
        <input type="hidden" name="unit" value="<?= $unitId ?>">

        <div class="grid gap-4 mb-4 grid-cols-2">
          <div class="col-span-2">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name <span class="text-gray-500 text-xs">(required)</span></label>
            <input
              type="text"
              name="name"
              id="name"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
              placeholder="">
            <p id="name-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>

          <div class="col-span-2 sm:col-span-1">
            <label for="phoneno" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contact no. <span class="text-gray-500 text-xs">(required)</span></label>
            <input
              type="number"
              name="phoneno"
              id="phoneno"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
              placeholder="">
            <p id="phoneno-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>

          <div class="col-span-2 sm:col-span-1">
            <label for="stay-duration" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Stay Duration <span class="text-gray-500 text-xs">(required)</span></label>
            <select id="stay-duration" name="stay-duration" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
              <option selected disabled>Select stay duration</option>
              <option value="2 Days 1 Night">2 Days / 1 Night</option>
              <option value="3 Days 2 Nights">3 Days / 2 Nights</option>
              <option value="7 Days 6 Nights">7 Days / 6 Nights</option>
            </select>
            <p id="stay-duration-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>

          <div class="col-span-2 sm:col-span-1">
            <label for="check-in-date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Check-in Date <span class="text-gray-500 text-xs">(required)</span></label>
            <input
              type="date"
              name="check_in_date"
              id="check-in-date"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            <p id="check-in-date-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>

          <div class="col-span-2 sm:col-span-1">
            <label for="check-out-date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Check-out Date <span class="text-gray-500 text-xs">(required)</span></label>
            <input
              type="date"
              name="check_out_date"
              id="check-out-date"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            <p id="check-out-date-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>

          <div class="col-span-2">
            <label for="emailAcc" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email Account <span class="text-gray-500 text-xs">(required)</span></label>
            <input
              type="email"
              name="emailAcc"
              id="emailAcc"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
              placeholder="">
            <p id="emailAcc-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>

          <div class="col-span-2">
            <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Message</label>
            <textarea
              id="description"
              name="description"
              rows="4"
              class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              placeholder="Leave a message..."></textarea>
          </div>
        </div>

        <!-- Terms and Conditions -->
        <div class="mb-4 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-600">
          <div class="p-3 bg-blue-50 dark:bg-blue-900 border-b border-blue-200 dark:border-blue-800 rounded-t-lg">
            <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-200 flex items-center gap-2">
              <i data-lucide="scroll-text" class="w-4 h-4"></i>
              Terms and Conditions & User Agreement
            </h4>
          </div>
          <div class="p-4 max-h-64 overflow-y-auto text-xs text-gray-700 dark:text-gray-300 space-y-3">
            <p class="font-semibold text-sm text-gray-900 dark:text-white">StaySmart – AI Powered Smart Airbnb Management and Booking System</p>

            <p>By accessing and using the StaySmart platform, both Hosts and Guests agree to the following terms and conditions. Guests acknowledge that the information they provide—such as their name, contact details, and booking preferences—must be accurate and truthful. They understand that submitting a booking request through the system does not automatically confirm their stay; instead, each request must be reviewed and approved by the Host. Guests also agree to respect the property rules provided in each listing and to communicate with Hosts in a polite, responsible, and cooperative manner.</p>

            <p>Hosts agree to maintain the accuracy and integrity of their property listings. This includes providing updated information on availability, pricing, house rules, and other important details that may affect a Guest's booking decision. Hosts acknowledge their responsibility to review booking requests promptly and communicate clearly with Guests. They also understand that their listings must comply with local regulations related to rental operations, safety, and accommodation standards.</p>

            <p>Both Hosts and Guests recognize that StaySmart includes an AI-powered chatbot designed to assist with common inquiries and to help facilitate communication. While this feature aims to improve the user experience, they understand that chatbot responses may not always be perfect and should not be treated as final or official advice. Users agree to use the platform responsibly, avoiding any actions that could mislead others, disrupt system operations, or violate applicable laws.</p>

            <p>The system collects necessary information to support its functions, including booking management and communication between Hosts and Guests. Users acknowledge that this data will be handled with care and will only be used for system-related purposes. Although the developers implement reasonable security measures, users understand that no digital system can guarantee absolute protection from risks.</p>

            <p class="font-semibold text-gray-900 dark:text-white">Property Damage Penalty:</p>
            <p>In the event that a Guest causes damage to any part of the Host's property—whether intentionally or accidentally—the Guest agrees to take full responsibility for the cost of repair or replacement. The Host has the right to assess the extent of the damage and provide clear documentation, such as photos or written reports, to support the claim. Guests understand that damages may include, but are not limited to, broken furniture or appliances, stained or destroyed bedding, lost items, or any harm that affects the safety, cleanliness, or normal functioning of the property. Once the damage has been verified, the Guest must settle any corresponding charges within a reasonable period agreed upon by both parties. Failure to comply may result in additional penalties, refusal of future bookings, or a formal report to the platform administrators. Hosts agree to be fair, transparent, and professional when assessing damages and determining the appropriate penalty.</p>

            <p>By continuing to use StaySmart, Hosts and Guests confirm that they have read, understood, and agreed to the terms of this agreement. They accept that the system is primarily developed for academic and functional demonstration purposes and that the developers are not responsible for disputes, inaccuracies in user-submitted information, or issues that may arise between Hosts and Guests. Ongoing use of the platform signifies acceptance of any future updates or changes to these terms.</p>
          </div>
          <div class="p-3 bg-gray-100 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 rounded-b-lg">
            <label class="flex items-center gap-2 text-xs cursor-pointer">
              <input type="checkbox" id="accept-terms" required class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
              <span class="text-gray-700 dark:text-gray-300">I have read and agree to the Terms and Conditions</span>
            </label>
          </div>
        </div>

        <button type="submit" class="text-white inline-flex items-center bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 text-center">
          <i data-lucide="plus" class="w-[18px] mr-1"></i>
          Submit Reservation
        </button>
      </form>
    </div>
  </div>
</div>

<script>
  lucide.createIcons();

  <?php
  require_once __DIR__ . '/../../../assets/js/utils.js';
  require_once __DIR__ . '/../js/main.js';
  ?>
</script>