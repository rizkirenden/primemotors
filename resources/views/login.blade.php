<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Link ke CDN Tailwind CSS -->
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Function to toggle password visibility
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var toggleIcon = document.getElementById("toggleIcon");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.src = "https://img.icons8.com/ios/452/visible.png"; // Change icon to 'visible'
            } else {
                passwordField.type = "password";
                toggleIcon.src = "https://img.icons8.com/ios/452/invisible.png"; // Change icon to 'invisible'
            }
        }
    </script>
</head>

<body class="bg-black h-screen"> <!-- Mengatur tinggi halaman menjadi full screen -->
    <div class="flex items-center justify-between w-full h-full"> <!-- Flex untuk membuat kedua div bersebelahan -->
        <!-- Menampilkan gambar dengan path yang benar -->
        <div class="bg-white w-1/2 h-full rounded-md flex justify-center items-center">
            <img src="images/silver.PNG" alt="PremierMotors" class="w-80 h-80">
        </div>
        <!-- Menambahkan div untuk teks WELCOME BACK di sebelah gambar -->
        <div class="flex flex-col justify-center w-1/2 h-full items-center">
            <strong class="text-white text-4xl mb-9">WELCOME BACK</strong>
            <p class="text-white">Sederhanakan alur kerja Anda dan tingkatkan</p>
            <p class="text-white">produktivitas Anda dengan Prime Motors</p>

            <!-- Form Input Username dan Password -->
            <div class="mt-12 w-3/4 flex flex-col justify-center items-center">
                <!-- Input Username -->
                <input type="text" placeholder="Username"
                    class="w-3/4 p-3 mb-6 bg-black text-white border border-white rounded-full focus:outline-none focus:ring-2 focus:ring-white">

                <!-- Input Password -->
                <input type="password" id="password" placeholder="Password"
                    class="w-3/4 p-3 mb-6 bg-black text-white border border-white rounded-full focus:outline-none focus:ring-2 focus:ring-white">

                <!-- Checkbox dan Label Show Password -->
                <div class="mt-1 w-3/4 flex items-center rounded-full">
                    <input type="checkbox" id="showPassword" class="mr-2" onclick="togglePassword()">
                    <label for="showPassword" class="text-white text-sm">Show Password</label>
                </div>

                <!-- Button Login -->
                <button class="w-3/4 p-3 mt-6 mb-12 bg-white text-black rounded-full ">Login</button>

                <strong class="text-white mt-12">PREMIER MOTORS</strong>
            </div>

        </div>
    </div>
</body>

</html>
