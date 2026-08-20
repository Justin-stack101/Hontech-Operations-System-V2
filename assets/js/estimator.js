/**
 * Hontech Auto Center - Service Cost Estimator & Appointment Booking
 */

const ESTIMATOR_SERVICES = [
    { id: 'pms', name: '50-Point Periodic Maintenance (PMS)', basePrice: 2500, category: 'Maintenance' },
    { id: 'oil_synthetic', name: 'Full Synthetic Oil & OEM Filter Change', basePrice: 3200, category: 'Maintenance' },
    { id: 'brakes', name: 'Brake Pad Replacement & Rotor Surfacing', basePrice: 2800, category: 'Mechanical' },
    { id: 'ac_service', name: 'Air Conditioning System Cleaning & Freon Recharge', basePrice: 3500, category: 'Electrical' },
    { id: 'diag_scan', name: 'OEM Computer Diagnostics & Sensor Scan', basePrice: 1500, category: 'Electrical' },
    { id: 'underchassis', name: 'Underchassis Bushings & Suspension Overhaul', basePrice: 4500, category: 'Mechanical' },
    { id: 'detailing', name: 'Engine Bay Detailing & Protective Coating', basePrice: 1800, category: 'Body & Care' },
    { id: 'ceramic', name: 'Ceramic Coating & Paint Enhancement', basePrice: 8500, category: 'Body & Care' }
];

const VEHICLE_MULTIPLIERS = {
    sedan: 1.0,
    suv: 1.25,
    van: 1.4
};

let currentVehicleType = 'sedan';
let selectedServices = new Set(['pms', 'oil_synthetic']);

function updateEstimatorUI() {
    const listContainer = document.getElementById('estimatorServiceList');
    if (!listContainer) return;

    listContainer.innerHTML = '';
    const multiplier = VEHICLE_MULTIPLIERS[currentVehicleType] || 1.0;
    let total = 0;

    ESTIMATOR_SERVICES.forEach(service => {
        const isChecked = selectedServices.has(service.id);
        const adjustedPrice = Math.round(service.basePrice * multiplier);
        if (isChecked) total += adjustedPrice;

        const card = document.createElement('label');
        card.className = `estimator-item ${isChecked ? 'active' : ''}`;
        card.style.cssText = `
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 18px;
            border-radius: 10px;
            background: ${isChecked ? '#1f2937' : '#ffffff'};
            color: ${isChecked ? '#ffffff' : '#111827'};
            border: 1.5px solid ${isChecked ? '#dc2626' : '#e5e7eb'};
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 10px;
        `;

        card.innerHTML = `
            <div style="display: flex; align-items: center; gap: 12px;">
                <input type="checkbox" value="${service.id}" ${isChecked ? 'checked' : ''} style="accent-color: #dc2626; width: 18px; height: 18px; cursor: pointer;">
                <div>
                    <div style="font-weight: 600; font-size: 0.95rem;">${service.name}</div>
                    <div style="font-size: 0.8rem; color: ${isChecked ? '#9ca3af' : '#6b7280'};">${service.category}</div>
                </div>
            </div>
            <div style="font-weight: 700; font-size: 1rem; color: ${isChecked ? '#ef4444' : '#dc2626'};">
                ₱${adjustedPrice.toLocaleString()}
            </div>
        `;

        const checkbox = card.querySelector('input[type="checkbox"]');
        checkbox.addEventListener('change', (e) => {
            if (e.target.checked) {
                selectedServices.add(service.id);
            } else {
                selectedServices.delete(service.id);
            }
            updateEstimatorUI();
        });

        listContainer.appendChild(card);
    });

    const totalEl = document.getElementById('estimatorTotalDisplay');
    if (totalEl) {
        totalEl.textContent = `₱${total.toLocaleString()}`;
    }

    const modalTotalEl = document.getElementById('modalEstimatedCost');
    if (modalTotalEl) {
        modalTotalEl.value = total;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Vehicle Type Pills
    const vehicleButtons = document.querySelectorAll('.vehicle-type-btn');
    vehicleButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            vehicleButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentVehicleType = btn.dataset.vehicle || 'sedan';
            updateEstimatorUI();
        });
    });

    updateEstimatorUI();

    // Booking Modal controls
    const bookBtn = document.getElementById('openBookingModalBtn');
    const modal = document.getElementById('bookingModal');
    const closeBtn = document.getElementById('closeBookingModalBtn');
    const bookingForm = document.getElementById('bookingModalForm');

    if (bookBtn && modal) {
        bookBtn.addEventListener('click', () => {
            if (selectedServices.size === 0) {
                if (typeof showToast === 'function') {
                    showToast('Please select at least one service to book an appointment.', 'error');
                } else {
                    alert('Please select at least one service.');
                }
                return;
            }

            // Populate selected services hidden input
            const selectedNames = Array.from(selectedServices).map(id => {
                const s = ESTIMATOR_SERVICES.find(item => item.id === id);
                return s ? s.name : id;
            }).join(', ');

            const hiddenServicesInput = document.getElementById('modalSelectedServices');
            if (hiddenServicesInput) hiddenServicesInput.value = selectedNames;

            modal.style.display = 'flex';
        });
    }

    if (closeBtn && modal) {
        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });
    }

    // Modal background click to close
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.style.display = 'none';
        });
    }

    // Booking Form AJAX Submit
    if (bookingForm) {
        bookingForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const submitBtn = bookingForm.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerText = 'Submitting Booking...';
            }

            const formData = new FormData(bookingForm);

            try {
                const res = await fetch('api/submit_booking.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();

                if (data.success) {
                    if (typeof showToast === 'function') {
                        showToast(`Booking Successful! Reference: ${data.reference_code}`, 'success');
                    } else {
                        alert(`Booking Successful! Reference Code: ${data.reference_code}`);
                    }
                    bookingForm.reset();
                    if (modal) modal.style.display = 'none';
                } else {
                    if (typeof showToast === 'function') {
                        showToast(data.message || 'Failed to submit booking.', 'error');
                    } else {
                        alert(data.message);
                    }
                }
            } catch (err) {
                console.error('Booking submission error:', err);
                if (typeof showToast === 'function') {
                    showToast('Network error submitting booking. Check XAMPP server.', 'error');
                }
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Confirm Booking';
                }
            }
        });
    }
});
