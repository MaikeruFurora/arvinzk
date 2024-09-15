const config = {
    handleFormSubmission: (formConfig, table) => {
        document.getElementById(formConfig.formId).addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            const formData = new FormData(this); // Collect form data
            const data = Object.fromEntries(formData.entries()); // Convert FormData to a plain object

            // Send the data using Axios
            axios.post(formConfig.apiUrl, data)
                .then(response => {
                    alert('Data saved successfully:', response.data);

                    // Clear the form fields
                    this.reset();

                    // Refresh the DataTable
                    table.ajax.reload();
                })
                .catch(error => {
                    console.error('Error saving data:', error);
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

        // Handle edit button clicks
        $('#' + tableConfig.tableId).on('click', '.btn-dark', function() {
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
