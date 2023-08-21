
@push('scripts')
<script>

    const startDateIdInput = document.getElementById('from_date');
    const endDateIdInput = document.getElementById('to_date');

    async function getCSVData(event) {
        event.preventDefault();
        const start_date = startDateIdInput.value.trim();
        const end_date = endDateIdInput.value.trim(); 

        if (start_date === '') {

            const fetchErrorToast = new bootstrap.Toast(document.getElementById('log-error-toast'), {
                animation: true
            });
            document.getElementById('log-error-toast').querySelector('.toast-body').textContent = 'Ingresa fecha de Inicio de Extracción de datos.';
            fetchErrorToast.show();
            return;
        }

        if (end_date === '') {
            const fetchErrorToast = new bootstrap.Toast(document.getElementById('log-error-toast'), {
                animation: true
            });
            document.getElementById('log-error-toast').querySelector('.toast-body').textContent = 'Ingresa fecha de Fin de Extracción de datos.';
            fetchErrorToast.show();
            return;
        }

        try {
            const response = await fetch(`{{env('BACKEND_URL')}}/meet-csv?start_date=${encodeURIComponent(start_date)}&end_date=${encodeURIComponent(end_date)}`);
            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'meets.csv';
                a.click();
                window.URL.revokeObjectURL(url);
            } else {
                // Show a toast for fetch error
                const fetchErrorToast = new bootstrap.Toast(document.getElementById('log-error-toast'), {
                    animation: true
                });
                document.getElementById('log-error-toast').querySelector('.toast-body').textContent = 'Error descargando los datos.' + response.status;
                fetchErrorToast.show();

                console.error('Failed to fetch CSV data:', response.status);
            }
        } catch (error) {
            // Show a toast for fetch error
            const fetchErrorToast = new bootstrap.Toast(document.getElementById('log-error-toast'), {
                animation: true
            });
            document.getElementById('log-error-toast').querySelector('.toast-body').textContent = 'Error descargando los datos. Intente de nuevo.';
            fetchErrorToast.show();
            console.error('Error fetching specialists:', error);
        }
    }


</script>
@endpush
