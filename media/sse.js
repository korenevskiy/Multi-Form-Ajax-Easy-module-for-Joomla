function init(){
	
	

const ul = document.getElementById('modList');
let liveSite	= ul.dataset.liveSite;
let modId		= ul.dataset.mod;
let orderId		= ul.dataset.order;

console.log(liveSite);
console.log(`${liveSite}/modules/mod_multi_form/media/sse.php?mod=${modId}&order=${orderId}`);

//liveSite = liveSite.replace('https:','http:');

let eventSource = new EventSource(`${liveSite}/modules/mod_multi_form/media/sse.php?mod=${modId}&order=${orderId}`); //&ajax=
//const ul = document.getElementById('list');

console.log('Loaded Page!');

let counter = 0;
  
eventSource.onmessage = function (currentEvent) {
//console.log(currentEvent, counter);
    if (currentEvent.data.length > 0) {
	    
	counter++;
		
//	if(counter<0)
//	    eventSource.close();
		
	if(counter > 10)
		ul.removeChild(ul.firstElementChild);

		const li = document.createElement('li');
		li.innerText = currentEvent.data;
		
		ul.appendChild(li)
		
		
		if(currentEvent.data.indexOf('OK:') == 0){
			li.innerHTML += '<br>Order:' + currentEvent.data.substr(3);
			eventSource.close();
		}
		if(currentEvent.data.indexOf('BAD:') == 0){
			li.innerHTML += '<br>Order:' + currentEvent.data.substr(4);
			eventSource.close();
		}
		
		if(currentEvent.data.indexOf('MESSAGE:') == 0){
			li.innerHTML += '<br>' + currentEvent.data.substr(8);
		}
		if(currentEvent.data.indexOf('stop:') == 0){
			li.innerHTML += '<br>StopMessage';
			eventSource.close();
console.log('StopMessage');
		}
		
			
			
	}
};



eventSource.addEventListener('open', (event) => {
  console.log('Open:');

  console.log(event); // an Event object with type="open"
});

eventSource.addEventListener('error', (event) => {
  console.log('Error:', new Date());

  console.log(event); // an Event object with type="error"
  
});

eventSource.addEventListener('message', (event) => {

//if(event.data)
//console.log('Message:'+event.data.length+' ', event.data,' -> ',new Date().toLocaleTimeString());

//  console.log(event); // a MessageEvent object
});

console.log('Initialized AddEventListener:',	new Date().toLocaleTimeString());
};
init();

//document.addEventListener('DOMContentLoaded', setTimeout.bind(this, init, 2000));