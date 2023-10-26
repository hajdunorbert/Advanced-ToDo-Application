const projectManager = new ProjectManager();
const taskManager = new TaskManager();
const notificationManager = new NotificationManager();

//Set up the sound
const taskCheckSound = document.getElementById("checkSound");
taskCheckSound.volume = 0.5;
//Set up the sound END

notificationManager.get();

$(document).ready(function () {

    //Load the projects
    getProjectIdFromUrl(projectManager.refreshProjectList);

    //Load the shared projects
    getProjectIdFromUrl(projectManager.refreshSharedProjectList);

    //Load the project content if the link has the project ID
    getProjectIdFromUrl(getProjectContent);
    
    projectManager.selectProjectColor();

    //If the user clicks on a project
    $(document).on("click", "#projectButton", function() {
        //Get the project ID from the project button
        var projectId = $(this).val();
        //Change the URL
        projectManager.changeUrl(projectId);
        //Reload the Task List
        taskManager.refreshTaskList(projectId);
        //Refresh the projects tab
        getProjectIdFromUrl(projectManager.refreshProjectList);
    });

    //If the user adds a new project
    $(document).on("click", "#addNewProject", function() {
        //Get the input field data
        var projectName = $('#newProjectName').val();
        var projectColor = $('#customSelect').data("value");

        //Check if the length of the project name is less than 3
        if(projectName.length < 3){
            //Change the background color of the input field to red
            $('#newProjectName').css('background-color', '#eb6760');
            setTimeout(function() {
                $('#newProjectName').css('background-color', 'transparent');
            }, 300);
        }else{
            //If the length is greater than 3
            projectManager.createProject(projectName, projectColor);
        }
    });

    //Rename the project
    //If the user clicks on the Title
    $(document).on("click", "#renameProjectTitleButton", function() {
        if ($(this).attr('type') === 'button') {
            var projectId = $(this).data('value');
            projectManager.createRenameForm(projectId);
        }
    });

    //Cancel the project rename by pressing ESC
    $(document).on("keydown", '#renameProjectTitleButton[type="text"]', function(e) {
        if (e.which === 27) {
            projectManager.projectRenameCancel();
        }
    });

    //If the user clicks on the cancel
    $(document).on("click", ".projectRenameCancelButton", function() {
        projectManager.projectRenameCancel();
    });

    //If the user clicks on the save button
    $(document).on("click", ".projectRenameSaveButton", function() {
        //Get the project ID from the button
        var projectId = $(this).data('value');
        projectManager.projectRenameSave(projectId);
    });
    //Rename the project END

    //Delete Project
    $(document).on("click", "#deleteProject", function(event) {
        // Prevent event propagation to the parent button
        event.stopPropagation();
        //Get the project ID from the button
        var projectId = $(this).data('value');
        //Delete the project
        projectManager.deleteProject(projectId);
    });
    //Delete Project END

    //TASK

    //If the user clicks on the priority button when creating a task
    $(document).on("click", ".dropdown-item", function() {
        const priorityNumber = $(this).find('i').attr('class').split(' ').pop();
        const priorityText = $(this).text().trim();
        const dataValue = $(this).find('i').attr('data-value');

        taskManager.setPriority(priorityNumber, priorityText, dataValue);
    });
    //If the user clicks on the priority button when creating a task END

    //If the user clicks on the Add Task button
    $(document).on("click", "#addNewTaskButton", function() {
        taskManager.createForm();
    });

    //If the user clicks on the Cacel button while the task Form is open
    $(document).on("click", "#newTaskCancelButton", function() {
        taskManager.removeForm();
    });

    //If the user clicks on the Add Task button while the task Form is open
    $(document).on("click", "#newTaskAddButton", function() {
        //Saving the task
        taskManager.saveTaskToDb();
        //Reload the task list
    });

    //If the user clicks on the checkmark
    $(document).on("click", "#checkbox", function() {
        event.stopPropagation();
        //Get the values
        let taskId = $(this).data('value');
        let taskStatus;
        $(this).prop('checked') ? taskStatus = 1 : taskStatus = -1;
        //Check the task
        taskManager.checkTask(taskId, taskStatus);
    });

    //If the user clicks on a task to edit it
    $(document).on("click", "#editTaskButton", function() {
        var dataValue = $(this).data('value');
        taskManager.populateTaskEditModalWindow(dataValue);
    });
    //If the user clicks on a task to edit it END

    //If the user clicks on the X to delete a task
    $(document).on("click", "#removeTask", function() {
        //Get the values
        let taskId = $(this).data('value');
        //Remove Task
        taskManager.removeTask(taskId);
        //Reload the Task List
        getProjectIdFromUrl(taskManager.refreshTaskList);
    });

    //Invite to project
    $(document).on("click", "#inviteToProject", function() {
        projectId = getProjectIdFromUrlSimple();

        //If there is no selected project
        if(projectId == null){
            $("#inviteToProjectMessage").html('');
            var errorMessage = $("<li>").text("First you need to select a project!");
            errorMessage.addClass('h6');
            $("#inviteToProjectMessage").append(errorMessage);
        }else{
            let email = $('#inviteEmail').val();
            let role = $('#roleDropdownButton').data('value');

            //Check if the email is valid
            if(!isEmailValid(email)){
                //If the email is invalid
                $("#inviteToProjectMessage").html('');
                var errorMessage = $("<li>").text("You need to add a valid Email address!");
                errorMessage.addClass('h6');
                $("#inviteToProjectMessage").append(errorMessage);
            }else{
                projectManager.inviteUser(projectId, email, role);
            }
        }
    });
    //Invite to project END

    //TASK END
});

function getProjectIdFromUrl(callback) {
    // Get the current URL
    const currentUrl = window.location.search;
    const urlSearchParams = new URLSearchParams(currentUrl);

    // Check if the "project" parameter exists
    if (urlSearchParams.has('project')) {
        // Get the value of the "project" parameter
        const projectId = urlSearchParams.get('project');

        // Call the callback with the projectId
        callback(projectId);
    }else{
        callback("null");
    }
}

function isEmailValid(email) {
    var emailRegex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
    return emailRegex.test(email);
}

function getProjectIdFromUrlSimple() {
    // Get the current URL
    const currentUrl = window.location.search;
    const urlSearchParams = new URLSearchParams(currentUrl);

    // Check if the "project" parameter exists
    if (urlSearchParams.has('project')) {
        // Get the value of the "project" parameter
        const projectId = urlSearchParams.get('project');

        // Call the callback with the projectId
        return projectId;
    }else{
        return null;
    }
}
  
function getProjectContent(projectId) {
    // Load the project
    taskManager.refreshTaskList(projectId);
}