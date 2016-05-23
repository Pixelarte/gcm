
//https://www.chromium.org/blink/serviceworker
//http://www.html5rocks.com/en/tutorials/service-worker/introduction/

var reg;
var sub;
var isSubscribed;
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
	    navigator.serviceWorker.register('/sw.min.js').then(initialiseState);  
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

	   		sub=subscription;


	       
	        //if (subscription==null) { 
	        // if (!pushSubscription || !pushSubscription.unsubscribe) { 
	        if (!subscription) {  
	          // We aren't subscribed to push, so set UI  
	          // to allow the user to enable push 

	          isSubscribed = false;
	       	  initButtonSubscribe();

	          return;  
	        }

	        
			sliceEndPoint(sub.endpoint);
	        isSubscribed = true;
 			initButtonSubscribe();

	        // Keep your server in sync with the latest subscriptionId
	        //sendSubscriptionToServer(subscription);

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

}




function initButtonSubscribe(){

	$("#subscribe").show();

	if(isSubscribed){
		$("#subscribe").html("Unsubscribe");
	}else{
		$("#subscribe").html("Subscribe");
	}

	$("#subscribe").on("click",function(e){
		e.preventDefault();
		
		if(!isSubscribed){ // debe subscribirse
			$("#subscribe").html("Unsubscribe");
			reg.pushManager.subscribe({userVisibleOnly: true}).then(function(pushSubscription){
				sub = pushSubscription;
				isSubscribed=true;
				console.info('Subscribed! Endpoint:', sub.endpoint);
				sliceEndPoint(sub.endpoint);
				subscribir();
			});
		}else{
			$("#subscribe").html("Subscribe");
			sub.unsubscribe().then(function(event) {
				    $("#subscribe").html("Subscribe");
				    console.log('Unsubscribed!', event);
				    isSubscribed = false;
				    dessubscribir();
				}).catch(function(error) {
				    console.log('Error unsubscribing', error);
				    $("#subscribe").html("Subscribe");
				});
		}

	})
}



function subscribir(){
	$.ajax({
			url: "php/subscribe.php",
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

function dessubscribir(){

	$.ajax({
			url: "php/unSubscribing.php",
			data: {
					campos: {endpoints:endpoint,num:num}
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


function sliceEndPoint(data){
	endpoint=data.slice(data.indexOf('send/')+5,data.length);
	console.log(endpoint);
}