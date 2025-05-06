// Function to prevent Enter key from submitting the form
function preventEnter(event) {
    if (event.keyCode === 13) {
        event.preventDefault();
    }
}

// Function to display client-side error messages
function showErrorMessage(message) {
    const errorContainer = document.getElementById('client-error-messages');
    const errorDiv = document.createElement('div');
    errorDiv.className = 'flex items-center justify-between p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg';
    errorDiv.setAttribute('role', 'alert');
    errorDiv.innerHTML = `
        <div>
            <strong class="font-bold">Error! </strong> ${message}
        </div>
        <button type="button" class="text-red-700 hover:text-red-900" onclick="this.parentElement.remove()" aria-label="Close">
                ✖
            </button>
    `;
    errorContainer.appendChild(errorDiv);
}

// Function to clear all client-side error messages
function clearErrorMessages() {
    const errorContainer = document.getElementById('client-error-messages');
    errorContainer.innerHTML = '';
}

// Payment error popup functions
function showPaymentErrorPopup() {
    const popup = document.getElementById('payment-error-popup');
    popup.classList.remove('hidden');
    setTimeout(() => {
        popup.style.opacity = '1';
    }, 10);
    setTimeout(closePaymentErrorPopup, 5000);
}

function closePaymentErrorPopup() {
    const popup = document.getElementById('payment-error-popup');
    popup.style.opacity = '0';
    setTimeout(() => {
        popup.classList.add('hidden');
    }, 300);
}
