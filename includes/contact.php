<?php
/**
 * Contact Section Component
 * Hontech Auto Center Inc.
 */
?>
<!-- ═══════════════════════════════════════
     CONTACT US
     ═══════════════════════════════════════ -->
<section class="section contact" id="contact" style="background: var(--color-bg-alt);">
    <div class="container">
        <div class="section-header text-center reveal">
            <div class="section-badge">
                <span class="badge-dot"></span>
                Get In Touch
            </div>
            <h2 class="section-title">
                Ready to Experience <span class="highlight">Hontech?</span>
            </h2>
            <p class="section-subtitle">
                Visit our Metro Manila facility or send us a message. Our service supervisors are ready to assist you.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 36px; margin-top: 40px; align-items: start;">
            
            <!-- Contact Info Cards -->
            <div class="reveal-left" style="display: flex; flex-direction: column; gap: 20px;">
                <div class="why-card" style="display: flex; gap: 16px; align-items: flex-start;">
                    <div style="background: var(--color-primary-50); padding: 12px; border-radius: 12px;">
                        <i data-lucide="map-pin" style="width:24px;height:24px;color:var(--color-primary)"></i>
                    </div>
                    <div>
                        <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--color-dark); margin-bottom: 4px;">Service Center Location</h4>
                        <p style="color: var(--color-text-secondary); font-size: 0.95rem; line-height: 1.5;">
                            Hontech Auto Center Facility, Metro Manila, Philippines
                        </p>
                    </div>
                </div>

                <div class="why-card" style="display: flex; gap: 16px; align-items: flex-start;">
                    <div style="background: var(--color-primary-50); padding: 12px; border-radius: 12px;">
                        <i data-lucide="phone-call" style="width:24px;height:24px;color:var(--color-primary)"></i>
                    </div>
                    <div>
                        <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--color-dark); margin-bottom: 4px;">Direct Phone & Hotline</h4>
                        <p style="color: var(--color-text-secondary); font-size: 0.95rem; line-height: 1.5;">
                            +63 (2) 8123-4567 / +63 917 123 4567
                        </p>
                    </div>
                </div>

                <div class="why-card" style="display: flex; gap: 16px; align-items: flex-start;">
                    <div style="background: var(--color-primary-50); padding: 12px; border-radius: 12px;">
                        <i data-lucide="clock" style="width:24px;height:24px;color:var(--color-primary)"></i>
                    </div>
                    <div>
                        <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--color-dark); margin-bottom: 4px;">Operating Hours</h4>
                        <p style="color: var(--color-text-secondary); font-size: 0.95rem; line-height: 1.5;">
                            Monday – Saturday: 8:00 AM – 5:30 PM<br>
                            Sunday: Closed (Emergency Dispatch by Request)
                        </p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="reveal-right" style="background: #ffffff; padding: 36px; border-radius: var(--radius-xl); box-shadow: var(--shadow-lg); border: 1px solid var(--color-border);">
                <h3 style="font-size: 1.3rem; font-weight: 800; color: var(--color-dark); margin-bottom: 8px;">Send an Inquiry</h3>
                <p style="font-size: 0.9rem; color: var(--color-text-secondary); margin-bottom: 20px;">
                    Have a question or custom quotation request? Leave your details below.
                </p>

                <form id="contactForm" style="display: flex; flex-direction: column; gap: 16px;">
                    <div>
                        <label style="font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 4px;">Your Full Name *</label>
                        <input type="text" name="name" required placeholder="e.g. Juan Dela Cruz" style="width: 100%; padding: 12px 14px; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.95rem;">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label style="font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 4px;">Email Address *</label>
                            <input type="email" name="email" required placeholder="name@example.com" style="width: 100%; padding: 12px 14px; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.95rem;">
                        </div>
                        <div>
                            <label style="font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 4px;">Contact Phone</label>
                            <input type="tel" name="phone" placeholder="0917 123 4567" style="width: 100%; padding: 12px 14px; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.95rem;">
                        </div>
                    </div>

                    <div>
                        <label style="font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 4px;">Service Inquiry Type</label>
                        <select name="service_type" style="width: 100%; padding: 12px 14px; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.95rem;">
                            <option value="Periodic Maintenance (PMS)">Periodic Maintenance (PMS)</option>
                            <option value="Engine & Mechanical Repair">Engine & Mechanical Repair</option>
                            <option value="Brakes & Suspension Overhaul">Brakes & Suspension Overhaul</option>
                            <option value="Air-Conditioning Service">Air-Conditioning Service</option>
                            <option value="Body Repair & Baking Paint">Body Repair & Baking Paint</option>
                            <option value="Corporate Fleet Service">Corporate Fleet Service</option>
                            <option value="Other General Inquiry">Other General Inquiry</option>
                        </select>
                    </div>

                    <div>
                        <label style="font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 4px;">Your Message / Inquiry *</label>
                        <textarea name="message" rows="4" required placeholder="Describe your car model, current issue, or service inquiry..." style="width: 100%; padding: 12px 14px; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.95rem; resize: vertical;"></textarea>
                    </div>

                    <button type="submit" class="btn-primary" style="padding: 14px; font-weight: 700; border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <i data-lucide="send" style="width:16px;height:16px"></i>
                        Send Message to Supervisors
                    </button>
                </form>
            </div>

        </div>
    </div>
</section>
