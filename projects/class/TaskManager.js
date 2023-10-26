class TaskManager {
    constructor() {
        this.lastFetchedData = null;
    }

    createForm = () => {
        //Add a border to the addNewTaskContainer Div
        $('#addNewTaskContainer').addClass('addNewTaskContainerActive');

        //Create the form inside the addNewTaskContainer Div
        $('#addNewTaskContainer').html(`
        <div class='rounded p-2 container newTaskContainer'>
            <input type='text' placeholder='Task Name' id='newTaskName'><br>
            <input type='text' placeholder='Description' id='newTaskDescription'><br>

            <div class="dropdown mt-2">
                
                <input type="date" class="btn btn-secondary" id="dueDate">

                <button id="priorityButton" class="btn btn-secondary dropdown-toggle" type="button" id="priorityDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i data-value="p0" id='selectFlag' class="fas fa-flag"></i> Priority 0
                </button>
                <ul class="dropdown-menu" aria-labelledby="priorityDropdown">
                    <li style='font-size:18px;'><a class="dropdown-item" href="#"><i data-value='p1' class="fas fa-flag text-danger"></i> Priority 1</a></li>
                    <li style='font-size:18px;'><a class="dropdown-item" href="#"><i data-value='p2' class="fas fa-flag text-warning"></i> Priority 2</a></li>
                    <li style='font-size:18px;'><a class="dropdown-item" href="#"><i data-value='p3' class="fas fa-flag text-info"></i> Priority 3</a></li>
                    <li style='font-size:18px;'><a class="dropdown-item" href="#"><i data-value='p0' class="fas fa-flag text-white"></i> Priority 0</a></li>
                </ul>
            </div><hr>

            <button class='btn btn-light' id='newTaskCancelButton'>Cancel</button>
            <button class='btn' id='newTaskAddButton'>Add Task</button>
        </div>`);

        this.datePickerSetup();
    }

    //Select priority when creating a task
    setPriority = (priorityNumber, priorityText, dataValue)  => {
        // Update the button content
        $('#priorityButton').html(`<i id='selectFlag' data-value="${dataValue}" class="fas fa-flag ${priorityNumber}"></i> ${priorityText}`);
    }

    removeForm = () => {
        $('#addNewTaskContainer').html(`<button id='addNewTaskButton' class='btn'>New Task</button>`);
        //Remove the border
        $('#addNewTaskContainer').removeClass('addNewTaskContainerActive');
    }

    refreshTaskList = (_projectId) => {
        $.ajax({
            type: "POST",
            data:{
                data: _projectId
            },
            url: "includes/getProjectDetails.php",
            success: (response) => {
                if (response !== this.lastFetchedData) {
                    this.lastFetchedData = response;
                    //If the project does not exist
                    if(response === ''){
                        //Remove the Task Button
                        $("#mainProjectTitle").html('');
                        $('#addNewTaskContainer').html(`<button class='btn w-100'>Select or Create a Project</button>`);
                    }else if(response === "Project does not exits or you don't have access to it."){
                        $("#mainProjectTitle").html('');
                        $('#addNewTaskContainer').html(`<button class='btn w-100'>Select or Create a Project</button>`);
                        $("#mainProjectTitle").html(response);
                    }else{
                        $("#mainProjectTitle").html(response);
                    }
                    setTimeout(() => {
                        getProjectIdFromUrl(this.refreshTaskList);
                    }, 3000);
                    //Set the Select or Create a Project text to the Add new Task button
                    $('#addNewTaskContainer').html(`<button id='addNewTaskButton' class='btn w-100'>Add Task</button>`);
                }else{
                    setTimeout(() => {
                        getProjectIdFromUrl(this.refreshTaskList);
                    }, 3000);
                }
            }
        });
    }

    datePickerSetup = () => {
        //DueDate input Setup
        const dueDateInput = document.getElementById("dueDate");
        const currentDate = new Date().toISOString().split('T')[0];
        dueDateInput.setAttribute("min", currentDate);
        //DueDate input Setup END
    }

    //Populate the modal window for the Task Edit
    populateTaskEditModalWindow = (_dataValue) => {
        //Change the title to the parent object name
        
    }
    //Populate the modal window for the Task Edit

    //Save Task TO DB
    saveTaskToDb = () => {
        let newTaskName     = $('#newTaskName').val();
        let newTaskDesc     = $('#newTaskDescription').val();
        let newTaskPriority = $('#selectFlag').data('value');
        let dateValue       = $('#dueDate').val();

        const queryParams = new URLSearchParams(window.location.search);
        const projectId = queryParams.get("project");

        //save the task to the database
        //Check if the name of the project is not empty
        if(newTaskName != ''){
            $.ajax({
                type: "POST",
                data: {
                    newTaskName: newTaskName,
                    newTaskDesc: newTaskDesc,
                    newTaskPriority: newTaskPriority,
                    project: projectId,
                    date: dateValue
                },
                url: "includes/saveTaskToDb.php",
                success: (response) => {
                    //Empty the task list
                    $("#taskList").html(response);

                    //Refresh the task list
                    this.refreshTaskList(projectId);

                    //Empty the form input values
                    $('#newTaskName').val('');
                    $('#newTaskDescription').val('');

                    //Keep the Form open
                    this.createForm();
                }
            });
        }else{
            $('#newTaskName').css('background-color', '#eb6760');
            setTimeout(function() {
                $('#newTaskName').css('background-color', 'transparent');
            }, 300);
        }
    }
    //Save Task TO DB END

    //Check Task
    checkTask = (_taskId, _taskStatus) => {
        $.ajax({
            type: "POST",
            url: "includes/checkTask.php",
            data: {
                taskId: _taskId,
                status: _taskStatus
            },
            success: () => {
                //Reload the Task List
                getProjectIdFromUrl(this.refreshTaskList);
                if(_taskStatus == 1){
                    if (taskCheckSound.paused) {
                        taskCheckSound.play();
                    }
                }
            }
        });
    }
    //Check Task END

    //Remove Task
    removeTask = (_taskId) => {
        $.ajax({
            type: "POST",
            url: "includes/removeTask.php",
            data: {
                taskId: _taskId
            },
            success: () => {
            }
        });
    }
    //Remove Task END

}