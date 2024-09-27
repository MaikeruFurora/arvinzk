const config = {
    overlay: document.getElementById('overlay'),
    token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    handleFormSubmission: (formConfig, table) => {
        document.getElementById(formConfig.formId).addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            const formData = new FormData(this); // Collect form data
            const data = Object.fromEntries(formData.entries()); // Convert FormData to a plain object

            // Send the data using Axios
            axios.post(formConfig.apiUrl, data)
                .then(response => {
                    // alert('Data saved successfully:', response.data);

                    // Clear the form fields
                    this.reset();
                    Swal.fire({
                        title: 'Success!',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonText: 'Done'
                    })
                    // Refresh the DataTable
                    table.ajax.reload();
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Warning!',
                        text: error.response.data.message,
                        icon: 'warning',
                        confirmButtonText: 'Done'
                    })
                });
        });
    },


    handleFormSubmissionGet : (formConfig, table) => {
        document.getElementById(formConfig.formId).addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
    
            const formData = new FormData(this); // Collect form data
            const data = Object.fromEntries(formData.entries()); // Convert FormData to a plain object
    
            // Send the data using Axios
            axios.get(formConfig.apiUrl, { params: data })
                .then(response => {
                    this.reset();
                    table.ajax.reload();
                })
                .catch(error => {
                    console.error('Error retrieving data:', error);
                    // Hide preloader in case of error
                    $('#ajax-preloader').fadeOut();
                });
        });
    },
    

    initializeDataTable: (tableConfig) => {
        const table = $('#' + tableConfig.tableId).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: tableConfig.ajaxUrl,
                type: 'GET'
            },
            columns: tableConfig.columns
        });

        $('#' + tableConfig.tableId).on('click', '.edit', function() {
            const data = table.row($(this).parents('tr')).data();
            // Populate the form with the selected row data
            config.populateForm(data);
        });

        return table;
    },

    populateForm: (data) => {
        // Loop through the form fields and populate them with the data
        Object.keys(data).forEach(key => {
            const element = document.getElementById('dataForm').elements[key];
            if (element) {
                if (element.type === 'checkbox') {
                    element.checked = data[key];
                } else {
                    element.value = data[key];
                }
            }
        });
    }

};

$(window).on('load', function() {
    
    // Show the overlay
    config.overlay.style.display = 'block';

    // Hide the overlay after 2 seconds
    setTimeout(function() {
        config.overlay.style.display = 'none';
    }, 1000);
});