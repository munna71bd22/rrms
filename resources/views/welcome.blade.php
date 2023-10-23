<!DOCTYPE html>

<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Jafran -Resuturant Online Reservation System </title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="Developed by Mehedi Hasan Munna,1986138451,munna71bd@gmail.com" name="description">

    <!-- Favicon -->
    <link href="img/favicon.jpg" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="">
    <link href="website/css2" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="website/all.min.css" rel="stylesheet">
    <link href="website/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="website/animate.min.css" rel="stylesheet">
    <link href="website/owl.carousel.min.css" rel="stylesheet">
    <link href="website/tempusdominus-bootstrap-4.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="website/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="website/style.css" rel="stylesheet">
</head>

<body class="" style="">
<div class="container-xxl bg-white p-0">
    <!-- Spinner Start -->
    <div id="spinner"
         class="bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->


    <!-- Navbar & Hero Start -->
    <div class="container-xxl position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 px-lg-5 py-3 py-lg-0" style="">
            <a href="https://themewagon.github.io/Jafran/index.html" class="navbar-brand p-0">
                <h1 class="text-primary m-0"><i class="fa fa-utensils me-3"></i>Jafran</h1>
                <!-- <img src="img/logo.png" alt="Logo"> -->
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0 pe-4">
                    <a href="#" class="nav-item nav-link active">Home</a>
                    <a href="#about-us" class="nav-item nav-link">About</a>
                    <a href="#service" class="nav-item nav-link">Service</a>
                    <a href="#menu" class="nav-item nav-link">Menu</a>
                    <a href="#contact" class="nav-item nav-link">Contact</a>
                </div>
                <a href="#book-table" class="btn btn-primary py-2 px-4">Book A Table</a>

                @if(Auth::user())
                    <a href="home" class="btn btn py-2 px-2">{{Auth::user()->name}}</a>
                @else
                    <a href="login" class="btn btn-primary py-2 px-4">Sign In</a>
                @endif
            </div>
        </nav>

        <div class="container-xxl py-5 bg-dark hero-header mb-5">
            <div class="container my-5 py-5">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6 text-center text-lg-start">
                        <h1 class="display-3 text-white animated slideInLeft">Enjoy Our<br>Delicious Meal</h1>
                        <p class="text-white animated slideInLeft mb-4 pb-2">Tempor erat elitr rebum at clita. Diam
                            dolor diam ipsum sit. Aliqu diam amet diam et eos. Clita erat ipsum et lorem et sit, sed
                            stet lorem sit clita duo justo magna dolore erat amet</p>
                        <a href="#book-table" class="btn btn-primary py-sm-3 px-sm-5 me-3 animated slideInLeft">Book A
                            Table</a>
                    </div>
                    <div class="col-lg-6 text-center text-lg-end overflow-hidden">
                        <img class="img-fluid" src="website/hero.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Navbar & Hero End -->


    <!-- Service Start -->
    <div id="service" class="container-xxl py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s"
                     style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInUp;">
                    <div class="service-item rounded pt-3">
                        <div class="p-4">
                            <i class="fa fa-3x fa-user-tie text-primary mb-4"></i>
                            <h5>Master Chefs</h5>
                            <p>Diam elitr kasd sed at elitr sed ipsum justo dolor sed clita amet diam</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.3s"
                     style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInUp;">
                    <div class="service-item rounded pt-3">
                        <div class="p-4">
                            <i class="fa fa-3x fa-utensils text-primary mb-4"></i>
                            <h5>Quality Food</h5>
                            <p>Diam elitr kasd sed at elitr sed ipsum justo dolor sed clita amet diam</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.5s"
                     style="visibility: visible; animation-delay: 0.5s; animation-name: fadeInUp;">
                    <div class="service-item rounded pt-3">
                        <div class="p-4">
                            <i class="fa fa-3x fa-cart-plus text-primary mb-4"></i>
                            <h5>Online Order</h5>
                            <p>Diam elitr kasd sed at elitr sed ipsum justo dolor sed clita amet diam</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.7s"
                     style="visibility: visible; animation-delay: 0.7s; animation-name: fadeInUp;">
                    <div class="service-item rounded pt-3">
                        <div class="p-4">
                            <i class="fa fa-3x fa-headset text-primary mb-4"></i>
                            <h5>24/7 Service</h5>
                            <p>Diam elitr kasd sed at elitr sed ipsum justo dolor sed clita amet diam</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Service End -->


    <!-- About Start -->
    <div id="about-us" class="container-xxl py-5">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <div class="row g-3">
                        <div class="col-6 text-start">
                            <img class="img-fluid rounded w-100 wow zoomIn" data-wow-delay="0.1s"
                                 src="website/about-1.jpg"
                                 style="visibility: visible; animation-delay: 0.1s; animation-name: zoomIn;">
                        </div>
                        <div class="col-6 text-start">
                            <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.3s"
                                 src="website/about-2.jpg"
                                 style="margin-top: 25%; visibility: visible; animation-delay: 0.3s; animation-name: zoomIn;">
                        </div>
                        <div class="col-6 text-end">
                            <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.5s"
                                 src="website/about-3.jpg"
                                 style="visibility: visible; animation-delay: 0.5s; animation-name: zoomIn;">
                        </div>
                        <div class="col-6 text-end">
                            <img class="img-fluid rounded w-100 wow zoomIn" data-wow-delay="0.7s"
                                 src="website/about-4.jpg"
                                 style="visibility: visible; animation-delay: 0.7s; animation-name: zoomIn;">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h5 class="section-title ff-secondary text-start text-primary fw-normal">About Us</h5>
                    <h1 class="mb-4">Welcome to <i class="fa fa-utensils text-primary me-2"></i>Jafran</h1>
                    <p class="mb-4">Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam amet diam et
                        eos erat ipsum et lorem et sit, sed stet lorem sit.</p>
                    <p class="mb-4">Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam amet diam et
                        eos. Clita erat ipsum et lorem et sit, sed stet lorem sit clita duo justo magna dolore erat
                        amet</p>
                    <div class="row g-4 mb-4">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center border-start border-5 border-primary px-3">
                                <h1 class="flex-shrink-0 display-5 text-primary mb-0" data-toggle="counter-up">15</h1>
                                <div class="ps-4">
                                    <p class="mb-0">Years of</p>
                                    <h6 class="text-uppercase mb-0">Experience</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center border-start border-5 border-primary px-3">
                                <h1 class="flex-shrink-0 display-5 text-primary mb-0" data-toggle="counter-up">50</h1>
                                <div class="ps-4">
                                    <p class="mb-0">Popular</p>
                                    <h6 class="text-uppercase mb-0">Master Chefs</h6>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- About End -->


        <!-- Menu Start -->
        <div id="menu" class="container-xxl py-5">
            <div class="container">
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s"
                     style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInUp;">
                    <h5 class="section-title ff-secondary text-center text-primary fw-normal">Food Menu</h5>
                    <h1 class="mb-5">Most Popular Items</h1>
                </div>
                <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.1s"
                     style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInUp;">
                    <ul class="nav nav-pills d-inline-flex justify-content-center border-bottom mb-5">
                        <li class="nav-item">
                            <a class="d-flex align-items-center text-start mx-3 ms-0 pb-3 active" data-bs-toggle="pill"
                               href="https://themewagon.github.io/Jafran/index.html#tab-1">
                                <i class="fa fa-coffee fa-2x text-primary"></i>
                                <div class="ps-3">
                                    <small class="text-body">Popular</small>
                                    <h6 class="mt-n1 mb-0">Breakfast</h6>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="d-flex align-items-center text-start mx-3 pb-3" data-bs-toggle="pill"
                               href="https://themewagon.github.io/Jafran/index.html#tab-2">
                                <i class="fa fa-hamburger fa-2x text-primary"></i>
                                <div class="ps-3">
                                    <small class="text-body">Special</small>
                                    <h6 class="mt-n1 mb-0">Launch</h6>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="d-flex align-items-center text-start mx-3 me-0 pb-3" data-bs-toggle="pill"
                               href="https://themewagon.github.io/Jafran/index.html#tab-3">
                                <i class="fa fa-utensils fa-2x text-primary"></i>
                                <div class="ps-3">
                                    <small class="text-body">Lovely</small>
                                    <h6 class="mt-n1 mb-0">Dinner</h6>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane fade show p-0 active">
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <div class="d-flex align-items-center">
                                        <img class="flex-shrink-0 img-fluid rounded" src="website/menu-1.jpg" alt=""
                                             style="width: 80px;">
                                        <div class="w-100 d-flex flex-column text-start ps-4">
                                            <h5 class="d-flex justify-content-between border-bottom pb-2">
                                                <span>Chicken Burger</span>
                                                <span class="text-primary">$115</span>
                                            </h5>
                                            <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo
                                                diam</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="d-flex align-items-center">
                                        <img class="flex-shrink-0 img-fluid rounded" src="website/menu-2.jpg" alt=""
                                             style="width: 80px;">
                                        <div class="w-100 d-flex flex-column text-start ps-4">
                                            <h5 class="d-flex justify-content-between border-bottom pb-2">
                                                <span>Chicken Burger</span>
                                                <span class="text-primary">$115</span>
                                            </h5>
                                            <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo
                                                diam</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="d-flex align-items-center">
                                        <img class="flex-shrink-0 img-fluid rounded" src="website/menu-3.jpg" alt=""
                                             style="width: 80px;">
                                        <div class="w-100 d-flex flex-column text-start ps-4">
                                            <h5 class="d-flex justify-content-between border-bottom pb-2">
                                                <span>Chicken Burger</span>
                                                <span class="text-primary">$115</span>
                                            </h5>
                                            <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo
                                                diam</small>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-lg-6">
                                    <div class="d-flex align-items-center">
                                        <img class="flex-shrink-0 img-fluid rounded" src="website/menu-8.jpg" alt=""
                                             style="width: 80px;">
                                        <div class="w-100 d-flex flex-column text-start ps-4">
                                            <h5 class="d-flex justify-content-between border-bottom pb-2">
                                                <span>Chicken Burger</span>
                                                <span class="text-primary">$115</span>
                                            </h5>
                                            <small class="fst-italic">Ipsum ipsum clita erat amet dolor justo
                                                diam</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Menu End -->


        <!-- Reservation Start -->
        <div id="book-table" class="container-xxl py-5 px-0 wow fadeInUp" data-wow-delay="0.1s"
             style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInUp;">
            <div class="row g-0">
                <div class="col-md-6">
                    <div class="video">
                        <button type="button" class="btn-play" data-bs-toggle="modal"
                                data-src="https://www.youtube.com/embed/DWRcNpR6Kdc" data-bs-target="#videoModal">
                            <span></span>
                        </button>
                    </div>
                </div>
                <div class="col-md-6 bg-dark d-flex align-items-center">
                    <div class="p-5 wow fadeInUp" data-wow-delay="0.2s"
                         style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
                        <h5 class="section-title ff-secondary text-start text-primary fw-normal">Reservation</h5>
                        <h1 class="text-white mb-4">Book A Table Online</h1>
                        <form>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="name" placeholder="Your Name"

                                               @if(Auth::user())
                                                   value="{{Auth::user()->name}}"
                                               readonly
                                            @endif >

                                        <label for="name">Your Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="email" placeholder="Your Email"

                                               @if(Auth::user())
                                                   value="{{Auth::user()->email}}"
                                               readonly
                                            @endif >

                                        <label for="email">Your Email</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating date" id="date3" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="datetime"
                                               placeholder="Date &amp; Time" data-target="#date3"
                                               data-toggle="datetimepicker">
                                        <label for="datetime">Date &amp; Time</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating" id="date3" data-target-input="nearest">
                                        <select class="form-select" id="select1">
                                            <option value="1">1st</option>
                                            <option value="2">2nd</option>
                                            <option value="3">3rd</option>
                                        </select>
                                        <label for="datetime">Floor</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating" id="date3" data-target-input="nearest">
                                        <select class="form-select" id="select1">
                                            <option value="1">111</option>
                                            <option value="2">222</option>
                                            <option value="3">333</option>
                                        </select>
                                        <label for="datetime">Room No</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating" id="date3" data-target-input="nearest">
                                        <select class="form-select" id="select1">
                                            <option value="1">2 seat</option>
                                            <option value="2">4 seats</option>
                                            <option value="3">6 seats</option>
                                            <option value="3">family dining table 12 seats</option>
                                        </select>
                                        <label for="datetime">Table</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="select1">
                                            <option value="1">People 1</option>
                                            <option value="2">People 2</option>
                                            <option value="3">People 3</option>
                                        </select>
                                        <label for="select1">No Of People</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Special Request" id="message"
                                                  style="height: 100px"></textarea>
                                        <label for="message">Special Request</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary w-100 py-3" type="submit">Book Now</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
             style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Youtube Video</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- 16:9 aspect ratio -->
                        <div class="ratio ratio-16x9">
                            <iframe class="embed-responsive-item" src="#" id="video" allowfullscreen=""
                                    allowscriptaccess="always" allow="autoplay"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Reservation Start -->


        <!-- Footer Start -->
        <div id="contact" class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s"
             style="visibility: visible; animation-delay: 0.1s; animation-name: fadeIn;">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-4 col-md-6">
                        <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Company</h4>
                        <a class="btn btn-link" href="#">About Us</a>
                        <a class="btn btn-link" href="#">Contact Us</a>
                        <a class="btn btn-link" href="#">Reservation</a>
                        <a class="btn btn-link" href="#">Privacy Policy</a>
                        <a class="btn btn-link" href="#">Terms &amp; Condition</a>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Contact</h4>
                        <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>123 Street, New York, USA</p>
                        <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                        <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@example.com</p>
                        <div class="d-flex pt-2">
                            <a class="btn btn-outline-light btn-social" href="#"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-outline-light btn-social" href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-light btn-social" href="#"><i class="fab fa-youtube"></i></a>
                            <a class="btn btn-outline-light btn-social" href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Opening</h4>
                        <h5 class="text-light fw-normal">Monday - Saturday</h5>
                        <p>09AM - 09PM</p>
                        <h5 class="text-light fw-normal">Sunday</h5>
                        <p>10AM - 08PM</p>
                    </div>

                </div>
            </div>
            <div class="container">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            Jafran © {{date('Y')}}

                            Designed By <a target="_blank" class="border-bottom"
                                           href="https://linkedin.com/in/munna1994">Mehedi Hasan Munna</a>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top" style="display: none;"><i
                class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="website/jquery-3.4.1.min.js.download"></script>
    <script src="website/bootstrap.bundle.min.js.download"></script>
    <script src="website/wow.min.js.download"></script>
    <script src="website/easing.min.js.download"></script>
    <script src="website/waypoints.min.js.download"></script>
    <script src="website/counterup.min.js.download"></script>
    <script src="website/owl.carousel.min.js.download"></script>
    <script src="website/moment.min.js.download"></script>
    <script src="website/moment-timezone.min.js.download"></script>
    <script src="website/tempusdominus-bootstrap-4.min.js.download"></script>

    <!-- Template Javascript -->
    <script src="website/main.js.download"></script>


</body>
</html>
