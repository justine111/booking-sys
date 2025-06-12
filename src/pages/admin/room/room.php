<div class="p-7 mt-14 sm:ml-64">
    <h1 class="mb-4 text-xl font-semibold text-gray-900 dark:text-white">Hotel/appartment</h1>
    <a href="#" type="button" data-modal-show="addHotelModal" data-modal-target="addHotelModal"
        class="mb-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        Add New Hotel
    </a>
    <div class="px-6 py-4 mb-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input type="text" id="search" name="search" placeholder="Search by name or address"
                    class="block w-full p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
        </div>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        <span class="sr-only">Image</span>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Hotel Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        room type
                    </th>
                    <th scope="col" class="px-6 py-3">
                        room description
                    </th>
                    <th scope="col" class="px-6 py-3">
                        ammenities
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="p-4">
                        <img src="src\repositories\uploads\pic1.jpg" class="w-16 md:w-32 max-w-full max-h-full"
                            alt="Appartment Image">
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                        Luxury Appartment
                    </td>
                    <td class="px-6 py-4">
                        Deluxe room
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                        A spacious and luxurious appartment with modern amenities.
                    </td>
                    <td class="px-6 py-4">
                        <ul class="list-disc list-inside text-gray-600 dark:text-gray-400">
                            <li>24/7 Room Service</li>
                            <li>Free Wi-Fi</li>
                            <li>Swimming Pool</li>
                            <li>Gym Access</li>
                        </ul>
                    </td>
                    <td class="px-6 py-4">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                            Available
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="#" class="font-medium text-red-600 dark:text-red-500 hover:underline">Remove</a>
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline ml-4">Edit</a>
                    </td>
                </tr>

                <tr
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="p-4">
                        <img src="src\repositories\uploads\pic2.jpg" class="w-16 md:w-32 max-w-full max-h-full"
                            alt="Appartment Image">
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                        Cozy Studio
                    </td>
                    <td class="px-6 py-4">
                        Standard room
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                        A cozy studio perfect for solo travelers or couples.
                    </td>
                    <td class="px-6 py-4">
                        <ul class="list-disc list-inside text-gray-600 dark:text-gray-400">
                            <li>Free Parking</li>
                            <li>Air Conditioning</li>
                            <li>Kitchenette</li>
                        </ul>
                    </td>
                    <td class="px-6 py-4">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                            Unavailable
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="#" class="font-medium text-red-600 dark:text-red-500 hover:underline">Remove</a>
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline ml-4">Edit</a>
                    </td>
                </tr>
                <tr
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="p-4">
                        <img src="src\repositories\uploads\pic3.jpg" class="w-16 md:w-32 max-w-full max-h-full"
                            alt="Appartment Image">
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                        Modern Loft
                    </td>
                    <td class="px-6 py-4">
                        Executive room
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                        A modern loft with a stunning city view and contemporary design.
                    </td>
                    <td class="px-6 py-4">
                        <ul class="list-disc list-inside text-gray-600 dark:text-gray-400">
                            <li>Free Breakfast</li>
                            <li>Pet Friendly</li>
                            <li>Business Center</li>
                        </ul>
                    </td>
                    <td class="px-6 py-4">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                            Available
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="#" class="font-medium text-red-600 dark:text-red-500 hover:underline">Remove</a>
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline ml-4">Edit</a>
                    </td>

            </tbody>
        </table>
    </div>
    <!-- Modal: Add Hotel -->
    <div id="addHotelModal" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 flex items-center justify-center hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <form class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <!-- Modal header -->
                <div
                    class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Add New Hotel
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="addHotelModal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-6 gap-6">
                        <!-- Example fields, adjust as needed -->
                        <div class="col-span-6 sm:col-span-3">
                            <label for="hotel-name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Apartment
                                Name</label>
                            <input type="text" name="hotel-name" id="hotel-name"
                                class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Hotel Name" required>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="description"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                            <textarea name="description" id="description" rows="4"
                                class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Description"></textarea>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="address"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                            <input type="text" name="address" id="address"
                                class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Address" required>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="city"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">City</label>
                            <input type="text" name="city" id="city"
                                class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="City" required>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="price"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
                            <input type="number" name="price" id="price"
                                class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Price per night" required>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="amenities"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Amenities</label>
                            <textarea name="amenities" id="amenities" rows="2"
                                class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Amenities (comma separated)"></textarea>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="status"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                            <select name="status" id="status"
                                class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="available">Available</option>
                                <option value="unavailable">Unavailable</option>
                            </select>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="image"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Upload
                                Image</label>
                            <input type="file" name="image" id="image" accept="image/*"
                                class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                        <!-- Add more fields as needed -->
                    </div>
                </div>
                <!-- Modal footer -->
                <div
                    class="flex items-center p-6 space-x-3 rtl:space-x-reverse border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save</button>
                </div>
            </form>
        </div>
    </div>

</div>