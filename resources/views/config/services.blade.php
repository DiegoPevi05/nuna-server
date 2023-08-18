@push('scripts')
<script>
    const optionsContainer = document.getElementById('options-container');
    const addOptionButton = document.getElementById('add-option');
    const removeOptionButton = document.getElementById('remove-option');

    let optionCount = 0;

    @if($editMode)
        const optionsArray = {!! $service->options ?? '{}' !!};
        const optionsAsArray = Object.values(optionsArray);
        optionCount = optionsAsArray.length;

        if (optionCount > 0) {
            for (let i = 0; i < optionsAsArray.length; i++) {

                const optionDiv = document.createElement('div');
                optionDiv.classList.add('row', 'my-3'); // Add the Bootstrap 'row' class and custom margin class
                optionDiv.innerHTML = `
                    <div class="col-2">
                        <label for="options[${i+1}][id]">id:</label>
                        <input type="number" class="form-control" step="0.1" name="options[${i+1}][id]" value="${optionsAsArray[i].id}" readonly>
                    </div>
                    <div class="col-5">
                        <label for="options[${i+1}][price]">Precio:</label>
                        <input type="number" class="form-control" step="0.1" name="options[${i+1}][price]" value="${optionsAsArray[i].price}" readonly>
                    </div>
                    <div class="col-5">
                        <label for="options[${i+1}][duration]">Duración en minutos:</label>
                        <input type="number" class="form-control" name="options[${i+1}][duration]" value="${optionsAsArray[i].duration}" readonly>
                    </div>
                `;

                optionsContainer.appendChild(optionDiv);
            
            }
            removeOptionButton.removeAttribute('disabled'); // Enable the button
        }
    @endif

    function addOption() {
        optionCount++;

        const optionDiv = document.createElement('div');
        optionDiv.classList.add('row', 'my-3'); // Add the Bootstrap 'row' class and custom margin class
        optionDiv.innerHTML = `
            <div class="col-2">
                <label for="options[${optionCount}][id]">id:</label>
                <input type="number" class="form-control" step="0.1" name="options[${optionCount}][id]" value="${optionCount}" readonly>
            </div>
            <div class="col-5">
                <label for="options[${optionCount}][price]">Precio:</label>
                <input type="number" class="form-control" step="0.1" name="options[${optionCount}][price]" required>
            </div>
            <div class="col-5">
                <label for="options[${optionCount}][duration]">Duración en minutos:</label>
                <input type="number" class="form-control" name="options[${optionCount}][duration]" required>
            </div>
        `;

        optionsContainer.appendChild(optionDiv);

        if (optionCount > 0) {
            removeOptionButton.removeAttribute('disabled'); // Enable the button
        }
    }

    function removeOption() {
        if (optionCount > 0) {
            optionsContainer.removeChild(optionsContainer.lastChild);
            optionCount--;

            if (optionCount === 0) {
                removeOptionButton.setAttribute('disabled', 'disabled'); // Disable the button
            }
        }
    }

    addOptionButton.addEventListener('click', addOption);
    removeOptionButton.addEventListener('click', removeOption);
</script>
@endpush
