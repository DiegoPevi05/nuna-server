@push('styles')
<style>
    .specialist-list {
        width: auto;
        max-height: 200px;
        background-color: white;
        border: 1px solid #ccc;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .specialist-list-item {
        padding: 8px 16px;
        cursor: pointer;
    }

    .specialist-content {
        display: flex;
        justify-content: flex-start;
        align-items: center;
    }

    .specialist-name {
        font-weight: bold;
    }

    /* Position the form group relative for overlay */
    .form-group {
        position: relative;
    }
</style>
@endpush


@push('scripts')
<script>

    //SEARCH USERS TO DISPLAY

    const specialistIdInput = document.getElementById('specialist_id');
    const specialistNameInput = document.getElementById('specialist_name');
    const specialistList = document.getElementById('specialists-list');

    async function fetchSpecialists(event) {
        event.preventDefault();
        const name = specialistNameInput.value.trim();

        if (name === '') {
            specialistList.innerHTML = '';
            return;
        }

        try {
            const response = await fetch(`{{env('BACKEND_URL')}}/search-specialists?name=${encodeURIComponent(name)}`);
            const data = await response.json();
            specialistList.innerHTML = '';
            if (data.length === 0) {
                // Show a toast for no results
                const noResultsToast = new bootstrap.Toast(document.getElementById('log-error-toast'), {
                    animation: true
                });
                document.getElementById('log-error-toast').querySelector('.toast-body').textContent = 'No hay especialistas existentes.';
                noResultsToast.show();
            }else{
                data.forEach(specialist => {
                    const listItem = document.createElement('li');
                    listItem.className = 'list-group-item specialist-list-item';

                    const specialistContent = document.createElement('div');
                    specialistContent.className = 'specialist-content';
                    specialistContent.innerHTML = `<span class="specialist-name">${specialist.id} - ${specialist.user_name}</span>`;

                    listItem.appendChild(specialistContent);

                    listItem.addEventListener('click', () => {
                        specialistIdInput.value = specialist.id;
                        specialistNameInput.value = specialist.user_name;
                        specialistList.innerHTML = '';
                    });

                    specialistList.appendChild(listItem);
                });
            }
        } catch (error) {
            // Show a toast for fetch error
            const fetchErrorToast = new bootstrap.Toast(document.getElementById('log-error-toast'), {
                animation: true
            });
            document.getElementById('log-error-toast').querySelector('.toast-body').textContent = 'Error trayendo los especialistas. Intente de nuevo.';
            fetchErrorToast.show();
            console.error('Error fetching specialists:', error);
        }
    }

</script>
@endpush
