@push('styles')
<style>
/* CSS for the modal container */
.modal-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}

/* CSS for the modal content */
.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 40%;
    max-height: 60%;
    overflow-y: scroll;
}

.button-meet{
    margin-left:40px;
}

/* CSS for the close button */
.close-button {
    margin-top: 10px;
    padding: 8px 16px;
    background-color: #f1f1f1;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
}

.close-button:hover {
    background-color: #ddd;
}

</style>
@endpush



@push('scripts')
<script>

    const meets = @json($meets);


    const calendarContainer = document.getElementById("calendar-container");
    const weekdays = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];

    // Define a function to display meetings in a modal-like div
    const displayMeetingsModal = (meetings) => {
        // Create the modal container
        const modalContainer = document.createElement("div");
        modalContainer.className = "modal-container";

        // Create the modal content
        const modalContent = document.createElement("div");
        modalContent.className = "modal-content";

        // Create an unordered list for the meetings
        const meetingsList = document.createElement("div");
        meetingsList.className = "meetings-list";

        const headerItem = document.createElement("h3");
        headerItem.innerHTML = `
            <strong>Lita de Sesiones</strong>
        `;
        meetingsList.appendChild(headerItem);

        // Add each meeting as a list item

        const backendUrl = "{{ env('BACKEND_URL') }}";
        const userRole = "{{ Auth::user()->role }}";
        var subRoute = "user-meets";

        if (userRole === "ADMIN" || userRole === "MODERATOR") {
            subRoute = "meets";
        }

        var prefixURL = backendUrl+"/"+subRoute;

        meetings.forEach(meeting => {
            const meetingItem = document.createElement("div");
            meetingItem.innerHTML = `
            <div class="row my-2 border border-gray-50 bg-gray-100 rounded-3 p-2">
                <div class="row my-1">
                    <div class="col-6">
                        <strong>Reunion:</strong> ${meeting.id}
                    </div>
                    <div class="col-6">
                        <strong>Especialidad:</strong> ${meeting.service_name}
                    </div>
                </div>
                <div class="row my-1">
                    <div class="col-6">
                        <strong>Usuario:</strong> ${meeting.user_name}
                    </div>
                    <div class="col-6">
                        <strong>Especialista:</strong> ${meeting.specialist_name} 
                    </div>
                </div>
                <div class="row my-1">
                    <div class="col-6">
                        <strong>Fecha:</strong> ${meeting.date_meet}
                    </div>
                    <div class="col-6">
                        <strong>Enlace:</strong> <a href="${meeting.date_meet}">Enlace Meet</a>
                    </div>
                </div>
                <div class="row my-1">
                    <div class="col-12 text-center" >
                        <form action="${prefixURL}/${meeting.id}" method="POST">
                            @csrf
                            @method('GET')
                            <button type="submit" class="btn btn-primary btn-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                   <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                   <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                   <path d="M11.102 17.957c-3.204 -.307 -5.904 -2.294 -8.102 -5.957c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6a19.5 19.5 0 0 1 -.663 1.032"></path>
                                   <path d="M15 19l2 2l4 -4"></path>
                                </svg>
                                Ver Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            `;
            meetingsList.appendChild(meetingItem);
        });

        // Add the meetings list to the modal content
        modalContent.appendChild(meetingsList);

        // Create a close button
        const closeButton = document.createElement("button");
        closeButton.className = "close-button";
        closeButton.textContent = "Cerrar";

        // Add a click event listener to the close button
        closeButton.addEventListener("click", () => {
            modalContainer.remove(); // Remove the modal when the close button is clicked
        });

        // Add the close button to the modal content
        modalContent.appendChild(closeButton);

        // Add the modal content to the modal container
        modalContainer.appendChild(modalContent);

        // Add the modal container to the document body
        document.body.appendChild(modalContainer);
    };

    const generateCalendar = () => {

        const currentDate = new Date("{{ $currentDate }}");

        let date;
        if (currentDate) {
            date = currentDate;
        } else {
            date = new Date();
        }
        const today = moment();
        const monthStart = moment(date).startOf("month");
        const monthEnd = moment(date).endOf("month");
        const startDate = moment(monthStart).startOf("week"); // Adjust to start on the correct week day
        const endDate = moment(monthEnd).endOf("week");     // Adjust to end on the correct week day

        const dateSquares = [];

        for (let day = moment(startDate); day <= endDate; day = day.clone().add(1, "day")) {
            dateSquares.push(day.clone());
        }

        const Weeks = [];
        let OneWeek = [];

        // Populate the array with empty days if the month starts on a day other than the first day of the week
        if (dateSquares[0].isAfter(monthStart)) {
            const emptyDaysCount = dateSquares[0].diff(monthStart, 'days');
            OneWeek = Array(emptyDaysCount).fill(null);
        }

        for (let i = 0; i < dateSquares.length; i++) {
            OneWeek.push(dateSquares[i]);

            if (OneWeek.length === 7) {
                Weeks.push(OneWeek);
                OneWeek = [];
            }
        }

        // Populate the array with empty days if the month ends on a day other than the last day of the week
        if (OneWeek.length > 0 && OneWeek.length < 7) {
            const emptyDaysCount = 7 - OneWeek.length;
            OneWeek = OneWeek.concat(Array(emptyDaysCount).fill(null));
            Weeks.push(OneWeek);
        }

        // Create the calendar structure
        const weekDaysRow = document.createElement("div");
        weekDaysRow.className = "flex row align-items-start";
        weekdays.forEach(weekday => {
            const dayDiv = document.createElement("div");
            dayDiv.className = "col border border-gray-50 text-center"
            dayDiv.style.height = "40px";
            dayDiv.textContent = weekday;
            weekDaysRow.appendChild(dayDiv);
        });
        calendarContainer.appendChild(weekDaysRow);

        Weeks.forEach(week => {
            const weekDiv = document.createElement("div");
            weekDiv.className = "flex row align-items-start";

            week.forEach(day => {
                const dayDiv = document.createElement("div");
                dayDiv.className = `col border border-gray-50`
                dayDiv.style.height = "120px";
                // Check if the day is from the current month
                if (day && day.isSame(moment(date), 'month')) {
                    dayDiv.style.backgroundColor = "#f8fafc"; // Current month's days


                    const container = document.createElement("div");
                    container.style.display = "flex";
                    container.style.flexDirection = "column";
                    container.style.alignItems = "start";


                    if (day) {
                        const dayNumber = document.createElement("span");
                        dayNumber.textContent = day.date();
                        container.appendChild(dayNumber);
                    }

                    //Check and add Meet the hover day
                    const meetingsOnThisDay = meets.filter(meet => moment(meet.date_meet).isSame(day, 'day'));

                    if (meetingsOnThisDay.length > 0) {

                        dayDiv.style.transition = "background-color 0.3s ease-in-out"; 

                        // Add a hover effect
                        dayDiv.addEventListener("mouseenter", () => {
                            dayDiv.style.backgroundColor = "#eaf7ec";
                        });

                        // Reset the background color when not hovering
                        dayDiv.addEventListener("mouseleave", () => {
                            dayDiv.style.backgroundColor = "#f8fafc";
                        });

                        const svgIcon = document.createElementNS("http://www.w3.org/2000/svg", "svg");
                        svgIcon.setAttribute("xmlns", "http://www.w3.org/2000/svg");
                        svgIcon.classList.add("icon", "icon-tabler", "icon-tabler-calendar-event");
                        svgIcon.setAttribute("width", "24");
                        svgIcon.setAttribute("height", "24");
                        svgIcon.setAttribute("viewBox", "0 0 24 24");
                        svgIcon.setAttribute("stroke-width", "2");
                        svgIcon.setAttribute("stroke", "currentColor");
                        svgIcon.setAttribute("fill", "none");
                        svgIcon.setAttribute("stroke-linecap", "round");
                        svgIcon.setAttribute("stroke-linejoin", "round");

                        svgIcon.innerHTML = `
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path>
                            <path d="M16 3l0 4"></path>
                            <path d="M8 3l0 4"></path>
                            <path d="M4 11l16 0"></path>
                            <path d="M8 15h2v2h-2z"></path>
                        `;

                        container.appendChild(svgIcon);

                        dayDiv.addEventListener("click", () => {
                            displayMeetingsModal(meetingsOnThisDay);
                        });

                        // Apply cursor pointer when hovering
                        dayDiv.style.cursor = "pointer";
                    
                    }

                    dayDiv.appendChild(container);

                } else {

                    dayDiv.style.backgroundColor = "#e2e8f0"; // Other month's days
                }

                weekDiv.appendChild(dayDiv);
            });

            calendarContainer.appendChild(weekDiv);
        });

    };

    generateCalendar();

</script>
@endpush
