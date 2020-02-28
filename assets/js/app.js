// Styles
import '../css/app.scss';

// jQuery
import $ from 'jquery';

// Toast Handling
import { Notyf } from 'notyf';
import 'notyf/notyf.min.css';

const notyf = new Notyf({
    duration: 5000
});
const toasts = document.querySelector('.toasts').children;
for (const toast of toasts) {
    notyf.open({
        type: toast.dataset.type,
        message: toast.textContent
    });
}

// Form Validation
$('.needs-validation').submit((event) => {
    const form = event.currentTarget;
    if (form.checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        form.classList.add('was-validated');
    }
});
