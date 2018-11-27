function tempAlert(message, duration)
{
	alert("hello");
	var el = document.createElement("div");
	el.setAttribute("style","position:absolute;top:40%;left:20%;background-color:white;");
	el.innerHTML = message;
	setTimeout(funtion() {
	el.parentNode.removeChild(el);
	},duration);
	document.body.appendChild(el);
}