
        const title = document.getElementById("title");
        const description = document.getElementById("description");
        const duration = document.getElementById("duration");
        const submitButton = document.getElementById("submitButton");

        submitButton.addEventListener('click', (event) => {
            // Allow the form to submit normally
            event.preventDefault();

            // Get the form element
            const form = document.getElementById('taskForm');

            // Perform the form submission using fetch or XMLHttpRequest
            fetch(form.action, {
                method: form.method,
                body: new FormData(form)
            }).then(response => {
                if (response.ok) {
                    // Clear the form fields if submission is successful
                    title.value = "";
                    description.value = "";
                    duration.value = "";
                    console.log('Form submitted and reset successfully');
                } else {
                    // Handle errors if the submission is not successful
                    console.error('Form submission failed.');
                }
            }).catch(error => {
                // Handle network or other errors
                console.error('Form submission failed:', error);
            });
        });
  
