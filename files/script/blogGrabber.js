function loadXMLDoc()
{
	var AOkey = "OAuthKeyFake";
	var blogid = "7812611771632514541";
	var html = "https://www.googleapis.com/blogger/v3/blogs/"+blogid+"/posts?key=" + AOkey;
	var request = new XMLHttpRequest();
	request.onreadystatechange = function()
	{
		if(this.readyState == 4 && this.status == 200)
		{
			var txt = "";
			var xmlDoc = JSON.parse(this.responseText);
			var entries = xmlDoc.items;
			for(var i = 0; i < entries.length; i++)
			{
				txt += entries[i].content + "<hr />";
			}
			document.getElementById("posts").innerHTML = txt;
		}
	};
	request.open("GET", html, true);
	request.send();
}
function readXML(xml)
{
	var txt = "";
	var xmlDoc = JSON.parse(xml.responseText);
	var entries = xmlDoc.items;
	for(var i = 0; i < entries.length; i++)
	{
		txt += entries[i].content + "<hr />";
	}
	document.getElementById("posts").innerHTML = txt;
}
loadXMLDoc();