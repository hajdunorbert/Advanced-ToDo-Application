class ProjectManager {
    constructor() {
    }

    refreshProjectList = (_projectId) => {
        $.ajax({
            type: "POST",
            data:{
                projectId: _projectId
            },
            url: "includes/getProjects.php",
            success: (response) => {
                $(".projects").html(response);
            }
        });
    }

    refreshSharedProjectList = (_projectId) => {
        $.ajax({
            type: "POST",
            data:{
                projectId: _projectId
            },
            url: "includes/getSharedProjects.php",
            success: (response) => {
                $(".sharedProjects").html(response);
            }
        });
    }

    changeUrl = (_projectId) => {
        //Set the URL to the project ID
        var newURL = `?project=${_projectId}`;
        history.pushState(null, null, newURL);
    }

    createRenameForm = (_projectId) => {
        //Change the button to text input
        let inputElement = $("#renameProjectTitleButton");

        inputElement.attr('type', 'text');
        // Set the cursor to the end of the input field
        inputElement.prop("selectionStart", inputElement.val().length);
        inputElement.prop("selectionEnd", inputElement.val().length);

        const $saveButton = $("<input>").attr({
            type: "button",
            class: "projectRenameSaveButton",
            value: "Save",
            "data-value": _projectId
        });

        const $cancelButton = $("<input>").attr({
            type: "button",
            class: "projectRenameCancelButton",
            value: "Cancel"
        });

        $(".renameProjectTitleButtonContainer").html('');
        $(".renameProjectTitleButtonContainer").append($saveButton, $cancelButton);
    }

    projectRenameCancel = () => {
        //Change the input field back to button
        let inputElement = $("#renameProjectTitleButton");

        inputElement.attr('type', 'button');

        //delete the buttons
        $(".renameProjectTitleButtonContainer").html('');
    }

    projectRenameSave = (_projectId) => {
        //get the project's new name
        let newProjectName = $('#renameProjectTitleButton').val();
        let oldProjectName = $('#renameProjectTitleButton').attr('name');

        //if the new name is empty, than cancel the rename process
        if(newProjectName === '' || newProjectName === oldProjectName){
            this.projectRenameCancel();
        }else{
            //If the new name is not empty than save it to the database
            $.ajax({
                type: "POST",
                url: "includes/renameProject.php",
                data: {
                    data: _projectId,
                    newName: newProjectName},
                success: (response) => {
                    if(response !== 'err'){
                        //If the rename was successfull
                        //Reload the project list
                        this.refreshProjectList(_projectId);
                        //reload the Task List
                        taskManager.refreshTaskList(response);
                    }
                }
            });
        }
    }

    //Delete Project
    deleteProject = (_taskId) => {
        $.ajax({
            type: "POST",
            url: "includes/removeProject.php",
            data: {
                projectId: _taskId
            },
            success: () => {
                //Refresh the project list
                getProjectIdFromUrl(this.refreshProjectList);
                //Refresh the task list
                getProjectIdFromUrl(taskManager.refreshTaskList);
            }
        });
    }
    //Delete Project END

    //Invite User
    inviteUser = (_projectId, _email, _role) => {
        $.ajax({
            type: "POST",
            url: "includes/inviteUser.php",
            data: {
                projectId: _projectId,
                email: _email,
                role: _role
            },
            success: () => {
                $("#inviteToProjectMessage").html('');
                $("#inviteToProjectMessage").toggleClass('text-danger text-success');
                var successMessage = $("<li>").html(`You have successfully invited <b>${_email}</b> to the project.`);
                successMessage.addClass('h6');
                $("#inviteToProjectMessage").append(successMessage);
            },
            error: () =>{
                $("#inviteToProjectMessage").html('');
                var errorMessage = $("<li>").html(`Failed to invite <b>${_email}</b> to the project.`);
                errorMessage.addClass('h6');
                $("#inviteToProjectMessage").append(errorMessage);
            }
        });
    }
    //Invite User END
    
    // Method for creating a new project
    createProject = (_projectName, _projectColor) => {
        $.ajax({
            type: "POST",
            url: "includes/addNewProject.php",
            data: {
                projectName: _projectName,
                projectColor: _projectColor
            },
            success: (projectId) => {
                //Change the URL
                this.changeUrl(projectId);
                //Refresh the project list
                this.refreshProjectList(projectId);
                //Refresh the task list
                taskManager.refreshTaskList(projectId);
                //Hide the modal
                $("[data-dismiss=modal]").trigger({ type: "click" });
                //Reset the modal input fields to empty
                $('#newProjectName').val('');
                $('#customSelect').data('value', '#36454F');
                $('#toggleOptionsDiv').css('background-color', '#36454F');
                $('#toggleOptionsSpan').html('Charcoal')
            }
        });
    }
    // Method for creating a new project END

    selectProjectColor = () => {
        const customSelect = $('#customSelect');
        const options = $('#options');
    
        // Toggle options display on customSelect click
        customSelect.click(function () {
            options.toggle();
        });
    
        // Prevent event propagation when clicking on toggleOptionsSpan
        $('#toggleOptionsSpan').click(function (event) {
            options.toggle();
            event.stopPropagation();
        });
        $('#toggleOptionsDiv').click(function () {
            options.toggle();
        });
    
        // Handle option selection
        options.on('click', '.option', function (event) {
            const color = $(this).data('value');

            // Update customSelect appearance
            customSelect.find('.dot').css('backgroundColor', color);
            customSelect.find('span').text($(this).find('.option-text').text());
            customSelect.data("value", color);

            // Hide all checkmarks and display the checkmark on the selected option
            $('.option .checkmark').hide();
            $(this).find('.checkmark').show();

            options.hide();
            event.stopPropagation(); // Prevent event from propagating to the document click event
        });
    
        // Close the dropdown when clicking outside
        $(document).on('click', function (event) {
            if (!customSelect.is(event.target) && !options.is(event.target)) {
                options.hide();
            }
        });
    
        const toggleContainer = $('#toggle');
        const toggleSlider = $('.toggle-slider');
    
        // Toggle slider on toggleContainer click
        toggleContainer.click(function () {
            toggleSlider.toggleClass('active');
            toggleContainer.toggleClass('active-background');
        });
    }
    
}