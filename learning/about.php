<?php
// --- SETUP ---
$page_title = 'About Us';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Section 1: Page Header -->
<section class="page-title-section" style="background-image: linear-gradient(rgba(0, 95, 115, 0.7), rgba(0, 95, 115, 0.7)), url('https://images.unsplash.com/photo-1557426272-fc759fdf7a8d?q=80&w=2070&auto=format&fit=crop');">
    <div class="container">
        <h1>About DADE Foundation</h1>
        <p class="tagline">Driving global inclusion for persons with disabilities through digital solutions, policy change, and empowerment.</p>
    </div>
</section>

<!-- Section 2: Our Mission & Vision -->
<div class="container">
    <div class="about-grid">
        <!-- THIS IS THE FIX: The "Our Vision" content is now wrapped in the about-card div -->
        <div class="about-card">
            <h3>Our Vision</h3>
            <p>A world where inclusive digital ecosystems and practices empower persons with disabilities to learn, work, lead, and drive equitable change globally.</p>
        </div>
        <div class="about-card">
            <h3>Our Mission</h3>
            <p>To enable economic independence and equal rights for persons with disabilities through technology, advocacy, and building inclusive communities.</p>
        </div>
    </div>
</div>

<!-- Section 3: What We Do -->
<section class="what-we-do-section">
    <div class="container">
        <h2 class="section-title">What We Promote and Do</h2>
        <p class="section-subtitle">Our work is centered around creating tangible, systemic change across Africa and beyond.</p>
        
        <div class="features-grid">
            <div class="feature-card"><h3><span class="icon">🏛️</span> Disability Rights Action</h3><p>We actively champion the rights of persons with disabilities through targeted campaigns and advocacy, driving policy changes.</p></div>
            <div class="feature-card"><h3><span class="icon">🎓</span> Inclusive Education</h3><p>We are dedicated to breaking down barriers, ensuring all persons with disabilities have access to quality learning opportunities.</p></div>
            <div class="feature-card"><h3><span class="icon">💻</span> Inclusive Technology</h3><p>We develop and promote accessible technology solutions that empower PWDs to connect, learn, and work.</p></div>
            <div class="feature-card"><h3><span class="icon">🤝</span> Mentorship Programs</h3><p>We connect persons with disabilities with experienced guides, fostering personal and professional growth.</p></div>
            <div class="feature-card"><h3><span class="icon">🔍</span> Research & Data</h3><p>We conduct vital research on the needs of PWDs to provide an evidence base for effective programs and policy.</p></div>
            <div class="feature-card"><h3><span class="icon">🌐</span> Global Partnerships</h3><p>We expand our reach and impact by sharing best practices and collaborating to advance disability inclusion globally.</p></div>
        </div>
    </div>
</section>

<!-- Section 4: Leadership -->
<div class="container">
    <h2 class="section-title">Guided by Leadership</h2>
    <p class="section-subtitle">Our foundation is led by a team that combines lived experience with deep expertise in inclusion and technology.</p>
    
    <div class="leadership-grid">
        <div class="leadership-card">
            <img src="<?php echo $base; ?>/assets/images/Awashima.jpg" alt="Photo of Ms. Awashima Atu" class="profile-picture">
            <h4>Ms. Awashima Atu</h4>
            <p class="title">Co-founder</p>
            <p class="bio">A person with a disability who understands firsthand the systemic barriers faced by marginalized communities.</p>
        </div>
        <div class="leadership-card">
            <img src="<?php echo $base; ?>/assets/images/Emmanuel.jpg" alt="Photo of Dr. Odumusi Emmanuel" class="profile-picture">
            <h4>Dr. Odumusi Emmanuel</h4>
            <p class="title">Co-founder & Global Inclusion Advocate</p>
            <p class="bio">An expert in global inclusion strategies and evidence-based policy and advocacy efforts.</p>
        </div>
        <div class="leadership-card">
            <img src="<?php echo $base; ?>/assets/images/Aaron.jpg" alt="Photo of Mr. Aaron Adeola" class="profile-picture">
            <h4>Mr. Aaron Adeola</h4>
            <p class="title">Co-founder & Digital Inclusion Strategist</p>
            <p class="bio">A specialist in harnessing technology and innovation to reach underserved communities.</p>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>