/**
 * Checkout JavaScript - Dynamic Payment Method instructions & Validation
 */

document.addEventListener('DOMContentLoaded', function () {
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const bankDetails = document.getElementById('b2b-bank-details');
    const creditTermsDetails = document.getElementById('b2b-creditterms-details');

    if (paymentRadios.length > 0) {
        paymentRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                if (bankDetails) bankDetails.classList.add('d-none');
                if (creditTermsDetails) creditTermsDetails.classList.add('d-none');

                if (this.value === 'Bank Transfer' && bankDetails) {
                    bankDetails.classList.remove('d-none');
                } else if (this.value === 'Credit Terms' && creditTermsDetails) {
                    creditTermsDetails.classList.remove('d-none');
                }
            });
        });
    }
});
