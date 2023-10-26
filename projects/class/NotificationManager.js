class NotificationManager{
  constructor() {
    this.lastFetchedData = null;
  }

  send = (_projectId, _message) => {
    $.ajax({
      type: "POST",
      data:{
          projectId: _projectId,
          message: _message
      },
      url: "includes/sendNotification.php",
      success: () => {
      }
    });
  }

  playNotificationSound = () => {
    const notificationSound = document.getElementById("notificationSound");
    notificationSound.volume = 0.5;
  
    if (notificationSound) {
      if (notificationSound.paused) {
        notificationSound.play();
      }
    }
  }

  get = () => {
    $.ajax({
      type: "POST",
      data:{   
      },
      url: "includes/getNotifications.php",
      dataType: "json",
      success: (response) => {
        if (JSON.stringify(response) !== JSON.stringify(this.lastFetchedData)) {
          this.lastFetchedData = response;

          this.playNotificationSound();

          var count = response.count;
          var notifications = response.notifications;

          if(notifications != ''){
            
            $('#notificationDropdown').html(notifications);
          }else{
            $('#notificationDropdown').html(`<li style='font-size:12px;'><a class='dropdown-item'>You don't have any notifications!</a></li>`);
          }

          if(count > 0){
            $('#notificationNumber').text(count);
          }

          setTimeout(() => {
            this.get();
          }, 3000);
        } else {
          setTimeout(() => {
            this.get();
          }, 3000);
        }
      },
      error: () => {
        setTimeout(() => {
          this.get();
        }, 10000);
      }
    });
  }

}