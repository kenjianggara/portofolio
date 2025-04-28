<!DOCTYPE html>
<html lang="en">
  <head>
    <title>About Me</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    
    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="./img/favicon.ico">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <!-- Navigation bar -->
    <?php include('./partials/header.php');?>

    <!-- About section -->
    <section class="about-section mt-5">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-6 order-lg-2">
                    <div class="about-text">
                        <h2>About Me</h2>
                        <p>I am a fresh graduate of Cyber Security major and I am passionate about CTFs and learning pentesting and bug bounty. Currently, I am an intern at CILSY as a security engineer.</p>
                    </div>
                </div>
                <div class="col-lg-6 order-lg-1">
                    <div class="about-img">
                        <img src="img/kenji_profile_picture_teen_male_anime_character_realistic_gamer_301e5790-171b-41b3-b945-754f0e0729c3.png" alt="My Photo">
                    </div>
                </div>
            </div>
            <br>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="about-desc">
                        <h2>What's Inside My Web</h2>
                        <p>This website is a platform for me to keep track of my CTF skills. The links on the homepage are CTF and lab platforms that I have tried before and might use in the future. In the future, I plan to add another page to showcase my CTF and lab writeups.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
      

    <!-- Footer -->
    <?php include('./partials/footer.php');?>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>

  </body>
</html>