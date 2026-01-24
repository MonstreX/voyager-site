<script>
    const formSendAJAX = async (event, el) => {
        event.preventDefault();

        if (!el) {
            return;
        }

        const holderId = el.dataset.holderId;
        const holder = holderId ? document.getElementById(holderId) : null;
        if (!holder || !holderId) {
            return;
        }

        const form = holder.querySelector('form');
        if (!form) {
            return;
        }

        const el_loader = holder.querySelector('.btn-loader');
        const el_title_normal = holder.querySelector('.btn-title-normal');
        const el_title_sending = holder.querySelector('.btn-title-sending');
        const el_message = holder.querySelector('.form-message');
        const el_button = holder.querySelector('.btn-send-form');

        if (!el_loader || !el_loader.classList.contains('hidden')) {
            return;
        }

        el_loader.classList.remove('hidden');
        if (el_title_normal) {
            el_title_normal.classList.add('hidden');
        }
        if (el_title_sending) {
            el_title_sending.classList.remove('hidden');
        }
        if (el_button) {
            el_button.disabled = true;
        }

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const text = await response.text();
            let data = null;
            try {
                data = JSON.parse(text);
            } catch (err) {
                data = { type: response.ok ? 'success' : 'error', messages: text };
            }

            el_loader.classList.add('hidden');
            el_title_normal?.classList.remove('hidden');
            el_title_sending?.classList.add('hidden');

            if (data.type !== 'success' && el_button) {
                el_button.disabled = false;
            }

            if (el_message) {
                el_message.classList.remove('success', 'error');
                el_message.classList.add(data.type || 'error');
                el_message.style.display = 'block';
                el_message.innerHTML = data.messages || '';
            }

            if (!response.ok && el_message) {
                setTimeout(() => {
                    el_message.style.display = 'none';
                }, 3000);
            }
        } catch (error) {
            el_loader.classList.add('hidden');
            el_title_normal?.classList.remove('hidden');
            el_title_sending?.classList.add('hidden');
            if (el_button) {
                el_button.disabled = false;
            }

            if (el_message) {
                el_message.classList.remove('success');
                el_message.classList.add('error');
                el_message.style.display = 'block';
                el_message.innerHTML = error && error.message ? error.message : 'Request failed';
                setTimeout(() => {
                    el_message.style.display = 'none';
                }, 3000);
            }
        }
    }
</script>

