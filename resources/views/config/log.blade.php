<!-- Toast Success -->
<div class="toast position-fixed top-0 start-50 translate-middle-x mt-3 fade" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000" data-bs-toggle="toast" id="log-success-toast">
    <div class="toast-header bg-green-lt">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check icon-green" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path d="M5 12l5 5l10 -10"></path>
        </svg>
        <strong class="me-auto">Exitoso</strong>
        <button type="button" class="ms-2 btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
        {{ session('logSuccess') }}
    </div>
</div>
<!-- Toast Error -->
<div class="toast position-fixed top-0 start-50 translate-middle-x mt-3 fade" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000" data-bs-toggle="toast" id="log-error-toast">
    <div class="toast-header bg-red-lt">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alert-hexagon-filled icon-red" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path d="M10.425 1.414a3.33 3.33 0 0 1 3.026 -.097l.19 .097l6.775 3.995l.096 .063l.092 .077l.107 .075a3.224 3.224 0 0 1 1.266 2.188l.018 .202l.005 .204v7.284c0 1.106 -.57 2.129 -1.454 2.693l-.17 .1l-6.803 4.302c-.918 .504 -2.019 .535 -3.004 .068l-.196 -.1l-6.695 -4.237a3.225 3.225 0 0 1 -1.671 -2.619l-.007 -.207v-7.285c0 -1.106 .57 -2.128 1.476 -2.705l6.95 -4.098zm1.585 13.586l-.127 .007a1 1 0 0 0 0 1.986l.117 .007l.127 -.007a1 1 0 0 0 0 -1.986l-.117 -.007zm-.01 -8a1 1 0 0 0 -.993 .883l-.007 .117v4l.007 .117a1 1 0 0 0 1.986 0l.007 -.117v-4l-.007 -.117a1 1 0 0 0 -.993 -.883z" stroke-width="0" fill="currentColor"></path>
        </svg>
        <strong class="me-auto">Error</strong>
        <button type="button" class="ms-2 btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
        {{ session('logError') }}
    </div>
</div>
<script>
    // JavaScript to show the toast immediately when session('logSuccess') is true
    document.addEventListener('DOMContentLoaded', function () {
        var successToast = document.getElementById('log-success-toast');
        var errorToast = document.getElementById('log-error-toast');

        @if (session('logSuccess'))
        if (successToast) {
            var successToastInstance = new bootstrap.Toast(successToast, {
                animation: true
            });
            successToastInstance.show();
        }
        @endif

            @if (session('logError'))
        if (errorToast) {
            var errorToastInstance = new bootstrap.Toast(errorToast, {
                animation: true
            });
            errorToastInstance.show();
        }
        @endif
    });
</script>
