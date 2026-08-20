<?php
/**
 * Interactive Service Cost Estimator & Booking Modal Component
 * Hontech Auto Center Inc.
 */
?>
<!-- ═══════════════════════════════════════
     SERVICE COST ESTIMATOR
     ═══════════════════════════════════════ -->
<section class="section estimator" id="estimator" style="background: var(--color-bg-alt);">
    <div class="container">
        <div class="section-header text-center reveal">
            <div class="section-badge">
                <span class="badge-dot"></span>
                Transparent Estimates
            </div>
            <h2 class="section-title">
                Interactive <span class="highlight">Service Cost Estimator</span>
            </h2>
            <p class="section-subtitle">
                Select your vehicle type and desired services to get an instant, transparent cost estimation with zero hidden charges.
            </p>
        </div>

        <div style="max-width: 900px; margin: 0 auto; background: var(--color-white); padding: 36px; border-radius: var(--radius-xl); box-shadow: var(--shadow-xl); border: 1px solid var(--color-border);" class="reveal">
            
            <!-- Vehicle Selector -->
            <div style="margin-bottom: 28px;">
                <label style="font-weight: 700; font-size: 1rem; color: var(--color-text); margin-bottom: 12px; display: block;">
                    1. Select Vehicle Classification:
                </label>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 12px;">
                    <button type="button" class="vehicle-type-btn active btn-secondary" data-vehicle="sedan" style="padding: 12px; border-radius: var(--radius-md); font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <i data-lucide="car" style="width:18px;height:18px"></i> Sedan / Hatch
                    </button>
                    <button type="button" class="vehicle-type-btn btn-secondary" data-vehicle="suv" style="padding: 12px; border-radius: var(--radius-md); font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <i data-lucide="truck" style="width:18px;height:18px"></i> SUV / Crossover
                    </button>
                    <button type="button" class="vehicle-type-btn btn-secondary" data-vehicle="van" style="padding: 12px; border-radius: var(--radius-md); font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <i data-lucide="bus" style="width:18px;height:18px"></i> Van / Pickup
                    </button>
                </div>
            </div>

            <!-- Service Checklist -->
            <div style="margin-bottom: 28px;">
                <label style="font-weight: 700; font-size: 1rem; color: var(--color-text); margin-bottom: 12px; display: block;">
                    2. Select Desired Maintenance & Repair Services:
                </label>
                <div id="estimatorServiceList">
                    <!-- Populated dynamically by assets/js/estimator.js -->
                </div>
            </div>

            <!-- Total Bar & Booking Trigger -->
            <div style="background: var(--color-dark); color: #ffffff; padding: 24px; border-radius: var(--radius-lg); display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 20px;">
                <div>
                    <div style="font-size: 0.85rem; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em;">Estimated Total (Labor & Standard Consumables)</div>
                    <div id="estimatorTotalDisplay" style="font-size: 2.2rem; font-weight: 900; color: #ef4444; margin-top: 4px;">₱0</div>
                </div>
                <button type="button" id="openBookingModalBtn" class="btn-primary" style="padding: 14px 28px; font-size: 1rem; border-radius: var(--radius-md);">
                    <i data-lucide="calendar" style="width:18px;height:18px"></i>
                    Book Appointment with this Estimate
                </button>
            </div>

            <p style="font-size: 0.8rem; color: var(--color-text-muted); margin-top: 14px; text-align: center;">
                *Estimates are calculated based on standard OEM parts and labor. Exact requirements may vary depending on vehicle condition upon actual multi-point inspection.
            </p>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════
     BOOKING MODAL
     ═══════════════════════════════════════ -->
<div id="bookingModal" style="display: none; position: fixed; inset: 0; background: rgba(17, 24, 39, 0.8); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #ffffff; max-width: 550px; width: 100%; border-radius: var(--radius-xl); padding: 32px; box-shadow: var(--shadow-2xl); position: relative; max-height: 90vh; overflow-y: auto;">
        
        <button type="button" id="closeBookingModalBtn" style="position: absolute; top: 20px; right: 20px; background: transparent; border: none; font-size: 1.5rem; cursor: pointer; color: #6b7280;">
            ✕
        </button>

        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
            <div style="background: var(--color-primary-50); padding: 8px; border-radius: 8px;">
                <i data-lucide="calendar-check" style="width:24px;height:24px;color:var(--color-primary)"></i>
            </div>
            <div>
                <h3 style="font-size: 1.3rem; font-weight: 800; color: var(--color-dark);">Schedule Service Booking</h3>
                <p style="font-size: 0.85rem; color: var(--color-text-secondary);">Hontech Auto Center Metro Manila Facility</p>
            </div>
        </div>

        <form id="bookingModalForm" style="display: flex; flex-direction: column; gap: 14px;">
            <input type="hidden" name="selected_services" id="modalSelectedServices">
            <input type="hidden" name="estimated_cost" id="modalEstimatedCost">

            <div>
                <label style="font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 4px;">Full Name *</label>
                <input type="text" name="customer_name" required placeholder="e.g. Juan Dela Cruz" style="width: 100%; padding: 10px 14px; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.95rem;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div>
                    <label style="font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 4px;">Email Address</label>
                    <input type="email" name="customer_email" placeholder="name@example.com" style="width: 100%; padding: 10px 14px; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.95rem;">
                </div>
                <div>
                    <label style="font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 4px;">Phone Number *</label>
                    <input type="tel" name="customer_phone" required placeholder="0917 123 4567" style="width: 100%; padding: 10px 14px; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.95rem;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div>
                    <label style="font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 4px;">Car Make & Model *</label>
                    <input type="text" name="vehicle_model" required placeholder="e.g. Honda Civic 2021" style="width: 100%; padding: 10px 14px; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.95rem;">
                </div>
                <div>
                    <label style="font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 4px;">Plate / CS Number</label>
                    <input type="text" name="plate_number" placeholder="e.g. NBT-1234" style="width: 100%; padding: 10px 14px; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.95rem;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div>
                    <label style="font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 4px;">Preferred Date *</label>
                    <input type="date" name="preferred_date" required value="<?= date('Y-m-d', strtotime('+1 day')) ?>" min="<?= date('Y-m-d') ?>" style="width: 100%; padding: 10px 14px; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.95rem;">
                </div>
                <div>
                    <label style="font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 4px;">Preferred Time</label>
                    <select name="preferred_time" style="width: 100%; padding: 10px 14px; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.95rem;">
                        <option value="Morning (8:00 AM - 12:00 PM)">Morning (8:00 AM - 12:00 PM)</option>
                        <option value="Afternoon (1:00 PM - 5:00 PM)">Afternoon (1:00 PM - 5:00 PM)</option>
                    </select>
                </div>
            </div>

            <div>
                <label style="font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 4px;">Special Requests / Vehicle Symptoms</label>
                <textarea name="additional_notes" rows="2" placeholder="Any specific vibrations, sounds, or concerns you'd like our supervisors to check..." style="width: 100%; padding: 10px 14px; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.95rem; resize: vertical;"></textarea>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; padding: 14px; margin-top: 8px; font-weight: 700;">
                Confirm Booking Appointment
            </button>
        </form>
    </div>
</div>
