<?php

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    //get location where the user was redirected
    $currenUrl = $_SERVER['REQUEST_URI'];
    $currenUrl = ltrim($currenUrl, $currenUrl[0]);
    $currenUrl = htmlspecialchars($currenUrl);
    // User is not logged in, so redirect to the home page
    $baseUrl = $_SERVER['HTTP_HOST'];

    include "../core/settings.php";

    if ($projectFolder == "") {
        header("Location: http://$baseUrl/login/?redirectedFrom=$currenUrl");
    } else {
        header("Location: http://$baseUrl/$projectFolder/login/?redirectedFrom=$currenUrl");
    }

    exit(); // Make sure to stop the script execution after the redirect
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include "../includes/header.html";
    ?>

</head>

<body>

    <!-- Sound -->
    <audio id="checkSound" src="../sounds/checkSound.mp3" preload="auto"></audio>
    <audio id="notificationSound" src="../sounds/notificationSound.mp3" preload="auto"></audio>

    <!-- Nav Bar -->
    <nav class="navbar navbar-expand-lg" style="background-color:#35495e; color:#ffffff">
        <div class="d-flex justify-content-center align-items-center">
            <sl-tooltip content='Toggle Side-Menu'>
                <button class="open-sidebar-button" id='openSidebar'><i class="fa-solid fa-bars"></i></button>
            </sl-tooltip>
            <a class="navbar-brand-projects" href="../">
                <i class="fa-solid fa-house"></i>
            </a>
            <span class="navBarLogoutContainer">

                <!-- Invitation button -->
                <sl-tooltip content='Invite your friends' class="inviteTooltip">
                    <button data-bs-toggle='modal' data-bs-target='#inviteModal' class='btn btn-secondary'><i class="fa-solid fa-share-nodes"></i></button>
                </sl-tooltip>


                <button id="notificationButton" class="btn btn-secondary dropdown-toggle no-caret notificationButton" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-regular fa-bell">
                        <label class='notificationNumber' id='notificationNumber'>

                        </label>
                    </i>
                </button>

                <ul class="dropdown-menu custom-dropdown-menu" aria-labelledby="notificationButton" id='notificationDropdown'>

                </ul>

                <a href="../logout" class="btn btn-secondary navBarLogoutButtons">Log out</a>
            </span>
        </div>
    </nav>
    <!-- Nav Bar END -->

    <!-- Main Content -->


    <div class="row w-100">

        <!-- Side Bar -->
        <div id='sideMenu' class="col-3">
            <div id="main-content" class="ml-auto main-content">
                <!-- Sidebar -->
                <div id="sidebar" class="sidebar open w-25 sidebarContainer">
                    <button type="button" class="mb-3 w-100 projectsSidebarProjects rounded">

                        Projects

                        <sl-tooltip content="Create new Project">
                            <label data-toggle="modal" data-target="#newProjectModal" class='projectsSidebarNewPorject'>+</label>
                        </sl-tooltip>

                    </button>
                    <ul class="projects">

                    </ul>

                    <!-- Shared Projects -->
                    <button type="button" class="mb-3 w-100 projectsSidebarProjects rounded" style="background-color: #244567;">

                        Shared Projects

                    </button>
                    <ul class="sharedProjects">

                    </ul>
                </div>
            </div>
        </div>
        <!-- Side Bar END -->

        <!-- Main -->
        <div id='mainContent' class="col-9 pl-2 pt-5 leftMargin w-75">
            <div id='mainProjectTitle'>

            </div>

            <div class='container' id="taskList">

            </div>

            <div id='addNewTaskContainer'>
                <?php
                if (isset($_GET['project'])) {
                    print "<button id='addNewTaskButton' class='btn w-100'>Add Task</button>";
                } else {
                    print "<button class='btn w-100'>Select or Create a Project</button>";
                }
                ?>
            </div>
            <!-- Add new project Modal -->
            <div class="modal" id="newProjectModal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Add project</h4>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <label>Name</label><br>
                            <input class='w-100 rounded mb-2' type="text" id='newProjectName'><br>

                            <label>Color</label>
                            <div class="custom-select w-100">
                                <div class="select-box" id="customSelect" data-value="#36454F">
                                    <div class="dot" id='toggleOptionsDiv' style="background-color: #36454F"></div>
                                    <span id='toggleOptionsSpan'>Charcoal</span>
                                </div>
                                <div class="options" id="options">
                                    <div class="option" data-value="#7B0051">
                                        <div class="dot" style="background-color: #7B0051;"></div>
                                        <span class="option-text">Berry Red</span>
                                        <span class="checkmark">✓</span>
                                    </div>
                                    <div class="option" data-value="#FF0000">
                                        <div class="dot" style="background-color: #FF0000;"></div>
                                        <span class="option-text">Red</span>
                                        <span class="checkmark">✓</span>
                                    </div>
                                    <div class="option" data-value="#FFA500">
                                        <div class="dot" style="background-color: #FFA500;"></div>
                                        <span class="option-text">Orange</span>
                                        <span class="checkmark">✓</span>
                                    </div>
                                    <div class="option" data-value="#FFFF00">
                                        <div class="dot" style="background-color: #FFFF00;"></div>
                                        <span class="option-text">Yellow</span>
                                        <span class="checkmark">✓</span>
                                    </div>
                                    <div class="option" data-value="#808000">
                                        <div class="dot" style="background-color: #808000;"></div>
                                        <span class="option-text">Olive Green</span>
                                        <span class="checkmark">✓</span>
                                    </div>
                                    <div class="option" data-value="#00FF00">
                                        <div class="dot" style="background-color: #00FF00;"></div>
                                        <span class="option-text">Lime Green</span>
                                        <span class="checkmark">✓</span>
                                    </div>
                                    <div class="option" data-value="#008000">
                                        <div class="dot" style="background-color: #008000;"></div>
                                        <span class="option-text">Green</span>
                                        <span class="checkmark">✓</span>
                                    </div>
                                    <div class="option" data-value="#98FB98">
                                        <div class="dot" style="background-color: #98FB98;"></div>
                                        <span class="option-text">Mint Green</span>
                                        <span class="checkmark">✓</span>
                                    </div>
                                    <div class="option" data-value="#008080">
                                        <div class="dot" style="background-color: #008080;"></div>
                                        <span class="option-text">Teal</span>
                                        <span class="checkmark">✓</span>
                                    </div>
                                    <div class="option" data-value="#87CEEB">
                                        <div class="dot" style="background-color: #87CEEB;"></div>
                                        <span class="option-text">Sky Blue</span>
                                        <span class="checkmark">✓</span>
                                    </div>
                                    <div class="option" data-value="#ADD8E6">
                                        <div class="dot" style="background-color: #ADD8E6;"></div>
                                        <span class="option-text">Light Blue</span>
                                        <span class="checkmark">✓</span>
                                    </div>
                                    <div class="option" data-value="#0000FF">
                                        <div class="dot" style="background-color: #0000FF;"></div>
                                        <span class="option-text">Blue</span>
                                        <span class="checkmark">✓</span>
                                    </div>
                                    <div class="option" data-value="#6A0DAD">
                                        <div class="dot" style="background-color: #6A0DAD;"></div>
                                        <span class="option-text">Grape</span>
                                        <span class="checkmark">✓</span>
                                    </div>
                                    <div class="option" data-value="#8A2BE2">
                                        <div class="dot" style="background-color: #8A2BE2;"></div>
                                        <span class="option-text">Violet</span>
                                        <span class="checkmark">✓</span>
                                    </div>
                                    <div class="option" data-value="#E6E6FA">
                                        <div class="dot" style="background-color: #E6E6FA;"></div>
                                        <span class="option-text">Lavender</span>
                                        <span class="checkmark">✓</span>
                                    </div>
                                    <div class="option" data-value="#FF00FF">
                                        <div class="dot" style="background-color: #FF00FF;"></div>
                                        <span class="option-text">Magenta</span>
                                        <span class="checkmark">✓</span>
                                    </div>
                                    <div class="option" data-value="#FA8072">
                                        <div class="dot" style="background-color: #FA8072;"></div>
                                        <span class="option-text">Salmon</span>
                                        <span class="checkmark">✓</span>
                                    </div>
                                    <div class="option" data-value="#36454F">
                                        <div class="dot" style="background-color: #36454F"></div>
                                        <span class="option-text">Charcoal</span>
                                        <span class="checkmark" style="display: block;">✓</span>
                                    </div>
                                    <div class="option" data-value="#808080">
                                        <div class="dot" style="background-color: #808080;"></div>
                                        <span class="option-text">Grey</span>
                                        <span class="checkmark">✓</span>
                                    </div>
                                    <div class="option" data-value="#483C32">
                                        <div class="dot" style="background-color: #483C32;"></div>
                                        <span class="option-text">Taupe</span>
                                        <span class="checkmark">✓</span>
                                    </div>

                                </div>
                            </div><br>

                            <label>Add to favorites</label>
                            <div class="toggle-container" id="toggle">
                                <div class="toggle-slider"></div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id='addNewProject'>Add</button>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Add new project Modal END -->

            <!-- Edit task Modal -->
            <div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="editTaskLabel">Edit Task Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <!-- Modal Body -->
                        <div class="modal-body">
                            <p>This is a simple Bootstrap modal example. You can add any content you like here.</p>
                        </div>

                        <!-- Modal Footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Edit task Modal END-->

            <!-- Invite your friend Modal -->
            <div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="inviteLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="inviteLabel">Invite your friends</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <!-- Modal Body -->
                        <div class="modal-body">
                            <h5>Email</h5>
                            <input type="email" class="form-control rounded-start" placeholder="Enter an Email" id="inviteEmail">
                            <hr>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle mb-2" type="button" id="roleDropdownButton" data-value='5' data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Viewer
                                </button>
                                <div class="dropdown-menu p-2" aria-labelledby="roleDropdownButton">
                                    <sl-tooltip content="Full access, can create, edit, and delete tasks, manage users, and change settings">
                                        <a class="dropdown-item rounded" id='1'>Admin</a>
                                    </sl-tooltip>
                                    <sl-tooltip content="Similar to an admin but with limited user management capabilities">
                                        <a class="dropdown-item rounded" id='2'>Manager</a>
                                    </sl-tooltip>
                                    <sl-tooltip content="Can create, edit, and delete tasks but cannot modify settings or manage users">
                                        <a class="dropdown-item rounded" id='3'>Editor</a>
                                    </sl-tooltip>
                                    <sl-tooltip content="Can create and edit tasks but cannot delete them or access settings">
                                        <a class="dropdown-item rounded" id='4'>Contributor</a>
                                    </sl-tooltip>
                                    <sl-tooltip content="Read-only access, can view tasks but cannot make any changes or access settings">
                                        <a class="dropdown-item rounded dropdown-item-selected" id='5'>Viewer</a>
                                    </sl-tooltip>
                                </div>
                            </div>

                            <script>
                                $('.dropdown-item').on('click', function() {
                                    const selectedRole = $(this).attr('id');
                                    const text = $(this).text();
                                    $('#roleDropdownButton').text(text).attr('data-value', selectedRole);
                                    $('.dropdown-item').removeClass('dropdown-item-selected');
                                    $(this).addClass('dropdown-item-selected');
                                });
                            </script>

                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" id="inviteToProject">Invite</button>
                            </div>
                            <hr>
                            <ul class='text-danger text-center list-unstyled' id='inviteToProjectMessage'>

                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Invite your friend Modal END-->

            </div>
            <!-- Main END -->

        </div>
        <!-- Main Content END -->

        <script>
            $('#openSidebar').on('click', function() {
                toggleSidebar();
            });

            function toggleSidebar() {
                var sidebar = $('#sidebar');
                var sideMenu = $('#sideMenu');
                var mainContent = $('#mainContent');

                if (sidebar.hasClass('open')) {
                    sideMenu.attr('class', 'col-0');
                    mainContent.attr('class', 'col-12 pl-2 pt-5 leftMargin');
                    sidebar.removeClass('open');
                } else {
                    sideMenu.attr('class', 'col-3');
                    mainContent.attr('class', 'col-9 pl-2 pt-5 leftMargin');
                    sidebar.addClass('open');
                }
            }
        </script>

        <script src="class/NotificationManager.js"></script>
        <script src="class/TaskManager.js"></script>
        <script src="class/ProjectManager.js"></script>
        <script src="coree.js"></script>

</body>

</html>