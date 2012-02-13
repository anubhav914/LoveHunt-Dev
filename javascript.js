// JavaScript Document
var e;
function change_class(event,choice)
{
	e = event.target;
	if(!e)
		e = event.srcElement;
	//alert(choice);
	document.getElementById(e.id).className='images '+choice+'Image_hover';
}
function restore_class(event,choice)
{
	e = event.target;
	if(!e)
		e = event.srcElement;
	//alert(choice);
//	alert(e.id);
	document.getElementById(e.id).className='images '+choice+'Image';
//	alert(e.id.className);
}
function friends_hover(div)
{
	div.className+=' friends_hover';
}
function friends_out(div)
{
	div.className='friends';
}
function hide(div)
{
	div.className+=' hidden';
}

function moreover()
{
	document.getElementById('more').id='more_hover';
	document.getElementById('more_image').id='more_image_hover';
	document.getElementById('more_text').id='more_text_hover';
}

function moreout()
{
	document.getElementById('more_hover').id='more';
	document.getElementById('more_image_hover').id='more_image';
	document.getElementById('more_text_hover').id='more_text';
}
/*
function GetXmlHttpObject()

{
	var xmlHttp=null;
	try
	{
		// Firefox, Opera 8.0+, Safari
		xmlHttp=new XMLHttpRequest();
	}
	catch (e)
	{
		//Internet Explorer
		try
		{
			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{
			xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	return xmlHttp;
}

*/
var userId;
var var_choice
var id;
/*function addToFields(divId,friendId,uid,choice)
{

/*	e = event.target;
	if(!e)
	{
		e = event.srcElement;
	} 
//	alert(divId.id);
	id = divId;
	xmlHttp = GetXmlHttpObject();
	//alert('Hello');
	if (xmlHttp == null)
	{
	      alert ("Browser does not support HTTP Request");
	      return;
	}
    	var url = "addToFields.php";
	//	var var_choice = choice;
	//url = url + "?friendId="+friendId+"&uid="+uid+"&choice="+choice;	
	
	document.getElementById(id).style.display = 'none';
	xmlHttp.onreadystatechange = statusUpdate;
        xmlHttp.open("POST",url,true);
        xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlHttp.send("friendId="+friendId+"&uid="+uid+"&choice="+choice);
	
	
	
}

function statusUpdate()
{
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{
		alert('hello');
	//	document.getElementById(id).style.display = 'none';		
	}

}
*/
function loadContent(page_name)
{
	xmlHttp = GetXmlHttpObject();
        //alert('Hello');
	if (xmlHttp == null)
	{
	      alert ("Browser does not support HTTP Request");
	      return;
	}
	var url = page_name+".php";
	
	//var value = document.getElementById('loadcontent').style.display;
	document.getElementById('loadcontent').style.display = 'block';
	document.getElementById('content').style.display = 'none';
	//var_choice = choice;
	//url = url + "?friendId="+friendId+"&uid="+uid+"&choice="+choice;	
	
	xmlHttp.onreadystatechange = displayResponse;
        xmlHttp.open("POST",url,true);
	xmlHttp.send(null);
	

}
function displayResponse()
{
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
  	{
		//alert(xmlHttp.responseText);
		//document.getElementById(e.id).setAttribute("disabled", "disabled");		
        	//var value = document.getElementById(e.id).value;
	document.getElementById('loadcontent').style.display = 'none';
	document.getElementById('content').style.display = 'block';
	document.getElementById('content').innerHTML = xmlHttp.responseText;
	
	}
}
function min(a,b)
{
	if (a < b)
		return a;
	else 
		return b;
}


