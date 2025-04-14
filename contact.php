<!DOCTYPE html>
<html>

<head>
    <title>Contact Us</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <!-- Navigation Bar -->
    <?php include 'partials/header.php'; ?>

    <section class="contact-us mt-5">
        <div class="container">
            <h1 class="text-center">Contact Us</h1>
            <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap mb-3">
                <span>📧 example@gmail.com</span>
                <span class="px-2">|</span>
                <span>📱 +62 812-3456-7890</span>
            </div>

            <form method="POST" action="./percobaan/send_email.php">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
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

        </div>
    </section>

    <!-- Footer -->
    <?php include 'partials/footer.php'; ?>

    <!--Bootstrap, JS and JQuery-->


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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
    </script>

</body>

</html>