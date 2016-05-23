
//https://www.chromium.org/blink/serviceworker
//http://www.html5rocks.com/en/tutorials/service-worker/introduction/

var reg;
var sub;
var isSubscribed = false;
var endpoint;

window.onload = init;

function init()
{
	console.info("followMe @PixelarteCl");
	initServiceWorker();
}

function initServiceWorker()
{

 	if ('serviceWorker' in navigator) { 
 		console.info('Service Worker is supported'); 
	    navigator.serviceWorker.register('/gcm/serviceWorker/sw.min.js').then(initialiseState);  
	} else {  
	    console.warn('Service workers aren\'t supported in this browser.');  
	}  


	// Once the service worker is registered set the initial state  
	function initialiseState() {  
	  // Are Notifications supported in the service worker?  
	  if (!('showNotification' in ServiceWorkerRegistration.prototype)) {  
	    console.warn('Notifications aren\'t supported.');  
	    return;  
	  }

	  // Check the current Notification permission.  
	  // If its denied, it's a permanent block until the  
	  // user changes the permission  
	  if (Notification.permission === 'denied') {  
	    console.warn('The user has blocked notifications.');  
	    return;  
	  }

	  // Check if push messaging is supported  
	  if (!('PushManager' in window)) {  
	    console.warn('Push messaging isn\'t supported.');  
	    return;  
	  }

	  // We need the service worker registration to check for a subscription 


	  navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) { 

	    

	  	reg = serviceWorkerRegistration; 

	  	    console.log(serviceWorkerRegistration);
	    // Do we already have a push message subscription?  
	    serviceWorkerRegistration.pushManager.getSubscription()  
	      .then(function(subscription) {  
	        // Enable any UI which subscribes / unsubscribes from  
	        // push messages.  
	        //var pushButton = document.querySelector('.js-push-button');  
	       // pushButton.disabled = false;

	        console.log(subscription);

	        if (!subscription) {  
	          // We aren't subscribed to push, so set UI  
	          // to allow the user to enable push  
	          return;  
	        }

	        // Keep your server in sync with the latest subscriptionId
	        sendSubscriptionToServer(subscription);

	        // Set your UI to show they have subscribed for  
	        // push messages  
	        //pushButton.textContent = 'Disable Push Messages';  
	        //isPushEnabled = true;
	         
	      })  
	      .catch(function(err) {  
	        console.warn('Error during getSubscription()', err);  
	      });  
	  });  
	}

	

	/*if ('serviceWorker' in navigator) {
				
		  console.log('Service Worker is supported 2');

		  //navigator.serviceWorker.register('https://lab.globaldigital.cl/gcm/assets/js/libs/sw/sw.min.js').then(function(serviceWorkerRegistration) {
		  navigator.serviceWorker.register('assets/js/libs/sw/sw.min.js').then(function(serviceWorkerRegistration) {
		  	reg = serviceWorkerRegistration;
		  return navigator.serviceWorker.ready;
		  }).then(function() {
		     console.log('Service Worker is ready :^)', reg);
		     isSubscribed = true;
		  }).catch(function(error) {
		    console.log('Service Worker Error :^(', error);
		  });

		  initButtonSubscribe();
	}else{
		console.log("no suported");
	}
	*/
}




function initButtonSubscribe(){

	$("#subscribe").show();

	$("#subscribe").on("click",function(e){
		e.preventDefault();

/*	
 
  navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {

  	console.log("pico b");
    serviceWorkerRegistration.pushManager.subscribe()
      .then(function(subscription) {
        // The subscription was successful
        console.log("pico");
        return sendSubscriptionToServer(subscription);
      })
      .catch(function(error) {
        if (Notification.permission === 'denied') {
          console.log('Permission for Notifications was denied');
          subscribeButton.disabled = true;
        } else {
          console.log('Unable to subscribe to push.', error);
          subscribeButton.disabled = false;
        }
      });
  });*/

		/*console.log(isSubscribed);
		  if(!isSubscribed){
			  reg.pushManager.subscribe({userVisibleOnly: true}).
			  then(function(pushSubscription){
			    sub = pushSubscription;
			    console.log('Subscribed! Endpoint:', sub.endpoint);
			    $("#subscribe").html("Unsubscribe");
			    isSubscribed = true;
			    registro();
			  });
			}else{
				sub.unsubscribe().then(function(event) {
				    $("#subscribe").html("Subscribe");
				    console.log('Unsubscribed!', event);
				    isSubscribed = false;
				}).catch(function(error) {
				    console.log('Error unsubscribing', error);
				    $("#subscribe").html("Subscribe");
				});
			}*/
	})
}



function registro(){

	endpoint=sub.endpoint.slice(sub.endpoint.indexOf('send/')+5,sub.endpoint.length);

	$.ajax({
			url: "php/registro.php",
			data: {
					campos: {endpoints:endpoint,dispositivo:dispositivo,num:num}
			},
			type: 'POST',
			dataType: "json",
			success: function (data) {
				
				console.log(data.result);
				if(data.result=="ok"){


				}
			}
		});
}
