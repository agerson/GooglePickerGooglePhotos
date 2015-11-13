<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Google Picker Example</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script type="text/javascript">

      // The Browser API key obtained from the Google Developers Console.
      var developerKey = 'xxx';

      // The Client ID obtained from the Google Developers Console. Replace with your own Client ID.
      var clientId = "xxx"
      // Scope to use to access user's photos.
     var scope = ['https://www.googleapis.com/auth/photos'];


    // Replace with your own App ID. (Its the first number in your Client ID)
    var appId = "xxx";

    // Scope to use to access user's Drive items.
    //var scope = ['https://www.googleapis.com/auth/drive'];

    var pickerApiLoaded = false;
    var oauthToken;

    // Use the Google API Loader script to load the google.picker script.
    function loadPicker() {
      gapi.load('auth', {'callback': onAuthApiLoad});
      gapi.load('picker', {'callback': onPickerApiLoad});
    }

    function onAuthApiLoad() {
      window.gapi.auth.authorize(
          {
            'client_id': clientId,
            'scope': scope,
            'immediate': false
          },
          handleAuthResult);
    }

    function onPickerApiLoad() {
      pickerApiLoaded = true;
      createPicker();
    }

    function handleAuthResult(authResult) {
      if (authResult && !authResult.error) {
        oauthToken = authResult.access_token;
        createPicker();
      }
    }

    // Create and render a Picker object for searching images.
    function createPicker() {
      if (pickerApiLoaded && oauthToken) {
        var view = new google.picker.View(google.picker.ViewId.PHOTOS);
        view.setMimeTypes("image/png,image/jpeg,image/jpg");
        var picker = new google.picker.PickerBuilder()
            .enableFeature(google.picker.Feature.NAV_HIDDEN)
            .enableFeature(google.picker.Feature.MULTISELECT_ENABLED)
            .setAppId(appId)
            .setOAuthToken(oauthToken)
            .addView(view)
            .setOrigin(window.location.protocol + '//' + window.location.host)
            .addView(new google.picker.DocsUploadView())
            .setDeveloperKey(developerKey)
            .setCallback(pickerCallback)
            .build();
         picker.setVisible(true);
      }
    }

    // A simple callback implementation.
    function pickerCallback(data) {
      if (data.action == google.picker.Action.PICKED) {
      		var urls = '';
      		var urlsArray = [];
      		for (var i = 0; i < data.docs.length; i++) {
        	console.log(data.docs[i].thumbnails[3].url);
        	urls = urls +  data.docs[i].thumbnails[3].url + '|';
        	urlsArray[i] =  data.docs[i].thumbnails[3].url;
    	}
    	console.log(urlsArray);
    	//window.location = 'http://www2.cgps.org/gp2.php?urls=' + urls;
    	elements = urlsArray.join(',')
		$.post('/results.php', {elements: elements})
		// elements contains a string with multiple comma-separated URLs
		alert(elements);
    }
  }

  function pickerCallback3(data) {
    if (data.action == google.picker.Action.PICKED) {
      var fileId = data.docs[0].id;
      alert('The user selected: ' + fileId);
      document.getElementById('googleFileId').value = fileId;
      var name = data.docs[0].name;
      var url = data.docs[0].url;
      var accessToken = gapi.auth.getToken().access_token;
      var request = new XMLHttpRequest();
      //request.open('GET', 'https://www.googleapis.com/drive/v2/files/' + fileId);
      //request.open('GET', 'https://picasaweb.google.com/data/entry/api/user/agerson@cgps.org/photoid/' + fileId);
      
      //request.setRequestHeader('Authorization', 'Bearer ' + accessToken);
      //request.addEventListener('load', function() {
        //  var item = JSON.parse(request.responseText);
          //window.open(item.webContentLink,"_self"); //Download file in Client Side 
      //});
      //request.send();
    }
    var message = 'File ID of choosen file : ' + fileId;
   message = 'https://picasaweb.google.com/data/entry/api/user/agerson@cgps.org/photoid/' + fileId;
    document.getElementById('result').innerHTML = message;
}




    </script>
  </head>
  <body>
    <div id="googleFileId"></div>
    <div id="result"></div>

    <!-- The Google API Loader script. -->
    <script type="text/javascript" src="https://apis.google.com/js/api.js?onload=loadPicker"></script>
  </body>
</html>