// Depends on https://developers.google.com/google-apps/spreadsheets/
/*
https://spreadsheets.google.com/feeds/cells/0AianULedsJ4SdExJSkxRQkc2VjFxbXN4ZTFRVTVyZFE/1/public/basic?alt=json
https://spreadsheets.google.com/feeds/list/0AianULedsJ4SdExJSkxRQkc2VjFxbXN4ZTFRVTVyZFE/1/public/full

var XMLData = "<entry xmlns='http://www.w3.org/2005/Atom'\
xmlns:gsx='http://schemas.google.com/spreadsheets/2006/extended'>\
<gsx:hours>1</gsx:hours>\
<gsx:ipm>1</gsx:ipm>\
<gsx:items>60</gsx:items>\
<gsx:name>Elizabeth Bennet</gsx:name>\
</entry>";

https://spreadsheets.google.com/feeds/list/0AianULedsJ4SdExJSkxRQkc2VjFxbXN4ZTFRVTVyZFE/1/public/basic

jQuery.post("https://spreadsheets.google.com/feeds/list/0AianULedsJ4SdExJSkxRQkc2VjFxbXN4ZTFRVTVyZFE/1/public/full",
  XMLData,
  function(data) {
   console.log(data)
  },
  "xml"
);

jQuery.ajax({
        url: "https://spreadsheets.google.com/feeds/list/0AianULedsJ4SdExJSkxRQkc2VjFxbXN4ZTFRVTVyZFE/1/public/full",
        type: "POST",
        contentType: 'application/atom+xml',
        processData: false,
        data: XMLData
      }
);
*/
function gradient(id, level)
{
    var box = document.getElementById(id);
    box.style.opacity = level;
    box.style.MozOpacity = level;
    box.style.KhtmlOpacity = level;
    box.style.filter = "alpha(opacity=" + level * 100 + ")";
    box.style.display="block";
    return;
}


function fadein(id) 
{
    var level = 0;
    while(level <= 1)
    {
        setTimeout( "gradient('" + id + "'," + level + ")", (level* 1000) + 10);
        level += 0.01;
    }
}


// Open the lightbox


function openbox(formtitle, fadin)
{
  var box = document.getElementById('box'); 
  document.getElementById('shadowing').style.display='block';

  var btitle = document.getElementById('boxtitle');
  btitle.innerHTML = formtitle;
  
  if(fadin)
  {
     gradient("box", 0);
     fadein("box");
  }
  else
  {     
    box.style.display='block';
  }     
}


// Close the lightbox

function closebox()
{
   document.getElementById('box').style.display='none';
   document.getElementById('shadowing').style.display='none';
}


// Validate the registration form

function temp() {
  var first = document.forms["registration"]["first"].value;
  var last = document.forms["registration"]["last"].value;
  var email = document.forms["registration"]["email"].value;

  if (first==null || first=="") {
    document.getElementById("error_msg").innerHTML = "Missing First Name";
    return false;
  }
  if (last==null || last=="") {
    document.getElementById("error_msg").innerHTML = "Missing Last Name";
    return false;
  }
  if (email==null || email=="") {
    document.getElementById("error_msg").innerHTML = "Missing Email";
    return false;
  }
}
