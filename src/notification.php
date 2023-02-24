<?php
    class NotificationView {
        public static function notify($message) {
            echo '
            <style>
            .message{
                position:sticky;
                top:0;
                margin:0 auto;
                width:60vw;
                background-color:lightgray;
                padding:2rem;
                display:flex;
                align-items:center;
                justify-content: space-between;
                z-index:6969;
            }
            .message > span{
                text-align:center;
                font-size:2rem;
                color:red;
             }
            .message > i{
                cursor: pointer;
                color:red;
                font-size:1.5rem;
            }
            </style>
            <script>
                let current_notifications = document.getElementsByClassName("message");
                if( current_notification.length > 0 )
                    { current_notification[0].remove(); }
            </script>
            <div class="message">
                <span>'.$message.'</span>
                <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            </div>
            <script>
                window.setTimeout(function() {
                    document.getElementsByClassName("message")[0].remove();
                }, 3000);
            </script>';
        }
    }
?>