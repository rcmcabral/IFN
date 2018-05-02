<!DOCTYPE html>
<html>
  	<head>
    	<meta charset="utf-8">
    	<title>Data Uploader</title>
    	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 	    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	  	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	  	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	</head>

  	<body>

  		<br/><br/>
  		<button type="button" onclick="ParseNodeData()">Parse CSV Node Data</button>
  		<button type="button" onclick="DeleteAllNodes()">Delete All Nodes</button>

		<br/><br/>
  		<button type="button" onclick="ParseRoadLinkData()">Parse CSV Road Link Data</button>
  		<button type="button" onclick="DeleteAllRoadLinks()">Delete All Road Links</button>

  		<br/><br/>
  		<button type="button" onclick="GenerateJSONData()">Generate JSON</button>

  		<div id="OutputBox"> </div>

		<script src="http://blueimp.github.io/jQuery-File-Upload/js/vendor/jquery.ui.widget.js"></script>
		<script src="http://blueimp.github.io/jQuery-File-Upload/js/jquery.iframe-transport.js"></script>
  		<script src="http://blueimp.github.io/jQuery-File-Upload/js/jquery.fileupload.js"></script>

		<script>

			$( document ).ready(function() {
				PageLoaded();
			});

			function PageLoaded() {

				// $("#NodeDataFileInput").on("change", FileInputChanged);
				$('#NodeDataFileInput').fileupload({
			        dataType: 'json',
			        url: "/Trial for Ideal Network/Uploader/UploadHandler.php",
			        done: function (e, data) {
			            $.each(data.result.files, function (index, file) {
			                // $('<p/>').text(file.name).appendTo(document.body);
			                console.log(file.name);
			            });
			        }
			    });
			}

			function FileInputChanged() {
				console.log("File Input Changed");
				console.log($("#NodeDataFileInput").val());

				input = document.getElementById('NodeDataFileInput');
				file = input.files[0];
			    fr = new FileReader();
			    fr.onload = FileInputLoaded;
			    //fr.readAsText(file);
			    fr.readAsDataURL(file);
			}

			function FileInputLoaded() {
				console.log("FILE LOADED");
			}

			function ParseNodeData() {
				//TODO: Pass uploaded file url
				var filePath = "C:/Users/Admin/Documents/Cabral/Metro Manila Graph/nodes-nb-mtpstu.txt";

				jQuery.ajax({
					type: "POST",
					// url: "Services/DataServices.php",
          url: "Scripts/Private/DataServices.php",
					dataType: "json",
					data: { functionName: "NodeManager.ImportDataFromCSV" },
					success: function (obj, textStatus) {

            console.log(obj);
						if (obj.error) {
							console.log("ERROR: " + obj.error);
							return;
						}

						alert("Inserted " + obj.result + " number of rows.");
					},
					error: function (xhr, textStatus, errorThrown) {
            console.log(xhr);
						console.log("ERROR: " + errorThrown);
					}
				});
			}

			function DeleteAllNodes() {
				jQuery.ajax({
					type: "POST",
					// url: "Services/DataServices.php",
          url: "Scripts/Private/DataServices.php",
					dataType: "json",
					data: { functionName: "NodeManager.DeleteAllNodes" },
					success: function (obj, textStatus) {
						if (obj.error) {
							console.log("ERROR: " + obj.error);
							return;
						}

						alert("Deleted " + obj.result + " number of rows.");
					},
					error: function (xhr, textStatus, errorThrown) {
            console.log(xhr);
						console.log("ERROR: " + errorThrown);
					}
				});
			}

			function ParseRoadLinkData() {
				//TODO: Pass uploaded file url
				var filePath = "C:/Users/Admin/Documents/Cabral/Metro Manila Graph/roadLinks-nb-mtpstu.txt";

				jQuery.ajax({
					type: "POST",
					// url: "Services/DataServices.php",
          url: "Scripts/Private/DataServices.php",
					dataType: "json",
					data: { functionName: "RoadLinkManager.ImportDataFromCSV" },
					success: function (obj, textStatus) {
						if (obj.error) {
							console.log("ERROR: " + obj.error);
							return;
						}

						alert("Inserted " + obj.result + " number of rows.");
					},
					error: function (xhr, textStatus, errorThrown) {
            console.log(xhr);
						console.log("ERROR: " + errorThrown);
					}
				});
			}

			function DeleteAllRoadLinks() {
				jQuery.ajax({
					type: "POST",
					// url: "Services/DataServices.php",
          url: "Scripts/Private/DataServices.php",
					dataType: "json",
					data: { functionName: "RoadLinkManager.DeleteAllRoadLinks" },
					success: function (obj, textStatus) {
						if (obj.error) {
							console.log("ERROR: " + obj.error);
							return;
						}

						alert("Deleted " + obj.result + " number of rows.");
					},
					error: function (xhr, textStatus, errorThrown) {
            console.log(xhr);
						console.log("ERROR: " + errorThrown);
					}
				});
			}

			function GenerateJSONData() {

				$("#OutputBox").html("Generating JSON file...");

				jQuery.ajax({
					type: "POST",
					// url: "Services/DataServices.php",
          url: "Scripts/Private/DataServices.php",
					dataType: "json",
					data: { functionName: "GraphDataManager.GenerateJSONData" },
					success: function (obj, textStatus) {
						if (obj.error) {
							console.log("ERROR: " + obj.error);
							return;
						}

						$("#OutputBox").html("JSON file successfully created");
					},
					error: function (xhr, textStatus, errorThrown) {
            console.log(xhr);
						console.log("ERROR: " + errorThrown);
					}
				});
			}

			function GenerateNodesJSONData() {
				jQuery.ajax({
					type: "POST",
					// url: "Services/DataServices.php",
          url: "Scripts/Private/DataServices.php",
					dataType: "json",
					data: { functionName: "GraphDataManager.GenerateNodesJSONData" },
					success: function (obj, textStatus) {
						if (obj.error) {
							console.log("ERROR: " + obj.error);
							return;
						}

						// $("#OutputBox").text(obj.result);
						console.log("Done");
					},
					error: function (xhr, textStatus, errorThrown) {
            console.log(xhr);
						console.log("ERROR: " + errorThrown);
					}
				});
			}

			function GenerateRoadLinksJSONData() {
				jQuery.ajax({
					type: "POST",
					// url: "Services/DataServices.php",
          url: "Scripts/Private/DataServices.php",
					dataType: "json",
					data: { functionName: "GraphDataManager.GenerateRoadLinksJSONData" },
					success: function (obj, textStatus) {
						if (obj.error) {
							console.log("ERROR: " + obj.error);
							return;
						}

						// $("#OutputBox").text(obj.result);
						console.log("Done");
					},
					error: function (xhr, textStatus, errorThrown) {
            console.log(xhr);
						console.log("ERROR: " + errorThrown);
					}
				});
			}

		</script>
	</body>
</html>
