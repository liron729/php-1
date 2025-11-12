<?php
// pages/terms_of_service.php
session_start();
include(__DIR__ . '/../config/db.php');       // optional, in case header or footer needs DB
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../includes/navbar.php');

$siteName = 'Audiverse';
$basePath = '/php-1/personalproject';
$lastUpdated = date('F j, Y');
?>

<div class="container" style="padding:30px 20px; line-height:1.6;">
    <h1>Terms of Service</h1>
    <p><em>Last updated: <?php echo $lastUpdated; ?></em></p>

    <p>Welcome to <?php echo htmlspecialchars($siteName); ?>. These Terms of Service ("Terms") govern your access to and use of our website and services. By accessing or using our site, you agree to be bound by these Terms.</p>

    <h2>1. Acceptance of Terms</h2>
    <p>By using this website you accept and agree to be bound by these Terms, our Privacy Policy, and any other policies posted on the site. If you do not agree, do not use the site.</p>

    <h2>2. Eligibility</h2>
    <p>You must be at least 13 years old to use this site. If you are under 18, you must have consent from a parent or guardian to use the website and to make purchases.</p>

    <h2>3. Accounts</h2>
    <p>To access certain features (including purchasing, leaving reviews, and managing wishlists), you must create an account. You are responsible for maintaining the confidentiality of your account credentials and for all activity under your account. Notify us immediately if you suspect unauthorized use.</p>

    <h2>4. Purchases & Orders</h2>
    <p>All orders are subject to acceptance and availability. Prices, promotions, and availability may change without notice. We will confirm accepted orders by email or through your account order history. Payment information is processed securely; see our Privacy Policy for details.</p>

    <h2>5. Shipping, Returns & Refunds</h2>
    <p>Shipping policies, return windows, and refund eligibility are described on our <a href="<?php echo $basePath; ?>/pages/returns.php">Returns & Refunds</a> page (if provided). If no policy is available, contact us at <a href="<?php echo $basePath; ?>/pages/contact.php">Contact</a> and we will help resolve the issue.</p>

    <h2>6. Reviews & User Content</h2>
    <p>By submitting reviews, comments, images, or other content ("User Content") you grant <?php echo htmlspecialchars($siteName); ?> a non-exclusive, royalty-free, worldwide license to use, display, and adapt the content on the site. You represent that you own or are allowed to post the content and that it does not violate any laws or third-party rights. We may remove any User Content that violates these Terms.</p>

    <h2>7. Prohibited Conduct</h2>
    <p>When using the site you must not: (a) post unlawful or offensive content, (b) attempt to access other users' accounts, (c) interfere with the operation of the site, or (d) use the site for unlawful purposes. Violations may result in account suspension or termination.</p>

    <h2>8. Intellectual Property</h2>
    <p>All site content, design, logos, and images are the property of <?php echo htmlspecialchars($siteName); ?> or licensed to us. You may not reproduce, distribute, or create derivative works from the content without our written permission.</p>

    <h2>9. Disclaimers</h2>
    <p>The site is provided "as is" and "as available" without warranties of any kind. While we strive to ensure information is accurate, we do not guarantee that product descriptions, prices, or other content are error-free.</p>

    <h2>10. Limitation of Liability</h2>
    <p>To the maximum extent permitted by law, <?php echo htmlspecialchars($siteName); ?> and its affiliates will not be liable for indirect, incidental, special, or consequential damages arising from your use of the site. Our total liability for direct damages is limited to the purchase price of the product or service at issue.</p>

    <h2>11. Indemnification</h2>
    <p>You agree to indemnify and hold harmless <?php echo htmlspecialchars($siteName); ?> and its officers, employees, and partners from any claims, losses, liabilities, and expenses arising out of your breach of these Terms or your violation of any law or the rights of a third party.</p>

    <h2>12. Changes to Terms</h2>
    <p>We may update these Terms occasionally. When we do, we will update the "Last updated" date above. Continued use of the site after changes indicates acceptance of the updated Terms.</p>

    <h2>13. Governing Law</h2>
    <p>These Terms are governed by the laws of the jurisdiction where the site operator is located. Any disputes will be resolved in local courts, unless otherwise required by law.</p>

    <h2>14. Contact</h2>
    <p>If you have questions or concerns about these Terms, please contact us at <a href="<?php echo $basePath; ?>/pages/contact.php">Contact</a>.</p>

    <hr style="margin:20px 0; border:none; border-top:1px solid #eee;">

    <p style="font-size:0.9em; color:#666;">By using <?php echo htmlspecialchars($siteName); ?> you agree to these Terms of Service.</p>
</div>

<?php
include(__DIR__ . '/../includes/footer.php');
?>
