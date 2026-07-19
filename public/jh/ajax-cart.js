document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.ajax-cart-form').forEach(function (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault(); // এইটাই page reload হতে বাধা দেয়

            const msgBox = form.querySelector('.ajax-cart-msg');
            const formData = new FormData(form);

            msgBox.textContent = 'Adding...';
            msgBox.style.color = 'white';

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: formData,
                });

                const data = await response.json();

                if (!response.ok) {
                    const firstError = data.errors
                        ? Object.values(data.errors)[0][0]
                        : (data.error || 'Something went wrong.');
                    throw new Error(firstError);
                }

                msgBox.textContent = data.message;
                msgBox.style.color = 'lightgreen';
            } catch (error) {
                msgBox.textContent = error.message;
                msgBox.style.color = 'salmon';
            }
        });
    });
});