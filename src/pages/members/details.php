<?php
require_once __DIR__ . '/../../controllers/members-controller.php';
$memberDetails = new member_controller();


$memberId = isset($_GET['member_id']) ? $_GET['member_id'] : null;

$lincGroup = $memberDetails->lincGroupCount($memberId, 2);
$details = $memberDetails->getMemberDetails($memberId);
?>

<main id="mainPage" class="content-wrapper">
  <div class="content flex flex-col lg:flex-row gap-4">
    <div class="w-full lg:w-1/2 p-6 bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
      <!-- Profile Header with Cover Photo -->
      <div class="mb-6 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm">
        <div class="relative h-48 bg-blue-500">
          <img
            src="/tupas/src/repositories/upload/<?= htmlspecialchars($details['cover_photo'] ?? 'cover.jpg'); ?>"
            alt="Cover photo"
            class="w-full h-full object-cover"
            id="coverPreview">

          <!-- Cover Photo Upload Overlay -->
          <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center opacity-0 hover:opacity-100 cursor-pointer"
            onclick="document.getElementById('coverInput').click()">
            <div class="text-white text-center">
              <i data-lucide="camera" class="w-8 h-8 mx-auto mb-2"></i>
              <span class="text-sm font-medium">Change Cover Photo</span>
            </div>
          </div>

          <!-- Cover Photo Upload Form -->
          <form role="form" action="" method="post" id="uploadCover" autocomplete="off" enctype="multipart/form-data" class="hidden">
            <input type="file" name="cover" id="coverInput" class="hidden" onchange="previewCover(event)">
            <input type="hidden" name="memberid" value="<?= $details['member_id']; ?>">
          </form>
        </div>

        <!-- Profile Content Area -->
        <div class="relative px-6 pb-12 pt-2">
          <div class="absolute -top-16 left-6">
            <div class="relative">
              <div class="w-34 h-30 md:w-36 md:h-36 rounded-lg border border-white dark:border-gray-900 bg-white dark:bg-gray-900 shadow-xl overflow-hidden">
                <img
                  class="w-full h-full object-cover cursor-pointer"
                  src="/tupas/src/repositories/upload/<?= htmlspecialchars($details['profile_path']) ?: 'tlh.png'; ?>"
                  id="preview"
                  onclick="document.getElementById('imageInput').click()">
              </div>

              <!-- Profile Picture Upload Overlay -->
              <div class="absolute inset-0 rounded-full bg-black bg-opacity-0 hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center opacity-0 hover:opacity-100 cursor-pointer"
                onclick="document.getElementById('imageInput').click()">
                <i data-lucide="camera" class="w-6 h-6 text-white"></i>
              </div>
            </div>
          </div>

          <!-- Profile Picture Upload Form -->
          <form role="form" action="" method="post" id="uploadProfile" autocomplete="off" enctype="multipart/form-data" class="hidden">
            <input type="file" name="image" id="imageInput" class="hidden" onchange="previewImage(event)">
            <input type="hidden" name="memberid" value="<?= $details['member_id']; ?>">
          </form>

          <!-- Name and Info Section -->
          <div class="flex flex-col md:flex-row justify-between items-start md:items-center md:mt-0 md:pl-40">
            <!-- Member Name and Details -->
            <div class="flex-1 mb-4 md:mb-0">
              <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <?= htmlspecialchars($details['first_name'] ?? '') . ' ' . htmlspecialchars($details['last_name'] ?? '') ?>
              </h1>
              <p class="text-gray-600 dark:text-gray-400 text-xs">
                <?= htmlspecialchars($details['role_name'] ?? 'Member') ?> •
                <?= htmlspecialchars($details['church_name'] ?? 'Church Member') ?>
              </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-2">
              <button
                type="submit"
                form="uploadProfile"
                class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white">
                <i data-lucide="upload" class="w-4 h-4 mr-2"></i>
                Upload Profile
              </button>

              <button
                type="submit"
                form="uploadCover"
                class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100 h-9 px-4 py-2">
                <i data-lucide="image" class="w-4 h-4 mr-2"></i>
                Upload Cover
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Personal Details Form -->
      <form role="form" action="" method="post" id="updateMember" autocomplete="off">
        <input type="hidden" name="member_id" value="<?= $memberId ?>">

        <div class="space-y-6">
          <!-- Personal Information -->
          <div>
            <h3 class="flex items-center text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">
              <i data-lucide="info" class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400"></i>
              Personal Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Last Name -->
              <div class="space-y-2">
                <label for="lastname" class="text-sm font-medium text-gray-700 dark:text-gray-300">Last name</label>
                <input
                  type="text"
                  id="lastname"
                  name="lastname"
                  value="<?= htmlspecialchars($details['last_name'] ?? 'N/A') ?>"
                  class="flex h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm ring-offset-background placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-gray-900 dark:text-gray-100"
                  placeholder="Enter last name" />
              </div>

              <!-- First Name -->
              <div class="space-y-2">
                <label for="firstname" class="text-sm font-medium text-gray-700 dark:text-gray-300">First name</label>
                <input
                  type="text"
                  id="firstname"
                  name="firstname"
                  value="<?= htmlspecialchars($details['first_name'] ?? 'N/A') ?>"
                  class="flex h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm ring-offset-background placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-gray-900 dark:text-gray-100"
                  placeholder="Enter first name" />
              </div>

              <!-- Middle Name -->
              <div class="space-y-2">
                <label for="middlename" class="text-sm font-medium text-gray-700 dark:text-gray-300">Middle name</label>
                <input
                  type="text"
                  id="middlename"
                  name="middlename"
                  value="<?= htmlspecialchars($details['middle_name'] ?? 'N/A') ?>"
                  class="flex h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm ring-offset-background placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-gray-900 dark:text-gray-100"
                  placeholder="Enter middle name" />
              </div>

              <!-- Gender -->
              <div class="space-y-2">
                <label for="gender" class="text-sm font-medium text-gray-700 dark:text-gray-300">Gender</label>
                <select
                  id="gender"
                  name="gender"
                  class="flex h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-gray-900 dark:text-gray-100">
                  <option value="<?= $details['gender']; ?>" selected><?= $details['gender']; ?></option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                </select>
              </div>

              <!-- Birthday -->
              <div class="space-y-2">
                <label for="birthday" class="text-sm font-medium text-gray-700 dark:text-gray-300">Birthday</label>
                <input
                  type="date"
                  id="birthday"
                  name="birthday"
                  value="<?= htmlspecialchars($details['birthdate']) ?>"
                  class="flex h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-gray-900 dark:text-gray-100" />
              </div>

              <!-- Civil Status -->
              <div class="space-y-2">
                <label for="civilstatus" class="text-sm font-medium text-gray-700 dark:text-gray-300">Civil Status</label>
                <select
                  id="civilstatus"
                  name="civilstatus"
                  class="flex h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-gray-900 dark:text-gray-100">
                  <option value="<?= $details['civil_status']; ?>" selected><?= $details['civil_status']; ?></option>
                  <option value="Single">Single</option>
                  <option value="Married">Married</option>
                  <option value="Widowed">Widowed</option>
                  <option value="Divorced">Divorced</option>
                  <option value="Separated">Separated</option>
                </select>
              </div>

              <!-- Blood Type -->
              <div class="space-y-2">
                <label for="bloodtype" class="text-sm font-medium text-gray-700 dark:text-gray-300">Blood type</label>
                <select
                  id="bloodtype"
                  name="bloodtype"
                  class="flex h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-gray-900 dark:text-gray-100">
                  <option value="<?= $details['blood_type']; ?>" selected><?= $details['blood_type']; ?></option>
                  <option value="A+">A+</option>
                  <option value="A-">A-</option>
                  <option value="B+">B+</option>
                  <option value="B-">B-</option>
                  <option value="AB+">AB+</option>
                  <option value="AB-">AB-</option>
                  <option value="O+">O+</option>
                  <option value="O-">O-</option>
                </select>
              </div>

              <!-- Contact No. -->
              <div class="space-y-2">
                <label for="contact" class="text-sm font-medium text-gray-700 dark:text-gray-300">Contact no.</label>
                <input
                  type="number"
                  id="contact"
                  name="contact"
                  value="<?= htmlspecialchars($details['contact_no']) ?>"
                  class="flex h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm ring-offset-background placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-gray-900 dark:text-gray-100"
                  placeholder="Enter contact number" />
              </div>

              <!-- Email Account -->
              <div class="space-y-2">
                <label for="email" class="text-sm font-medium text-gray-700 dark:text-gray-300">Email Account</label>
                <input
                  type="text"
                  id="email"
                  name="email"
                  value="<?= htmlspecialchars($details['email'] ?? 'N/A') ?>"
                  class="flex h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm ring-offset-background placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-gray-900 dark:text-gray-100"
                  placeholder="Enter email address" />
              </div>

              <!-- Employment -->
              <div class="space-y-2">
                <label for="employment" class="text-sm font-medium text-gray-700 dark:text-gray-300">Employment</label>
                <input
                  type="text"
                  id="employment"
                  name="employment"
                  value="<?= htmlspecialchars($details['employment']) ?>"
                  class="flex h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm ring-offset-background placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-gray-900 dark:text-gray-100"
                  placeholder="Enter employment" />
              </div>

              <!-- School Attended -->
              <div class="space-y-2 md:col-span-2">
                <label for="school" class="text-sm font-medium text-gray-700 dark:text-gray-300">School attended</label>
                <input
                  type="text"
                  id="school"
                  name="school"
                  value="<?= htmlspecialchars($details['school']) ?>"
                  class="flex h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm ring-offset-background placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-gray-900 dark:text-gray-100"
                  placeholder="Enter school name" />
              </div>

              <!-- Complete Address -->
              <div class="space-y-2 md:col-span-2">
                <label for="address" class="text-sm font-medium text-gray-700 dark:text-gray-300">Complete Address</label>
                <input
                  type="text"
                  id="address"
                  name="address"
                  value="<?= htmlspecialchars($details['address'] ?? 'N/A') ?>"
                  class="flex h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm ring-offset-background placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-gray-900 dark:text-gray-100"
                  placeholder="Enter complete address" />
              </div>
            </div>
          </div>

          <!-- Family Background -->
          <div>
            <div class="flex items-center mb-4">
              <i data-lucide="heart-handshake" class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400"></i>
              <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Family Background</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Spouse Name -->
              <div class="space-y-2">
                <label for="spousename" class="text-sm font-medium text-gray-700 dark:text-gray-300">Spouse name</label>
                <input
                  type="text"
                  id="spousename"
                  name="spousename"
                  value="<?= htmlspecialchars($details['spouse_name']) ?>"
                  class="flex h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm ring-offset-background placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-gray-900 dark:text-gray-100"
                  placeholder="Enter spouse name" />
              </div>

              <!-- Spouse Occupation -->
              <div class="space-y-2">
                <label for="spousework" class="text-sm font-medium text-gray-700 dark:text-gray-300">Spouse Occupation</label>
                <input
                  type="text"
                  id="spousework"
                  name="spousework"
                  value="<?= htmlspecialchars($details['spouse_occupation']) ?>"
                  class="flex h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm ring-offset-background placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-gray-900 dark:text-gray-100"
                  placeholder="Enter spouse occupation" />
              </div>

              <!-- Father Name -->
              <div class="space-y-2">
                <label for="fathername" class="text-sm font-medium text-gray-700 dark:text-gray-300">Father name</label>
                <input
                  type="text"
                  id="fathername"
                  name="fathername"
                  value="<?= htmlspecialchars($details['father_name']) ?>"
                  class="flex h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm ring-offset-background placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-gray-900 dark:text-gray-100"
                  placeholder="Enter father name" />
              </div>

              <!-- Father Occupation -->
              <div class="space-y-2">
                <label for="fatherwork" class="text-sm font-medium text-gray-700 dark:text-gray-300">Father Occupation</label>
                <input
                  type="text"
                  id="fatherwork"
                  name="fatherwork"
                  value="<?= htmlspecialchars($details['father_occupation']) ?>"
                  class="flex h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm ring-offset-background placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-gray-900 dark:text-gray-100"
                  placeholder="Enter father occupation" />
              </div>

              <!-- Mother Name -->
              <div class="space-y-2">
                <label for="mothername" class="text-sm font-medium text-gray-700 dark:text-gray-300">Mother name</label>
                <input
                  type="text"
                  id="mothername"
                  name="mothername"
                  value="<?= htmlspecialchars($details['mother_name']) ?>"
                  class="flex h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm ring-offset-background placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-gray-900 dark:text-gray-100"
                  placeholder="Enter mother name" />
              </div>

              <!-- Mother Occupation -->
              <div class="space-y-2">
                <label for="motherwork" class="text-sm font-medium text-gray-700 dark:text-gray-300">Mother Occupation</label>
                <input
                  type="text"
                  id="motherwork"
                  name="motherwork"
                  value="<?= htmlspecialchars($details['mother_occupation']) ?>"
                  class="flex h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm ring-offset-background placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-gray-900 dark:text-gray-100"
                  placeholder="Enter mother occupation" />
              </div>
            </div>
          </div>
        </div>
    </div>

    <!-- Church Information Section -->
    <div class="w-full lg:w-1/2 p-6 bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
      <!-- Church Background -->
      <div class="mb-6">
        <div class="flex items-center mb-4">
          <i data-lucide="house" class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400"></i>
          <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Church Background</h3>
        </div>

        <!-- LinC Members -->
        <div class="mb-6">
          <div class="flex justify-between items-center mb-3">
            <span class="flex items-center text-gray-700 dark:text-gray-300 text-sm font-medium">
              <i data-lucide="users" class="w-4 h-4 mr-2"></i>
              <?php
              if (!empty($lincGroup) && isset($lincGroup[0]['total'])) {
                echo $lincGroup[0]['total'] . ' LinC Members';
              } else {
                echo 'LinC Member';
              }
              ?>
            </span>
          </div>
          <div class="flex">
            <div class="flex">
              <?php
              $displayed = 0;
              foreach ($lincGroup as $row):
                if ($displayed < 8):
              ?>
                  <img class="border-2 border-white dark:border-gray-800 rounded-full h-8 w-8 md:h-10 md:w-10 <?= count($lincGroup) == 1 ? '' : '-mr-3 md:-mr-3' ?>"
                    src="src/repositories/upload/<?= !empty($row['profile_path']) ? htmlspecialchars($row['profile_path']) : 'profile.png'; ?>"
                    lazy="loading"
                    alt="">
                <?php
                  $displayed++;
                endif;
              endforeach;

              if (count($lincGroup) > 8):
                ?>
                <div class="border-2 border-white dark:border-gray-800 rounded-full h-8 w-8 md:h-10 md:w-10 -mr-3 md:-mr-3 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                  <span class="text-xs font-bold text-gray-700 dark:text-gray-300">+<?= count($lincGroup) - 8 ?></span>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Church Information Form -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Church -->
          <div class="space-y-2">
            <label for="church" class="text-sm font-medium text-gray-700 dark:text-gray-300">Church</label>
            <select
              id="church"
              name="church"
              class="flex h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-gray-900 dark:text-gray-100">
              <option value="" selected disabled>Choose Church</option>
              <option value="<?= $details['church_id']; ?>" selected><?= $details['church_name']; ?></option>
              <?php
              $church = $memberDetails->selectChurch();
              foreach ($church as $row):
              ?>
                <option value="<?= $row['church_id']; ?>"><?= $row['church_name']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Ministry -->
          <div class="space-y-2">
            <label for="ministry" class="text-sm font-medium text-gray-700 dark:text-gray-300">Ministry</label>
            <select
              id="ministry"
              name="ministry"
              class="flex h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-gray-900 dark:text-gray-100">
              <option value="<?= $details['ministry_id']; ?>" selected><?= $details['ministry_name']; ?></option>
              <?php
              $ministry = $memberDetails->selectMinistries();
              foreach ($ministry as $row):
              ?>
                <option value="<?= $row['ministry_id']; ?>"><?= $row['ministry_name']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Discipler Name -->
          <div class="space-y-2">
            <label for="discipler" class="text-sm font-medium text-gray-700 dark:text-gray-300">Discipler name</label>
            <select
              id="discipler"
              name="discipler"
              class="flex h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-gray-900 dark:text-gray-100">
              <option value="<?= $details['lincleaderId']; ?>" selected><?= $details['lincleader']; ?></option>
              <option value="0">NA</option>
              <?php
              $discipler = $memberDetails->selectDiscipler(2);
              foreach ($discipler as $row):
              ?>
                <option value="<?= $row['member_id']; ?>"><?= $row['first_name'] . ' ' . $row['last_name']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- LinC Leader -->
          <div class="space-y-2">
            <label for="lincleader" class="text-sm font-medium text-gray-700 dark:text-gray-300">LinC Leader ?</label>
            <select
              id="lincleader"
              name="lincleader"
              class="flex h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-gray-900 dark:text-gray-100">
              <option value="0" <?php echo ($details['Linc_leader'] == 0) ? 'selected' : ''; ?>>Linc Member</option>
              <option value="1" <?php echo ($details['Linc_leader'] == 1) ? 'selected' : ''; ?>>Linc Leader</option>
            </select>
          </div>

          <!-- Commitment Date -->
          <div class="space-y-2">
            <label for="commitment" class="text-sm font-medium text-gray-700 dark:text-gray-300">Commitment date</label>
            <input
              type="date"
              id="commitment"
              name="commitment"
              value="<?= htmlspecialchars($details['commitment_date']) ?>"
              class="flex h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-gray-900 dark:text-gray-100" />
          </div>

          <!-- Age Group -->
          <div class="space-y-2">
            <label for="group" class="text-sm font-medium text-gray-700 dark:text-gray-300">Age group</label>
            <select
              id="group"
              name="group"
              class="flex h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-gray-900 dark:text-gray-100">
              <option value="<?= $details['group_category']; ?>" selected><?= $details['group_category']; ?></option>
              <option value="Youth">Youth</option>
              <option value="Teens">Teens</option>
              <option value="Children">Children</option>
            </select>
          </div>

          <!-- Member Role -->
          <div class="space-y-2">
            <label for="role" class="text-sm font-medium text-gray-700 dark:text-gray-300">Member role</label>
            <select
              id="role"
              name="role"
              class="flex h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-gray-900 dark:text-gray-100">
              <option value="<?= $details['role_id']; ?>" selected><?= $details['role_name']; ?></option>
              <option value="3" selected>Member</option>
              <option value="2">Sub-Admin</option>
              <option value="1">Admin</option>
            </select>
          </div>

          <!-- Sunday School Teacher -->
          <div class="space-y-2">
            <label for="teacher" class="text-sm font-medium text-gray-700 dark:text-gray-300">Sunday school teacher</label>
            <select
              id="teacher"
              name="teacher"
              class="flex h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-gray-900 dark:text-gray-100">
              <option value="<?= $details['teacher_id']; ?>" selected><?= $details['name']; ?></option>
              <?php
              $teacher = $memberDetails->selectTeacher(2);
              foreach ($teacher as $row):
              ?>
                <option value="<?= $row['teacher_id']; ?>"><?= $row['name']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>

      <!-- Milestone Section -->
      <div>
        <div class="flex justify-between items-center mb-4">
          <div class="flex items-center">
            <i data-lucide="award" class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400"></i>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Milestone</h3>
          </div>
          <button
            data-modal-target="add-milestone"
            data-modal-toggle="add-milestone"
            class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white"
            type="button">
            <i data-lucide="file-plus-2" class="w-4 h-4 mr-2"></i>
            Add Milestone
          </button>
        </div>

        <?php require_once __DIR__ . '/./components/milestone-card.php' ?>
      </div>

      <!-- Submit Button -->
      <div class="sticky bottom-0 left-0 bg-white dark:bg-gray-900 dark:border-gray-700 py-4 flex justify-end">
        <button
          type="submit"
          form="updateMember"
          class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-yellow-600 hover:bg-yellow-700 text-white h-10 px-4 py-2 shadow-sm transition">
          <i data-lucide="upload" class="w-4 h-4 mr-2"></i>
          Update Member
        </button>
      </div>
    </div>
  </div>
  </form>

  <?php require_once __DIR__ . '/./modal/add-milestone-modal.php'; ?>
</main>

<script>
  function previewImage(event) {
    const preview = document.getElementById('preview');
    preview.src = URL.createObjectURL(event.target.files[0]);
  }

  function previewCover(event) {
    const preview = document.getElementById('coverPreview');
    const file = event.target.files[0];

    if (file) {
      const reader = new FileReader();

      reader.onload = function(e) {
        preview.src = e.target.result;
      }

      reader.readAsDataURL(file);

      // Auto-submit the cover photo form
      document.getElementById('uploadCover').submit();
    }
  }

  <?php
  require_once __DIR__ . '/../../assets/js/util.js';
  require_once __DIR__ . '/./js/script.js';
  ?>
</script>