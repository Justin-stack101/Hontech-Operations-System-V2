<?php
/**
 * Global Footer Include
 * Hontech Auto Center Inc.
 */
?>
<!-- ═══════════════════════════════════════
     FOOTER
     ═══════════════════════════════════════ -->
<footer class="footer" style="background: var(--color-dark); color: #ffffff; padding: 70px 0 30px; margin-top: 60px;">
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 40px; margin-bottom: 50px;">
            
            <!-- Col 1: Brand -->
            <div>
                <div class="navbar-logo" style="margin-bottom: 16px; display: inline-flex; align-items: center; gap: 10px;">
                    <div class="navbar-logo-icon" style="background: var(--color-primary); width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i data-lucide="wrench" style="width:18px;height:18px;color:white"></i>
                    </div>
                    <div class="navbar-logo-text" style="font-size: 1.4rem; font-weight: 900; color: #ffffff;">Hon<span style="color: var(--color-primary);">Tech</span></div>
                </div>
                <p style="color: #9ca3af; font-size: 0.9rem; line-height: 1.6; margin-bottom: 20px;">
                    HONTECH AUTO CENTER, INC. (HACI) — Delivering CASA-standard car care services with fair, transparent pricing across Metro Manila.
                </p>
                <div style="display: flex; gap: 12px;">
                    <span style="background: #1f2937; padding: 8px 12px; border-radius: 6px; font-size: 0.8rem; color: #9ca3af;">Quality Over Profit</span>
                </div>
            </div>

            <!-- Col 2: Quick Links -->
            <div>
                <h4 style="font-size: 1.1rem; font-weight: 700; color: #ffffff; margin-bottom: 16px;">Quick Navigation</h4>
                <ul style="display: flex; flex-direction: column; gap: 10px; font-size: 0.9rem; color: #9ca3af;">
                    <li><a href="index.php#about" style="hover:color: #ffffff;">About Us</a></li>
                    <li><a href="index.php#services" style="hover:color: #ffffff;">Automotive Services</a></li>
                    <li><a href="index.php#estimator" style="hover:color: #ffffff;">Service Cost Estimator</a></li>
                    <li><a href="blog.php" style="hover:color: #ffffff;">Blog & Maintenance Tips</a></li>
                    <li><a href="index.php#milestones" style="hover:color: #ffffff;">Company Milestones</a></li>
                    <li><a href="index.php#contact" style="hover:color: #ffffff;">Get In Touch</a></li>
                </ul>
            </div>

            <!-- Col 3: Services -->
            <div>
                <h4 style="font-size: 1.1rem; font-weight: 700; color: #ffffff; margin-bottom: 16px;">Core Services</h4>
                <ul style="display: flex; flex-direction: column; gap: 10px; font-size: 0.9rem; color: #9ca3af;">
                    <li>Periodic Maintenance (PMS)</li>
                    <li>Engine Overhaul & Tuning</li>
                    <li>Brakes & Underchassis</li>
                    <li>Computer Diagnostics Scan</li>
                    <li>Air-Conditioning Overhaul</li>
                    <li>Baking Oven Body Painting</li>
                </ul>
            </div>

            <!-- Col 4: Employee & Staff -->
            <div>
                <h4 style="font-size: 1.1rem; font-weight: 700; color: #ffffff; margin-bottom: 16px;">Internal Access</h4>
                <p style="color: #9ca3af; font-size: 0.85rem; line-height: 1.5; margin-bottom: 14px;">
                    Staff, supervisors, technicians, and marketing editors may access the central operations portal:
                </p>
                <a href="admin/login.php" class="btn-secondary" style="display: inline-flex; align-items: center; gap: 8px; font-size: 0.85rem; padding: 10px 18px; border-radius: var(--radius-sm);">
                    <i data-lucide="shield" style="width:16px;height:16px"></i>
                    Employee Login Portal
                </a>
            </div>

        </div>

        <div style="border-top: 1px solid #1f2937; padding-top: 24px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px; font-size: 0.85rem; color: #6b7280;">
            <div>
                © <?= date('Y') ?> Hontech Auto Center, Inc. (HACI). All Rights Reserved.
            </div>
            <div style="display: flex; gap: 20px;">
                <a href="index.php#terms" style="color: #6b7280;">Terms of Service</a>
                <a href="index.php#privacy" style="color: #6b7280;">Privacy Policy</a>
                <a href="admin/login.php" style="color: #6b7280;">Staff RBAC Access</a>
            </div>
        </div>
    </div>
</footer>

<!-- JavaScript Assets -->
<script src="assets/js/main.js"></script>
<script src="assets/js/estimator.js"></script>
<script src="assets/js/contact.js"></script>
</body>
</html>
