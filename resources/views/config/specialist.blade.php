@push('styles')
<style>
    .user-list, .service-list {
        width: auto;
        max-height: 200px;
        background-color: white;
        border: 1px solid #ccc;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .user-list-item, .service-list-item {
        padding: 8px 16px;
        cursor: pointer;
    }

    .user-content, .service-content {
        display: flex;
        justify-content: flex-start;
        align-items: center;
    }

    .user-name, .service-name {
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
    const educationsContainer = document.getElementById('educations-container');
    const addEducationButton = document.getElementById('add-education');
    const removeEducationButton = document.getElementById('remove-education');


    let educationCount = 0;

    @if($editMode)
        const educationsArray = {!! $specialist->educations ?? '{}' !!};
        const educationsAsArray = Object.values(educationsArray);
        educationCount = educationsAsArray.length;

        if (educationCount > 0) {
            for (let i = 0; i < educationsAsArray.length; i++) {

                const educationDiv = document.createElement('div');
                educationDiv.classList.add('row', 'my-3'); // Add the Bootstrap 'row' class and custom margin class
                educationDiv.innerHTML = `
                    <div class="row">
                        <h4>Educacion N°:${i+1}</h4>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label for="educations[${i+1}][header]">Encabezado:</label>
                            <input type="text" class="form-control" name="educations[${i+1}][header]" value="${educationsAsArray[i].header}" placeholder="titulo"required>
                        </div>
                        <div class="col-6">
                            <label for="educations[${i+1}][subheader]">Sub-Encabezado:</label>
                            <input type="text" class="form-control" name="educations[${i+1}][subheader]" value="${educationsAsArray[i].subheader}" placeholder="institutcion" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label for="educations[${i+1}][from]">Fecha de Inicio:</label>
                            <input type="date" class="form-control" name="educations[${i+1}][from]"  value="${educationsAsArray[i].from}" required>
                        </div>
                        <div class="col-6">
                            <label for="educations[${i+1}][to]">Fecha de Fin:</label>
                            <input type="date" class="form-control" name="educations[${i+1}][to]" value="${educationsAsArray[i].to}">
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-6">
                            <label for="educations[${i+1}][is_active]">Actualmente estudias aca?</label>
                            <input type="checkbox" class="form-check-input" name="educations[${i+1}][is_active]" ${educationsAsArray[i].is_active ? 'checked' : ''}>
                        </div>
                    </div>
                `;

                educationsContainer.appendChild(educationDiv);
            
            }
            removeEducationButton.removeAttribute('disabled'); // Enable the button
        }
    @endif

    function addEducation() {
        educationCount++;

        const educationDiv = document.createElement('div');
        educationDiv.classList.add('row', 'my-3'); // Add the Bootstrap 'row' class and custom margin class
        educationDiv.innerHTML = `
            <div class="row">
                <h4>Educacion N°:${educationCount}</h4>
            </div>
            <div class="row">
                <div class="col-6">
                    <label for="educations[${educationCount}][header]">Encabezado:</label>
                    <input type="text" class="form-control" name="educations[${educationCount}][header]" placeholder="titulo"required>
                </div>
                <div class="col-6">
                    <label for="educations[${educationCount}][subheader]">Sub-Encabezado:</label>
                    <input type="text" class="form-control" name="educations[${educationCount}][subheader]" placeholder="institutcion" required>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <label for="educations[${educationCount}][from]">Fecha de Inicio:</label>
                    <input type="date" class="form-control" name="educations[${educationCount}][from]" required>
                </div>
                <div class="col-6">
                    <label for="educations[${educationCount}][to]">Fecha de Fin:</label>
                    <input type="date" class="form-control" name="educations[${educationCount}][to]" >
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-6">
                    <label for="educations[${educationCount}][is_active]">Actualmente estudias aca?</label>
                    <input type="checkbox" class="form-check-input" name="educations[${educationCount}][is_active]">
                </div>
            </div>
        `;

        educationsContainer.appendChild(educationDiv);

        if (educationCount > 0) {
            removeEducationButton.removeAttribute('disabled'); // Enable the button
        }
    }

    function removeEducation() {
        if (educationCount > 0) {
            educationsContainer.removeChild(educationsContainer.lastChild);
            educationCount--;

            if (educationCount === 0) {
                removeEducationButton.setAttribute('disabled', 'disabled'); // Disable the button
            }
        }
    }

    addEducationButton.addEventListener('click', addEducation);
    removeEducationButton.addEventListener('click', removeEducation);


    const experiencesContainer = document.getElementById('experiences-container');
    const addExperienceButton = document.getElementById('add-experience');
    const removeExperienceButton = document.getElementById('remove-experience');


    let experienceCount = 0;

    @if($editMode)
        const experiencesArray = {!! $specialist->experiences ?? '{}' !!};
        const experiencesAsArray = Object.values(experiencesArray);
        experienceCount = experiencesAsArray.length;

        if (experienceCount > 0) {

            for (let i = 0; i < educationsAsArray.length; i++) {

                const experienceDiv = document.createElement('div');
                experienceDiv.classList.add('row', 'my-3'); // Add the Bootstrap 'row' class and custom margin class
                experienceDiv.innerHTML = `
                    <div class="row">
                        <h4>Experiencia N°:${i+1}</h4>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label for="experiences[${i+1}][header]">Encabezado:</label>
                            <input type="text" class="form-control" name="experiences[${i+1}][header]" value="${educationsAsArray[i].header}" placeholder="puesto" required>
                        </div>
                        <div class="col-6">
                            <label for="experiences[${i+1}][subheader]">Sub-Encabezado:</label>
                            <input type="text" class="form-control" name="experiences[${i+1}][subheader]"  value="${educationsAsArray[i].subheader}" placeholder="empresa" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label for="experiences[${i+1}][from]">Fecha de Inicio:</label>
                            <input type="date" class="form-control" name="experiences[${i+1}][from]"  value="${educationsAsArray[i].from}" required>
                        </div>
                        <div class="col-6">
                            <label for="experiences[${i+1}][to]">Fecha de Fin:</label>
                            <input type="date" class="form-control" name="experiences[${i+1}][to]" value="${educationsAsArray[i].to}">
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-6">
                            <label for="experiences[${i+1}][is_active]">Actualmente trabajas aca?</label>
                            <input type="checkbox" class="form-check-input" name="experiences[${i+1}][is_active]" ${educationsAsArray[i].is_active ? 'checked' : ''}>
                        </div>
                    </div>
                `;

                experiencesContainer.appendChild(experienceDiv);
            
            }
            removeExperienceButton.removeAttribute('disabled');
        }
    @endif


    function addExperience() {
        //console.log('experienceCount:',experienceCount);
        experienceCount++;

        const experienceDiv = document.createElement('div');
        experienceDiv.classList.add('row', 'my-3');
        experienceDiv.innerHTML = `
            <div class="row">
                <h4>Experiencia N°:${experienceCount}</h4>
            </div>
            <div class="row">
                <div class="col-6">
                    <label for="experiences[${experienceCount}][header]">Encabezado:</label>
                    <input type="text" class="form-control" name="experiences[${experienceCount}][header]" placeholder="puesto" required>
                </div>
                <div class="col-6">
                    <label for="experiences[${experienceCount}][subheader]">Sub-Encabezado:</label>
                    <input type="text" class="form-control" name="experiences[${experienceCount}][subheader]" placeholder="empresa" required>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <label for="experiences[${experienceCount}][from]">Fecha de Inicio:</label>
                    <input type="date" class="form-control" name="experiences[${experienceCount}][from]" required>
                </div>
                <div class="col-6">
                    <label for="experiences[${experienceCount}][to]">Fecha de Fin:</label>
                    <input type="date" class="form-control" name="experiences[${experienceCount}][to]">
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-6">
                    <label for="experiences[${experienceCount}][is_active]">Actualmente trabajas aca?</label>
                    <input type="checkbox" class="form-check-input" name="experiences[${experienceCount}][is_active]">
                </div>
            </div>
        `;

        experiencesContainer.appendChild(experienceDiv);

        if (experienceCount > 0) {
            removeExperienceButton.removeAttribute('disabled');
        }
    }

    function removeExperience() {
        if (experienceCount > 0) {
            experiencesContainer.removeChild(experiencesContainer.lastChild);
            experienceCount--;

            if (experienceCount === 0) {
                removeExperienceButton.setAttribute('disabled', 'disabled');
            }
        }
    }

    addExperienceButton.addEventListener('click', addExperience);
    removeExperienceButton.addEventListener('click', removeExperience);

    const awardsContainer = document.getElementById('awards-container');
    const addAwardButton = document.getElementById('add-award');
    const removeAwardButton = document.getElementById('remove-award');

    let awardCount = 0;

    @if($editMode)
        const awardsArray = {!! $specialist->awards ?? '{}' !!};
        const awardsAsArray = Object.values(awardsArray);
        awardCount = awardsAsArray.length;

        if (awardCount > 0) {

            for (let i = 0; i < awardsAsArray.length; i++) {
                const awardDiv = document.createElement('div');
                awardDiv.classList.add('row', 'my-3');
                awardDiv.innerHTML = `
                    <div class="row">
                        <h4>Premio o Reconocimiento N°:${i+1}</h4>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label for="awards[${i+1}][header]">Encabezado:</label>
                            <input type="text" class="form-control" name="awards[${i+1}][header]" value="${awardsAsArray[i].header}" placeholder="premio" required>
                        </div>
                        <div class="col-6">
                            <label for="awards[${i+1}][subheader]">Sub-Encabezado:</label>
                            <input type="text" class="form-control" name="awards[${i+1}][subheader]" value="${awardsAsArray[i].subheader}" placeholder="organización" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label for="awards[${i+1}][date]">Fecha:</label>
                            <input type="date" class="form-control" name="awards[${i+1}][date]" value="${awardsAsArray[i].date}" required>
                        </div>
                    </div>
                `;
                awardsContainer.appendChild(awardDiv);
            }
            removeAwardButton.removeAttribute('disabled');
        }
    @endif

    function addAward() {
        awardCount++;

        const awardDiv = document.createElement('div');
        awardDiv.classList.add('row', 'my-3');
        awardDiv.innerHTML = `
            <div class="row">
                <h4>Premio o Reconocimiento N°:${awardCount}</h4>
            </div>
            <div class="row">
                <div class="col-6">
                    <label for="awards[${awardCount}][header]">Encabezado:</label>
                    <input type="text" class="form-control" name="awards[${awardCount}][header]" placeholder="premio" required>
                </div>
                <div class="col-6">
                    <label for="awards[${awardCount}][subheader]">Sub-Encabezado:</label>
                    <input type="text" class="form-control" name="awards[${awardCount}][subheader]" placeholder="organización" required>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <label for="awards[${awardCount}][date]">Fecha:</label>
                    <input type="date" class="form-control" name="awards[${awardCount}][date]" required>
                </div>
            </div>
        `;

        awardsContainer.appendChild(awardDiv);

        if (awardCount > 0) {
            removeAwardButton.removeAttribute('disabled');
        }
    }

    function removeAward() {
        if (awardCount > 0) {
            awardsContainer.removeChild(awardsContainer.lastChild);
            awardCount--;

            if (awardCount === 0) {
                removeAwardButton.setAttribute('disabled', 'disabled');
            }
        }
    }



    addAwardButton.addEventListener('click', addAward);
    removeAwardButton.addEventListener('click', removeAward);

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

    const servicesContainer = document.getElementById('services-container');
    const serviceList = document.getElementById('service-list');
    const removeServiceButton = document.getElementById('remove-service');

    let serviceCount = 0;

    @if($editMode)
        const servicesArray = {!! $specialist->services ?? '{}' !!};
        const servicesAsArray = Object.values(servicesArray);
        serviceCount = servicesAsArray.length;

        if (serviceCount > 0) {
            for (let i = 0; i < servicesAsArray.length; i++) {
                const serviceDiv = document.createElement('div');
                serviceDiv.classList.add('row', 'my-3');
                serviceDiv.innerHTML = `
                    <div class="row">
                        <h4>Servicio N°:${i+1}</h4>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            <label for="services[${i+1}][id_service]">ID</label>
                            <input type="text" class="form-control" name="services[${i+1}][id_service]" value=${servicesAsArray[i].id_service} readonly>
                        </div>
                        <div class="col-5">
                            <label for="services[${i+1}][name_service]">Nombre</label>
                            <input type="text" class="form-control" name="services[${i+1}][name_service]" value=${servicesAsArray[i].name_service} readonly>
                        </div>
                    </div>
                `;
                servicesContainer.appendChild(serviceDiv);
            }
            removeServiceButton.removeAttribute('disabled');
        }


    @endif

    //SEARCH SERVICES TO DISPLAY
    async function fetchServices(event) {
        event.preventDefault();

        const serviceName = document.getElementById('service_name').value.trim();

        if (serviceName === '') {
            return;
        }

        try {
            const response = await fetch(`{{env('BACKEND_URL')}}/search-services?name=${encodeURIComponent(serviceName)}`);
            console.log(response)
            const services = await response.json();
            serviceList.innerHTML = '';
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
                    listItem.className = 'list-group-item service-list-item';

                    const serviceContent = document.createElement('div');
                    serviceContent.className = 'service-content';
                    serviceContent.innerHTML = `<span class="service-name">${service.name}</span>`;

                    listItem.appendChild(serviceContent);

                    listItem.addEventListener('click', () => {
                        serviceCount++;

                        const serviceDiv = document.createElement('div');
                        serviceDiv.classList.add('row', 'my-3');
                        serviceDiv.innerHTML = `
                            <div class="row">
                                <h4>Servicio N°:${serviceCount}</h4>
                            </div>
                            <div class="row">
                                <div class="col-5">
                                    <label for="services[${serviceCount}][id_service]">ID</label>
                                    <input type="text" class="form-control" name="services[${serviceCount}][id_service]" value=${service.id} readonly>
                                </div>
                                <div class="col-5">
                                    <label for="services[${serviceCount}][name_service]">Nombre</label>
                                    <input type="text" class="form-control" name="services[${serviceCount}][name_service]" value=${service.name} readonly>
                                </div>
                            </div>
                        `;

                        servicesContainer.appendChild(serviceDiv);
                        serviceList.innerHTML = '';

                        if (serviceCount > 0) {
                            removeServiceButton.removeAttribute('disabled');
                        }
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

    function removeService() {
        if (serviceCount > 0) {
            servicesContainer.removeChild(servicesContainer.lastChild);
            serviceCount--;

            if (serviceCount === 0) {
                removeServiceButton.setAttribute('disabled', 'disabled');
            }
        }
    }

    removeServiceButton.addEventListener('click', removeService);



</script>
@endpush
