<?php

session_start();

//Redirect user if already logged in
include 'includes/redirectUserIfLoggedIn.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nortaur</title>

  <link rel="icon" type="image/x-icon" href="images/logo.png">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

  <link rel="stylesheet" href="style.css" />

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.9.0/cdn/themes/light.css" />

</head>

<body>

  <nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img alt='home' width="50px" src="images/logo.png">
        Nortaur
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">

          </li>
        </ul>
        <span class="navbar-text">
          <a href="login" class="btn">Log in</a>
          <a href="signup/" class="btn startBtn">Start for free</a>
        </span>
      </div>
    </div>
  </nav>

  <header class="hero w-100">
    <!-- Background Image -->
    <div class="hero-bg lazy-load pt-5">

      <!-- Content -->
      <div class="text-center py-5 text-white">
        <h1>Stay Organized. Achieve More.</h1>
        <a href="signup" class="btn btn-lg btn-info">Start for free</a>
      </div>
    </div>
  </header>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var heroBg = document.querySelector(".hero-bg");
      var bgImage = new Image();
      bgImage.src = "https://images.unsplash.com/photo-1586892477838-2b96e85e0f96?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1759&q=80";

      bgImage.onload = function() {
        heroBg.style.backgroundImage = `url(${bgImage.src})`;
      };
    });
  </script>

  <sl-animation name="fadeInLeft" easing="ease-in-out" duration="1000" play iterations="1">
  </sl-animation>

  <section id="features" class="py-5 d-flex justify-content-center align-items-center">
    <div class="container">
      <sl-animation name="fadeInLeft" easing="ease-in-out" duration="1000" play iterations="1">
        <h2>Features</h2>
      </sl-animation>
      <div class="row">
        <div class="col-md-4">
          <sl-animation name="fadeInLeft" easing="ease-in-out" duration="1000" play iterations="1">
            <div class="card mb-4 h-100 shadow">
              <img src="https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1769&q=80" class=" card-img-top" alt="Feature 1">
              <div class="card-body">
                <h4 class="card-title">Smart Task Organization</h4>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item featureDescription">Collaborate seamlessly with colleagues, friends, and family members on shared projects.</li>
                  <li class="list-group-item featureDescription">Share tasks, assign responsibilities, and work together towards common objectives.</li>
                  <li class="list-group-item featureDescription">Boost productivity by sharing the workload and staying in sync with your team.</li>
                </ul>
              </div>
            </div>
          </sl-animation>
        </div>

        <div class="col-md-4">
          <sl-animation name="fadeInUp" easing="ease-in-out" duration="1000" play iterations="1">
            <div class="card mb-4 h-100 shadow">
              <img src="https://images.unsplash.com/photo-1520110120835-c96534a4c984?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1767&q=80" class=" card-img-top" alt="Feature 1">
              <div class="card-body">
                <h4 class="card-title">Collaborative Task Sharing</h4>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item featureDescription">Tailor your task lists to suit your unique needs and workflow.</li>
                  <li class="list-group-item featureDescription">Stay on top of your to-do list with automated task sorting and categorization.</li>
                  <li class="list-group-item featureDescription">Effortlessly manage your tasks with our smart organization system.</li>
                </ul>
              </div>
            </div>
          </sl-animation>
        </div>

        <div class="col-md-4">
          <sl-animation name="fadeInRight" easing="ease-in-out" duration="1000" play iterations="1">
            <div class="card mb-4 h-100 shadow">
              <img src="https://images.unsplash.com/photo-1496171367470-9ed9a91ea931?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1770&q=80" class=" card-img-top" alt="Feature 1">
              <div class="card-body">
                <h4 class="card-title">Cross-Platform Synchronization</h4>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item featureDescription">Access your tasks and to-do lists from anywhere, on any device.</li>
                  <li class="list-group-item featureDescription">Stay synced across desktop, mobile, and tablet for seamless productivity.</li>
                  <li class="list-group-item featureDescription">Work on tasks at home, in the office, or on the go without missing a beat.</li>
                </ul>
              </div>
            </div>
          </sl-animation>
        </div>

      </div>
    </div>
  </section>

  <sl-animation name="fadeInLeft" easing="ease-in-out" duration="1000" play iterations="1">
    <!-- Newsletter Signup Section -->
    <section id="newsletter" class="py-5 d-flex justify-content-center align-items-center bg-white">
      <div class="container">
        <h2>Subscribe to Our Newsletter</h2>
        <form action="subscribe.php" method="POST" class="newsletter-form">
          <div class="input-group">
            <input type="email" class="form-control rounded-start" name="email" placeholder="Your email" required>
            <button type="submit" class="btn btn-primary rounded-end">Subscribe</button>
          </div>
        </form>
      </div>
    </section>
  </sl-animation>

  <sl-animation name="fadeInLeft" easing="ease-in-out" duration="1000" play iterations="1">
    <section id="contact" class="py-5 d-flex justify-content-center align-items-center">
      <div class="container">
        <h2>Contact Us</h2>
        <p class="lead">hajdu.norbert97@gmail.com</p>
      </div>
    </section>
  </sl-animation>

  

  <footer class="bg-black text-center py-5">
    <div class="px-5">
      <div class="text-white-50 small">
        <div class="mb-2">© Nortaur 2023. All Rights Reserved.</div>
        <a href="privacy">Privacy</a>
        <span class="mx-1">·</span>
        <a href="terms">Terms</a>
      </div>
    </div>
  </footer>

  <script type="module" src="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.9.0/cdn/shoelace-autoloader.js"></script>

  <script src="https://kit.fontawesome.com/52bacc16ae.js" crossorigin="anonymous"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script src='core.js'></script>

</body>

</html>