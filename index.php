<?php
// include head start here
require_once 'includes/head.php';
?>

<?php
// $uri = $_SERVER['REQUEST_URI'];
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

?>


<!-- hero slider -->
<section class="hero-section overlay bg-cover" data-background="assets/images/banner/pg_slid1.jpg">
    <div class="container">
        <div class="hero-slider">
            <!-- slider item -->
            <div class="hero-slider-item">
                <div class="row">
                    <div class="col-md-8">
                        <h1 class="text-white" data-animation-out="fadeOutRight" data-delay-out="5"
                            data-duration-in=".3" data-animation-in="fadeInLeft" data-delay-in=".1">
                            Welcome to PG
                            School, FUO.</h1>
                        <p class="text-muted mb-4" data-animation-out="fadeOutRight" data-delay-out="5"
                            data-duration-in=".3" data-animation-in="fadeInLeft" data-delay-in=".4">
                            You are welcomed to the website of the School of Postgraduate Studies, Fountain University,
                            Osogbo.
                            The postgraduate School was established in the year 2017 to advance the frontiers of
                            knowledge
                            beyond the basic provisions of the programmes at undergraduate level.</p>
                        <a href="admissionportal/registration" class="btn btn-primary" data-animation-out="fadeOutRight"
                            data-delay-out="5" data-duration-in=".3" data-animation-in="fadeInLeft"
                            data-delay-in=".7">Apply now</a>
                    </div>
                </div>
            </div>
            <!-- slider item -->
            <div class="hero-slider-item">
                <div class="row">
                    <div class="col-md-8">
                        <h1 class="text-white" data-animation-out="fadeOutUp" data-delay-out="5" data-duration-in=".3"
                            data-animation-in="fadeInDown" data-delay-in=".1">Your bright future is our mission</h1>
                        <p class="text-muted mb-4" data-animation-out="fadeOutUp" data-delay-out="5"
                            data-duration-in=".3" data-animation-in="fadeInDown" data-delay-in=".4">
                            Our Mission is to provide Students with sound academic training using cutting-edge
                            technologies
                            ranging from physical to virtual (high-breed) handled by world-class Academics
                            with ICT as tools.
                        </p>
                        <a href="admissionportal/registration" class="btn btn-primary" data-animation-out="fadeOutUp"
                            data-delay-out="5" data-duration-in=".3" data-animation-in="fadeInDown"
                            data-delay-in=".7">Apply now</a>
                    </div>
                </div>
            </div>
            <!-- slider item -->
            <div class="hero-slider-item">
                <div class="row">
                    <div class="col-md-8">
                        <h1 class="text-white" data-animation-out="fadeOutDown" data-delay-out="5" data-duration-in=".3"
                            data-animation-in="fadeInUp" data-delay-in=".1"> We Are Committed to Giving You the Best
                        </h1>
                        <p class="text-muted mb-4" data-animation-out="fadeOutDown" data-delay-out="5"
                            data-duration-in=".3" data-animation-in="fadeInUp" data-delay-in=".4">
                            You are encouraged to explore our website to obtain the necessary information about our
                            postgraduate programmes.
                            Taking any of our programmes will make you navigate the ocean of knowledge and become not
                            only employable but
                            also an employer of labour. </p>
                        <a href="admissionportal/registration" class="btn btn-primary" data-animation-out="fadeOutDown"
                            data-delay-out="5" data-duration-in=".3" data-animation-in="zoomIn" data-delay-in=".7">Apply
                            now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /hero slider -->

<!-- banner-feature -->
<section class="bg-gray">
    <div class="container-fluid p-0">
        <div class="row no-gutters">
            <div class="col-xl-4 col-lg-5 align-self-end">
                <img class="img-fluid w-100" src="assets/images/banner/banner-feature.png" alt="banner-feature">
            </div>
            <div class="col-xl-8 col-lg-7">
                <div class="row feature-blocks bg-gray justify-content-between">
                    <div class="col-sm-6 col-xl-5 mb-xl-5 mb-lg-3 mb-4 text-center text-sm-left">
                        <i class="ti-book mb-xl-4 mb-lg-3 mb-4 feature-icon"></i>
                        <h3 class="mb-xl-4 mb-lg-3 mb-4">Masters Programme</h3>
                        <p>
                            <?= $protocol . $_SERVER['HTTP_HOST'] . '/admission_portal' ?>
                            Generally, Masters programmes in Fountain University are of two forms: Academic and
                            Professional Masters; depending on the Department. Applicants are enjoined to confirm from
                            their respective Departments or directly from the Sevretaty of the SPGS about the forms of
                            Masters available before completing their applications.
                        </p>
                    </div>
                    <div class="col-sm-6 col-xl-5 mb-xl-5 mb-lg-3 mb-4 text-center text-sm-left">
                        <i class="ti-blackboard mb-xl-4 mb-lg-3 mb-4 feature-icon"></i>
                        <h3 class="mb-xl-4 mb-lg-3 mb-4"> Post Graduate Diploma (PGD) </h3>
                        <p>
                            Postgraduate Diplomas are usually meant to serve as complementary programmes. It could be a
                            leverage for a pass level grade for a degree in the same field or employed to switch to
                            another programme; having obtained an initial degree in related or cognate degree.
                        </p>
                    </div>
                    <div class="col-sm-6 col-xl-5 mb-xl-5 mb-lg-3 mb-4 text-center text-sm-left">
                        <i class="ti-agenda mb-xl-4 mb-lg-3 mb-4 feature-icon"></i>
                        <h3 class="mb-xl-4 mb-lg-3 mb-4"> Doctor of Philosophy (Ph.D) </h3>
                        <p>
                            Doctor of Philosophy is usually meant for Applicants with Ph.D-proceed or Distinction grade
                            with academic Masters of Fountain University and other reputable University.
                            Basically, this would imply a minimum of three (3) years for the award of Doctor of
                            Philosophy of Fountain University. Applicants with lesser qualification, not lower than M.
                            Phil/Ph.D grade or it's equivalent would spend minimum of four (4) years.
                        </p>
                    </div>
                    <div class="col-sm-6 col-xl-5 mb-xl-5 mb-lg-3 mb-4 text-center text-sm-left">
                        <i class="ti-write mb-xl-4 mb-lg-3 mb-4 feature-icon"></i>
                        <h3 class="mb-xl-4 mb-lg-3 mb-4">Apply Now</h3>
                        <p>
                            Admission now opens for all programmes at Fountain University, Osogbo - School of
                            Postgraduate. Click admission at the top-right side of this page and follow the procedure.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /banner-feature -->

<?php include 'includes/foot.php' ?>