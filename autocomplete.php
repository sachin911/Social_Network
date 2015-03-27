
<html>
   <head>
        <script type="text/javascript"
        src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <script type="text/javascript"
        src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
        <link rel="stylesheet" type="text/css"
        href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" />
 
       <!-- 
 <script type="text/javascript">
                $(document).ready(function(){
                    $("#response").autocomplete({
                        source:'search.php',
                        minLength:1
                        
                        
                    });
                });
        </script>
 -->
 
 <script type="text/javascript">
 function findmatch() {
 if(window.XMLHttpRequest) {
 
 		xmlhttp = new XMLHttpRequest();
 		} else {
 		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
 		}
 		
 		xmlhttp.onreadystatechange = function() {
 		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
 		document.getElementById('results').innerHTML = xmlhttp.responseText;
 		
 		}
 		}
 		
 		xmlhttp.open('GET','search.inc.php?search_text='+document.search.search_text.value,true);
 		xmlhttp.send();
 }
 
 
 </script>
   </head>
 
   <body>
 
      <form id ="search" method="GET" name="search" >
             Name : <input type="text" name="search_text" onkeyup="findmatch();"/>
      </form>

 
 <div id="results"> hello </div>		

 
<!-- 
<html>
<head>
<link rel="stylesheet" type="text/css" href="jquery/development-bundle/themes/base/jquery.ui.all.css" />
<script type="text/javascript" src="jquery/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="jquery/development-bundle/ui/jquery.ui.core.js"></script>
<script type="text/javascript" src="jquery/development-bundle/ui/jquery.ui.widget.js"></script>
<script type="text/javascript" src="jquery/development-bundle/ui/jquery.ui.position.js"></script>
<script type="text/javascript" src="jquery/development-bundle/ui/jquery.ui.autocomplete.js"></script>
<script  type="text/javascript">

$(function() {
		
		$( "#go" ).autocomplete({
			source: function( request, response )
				{
				
				$.ajax( {
					dataType: "json",
					url: "http://localhost:8983/solr/select",
					data: {
						q: "go_definition:"+request.term,
						wt:"xslt",
						tr:"solr2json.xsl",
						},
					success:  response
					});
				return false;
				}
		});
	});

</script>
</head>
<body>
<form>
<div class="ui-widget">
<label for="go">Search:</label><input id="go"/>
</div>
</form>
</body>
</html>
 -->