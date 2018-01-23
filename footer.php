<div class="outer-wrapper">
 <section class="padding-5000 padding-0050 footer-section">
  <div class="wrapper">
    <div class="footer-block">
		<img src="models/site-templates/images/footer-logo.png">
    	<p>onsectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et donsectetur .</p>
		<div class="social-icon">
            <ul>
            <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
            <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
            <li><a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
            </ul>
          </div>
    </div>
    <div class="footer-block">
		<h4 class="footer-heading">Interesting Stuff</h4>
			<ul class="comm-links">
            <li><a href="affiliates">Affiliates</a></li>
            <li><a href="advertisers">Advertisers</a></li>
            <li><a href="lead-generation">Lead Generation</a></li>
            <li><a href="about-us">About Us</a></li>
            <li><a href="careers">Careers</a></li>
            </ul>
    </div>
    <div class="footer-block">
		<h4 class="footer-heading">Useful Stuff</h4>
			<ul class="comm-links">
            <li><a href="register">Create An Account</a></li>
            <li><a href="login">Sign In To Your Account </a></li>
            <li><a href="contact">Contact Us</a></li>
            </ul>
    </div>
    <div class="footer-block">
		<h4 class="footer-heading">Legal Stuff</h4>
			<ul class="comm-links">
            <li><a href="terms-conditions">Terms of Use</a></li>
            <li><a href="privacy-policy">Privacy Policy </a></li>
            <li><a href="affiliate-terms">Affiliate Terms & Conditions</a></li>
            <li><a href="advertiser-terms">Advertiser Terms & Conditions</a></li>
            </ul>
    </div>
    </div>
    </div>
</section>
</div>
<div class="outer-wrapper">
<section class="copyright">
<div class="wrapper"><p>Copyright &copy; 2011-2017 InvideMedia UK. All rights reserved.</p></div>
</section>
</div>   
<script>
jQuery(document).ready(function (e) {
    function t(t) {
        e(t).bind("click", function (t) {
            t.preventDefault();
            e(this).parent().fadeOut()
        })
    }
    e(".dropdown-toggle").click(function () {
        var t = e(this).parents(".button-dropdown").children(".dropdown-menu").is(":hidden");
        e(".button-dropdown .dropdown-menu").hide();
        e(".button-dropdown .dropdown-toggle").removeClass("active");
        if (t) {
            e(this).parents(".button-dropdown").children(".dropdown-menu").toggle().parents(".button-dropdown").children(".dropdown-toggle").addClass("active")
        }
    });
    e(document).bind("click", function (t) {
        var n = e(t.target);
        if (!n.parents().hasClass("button-dropdown")) e(".button-dropdown .dropdown-menu").hide();
    });
    e(document).bind("click", function (t) {
        var n = e(t.target);
        if (!n.parents().hasClass("button-dropdown")) e(".button-dropdown .dropdown-toggle").removeClass("active");
    })
});
</script>
</body>
</html> 
<?php mysqli_close($mysqli); ?>