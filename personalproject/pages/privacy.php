<?php
session_start();
include(__DIR__ . '/../config/db.php');
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../includes/navbar.php');

$siteName = 'Audiverse';
$basePath = '/php-1/personalproject';
$lastUpdated = date('F j, Y');
?>

<div class="container" style="padding:30px 20px; line-height:1.6;">
    <h1>Privacy Policy</h1>
    <p><em>Last updated: <?php echo $lastUpdated; ?></em></p>

    <p><?php echo htmlspecialchars($siteName); ?> (“we”, “our”, or “us”) values your privacy. This Privacy Policy explains how we collect, use, and protect your personal information when you use our website or services.</p>

    <h2>1. Information We Collect</h2>
    <p>We collect information that you voluntarily provide when you register, make a purchase, or contact us. This may include:</p>
    <ul>
        <li>Account information (name, email, username, password)</li>
        <li>Order information (billing/shipping address, phone number)</li>
        <li>Payment information (processed securely through third-party providers)</li>
        <li>Reviews, comments, and communication data</li>
    </ul>

    <h2>2. How We Use Your Information</h2>
    <p>We use your data to:</p>
    <ul>
        <li>Process and deliver your orders</li>
        <li>Provide customer support</li>
        <li>Improve our products, services, and website experience</li>
        <li>Send account updates, order notifications, and promotional content (if you’ve opted in)</li>
    </ul>

    <h2>3. Cookies</h2>
    <p>Our website uses cookies to enhance user experience and analyze site traffic. You may disable cookies in your browser settings, but some parts of the website may not function properly.</p>

    <h2>4. Data Storage and Security</h2>
    <p>We use secure servers and encryption to protect your personal information. While no online system is 100% secure, we strive to safeguard your data using industry-standard security measures.</p>

    <h2>5. Sharing of Information</h2>
    <p>We do not sell or rent your personal information. We may share it only with:</p>
    <ul>
        <li>Payment processors (for secure transactions)</li>
        <li>Delivery services (for order fulfillment)</li>
        <li>Law enforcement if required by applicable law</li>
    </ul>

    <h2>6. Your Rights</h2>
    <p>You have the right to access, correct, or delete your personal information at any time by logging into your account or contacting us directly. You may also request that we stop sending promotional emails.</p>

    <h2>7. Third-Party Links</h2>
    <p>Our website may include links to other sites. We are not responsible for the privacy practices or content of these external websites.</p>

    <h2>8. Data Retention</h2>
    <p>We retain your data as long as your account is active or as needed to provide services. You can request deletion of your account and data by contacting us.</p>

    <h2>9. Children’s Privacy</h2>
    <p>We do not knowingly collect data from individuals under the age of 13. If we become aware of such collection, we will promptly delete the data.</p>

    <h2>10. Updates to This Policy</h2>
    <p>We may update this Privacy Policy occasionally. Any changes will be posted on this page with an updated “Last updated” date.</p>

    <h2>11. Contact Us</h2>
    <p>If you have any questions or concerns regarding this Privacy Policy, please contact us at:  
        <a href="<?php echo $basePath; ?>/pages/contact.php">Contact Page</a>
    </p>

    <hr style="margin:20px 0; border:none; border-top:1px solid #eee;">

    <p style="font-size:0.9em; color:#666;">By using <?php echo htmlspecialchars($siteName); ?>, you consent to this Privacy Policy.</p>
</div>

<?php
include(__DIR__ . '/../includes/footer.php');
?>
