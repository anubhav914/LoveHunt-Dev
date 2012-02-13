var tagged_friends_count = 0;

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

function fade_out_callback(counter, count)
{
	tagged_friends_count++;
	var new_counter = Number(counter) + Number(5);
	//alert('#friend' +  new_counter);

	if(new_counter < count)
	{
		$('#friend' + new_counter).css('display', 'block').hide().fadeIn(2000);
		//$('#friend' + new_counter).show('slow');		
	}
	if(tagged_friends_count == count)
		alert("there are no more elemetns to tag");
}


function addToFields(counter, friendId, friendName, my_id, choice, count)
{
	$('#friend' + counter).fadeOut(800, fade_out_callback(counter, count));
	xmlHttp = GetXmlHttpObject();
	if (xmlHttp == null)
	{
	      alert ("Browser does not support HTTP Request");
	      return;
	}
	//alert(xmlHttp);
    var url = "addToFields.php";

	xmlHttp.onreadystatechange = statusUpdate;
    xmlHttp.open("POST",url,true);
    xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlHttp.send("friendId="+friendId+"&my_id="+my_id+"&choice="+choice+"&friendName="+friendName);

}


function statusUpdate()
{
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{
		//alert(xmlHttp.responseText);
	//	document.getElementById(id).style.display = 'none';		
	}

}

