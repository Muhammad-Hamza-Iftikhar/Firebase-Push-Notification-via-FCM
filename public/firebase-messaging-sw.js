importScripts("https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js");
importScripts(
    "https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js"
);

firebase.initializeApp({
    apiKey: "",
    authDomain: "",
    projectId: "",
    storageBucket: "",
    messagingSenderId: "",
    appId: "",
    measurementId: ""
  });

  self.addEventListener('push', function (event) {
    console.log('[Service Worker] Push Received', event.data.text());
  
    try {
      const pushData = event.data.json();
      const notification = pushData.notification;
  
      const options = {
        body: notification.body,
      };
  
      event.waitUntil(self.registration.showNotification(notification.title, options));
    } catch (error) {
      console.error('Error parsing push notification:', error);
    }
  });
  
  self.addEventListener('notificationclick', function (event) {
    console.log('[Service Worker] Notification clicked');
    event.notification.close();
  
    // Extracting data from the notification payload, if needed
    const pushData = event.notification.data.data; // Assuming your data is nested under 'data' key
    const userId = pushData.user_id; // Accessing the 'user_id' from the notification payload
  
    // Perform actions based on the notification payload
    // For example, open a URL or perform a task
    const clickedPromise = clients.openWindow('https:127.0.0.1:8000');
    event.waitUntil(clickedPromise);
  });
  
  /*
Retrieve an instance of Firebase Messaging so that it can handle background messages.
*/
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) {
    console.log(
        "[firebase-messaging-sw.js] Received background message ",
        payload
    );
    // Customize notification here
    const notificationTitle = "Background Message Title";
    const notificationOptions = {
        body: "Background Message body.",
        icon: "/itwonders-web-logo.png",
    };

    return self.registration.showNotification(
        notificationTitle,
        notificationOptions
    );
});
