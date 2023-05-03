function createNotification(title, content) {
    if (Notification.permission === 'granted') {
        var notification = new Notification(title, {
            body: content
        });
    }
    else if (Notification.permission !== 'denied') {
        Notification.requestPermission(function(permission) {
            if (permission === 'granted') {
                var notification = new Notification(title, {
                    body: content
                });
            }
        });
    }
}