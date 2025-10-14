<!DOCTYPE html>
<html lang="en">

<head>
    <title>Contact Us</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="./img/favicon.ico">

    <!-- bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- Navigation Bar -->
    <!-- Navbar Container -->
    <?php include('./partials/header.php'); ?>

    <!-- Contact Us Section -->
    <section class="contact-us mt-5">
        <div class="container">
            <h1 class="text-center">Contact Us</h1>
            <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap mb-3">
                <span>📧 kenjianggara@linuxmail.com</span>
                <span class="px-2">|</span>
                <span>📱 +62 812-3456-7890</span>
            </div>

            <form id="contactForm" method="POST" action="./send_email.php">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                        title="Format email tidak sesuai.">
                </div>
                <div class="form-group">
                    <label for="message">Message:</label>
                    <textarea class="form-control" id="message" name="message" rows="5"
                        placeholder="Input your message here"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            <!-- pesan berhasil terkirim-->
            <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
                <div id="statusMessage" class="text-center my-3">
                    <span class="rounded px-3 py-2 alert-success" style="font-size: 1rem;">
                        ✅ Terima kasih sudah menghubungi kami!
                    </span>
                </div>
            <?php endif; ?>
            <!-- pesan email tidak valid-->
            <?php if (isset($_GET['status']) && $_GET['status'] === 'invalid'): ?>
            <script>alert("Email tidak valid! Format harus nama@domain.com");</script>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <?php include('./partials/footer.php'); ?>

    <!--Bootstrap, JS and JQuery-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>


    <script>
        window.history.replaceState({}, document.title, "contact.php");

        $(document).ready(function() {
            const statusEl = $('#statusMessage');
            if (statusEl.length) {
                statusEl.hide().fadeIn(600); // Tampilkan pesan dengan animasi
                setTimeout(() => {
                    statusEl.fadeOut(600); // Sembunyikan pesan setelah 3 detik
                }, 3000);
            }
        });
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            const emailInput = document.getElementById('email').value;
            const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            if (!regex.test(emailInput)) {
                e.preventDefault(); // cegah form terkirim
                alert("Email tidak valid! Format harus nama@domain.com");
            }
        });
    </script>

</body>

</html>