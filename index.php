<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<title>Ideal Network Flow Demo</title>

		<link rel="stylesheet" href="Master.css">
		<link rel="stylesheet" href="Editor.css">

		<!-- ONLINE SCRIPTS -->
		<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script type="text/javascript" src="https://d3js.org/d3.v3.min.js"></script>
		<script type="text/javascript" src="http://d3js.org/d3.geo.tile.v0.min.js"></script>

		<script type="text/javascript" src="http://blueimp.github.io/jQuery-File-Upload/js/vendor/jquery.ui.widget.js"></script>
		<script type="text/javascript" src="https://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
		<script type="text/javascript" src="https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
		<script type="text/javascript" src="http://blueimp.github.io/jQuery-File-Upload/js/jquery.iframe-transport.js"></script>
		<script type="text/javascript" src="http://blueimp.github.io/jQuery-File-Upload/js/jquery.fileupload.js"></script>
		<script type="text/javascript" src="http://blueimp.github.io/jQuery-File-Upload/js/jquery.fileupload-process.js"></script>
		<script type="text/javascript" src="http://blueimp.github.io/jQuery-File-Upload/js/jquery.fileupload-validate.js"></script>
		<script type="text/javascript" src="http://blueimp.github.io/jQuery-File-Upload/js/jquery.fileupload-image.js"></script>

		<script type="text/javascript" src="https://cdn.rawgit.com/eligrey/FileSaver.js/5733e40e5af936eb3f48554cf6a8a7075d71d18a/FileSaver.js"></script> -->

		<!-- LOCAL SCRIPTS -->
		<script type="text/javascript" src="/Scripts/Public/jQuery/jquery_3.2.1.min.js"></script>
		<script type="text/javascript" src="/Scripts/Public/d3/d3.v3.min.js"></script>
		<script type="text/javascript" src="/Scripts/Public/d3/d3.geo.tile.v0.min.js"></script>

		<script type="text/javascript" src="/Scripts/Public/jQuery File Upload/vendor/jquery.ui.widget.js"></script>
		<script type="text/javascript" src="/Scripts/Public/jQuery File Upload/load-image.all.min.js"></script>
		<script type="text/javascript" src="/Scripts/Public/jQuery File Upload/canvas-to-blob.min.js"></script>
		<script type="text/javascript" src="/Scripts/Public/jQuery File Upload/jquery.iframe-transport.js"></script>
		<script type="text/javascript" src="/Scripts/Public/jQuery File Upload/jquery.fileupload.js"></script>
		<script type="text/javascript" src="/Scripts/Public/jQuery File Upload/jquery.fileupload-process.js"></script>
		<script type="text/javascript" src="/Scripts/Public/jQuery File Upload/jquery.fileupload-validate.js"></script>
		<script type="text/javascript" src="/Scripts/Public/jQuery File Upload/jquery.fileupload-image.js"></script>

		<script type="text/javascript" src="/Scripts/Public/FileSaver/FileSaver.js"></script>

	</head>
	<body>
		<div class="top">
			<div class="topLeft"></div>
			<div class="topCenter"></div>
			<div class="topRight"></div>
		</div>
		<div class="middle">
			<div class="middleLeft"></div>
			<div class="middleCenter">
				<?php include("./Shared/SVGDefinitions.php"); ?>
					<div id="Modals">
						<div id="LoadingBarDiv" style="display: none;">
							<div id="LoadingBarCloseDiv" style="visibility: hidden;"> <!-- NOTE: Show close button if needed -->
								<div id="LoadingBarCloseButton" class="toolBarButton" title="Close">
									<a href="javascript:void(0);" onclick="LoadingBarCloseButton_Clicked();">
										<svg class="toolBarButtonIcon"><use xlink:href="#icon-close2"></use></svg>
									</a>
								</div>
							</div>
							<div id="LoadingBarImage">
								<svg class="spinner"><use xlink:href="#icon-loading"></use></svg>
							</div>
						</div>
					</div>
					<div id="Header">
					<div class="top">
						<div class="topLeft"></div>
						<div class="topCenter"></div>
						<div class="topRight"></div>
					</div>
					<div class="middle">
						<div class="middleLeft"></div>
						<div class="middleCenter">
							<div id="LogoDiv"><span>Ideal Flow Network</span></div>
							<div id="NavigationBarDiv"></div>
							<div id="ProgressBarDiv" style="display: none;">
								<!-- <span id="ProgressBarText">Uploading...</span> -->
								<div id="ProgressBar" style="width: 0;"></div>
							</div>
						</div>
						<div class="middleRight"></div>
					</div>
					<div class="bottom">
						<div class="bottomLeft"></div>
						<div class="bottomCenter"></div>
						<div class="bottomRight"></div>
					</div>
				</div>
				<div id="Content">
					<div class="top">
						<div class="topLeft"></div>
						<div class="topCenter"></div>
						<div class="topRight"></div>
					</div>
					<div class="middle">
						<div class="middleLeft"></div>
						<div class="middleCenter">
							<div id="ErrorDiv"></div>
							<?php include("./Shared/QuickGuide.php"); ?>
							<div id="ToolBarDiv">
								<div id="ToggleInstructionsButton" class="toolBarButton" title="Show Instructions">
									<a href="javascript:void(0);" onclick="ToggleInstructionsButton_Clicked();">
										<svg class="toolBarButtonIcon"><use xlink:href="#icon-help2"></use></svg>
										<span>Show Instructions</span>
									</a>
								</div>
								<div id="AddNetworkButton" class="toolBarButton" title="Add Network">
									<a href="javascript:void(0);" onclick="AddNetworkButton_Clicked();">
										<svg class="toolBarButtonIcon"><use xlink:href="#icon-network"></use></svg>
										<span>Add Network</span>
									</a>
								</div>
								<div id="AddNodeButton" class="toolBarButton" title="Add Nodes">
									<a href="javascript:void(0);" onclick="AddNodeButton_Clicked();">
										<svg class="toolBarButtonIcon"><use xlink:href="#icon-location"></use></svg>
										<span>Add Nodes</span>
									</a>
								</div>
								<div id="AddLinkButton" class="toolBarButton" title="Add Links">
									<a href="javascript:void(0);" onclick="AddLinkButton_Clicked();">
										<svg class="toolBarButtonIcon"><use xlink:href="#icon-line"></use></svg>
										<span>Add Links</span>
									</a>
								</div>
								<!-- TODO: Add hide nodes button -->
								<!-- <div id="ToggleNodeButton" class="toolBarButton" title="Hide Nodes">
									<a href="javascript:void(0);" onclick="ToggleNodeButton_Clicked();">
										<svg class="toolBarButtonIcon"><use xlink:href="#icon-location"></use></svg>
										<span>Add Nodes</span>
									</a>
								</div> -->
								<div id="SelectButton" class="toolBarButton" title="Select">
									<a href="javascript:void(0);" onclick="SelectButton_Clicked();">
										<svg class="toolBarButtonIcon"><use xlink:href="#icon-select"></use></svg>
										<span>Select</span>
									</a>
								</div>

								<!-- TODO: Determine if specific select all still needed -->
								<!-- <div id="SelectAllNodesButton" class="toolBarButton" title="Select All Nodes" style="display: none;"><a href="javascript:void(0);" onclick="SelectAllNodesButton_Clicked();">Select All Nodes</a></div> -->
								<!-- <div id="SelectAllLinksButton" class="toolBarButton" style="display: none;"><a href="javascript:void(0);" onclick="SelectAllLinksButton_Clicked();">Select All Links</a></div> -->
								<div id="SelectAllButton" class="toolBarButton" title="Select All" style="display: none;">
									<a href="javascript:void(0);" onclick="SelectAllButton_Clicked();">
										<svg class="toolBarButtonIcon"><use xlink:href="#icon-select-all"></use></svg>
										<span>Select All</span>
									</a>
								</div>
								<div id="DeselectAllButton" class="toolBarButton" title="Deselect All" style="display: none;">
									<a href="javascript:void(0);" onclick="DeselectAllButton_Clicked();">
										<svg class="toolBarButtonIcon"><use xlink:href="#icon-deselect"></use></svg>
										<span>Deselect All</span>
									</a>
								</div>
								<div id="DeleteButton" class="toolBarButton" title="Delete" style="display: none;">
									<a href="javascript:void(0);" onclick="DeleteButton_Clicked();">
										<svg class="toolBarButtonIcon"><use xlink:href="#icon-trash"></use></svg>
										<span>Delete</span>
									</a>
								</div>

								<div id="CenterCanvasButton" class="toolBarButton" title="Center Canvas">
									<a href="javascript:void(0);" onclick="CenterCanvasButton_Clicked();">
										<svg class="toolBarButtonIcon"><use xlink:href="#icon-center"></use></svg>
										<span>Center Canvas</span>
									</a>
								</div>
								<div id="SaveProjectButton" class="toolBarButton" title="Save Project">
									<a href="javascript:void(0);" onclick="SaveProjectButton_Clicked();">
										<svg class="toolBarButtonIcon"><use xlink:href="#icon-download"></use></svg>
										<span>Save Project</span>
									</a>
								</div>
								<div id="LoadProjectButton" class="toolBarButton fileUploaderDiv" title="Load Project">
									<a href="#">
										<input id="FileUpload" class="fileUploader" type="file" name="files[]"/>
										<svg class="toolBarButtonIcon"><use xlink:href="#icon-upload"></use></svg>
										<span>Load Project</span>
									</a>
								</div>
								<div id="DoneButton" class="toolBarButton" title="Done" style="display: none;">
									<a href="javascript:void(0);" onclick="DoneButton_Clicked();">
										<svg class="toolBarButtonIcon"><use xlink:href="#icon-check"></use></svg>
										<span>Done</span>
									</a>
								</div>
							</div>
							<div id="D3CanvasDiv"></div>
						</div>
						<div class="middleRight"></div>
					</div>
					<div class="bottom">
						<div class="bottomLeft"></div>
						<div class="bottomCenter"></div>
						<div class="bottomRight"></div>
					</div></div>
				<div id="Footer">
					<div class="top">
						<div class="topLeft"></div>
						<div class="topCenter"></div>
						<div class="topRight"></div>
					</div>
					<div class="middle">
						<div class="middleLeft"></div>
						<div class="middleCenter">
							<div id="FooterCopyrightDiv">
								<span>© Ateneo Pedestrian and Traffic Computing Laboratory</span>
								<!-- <span>Developed by Rina Cabral</span> -->
							</div>
						</div>
						<div class="middleRight"></div>
					</div>
					<div class="bottom">
						<div class="bottomLeft"></div>
						<div class="bottomCenter"></div>
						<div class="bottomRight"></div>
					</div>
				</div>
			</div>
			<div class="middleRight"></div>
		</div>
		<div class="bottom">
			<div class="bottomLeft"></div>
			<div class="bottomCenter"></div>
			<div class="bottomRight"></div>
		</div>

		<script type="text/javascript" src="/dataproperties.js"></script>
		<script type="text/javascript" src="/index.js"></script>
	</body>
</html>
