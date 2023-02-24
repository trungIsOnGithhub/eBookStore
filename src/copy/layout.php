<div class="app">
            <div class="header">
                <div class="menu-circle"></div>
               
                <div class="search-bar">
                </div>
                <?php
                include 'config.php';
                session_start();
                if (isset($_SESSION['user_id'])) {
                    
                }
                ?>
            </div>
            <div class="wrapper">
                
                <div style="margin: 0 !important;" id="navbar">
                    <div class="sidebar-container shrink">
                        <!-- <button class="sidebar-viewButton" type="button" aria-label="Shrink Sidebar" title="Shrink" onclick="changeSidebarView()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                        </button> -->
                        <div class="sidebar-wrapper">
                            <div class="sidebar-themeContainer">
                                <label labelfor="theme-toggle" class="sidebar-themeLabel ">
                                    <input class="sidebar-themeInput" type="checkbox" id="theme-toggle" />
                                    <div class="sidebar-themeType light " onclick="changeTheme()">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sidebar-listIcon">
                                            <circle cx="12" cy="12" r="5"></circle>
                                            <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"></path>
                                        </svg>
                                        <span class="sidebar-themeInputText">Light</span>
                                    </div>
                                    <div class="sidebar-themeType dark " onclick="changeTheme()">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sidebar-listIcon">
                                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                                        </svg>
                                        <span class="sidebar-themeInputText">Dark</span>
                                    </div>
                                </label>
                            </div>
                            <div>
                            </div>
                        </div>
                    </div>
                    <p><a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i></a></p>
                </div>
                