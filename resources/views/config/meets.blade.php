@push('styles')
<style>
    .user-list, .specialists-list, .services-list, .options-list, .discounts-list {
        width: auto;
        max-height: 200px;
        background-color: white;
        border: 1px solid #ccc;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .user-list-item, .specialists-list-item, .services-list-item, .options-list-item, .discounts-list-item{
        padding: 8px 16px;
        cursor: pointer;
    }

    .user-content, .specialists-content, .services-content, .options-content, .discounts-content{
        display: flex;
        justify-content: flex-start;
        align-items: center;
    }

    .user-name, .specialists-name, .services-name, .options-name, .discounts-name{
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

    const userIdInput = document.getElementById('user_id');
    const userNameInput = document.getElementById('user_name');
    const userList = document.getElementById('user-list');

    async function fetchUsers(event) {
        event.preventDefault();
        const name = userNameInput.value.trim();

        if (name === '') {
            userList.innerHTML = '';
            return;
        }

        try {
            const response = await fetch(`{{env('BACKEND_URL')}}/search-users?name=${encodeURIComponent(name)}`);
            const data = await response.json();
            userList.innerHTML = '';
            if (data.length === 0) {
                // Show a toast for no results
                const noResultsToast = new bootstrap.Toast(document.getElementById('log-error-toast'), {
                    animation: true
                });
                document.getElementById('log-error-toast').querySelector('.toast-body').textContent = 'No hay usuarios existentes.';
                noResultsToast.show();
            }else{
                data.forEach(user => {
                    const listItem = document.createElement('li');
                    listItem.className = 'list-group-item user-list-item';

                    const userContent = document.createElement('div');
                    userContent.className = 'user-content';
                    userContent.innerHTML = `<span class="user-name">${user.name} - ${user.email}</span>`;

                    listItem.appendChild(userContent);

                    listItem.addEventListener('click', () => {
                        userIdInput.value = user.id;
                        userNameInput.value = user.name;
                        userList.innerHTML = '';
                    });

                    userList.appendChild(listItem);
                });
            }
        } catch (error) {
            // Show a toast for fetch error
            const fetchErrorToast = new bootstrap.Toast(document.getElementById('log-error-toast'), {
                animation: true
            });
            document.getElementById('log-error-toast').querySelector('.toast-body').textContent = 'Error trayendo usuarios. Intente de nuevo.';
            fetchErrorToast.show();
            console.error('Error fetching users:', error);
        }
    }



    //SEARCH SPECIALIST TO DISPLAY

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
                    listItem.className = 'list-group-item specialists-list-item';

                    const specialistContent = document.createElement('div');
                    specialistContent.className = 'specialists-content';
                    specialistContent.innerHTML = `<span class="specialists-name">${specialist.id} - ${specialist.user_name}</span>`;

                    listItem.appendChild(specialistContent);

                    listItem.addEventListener('click', () => {
                        specialistIdInput.value = specialist.id;
                        specialistNameInput.value = specialist.user_name;
                        specialistList.innerHTML = '';
                        fetchServices(specialist.id)
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

    //SEARCH SPECIALIST TO DISPLAY

    const serviceIdInput = document.getElementById('service_id');
    const serviceNameInput = document.getElementById('service_name');
    const serviceList = document.getElementById('services-list');
    const optionList = document.getElementById('options-list');


    async function fetchServices(specialist_id) {

        try {
            const response = await fetch(`{{env('BACKEND_URL')}}/search-services-specialist?specialist_id=${encodeURIComponent(specialist_id)}`);
            const services = await response.json();
            serviceList.innerHTML = '';
            optionList.innerHTML = '';
            if (services.length === 0) {
                // Show a toast for no results
                const noResultsToast = new bootstrap.Toast(document.getElementById('log-error-toast'), {
                    animation: true
                });
                document.getElementById('log-error-toast').querySelector('.toast-body').textContent = 'No hay servicios existentes.';
                noResultsToast.show();
            }else{
                services.forEach(service => {
                        const listItem = document.createElement('li');
                        listItem.className = 'list-group-item services-list-item';

                        const serviceContent = document.createElement('div');
                        serviceContent.className = 'services-content';
                        serviceContent.innerHTML = `<span class="services-name">${service.id} - ${service.name}</span>`;

                        listItem.appendChild(serviceContent);

                        listItem.addEventListener('click', () => {
                            serviceIdInput.value = service.id;
                            serviceNameInput.value = service.name;
                            serviceList.innerHTML = '';
                            optionList.innerHTML = '';
                            const optionsJSON = JSON.parse(service.options);
                            const options = Object.values(optionsJSON);
                            generateOptions(options);

                    });

                    serviceList.appendChild(listItem);

                });

            }

        } catch (error) {
            // Show a toast for fetch error
            const fetchErrorToast = new bootstrap.Toast(document.getElementById('log-error-toast'), {
                animation: true
            });
            document.getElementById('log-error-toast').querySelector('.toast-body').textContent = 'Error trayendo los servicios. Intente de nuevo.';
            fetchErrorToast.show();
            console.error('Error fetching services:', error);
        }
    }


    const serviceOptionIdInput  = document.getElementById('service_option_id');
    const serviceDurationInput  = document.getElementById('duration');
    const priceInput            = document.getElementById('price');

    function generateOptions(options){
        options.forEach(option =>{

            const listItem = document.createElement('li');
            listItem.className = 'list-group-item options-list-item';

            const serviceOptionContent = document.createElement('div');
            serviceOptionContent.className = 'options-content';
            serviceOptionContent.innerHTML = `<span class="options-name">${option.id}.- S/.${option.price} - ${option.duration} min.</span>`;

            listItem.appendChild(serviceOptionContent);

            listItem.addEventListener('click', () => {
                serviceOptionIdInput.value  = option.id;
                serviceDurationInput.value  = option.duration;
                priceInput.value            = option.price;
                optionList.innerHTML        = '';
                updateDiscountedPrice();
            });

            optionList.appendChild(listItem);
        });
    }

    //SEARCH DISCOUNTS TO DISPLAY

    const discountIdInput = document.getElementById('discount_code_id');
    const discountNameInput = document.getElementById('discount_name');
    const discountInput = document.getElementById('discount');
    const discountList = document.getElementById('discounts-list');

    async function fetchDiscounts(event) {
        event.preventDefault();
        const name = discountNameInput.value.trim();

        if (name === '') {
            discountList.innerHTML = '';
            return;
        }

        try {
            const response = await fetch(`{{env('BACKEND_URL')}}/search-discounts?name=${encodeURIComponent(name)}`);
            const data = await response.json();
            discountList.innerHTML = '';
            if (data.length === 0) {
                // Show a toast for no results
                const noResultsToast = new bootstrap.Toast(document.getElementById('log-error-toast'), {
                    animation: true
                });
                document.getElementById('log-error-toast').querySelector('.toast-body').textContent = 'No hay descuentos existentes.';
                noResultsToast.show();
            }else{
                data.forEach(discount => {
                    const listItem = document.createElement('li');
                    listItem.className = 'list-group-item discounts-list-item';

                    const discountContent = document.createElement('div');
                    discountContent.className = 'discounts-content';
                    discountContent.innerHTML = `<span class="discounts-name">${discount.id} - ${discount.name}</span>`;

                    listItem.appendChild(discountContent);

                    listItem.addEventListener('click', () => {
                        discountIdInput.value = discount.id;
                        discountNameInput.value = discount.name;
                        discountInput.value = discount.discount;
                        discountList.innerHTML = '';
                        updateDiscountedPrice();
                    });

                    discountList.appendChild(listItem);
                });
            }
        } catch (error) {
            // Show a toast for fetch error
            const fetchErrorToast = new bootstrap.Toast(document.getElementById('log-error-toast'), {
                animation: true
            });
            document.getElementById('log-error-toast').querySelector('.toast-body').textContent = 'Error trayendo los descuentos. Intente de nuevo.';
            fetchErrorToast.show();
            console.error('Error fetching specialists:', error);
        }
    }


    const discountedPriceInput = document.getElementById('discounted_price');


    function updateDiscountedPrice() {
        const price = parseFloat(priceInput.value);
        const discount = parseFloat(discountInput.value);


        if (price === 0 || isNaN(price)) {
            discountedPriceInput.value = 0;
        } else if (discount === 0 || isNaN(discount)) {
            discountedPriceInput.value = price.toFixed(2);
        }else {
            const discountedPrice = (price - (price * discount / 100)).toFixed(2);
            discountedPriceInput.value = discountedPrice;
        }
    }

    const priceCalculatedCheckbox = document.getElementById('price_calculated');


    function enableInputs() {
        priceInput.removeAttribute('readonly');
        discountInput.removeAttribute('readonly');
        priceInput.value = 0;
        discountInput.value=0;
        priceInput.addEventListener('input', updateDiscountedPrice);
        discountInput.addEventListener('input', updateDiscountedPrice);
    }

    function disableInputs() {
        priceInput.setAttribute('readonly', true);
        discountInput.setAttribute('readonly', true);
        priceInput.removeEventListener('input', updateDiscountedPrice);
        discountInput.removeEventListener('input', updateDiscountedPrice);

    }

    @if($editMode)
        if (!priceCalculatedCheckbox.checked) {
            enableInputs();
        } else {
            disableInputs();
        }
    @endif

    priceCalculatedCheckbox.addEventListener('change', function() {
        if (this.checked) {
            disableInputs();
        } else {
            enableInputs();
        }
    });




</script>
@endpush
