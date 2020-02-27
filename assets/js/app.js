// Styles
import '../css/app.scss';

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
