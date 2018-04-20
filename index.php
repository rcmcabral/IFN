<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<title>Ideal Network Flow Demo</title>

		<link rel="stylesheet" href="Master.css">
		<link rel="stylesheet" href="Editor.css">

		<!-- ONLINE SCRIPTS -->
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
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

		<script type="text/javascript" src="https://cdn.rawgit.com/eligrey/FileSaver.js/5733e40e5af936eb3f48554cf6a8a7075d71d18a/FileSaver.js"></script>

		<!-- LOCAL SCRIPTS -->
		<!-- <script type="text/javascript" src="/TempScripts/Public/jQuery/jquery_3.2.1.min.js"></script>
		<script type="text/javascript" src="/TempScripts/Public/d3/d3.v3.min.js"></script>
		<script type="text/javascript" src="/TempScripts/Public/d3/d3.geo.tile.v0.min.js"></script>

		<script type="text/javascript" src="/TempScripts/Public/jQuery File Upload/vendor/jquery.ui.widget.js"></script>
		<script type="text/javascript" src="/TempScripts/Public/jQuery File Upload/load-image.all.min.js"></script>
		<script type="text/javascript" src="/TempScripts/Public/jQuery File Upload/canvas-to-blob.min.js"></script>
		<script type="text/javascript" src="/TempScripts/Public/jQuery File Upload/jquery.iframe-transport.js"></script>
		<script type="text/javascript" src="/TempScripts/Public/jQuery File Upload/jquery.fileupload.js"></script>
		<script type="text/javascript" src="/TempScripts/Public/jQuery File Upload/jquery.fileupload-process.js"></script>
		<script type="text/javascript" src="/TempScripts/Public/jQuery File Upload/jquery.fileupload-validate.js"></script>
		<script type="text/javascript" src="/TempScripts/Public/jQuery File Upload/jquery.fileupload-image.js"></script>

		<script type="text/javascript" src="/TempScripts/Public/FileSaver/FileSaver.js"></script> -->

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
							<?php include("./Shared/QuickGuide.php"); ?>
							<div id="ToolBarDiv">
								<div id="ToggleInstructionsButton" class="toolBarButton" title="Show Instructions">
									<a href="javascript:void(0);" onclick="ToggleInstructionsButton_Clicked();">
										<svg class="toolBarButtonIcon"><use xlink:href="#icon-help2"></use></svg>
										<span>Show Instructions</span>
									</a>
								</div>
								<div id="AddCategoryButton" class="toolBarButton" title="Add Network">
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

		<script>
			$(document).ready(function() {
			    PageLoaded();
			});

			var width = $("#D3CanvasDiv").width();
			var height = $("#D3CanvasDiv").height();

			var networks = [];
			var nodes = [];
			var links = [];

			var sourceNode;
			var selectedNodes = [];
			var selectedLinks = [];
			var adjacentNodes = [];
			var adjacentLinks = [];
			var selectedNetworks = [];
			var hiddenNetworks = [];


			//DATA NAMING
			var lastNodeId = -1;
			var lastCategoryId = 0;
			var prefixNetwork = "Network";
			var prefixNode = "IFN_No_";
			var prefixLink = "IFN_Li_";


			//MODE FLAGS
			var selectEnabled = false;
			var zoomEnabled = true;
			var zoomRectEnabled = false;


			//SVG ELEMENTS
			var svg;
			var canvas;
			var layer;
			var paths;
			var circles;
			var dragLine;
			var map;
			var canvasCenter;
			var coordinatesText;


			//SVG ELEMENTS - Styles and Measurements
			var radius = 4;
			var sourcePadding = 12; 				//Gap between node and start of path
			var targetPadding = 12; 				//Gap between node and end of path
			var distanceFromCenter = 10; 		//Gap between directional paths (<=>)
			var defaultPathStrokeWidth = 3; //Default path width
			var maxPathStrokeWidth = 6;

			var networkBox;
			var networkBoxWidth = 200;
			var networkBoxHeight = 200;
			var networkBoxX = width - networkBoxWidth;
			var networkBoxY = 0;
			var networkBoxIconWidth = 16;
			var networkBoxIconHeight = 16;
			var networkMaxCount = 10; //NOTE: Limited due to svg space issues

			//NOTE: Colors should match networkMaxCount
			var networkColors = [ "#1f77b4", "#ff7f0e", "#2ca02c", "#d62728", "#9467bd", "#8c564b", "#e377c2", "#7f7f7f", "#bcbd22", "#17becf" ];
			var networkStyles = [];

			var infoBox;
			var infoBoxWidth = 200;
			var infoBoxHeight = height - networkBoxHeight;
			var infoBoxX = width - infoBoxWidth;
			var infoBoxY = networkBoxY + networkBoxHeight; //place under networkBox
			var infoPropertyMaxCount = 25; //NOTE: Limited due to svg space issues

			var infoBoxDefaultEditValue = "*add value*";
			var infoBoxFontSize = 12;
			var infoBoxTextBox_TopPadding = 4;
			var infoBoxLabel_LeftPadding = 4;
			var infoBoxValue_LeftPadding = 75;


			//ZOOM ELEMENTS
			var zoomRect;
			var zoomRectCoordinates = [0, 0];
			var zoomRectDimensions = [0, 0];


			//MAP ELEMENTS
			var zoomMax = (1 << 19);
			var zoomMin = (1 << 26); //NOTE: Max value 26. 27 and above makes the paths disappear

			var tile = d3.geo.tile()
				.size([width, height]);

			var projection = d3.geo.mercator()
				.center([121.02722,14.57043])				//Manila center
				.scale(zoomMin / 2 / Math.PI)
				.translate([width / 2, height / 2]);

			//TODO: Verify difference from projection
			//Projection for calculating coordinates
			var invProjection = d3.geo.mercator();

			//TODO: Verify need, in sample (http://bl.ocks.org/emeeks/6147081)
			// projection
			//     .scale(1 / 2 / Math.PI)
			//     .translate([0, 0]);


			//EVENTS
			var zoomEvent = d3.behavior.zoom()
				.scale(projection.scale() * 2 * Math.PI)
			    .scaleExtent([zoomMax, zoomMin])
			    .translate(projection([0,0]))
				.on("zoomstart", ZoomStart)
				.on("zoom", Zooming)
				.on("zoomend", ZoomEnd);

			var initialZoomTranslate = zoomEvent.translate();
			var initialZoomScale = zoomEvent.scale();
			var currentZoomScale = zoomEvent.scale();
			var previousTranslation = [0,0];
			var currentTranslation = [0,0];
			var previousScale = 1;
			var currentScale = 1;

			var dragMoveEvent = d3.behavior.drag()
					.origin(function(d) { return d; })
					.on("dragstart", DragStart)
					.on("drag", MoveSelection)
					.on("dragend", DragEnd);

			var dragNullEvent = d3.behavior.drag()
					.origin(function(d) { return d; })
					.on("dragstart", null)
					.on("drag", null)
					.on("dragend", null);



			function PageLoaded() {

				InitializeUploader();
				InitializeCanvas();
				InitializeNetworkBox();
				InitializeInfoBox();
				RefreshNetworksBox();

				window.addEventListener("resize", ResizeWindow);

				//Add a starting network
				if (networks.length == 0) {
					InsertNewNetwork();
				}
			}


			/************
			UI/BUTTON EVENTS
			*************/

			function ToggleInstructionsButton_Clicked() {

				var isVisible = ($("#InstructionDiv").css("display") !== "none");

				if (isVisible) {
					$("#ToggleInstructionsButton").prop("title", "Show Instructions");
					$("#ToggleInstructionsButton > a > span").text("Show Instructions");
					$("#ToggleInstructionsButton .toolBarButtonIcon > use").attr("xlink:href", "#icon-help2");
					$("#InstructionDiv").hide();
				}
				else {
					$("#ToggleInstructionsButton").prop("title", "Hide Instructions");
					$("#ToggleInstructionsButton > a > span").text("Hide Instructions");
					$("#ToggleInstructionsButton .toolBarButtonIcon > use").attr("xlink:href", "#icon-help-alt");
					$("#InstructionDiv").show();
				}

				ResizeWindow();
			}

			function AddNetworkButton_Clicked() {
				InsertNewNetwork();
			}

			function AddNodeButton_Clicked() {
				$("#ToolBarDiv > .toolBarButton").hide();
				$("#ToggleInstructionsButton").show();
				$("#DoneButton").show();

				$(".instructionBlock.dynamic").hide();
				$("#AddNodeInstructionDiv").show();

				svg.on("click", InsertNewNode);
				circles.on("click", null);
				paths.on("click", null);

				ResetStyles();
			}

			function AddLinkButton_Clicked() {
				$("#ToolBarDiv > .toolBarButton").hide();
				$("#ToggleInstructionsButton").show();
				$("#DoneButton").show();

				$(".instructionBlock.dynamic").hide();
				$("#AddLinkInstructionDiv").show();

				circles
					.on("mousedown", SelectSourceNode)
					.on("mouseup", SelectEndNode)
					.on("click", null);

				svg.on("mouseup", HideDragLine);
				paths.on("click", null);

				ResetStyles();
			}

			function CenterCanvasButton_Clicked() {
				CenterCanvas();
			}

			function SelectButton_Clicked() {
				$("#ToolBarDiv > .toolBarButton").hide();
				$("#ToggleInstructionsButton").show();

				$("#SelectAllNodesButton").show();
				$("#SelectAllLinksButton").show();
				$("#SelectAllButton").show();
				$("#DeselectAllButton").show();
				$("#DeleteButton").show();
				$("#DoneButton").show();

				$(".instructionBlock.dynamic").hide();
				$("#SelectInstructionDiv").show();

				circles.on("click", SelectNode);
				paths.on("click", SelectLink);

				selectedLinks = [];
				selectedNodes = [];

				selectEnabled = true;

				ResetStyles();
			}

			function SelectAllNodesButton_Clicked() {
				SelectAllNodes();
				RefreshCategoryIcons();
			}

			function SelectAllLinksButton_Clicked() {
				SelectAllLinks();
				RefreshCategoryIcons();
			}

			function SelectAllButton_Clicked() {
				SelectAllNodes();
				SelectAllLinks();
				RefreshCategoryIcons();
			}

			function DeselectAllButton_Clicked() {
				circles.selectAll("circle").classed("selected", false).attr("transform", "");
				paths.classed("selected", false);

				RefreshCategoryIcons();
			}

			function DeleteButton_Clicked() {

				var nodeArray = selectedNodes.slice();
				var linkArray = selectedLinks.concat(adjacentLinks);

				if (nodeArray.length == 0 && linkArray.length == 0) {
					alert("There is no selection to delete.");
					return;
				}

				var isConfirmed = confirm("Are you sure you want to delete the selected elements?");
				if (!isConfirmed) {
					return;
				}

				DeleteSelection(nodeArray, linkArray);

				selectedNodes = [];
				adjacentLinks = [];
				selectedLinks = [];
			}

			function DoneButton_Clicked() {
				$("#ToolBarDiv > .toolBarButton").show();

				$("#SelectAllNodesButton").hide();
				$("#SelectAllLinksButton").hide();
				$("#SelectAllButton").hide();
				$("#DeselectAllButton").hide();
				$("#DeleteButton").hide();
				$("#DoneButton").hide();

				$(".instructionBlock.dynamic").hide();

				ResetButtonEvents();
			}

			function SaveProjectButton_Clicked() {

				var fileName = prompt("Save as? (default file type is json)", "download.json");
				if (!fileName) {
					return;
				}

				var projectData = CompileProjectData();
				DownloadProjectData(projectData, fileName);
			}

			function Path_MouseOver(d) {

				var path = d3.select(this);

				path.classed("hover", true);
	    		path.style("marker-end", "url(#end-arrow-hover)");

			}

			function Path_MouseOut(d) {

				var path = d3.select(this);
		    	var isSelected = path.classed("selectInfo");
		    	var isActive = path.classed("active");

		    	path.classed("hover", false);

		    	RefreshPathStyles();
			}

			function AssignToNetwork_Clicked(d) {

				var categoryId = d3.select(this).attr("data-id");

				//Add selectedNodes, selectedLinks and adjacentNodes
				var nodeArray = selectedNodes.concat(adjacentNodes);
				var linkArray = selectedLinks.slice();

				AssignToNetwork(categoryId, nodeArray, linkArray);

				selectedNodes = [];
				selectedLinks = [];
				adjacentNodes = [];
			}

			function RemoveFromNetwork_Clicked(d) {

				var categoryId = d3.select(this).attr("data-id");
				var nodeArray = selectedNodes.slice();
				var linkArray = selectedLinks.concat(adjacentLinks);

				RemoveFromNetwork(categoryId, nodeArray, linkArray);

				selectedNodes = [];
				selectedLinks = [];
				adjacentLinks = [];
			}

			/**************
			TASK FUNCTIONS
			***************/

			//Resets all variables and bound events to their initial state
			function ResetButtonEvents() {

				//Reset Variables
				selectedLinks = [];
				selectedNodes = [];
				adjacentLinks = [];
				adjacentNodes = [];

				selectEnabled = false;

				//Reset Events
				svg.on("mouseup", null)
					.on("mousedown", null)
					.on("mousemove", UpdateCoordinates)
					.on("click", null);

				circles.on("mousedown", null)
					.on("mouseup", null)
					.on("click", ToggleNodeInfoBox)
					.on('mousedown.drag', null);

				paths.on("mousedown", null)
					.on("click", TogglePathInfoBox);

				$(".instructionBlock.dynamic").hide();

				ResetStyles();
			}

			//Resets classes, styles, icons to their default values
			function ResetStyles() {
				paths.classed("selected", false)
					.classed("selectInfo", false);
				circles.selectAll("circle")
					.classed("selected", false)
					.classed("selectInfo", false)
					.attr("transform", "");
				circles.attr("transform", CalculateNodeTranslation);

				RefreshPathStyles();
				RefreshCategoryIcons();

				//TODO: Decide implementation
				networkBox.selectAll(".categoryGroup text").style("fill", "");
				// networkBox.selectAll(".categoryGroup:not(.active) text").style("font-weight", "");
				$("#InfoBox").hide();
			}

			function InitializeCanvas() {

				svg = d3.select("#D3CanvasDiv").append("svg")
					.attr("width", width)
					.attr("height", height)
					.on("mousemove", UpdateCoordinates)
					.call(zoomEvent);

				//TODO: Transfer to separate file
				//TODO: Test using context-fill & context-stroke so that each arrow head color doesn't need a separate marker (https://svgwg.org/svg2-draft/painting.html#VertexMarkerProperties)
				// Define arrow markers for graph links
				var arrowDefs = svg.append('svg:defs')
				arrowDefs.append('svg:marker')
				    .attr('id', 'end-arrow')
				    .attr("class", "markerArrow")
				    .attr('viewBox', '0 -5 10 10')
				    .attr('refX', 6)
				    .attr('markerWidth', 3)
				    .attr('markerHeight', 3)
				    .attr('orient', 'auto')
				  .append('svg:path')
				    .attr('d', 'M0,-5L10,0L0,5')
				    .attr('fill', '#000');

				//arrowDefs = svg.append('svg:defs').append('svg:marker')
				//     .attr('id', 'start-arrow')
				// 		 .attr("class", "markerArrow")
				//     .attr('viewBox', '0 -5 10 10')
				//     .attr('refX', 4)
				//     .attr('markerWidth', 3)
				//     .attr('markerHeight', 3)
				//     .attr('orient', 'auto')
				//   .append('svg:path')
				//     .attr('d', 'M10,-5L0,0L10,5')
				//     .attr('fill', '#000');

				arrowDefs.append('svg:marker')
				    .attr('id', 'end-arrow-hover')
				    .attr("class", "markerArrow")
				    .attr('viewBox', '0 -5 10 10')
				    .attr('refX', 6)
				    .attr('markerWidth', 3)
				    .attr('markerHeight', 3)
				    .attr('orient', 'auto')
				  .append('svg:path')
				    .attr('d', 'M0,-5L10,0L0,5')
				    .attr('fill', '#777777');

				arrowDefs.append('svg:marker')
				    .attr('id', 'end-arrow-selected')
				    .attr("class", "markerArrow")
				    .attr('viewBox', '0 -5 10 10')
				    .attr('refX', 6)
				    .attr('markerWidth', 3)
				    .attr('markerHeight', 3)
				    .attr('orient', 'auto')
				  .append('svg:path')
				    .attr('d', 'M0,-5L10,0L0,5')
				    .attr('fill', 'red');

				arrowDefs.append('svg:marker')
				    .attr('id', 'end-arrow-selectInfo')
				    .attr("class", "markerArrow")
				    .attr('viewBox', '0 -5 10 10')
				    .attr('refX', 6)
				    .attr('markerWidth', 3)
				    .attr('markerHeight', 3)
				    .attr('orient', 'auto')
				  .append('svg:path')
				    .attr('d', 'M0,-5L10,0L0,5')
				    .attr('fill', 'blue');

				//Colored arrow head markers for network selection
				var colorCount = networkColors.length;
				for(var i = 0; i < colorCount; i++) {
					arrowDefs.append('svg:marker')
					    .attr('id', 'end-arrow-set' + i)
					    .attr("class", "markerArrow")
					    .attr('viewBox', '0 -5 10 10')
					    .attr('refX', 6)
					    .attr('markerWidth', 3)
					    .attr('markerHeight', 3)
					    .attr('orient', 'auto')
					  .append('svg:path')
					    .attr('d', 'M0,-5L10,0L0,5')
					    .attr('fill', networkColors[i]);
				}

				//TODO: Transfer to svg definitions file
				// Define layer visibility icons
				var iconDefs = svg.append("svg:defs");
				var iconDef;

				//Visible icons - https://leungwensen.github.io/svg-icon/#ionic
				iconDef = iconDefs.append("svg:symbol")
						.attr("id", "icon-visible")
						.attr("viewBox", "0 0 448 256")
				iconDef.append("svg:rect")
					.attr("width", "100%")
					.attr("height", "100%")
					.style("fill-opacity", 0)
				iconDef.append("svg:title")
					.text("Hide")
				iconDef.append("svg:path")
					.attr("d", "M224 0C142.1 0 78.3 48.8 0 128c67.4 67.7 124 128 224 128 99.9 0 173.4-76.4 224-126.6C396.2 70.6 322.8 0 224 0zm0 219.3c-49.4 0-89.6-41-89.6-91.3 0-50.4 40.2-91.3 89.6-91.3s89.6 41 89.6 91.3c0 50.4-40.2 91.3-89.6 91.3z");
				iconDef.append("svg:path")
					.attr("d", "M224 96c0-7.9 2.9-15.1 7.6-20.7-2.5-.4-5-.6-7.6-.6-28.8 0-52.3 23.9-52.3 53.3s23.5 53.3 52.3 53.3 52.3-23.9 52.3-53.3c0-2.3-.2-4.6-.4-6.9-5.5 4.3-12.3 6.9-19.8 6.9-17.8 0-32.1-14.3-32.1-32z");

				//Hidden icon - https://leungwensen.github.io/svg-icon/#ionic
				iconDef = iconDefs.append("svg:symbol")
						.attr("id", "icon-hidden")
						.attr("viewBox", "0 0 448 384")
				iconDef.append("svg:rect")
					.attr("width", "100%")
					.attr("height", "100%")
					.style("fill-opacity", 0)
				iconDef.append("svg:title")
					.text("Show")
				iconDef.append("svg:path")
					.attr("d", "M344.4 94.3L416 22.6 393.4 0l-77.6 77.6C288 63.7 257.5 55 224 55 142.1 55 78.3 107.2 0 192c34.8 37.4 66.8 72.7 103.3 98.1L32 361.4 54.6 384l76.9-76.9c27 13.7 57 21.9 92.5 21.9 99.9 0 173.4-81.8 224-135.5-28-34.1-62.4-71.8-103.6-99.2zm-210 97.7c0-50.4 40.2-91.3 89.6-91.3 19.3 0 37.2 6.2 51.8 16.9l-50.7 50.7c-.7-2.6-1.1-5.4-1.1-8.3 0-7.9 2.9-15.1 7.6-20.7-2.5-.4-5-.6-7.6-.6-28.8 0-52.3 23.9-52.3 53.3 0 8.6 2 16.8 5.6 24L150 243.4c-9.8-14.7-15.6-32.4-15.6-51.4zm89.6 91.3c-19.3 0-37.2-6.2-51.8-16.9l27.4-27.4c7.3 4 15.6 6.2 24.4 6.2 28.8 0 52.3-23.9 52.3-53.3 0-2.3-.2-4.6-.4-6.9-5.5 4.3-12.3 6.9-19.8 6.9-2.9 0-5.6-.4-8.3-1.1l50.3-50.3c9.8 14.6 15.6 32.3 15.6 51.4-.1 50.5-40.3 91.4-89.7 91.4z");

				//Add icon - https://leungwensen.github.io/svg-icon/#ionic
				iconDef = iconDefs.append("svg:symbol")
						.attr("id", "icon-add")
						.attr("viewBox", "0 0 416 416")
				iconDef.append("svg:rect")
					.attr("width", "100%")
					.attr("height", "100%")
					.style("fill-opacity", 0)
				iconDef.append("svg:title")
					.text("Add selected elements to layer")
				iconDef.append("svg:path")
					.attr("d", "M208 0C93.125 0 0 93.125 0 208s93.125 208 208 208 208-93.125 208-208S322.875 0 208 0zm107 229h-86v86h-42v-86h-86v-42h86v-86h42v86h86v42z");

				//Subtract icon - https://leungwensen.github.io/svg-icon/#ionic
				iconDef = iconDefs.append("svg:symbol")
						.attr("id", "icon-remove")
						.attr("viewBox", "0 0 416 416")
				iconDef.append("svg:rect")
					.attr("width", "100%")
					.attr("height", "100%")
					.style("fill-opacity", 0)
				iconDef.append("svg:title")
					.text("Remove selected elements from layer")
				iconDef.append("svg:path")
					.attr("d", "M208 0C93.125 0 0 93.125 0 208s93.125 208 208 208 208-93.125 208-208S322.875 0 208 0zm107 229H101v-42h214v42z");

				//Delete icon - https://leungwensen.github.io/svg-icon/#ionic
				iconDef = iconDefs.append("svg:symbol")
						.attr("id", "icon-delete")
						.attr("viewBox", "0 0 320 384")
				iconDef.append("svg:rect")
					.attr("width", "100%")
					.attr("height", "100%")
					.style("fill-opacity", 0)
				iconDef.append("svg:title")
					.text("Delete layer")
				iconDef.append("svg:path")
					.attr("d", "M32 341.429C32 364.846 51.198 384 74.667 384h170.667C268.802 384 288 364.846 288 341.429V96H32v245.429zM320 32h-80L213.215 0H106.786L80 32H0v32h320V32z");

				//Info icon - https://leungwensen.github.io/svg-icon/#ionic
				iconDef = iconDefs.append("svg:symbol")
						.attr("id", "icon-info")
						.attr("viewBox", "0 0 451 451")
				iconDef.append("svg:rect")
					.attr("width", "100%")
					.attr("height", "100%")
					.style("fill-opacity", 0)
				iconDef.append("svg:title")
					.text("Open info")
				iconDef.append("svg:path")
					.attr("d", "M449.6 222.6C447.9 98.9 346.3 0 222.6 1.6S0 104.9 1.6 228.6c1.7 123.7 103.3 222.6 227 221 123.7-1.7 222.7-103.3 221-227zm-224-141.1c17.7 0 32 14.3 32 32s-14.3 32-32 32-32-14.3-32-32 14.3-32 32-32zm44 283.1h-88v-11h22v-160h-22v-12h66v172h22v11z");

				iconDef = iconDefs.append("svg:symbol")
						.attr("id", "icon-info-alt")
						.attr("viewBox", "0 0 64 200")
				iconDef.append("svg:rect")
					.attr("width", "100%")
					.attr("height", "100%")
					.style("fill-opacity", 0)
				iconDef.append("svg:title")
					.text("Close info")
				var iconDefG = iconDef.append("svg:g")
					.attr("transform", "translate(-224 -152)");
				iconDefG.append("svg:circle")
					.attr("cx", 251.5)
					.attr("cy", 172)
					.attr("r", 20)
				iconDefG.append("svg:path")
					.attr("d", "M272 344V216h-48v8h16v120h-16v8h64v-8z");

				//Map icon - https://leungwensen.github.io/svg-icon/#ionic
				iconDef = iconDefs.append("svg:symbol")
						.attr("id", "icon-map")
						.attr("viewBox", "0 0 448.0010070800781 385.51702880859375")
				iconDef.append("svg:rect")
					.attr("width", "100%")
					.attr("height", "100%")
					.style("fill-opacity", 0)
				iconDef.append("svg:title")
					.text("Show Map")
				iconDef.append("svg:path")
					.attr("d", "M441.152 73.349L336.594 3.076C331.95 0 326.229 0 321.563 3.076l-97.094 65.195L127.36 3.076C122.717 0 116.916 0 112.298 3.076L7.344 73.349C2.945 76.298 0 81.621 0 87.399v280.97c0 5.904 3.07 11.347 7.663 14.271 4.62 2.877 10.382 2.829 14.904-.223l97.188-65.197 97.181 65.197c4.666 3.1 10.44 3.1 15.084 0l97.158-65.197 97.151 65.197c2.311 1.55 4.912 2.353 7.538 2.353 2.455 0 4.709-.747 6.969-2.13 4.594-2.924 7.165-8.366 7.165-14.271V87.399c-.001-5.778-2.426-11.101-6.849-14.05zM103 290.017l-71 49.404V97.671l71-49.428v241.774zm32-.482v-122.7a85.853 85.853 0 0 1 3.389 2.301l9.333-12.996c-3.904-2.804-8.292-5.501-12.722-7.705V47.761l73 48.992v108.574c-5.372-1.855-10.586-4.37-15.893-7.682l-8.471 13.574c6.848 4.273 13.621 7.467 20.704 9.765l3.66-11.286v128.806l-73-48.969zm105 48.968V225.138c.349-.021.693-.03 1.043-.054a98.66 98.66 0 0 0 8.518-.946l-2.479-15.807a82.6 82.6 0 0 1-7.082.786V96.753l73-48.992v118.788l-4.844-4.372c-2.997 3.319-5.833 6.575-8.575 9.725-1.806 2.073-3.578 4.108-5.339 6.095l11.974 10.613a938.209 938.209 0 0 0 5.431-6.2c.443-.509.905-1.035 1.354-1.549v108.675L240 338.503zm176-.082l-71-48.438v-136.91c3.785-1.715 7.679-2.992 11.639-3.792l-3.166-15.683a69.216 69.216 0 0 0-8.473 2.281V48.208l71 48.461v241.752z");
				iconDef.append("svg:path")
					.attr("d", "M97.285 157.88c2.218-1.195-1.353-1.071 1.012-1.837l-4.928-15.222a57.625 57.625 0 0 0-9.571 4.093c-3.824 2.062-7.745 4.916-11.339 8.253l10.888 11.724c2.616-2.428 11.292-5.584 13.938-7.011zm167.517 45.016l6.985 14.395c6.596-3.201 12.842-7.445 19.095-12.974l-10.599-11.987c-5.148 4.552-10.212 8.008-15.481 10.566zM67.364 192.775c1.893-5.926 3.528-11.044 7.129-16.304l-13.204-9.037c-4.924 7.194-7.081 13.944-9.167 20.471l-.242.758 15.238 4.879.246-.767zm98.947-18.253c-1.007-1.204-2.014-2.408-3.032-3.603l-12.178 10.379c.986 1.156 1.961 2.322 2.936 3.488 3.593 4.296 7.308 8.739 11.61 12.809l10.996-11.624c-3.623-3.427-6.882-7.324-10.332-11.449zm209.845-8.096l10.344-10.343 10.344 10.343 11.312-11.315-10.342-10.342 10.342-10.343-11.312-11.315-10.344 10.343-10.344-10.343-11.312 11.315 10.342 10.343-10.342 10.342z");

				//Map icon - https://leungwensen.github.io/svg-icon/#ionic
				iconDef = iconDefs.append("svg:symbol")
						.attr("id", "icon-map2")
						.attr("viewBox", "0 0 384 384")
				iconDef.append("svg:rect")
					.attr("width", "100%")
					.attr("height", "100%")
					.style("fill-opacity", 0)
				iconDef.append("svg:title")
					.text("Hide Map")
				iconDef.append("svg:path")
					.attr("d", "M373.333 0c-2.176 0-4.396 1.369-9.176 3.207L256 44.802 128 0 7.469 40.531C3.197 41.604 0 45.864 0 51.197v322.136C0 379.729 4.271 384 10.666 384c1.828 0 6.505-2.33 9.087-3.319L128 339.197 256 384l120.531-40.531c4.271-1.073 7.469-5.334 7.469-10.667V10.666C384 4.271 379.729 0 373.333 0zM256 341.333l-128-44.802V42.666l128 44.803v253.864z");

				//Loading icon - https://codepen.io/aurer/pen/jEGbA
				iconDef = iconDefs.append("svg:symbol")
					.attr("id", "icon-loading")
					.attr("viewBox", "0 0 40 40")
					.attr("enable-background", "new 0 0 40 40");
				iconDef.append("svg:path")
					.attr("opacity", 0.2)
					.attr("fill", "#000")
					.attr("d", "M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946 s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634 c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z");
				iconDef.append("svg:path")
					.attr("fill", "#000")
					.attr("d", "M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0 C22.32,8.481,24.301,9.057,26.013,10.047z")
					.append("animateTransform")
						.attr("attributeType","xml")
      			.attr("attributeName", "transform")
      			.attr("type", "rotate")
      			.attr("from", "0 20 20")
      			.attr("to", "360 20 20")
      			.attr("dur", "0.5s")
      			.attr("repeatCount", "indefinite");



				map = svg.append("svg:g").attr("id", "MapLayer");
				canvas = svg.append("svg:g").attr("id", "canvas");

				//TEMPORARY: Marks the center of canvas
				canvasCenter = svg.append("circle")
					.attr("id", "canvascenter")
					.attr("transform", "translate(" + (width/2) + "," + (height/2) + ")")
					.attr("r",  4)
					.attr("fill", "black");

				//TEMPORARY: display coordinates of mouse
				coordinatesText = svg.append("svg:text")
					.attr("id", "CoordinatesText")
					.attr("transform", "translate(0," + (height - 2) + ")")
					.attr("width", 100)
					.attr("height", infoBoxFontSize);

				layer = canvas.append("svg:g").attr("id", "BaseLayer").classed("layer", true);
				paths = layer.append("svg:g").attr("id", "paths").selectAll("path");
				circles = layer.append("svg:g").attr("id", "nodes").selectAll("g");

				//Note: Drag line & zoom rectangle appended to canvas to account for canvas translation after zoom/pan
				dragLine = canvas.append("svg:path").attr("class", "link dragLine hidden");
				zoomRect = canvas.append("svg:rect").attr("id", "ZoomRect");

				//Define color styles
				for(var i = 0; i < networkMaxCount; i++) {
					networkStyles[i] = null;
				}

				$(".instructionBlock.dynamic").hide();

				RefreshCanvas();
				RefreshMapZoom();
			}

			//Manages the display of nodes and paths and events bound to them
			function RefreshCanvas() {

			    //Paths (links)
  				paths = paths.data(links, function(d){ return GetLinkId(d); });

			    //TODO: Verify use - Update existing paths
			    paths.style('marker-end', 'url(#end-arrow)')
			    	.style("stroke-width", CalculateLinkWidth);

  				//Add new links to canvas
			  	var line = paths.enter().append('svg:path')
			  		.attr("id", GetLinkId)
				    .attr('class', 'link')
				    .attr("d", CalculateLinkCoordinates)
				    .style('marker-end', 'url(#end-arrow)')
				    .style("stroke-width", CalculateLinkWidth)
				    .on("mouseover", Path_MouseOver)
				    .on("mouseout", Path_MouseOut)
				    .on("click", TogglePathInfoBox);

			  	//Remove old link data
			  	paths.exit().remove();

					//Circles (nodes)
			    // NOTE: (from source) the function arg is crucial here! nodes are known by id, not by index!
			    circles = layer.select("#nodes").selectAll("g").data(nodes, function(d) { return d.id; });

			    //Add new nodes to canvas
			    var node = circles.enter().append('svg:g');
			    node.attr("transform", CalculateNodeTranslation)
			    	.attr("id", function(d) { return d.id; })
			    	.on("click", ToggleNodeInfoBox);

			    node.append('svg:circle')
			      .attr('class', 'node')
			      .attr('r', radius)
			      .attr("stroke-width", 4)
			      // .attr("r", 24 / zoomEvent.scale()) 						//NOTE: Default tile implementation: scales node with zoom
			      // .style("stroke-width", 8 / zoomEvent.scale())	//NOTE: Default tile implementation: scales node with zoom
			      .on("mouseover", function(d) { d3.select(this).classed("hover", true)})
			      .on("mouseout", function(d) { d3.select(this).classed("hover", false)});

			    //Remove old nodes data
			    circles.exit().remove();
			}

			//Deletes and rebuilds all contents (icons and names) contained in  Network Box
			function RefreshNetworksBox() {

				//Remove all layers/categories
				networkBox.selectAll(".categoryGroup").remove();

				//Add global controls
				var baseLayer = networkBox.append("svg:g")
					.attr("id", "BaseGroup")
					.classed("categoryGroup", true);

				//Compute positioning
				var y = (infoBoxFontSize + infoBoxTextBox_TopPadding);

				baseLayer.append("svg:use")
					.classed("layerIcon", true)
					.attr("id", "AllVisibilityIcon")
					.attr("xlink:href", "#icon-visible")
					.attr("width", networkBoxIconWidth)
					.attr("height", networkBoxIconHeight)
					.attr("x", infoBoxLabel_LeftPadding)
					.attr("y", y)
					.attr("data-type", "visibility")
					.on("click", ToggleAllNetworkVisibility);

				baseLayer.append("svg:use")
					.classed("layerIcon", true)
					// .classed("disabled", true)
					.attr("xlink:href", "#icon-map2")
					.attr("width", networkBoxIconWidth)
					.attr("height", networkBoxIconHeight - 4)
					.attr("x", (infoBoxLabel_LeftPadding + (networkBoxIconWidth * 1)))
					.attr("y", y + 2)
					.on("click", ToggleMapVisibility);

				// baseLayer.append("svg:use")
				// 	.classed("layerIcon", true)
				// 	// .classed("disabled", true)
				// 	.attr("xlink:href", "#icon-map2")
				// 	.attr("width", networkBoxIconWidth)
				// 	.attr("height", networkBoxIconHeight - 4)
				// 	.attr("x", (infoBoxLabel_LeftPadding + (networkBoxIconWidth * 2)))
				// 	.attr("y", y + 2);

				var networkCount = networks.length;
				for(var i = 0; i < networkCount; i++) {

					AddNetworkToStyles(networks[i].id);
					AddNetworkToCanvas(networks[i].id, networks[i].name, i + 1);

					if (lastCategoryId < i + 1) {
						lastCategoryId = i + 1;
					}
				}
			}

			//Adds the network id to an array of styles to keep track of the colors used
			//Ensures that a network stays on the same color even after other networks have been deleted
			function AddNetworkToStyles(categoryId) {

				if (networkStyles.indexOf(categoryId) >= 0) {
					return;
				}

				var index = networkStyles.indexOf(null);
				networkStyles[index] = categoryId;
			}

			//Renders the network info and icons to the network box
			function AddNetworkToCanvas(categoryId, categoryName, categoryCount) {

				var styleId = GetStyleId(categoryId);

				if (categoryName == "") {
					categoryName = categoryId;
				}

				//Network Box
				//Deactivate all other layers then add a new layer
				networkBox.selectAll("g").classed("active", false);
				var categoryGroup = networkBox.append("svg:g")
					.attr("id", "CategoryGroup" + categoryCount)
					.attr("data-id", categoryId)
					.classed("categoryGroup", true)
					// .classed("active", true)
					.classed("set" + styleId, true);

				//Compute positioning of new layer
				//NOTE: Plus 1 to account for global controls
				var y = (categoryCount + 1) * (infoBoxFontSize + infoBoxTextBox_TopPadding);

				var iconVis = "#icon-visible";
				if (hiddenNetworks.find(x => x == categoryId)) {
					iconVis = "#icon-hidden";
				}

				//Icons
				categoryGroup.append("svg:use")
					.classed("layerIcon", true)
					.attr("xlink:href", iconVis)
					.attr("width", networkBoxIconWidth)
					.attr("height", networkBoxIconHeight)
					.attr("x", infoBoxLabel_LeftPadding)
					.attr("y", y)
					.attr("data-id", categoryId)
					.attr("data-type", "visibility")
					.on("click", ToggleNetworkVisibility);

				categoryGroup.append("svg:use")
					.classed("layerIcon", true)
					.classed("disabled", true)
					.attr("xlink:href", "#icon-add")
					.attr("width", networkBoxIconWidth)
					.attr("height", networkBoxIconHeight - 4)
					.attr("x", (infoBoxLabel_LeftPadding + (networkBoxIconWidth * 1)))
					.attr("y", y + 2)
					.attr("data-id", categoryId)
					.attr("data-type", "add")
					.style("display", "none");

				categoryGroup.append("svg:use")
					.classed("layerIcon", true)
					.classed("disabled", true)
					.attr("xlink:href", "#icon-remove")
					.attr("width", networkBoxIconWidth)
					.attr("height", networkBoxIconHeight - 4)
					.attr("x", (infoBoxLabel_LeftPadding + (networkBoxIconWidth * 2)))
					.attr("y", y + 2)
					.attr("data-id", categoryId)
					.attr("data-type", "remove")
					.style("display", "none");

				categoryGroup.append("svg:use")
					.classed("layerIcon", true)
					.attr("xlink:href", "#icon-delete")
					.attr("width", networkBoxIconWidth)
					.attr("height", networkBoxIconHeight - 4)
					.attr("x", (infoBoxLabel_LeftPadding + (networkBoxIconWidth * 1)))
					.attr("y", y + 2)
					.attr("data-id", categoryId)
					.attr("data-type", "delete")
					.on("click", DeleteNetwork);

				categoryGroup.append("svg:use")
					.classed("layerIcon", true)
					.attr("xlink:href", "#icon-info")
					.attr("width", networkBoxIconWidth)
					.attr("height", networkBoxIconHeight - 4)
					.attr("x", (infoBoxLabel_LeftPadding + (networkBoxIconWidth * 2)))
					.attr("y", y + 2)
					.attr("data-id", categoryId)
					.attr("data-type", "info")
					.on("click", ToggleNetworkInfoBox);

				categoryGroup.append("svg:text")
					.attr("data-type", "category")
					.attr("data-key", "name")
					.attr("data-id", categoryId)
					.append("svg:tspan")
						.classed("layerText", true)
						.classed("edit", true)
						.attr("x", (infoBoxLabel_LeftPadding + (networkBoxIconWidth * 3)))
						.attr("y", y + infoBoxFontSize)
						.text(categoryName);

				//TODO: Implement as hover state instead of class
				//Add events to editable text
				networkBox.selectAll("tspan.edit")
					.on("mouseover", function(d) { d3.select(this).classed("hover", true)})
					.on("mouseout", function(d) { d3.select(this).classed("hover", false)})
					.on("dblclick", function(d) {
						StopPropagation();
						PreventDefault();
						DisplayInputBox(this, networkBox);
					})
					.on("click", ToggleNetworkState);

			}

			//Sets the specified network as active or inactive
			function ToggleNetworkState(d) {

				//Prevent adding nodes at the back
				StopPropagation();
				PreventDefault();

				var element = d3.select(this);
				var categoryGroupElement = d3.select(this.parentNode.parentNode);
				var categoryId = categoryGroupElement.attr("data-id");
				var isActive = networkBox.select("[data-id='" + categoryId + "'].categoryGroup").classed("active");


				if (isActive) {
					//Do not activate new category
					ClearActiveNetwork();
					RefreshPathStyles();
					RefreshEditNetworkButtons();
				}
				else {
					ActivateNetwork(categoryId);
				}

				//Adjust info data if shown
				if (selectedLinks.length > 0) {
					PreparePathData(selectedLinks[0]);
				}
				else if (selectedNodes.length > 0) {
					PrepareNodeData(selectedNodes[0]);
				}

			}

			//Disables add node and link buttons if there is no selected/active network
			//Ensures that nodes and links added belong to a network
			function RefreshEditNetworkButtons() {

				var categoryId = GetActiveNetworkId();

				if (categoryId) {
					$("#AddNodeButton").removeClass("disabled");
					$("#AddLinkButton").removeClass("disabled");
				}
				else {
					$("#AddNodeButton").addClass("disabled");
					$("#AddLinkButton").addClass("disabled");
				}
			}

			//Highlights all nodes and links that belong to a network based on the assigned color
			function ActivateNetwork(categoryId) {

				var network = networks.find(x => x.id == categoryId);
				var networkNodes = network.nodes;
				var networkLinks = network.links;
				var nodeCount = networkNodes.length;
				var linkCount = networkLinks.length
				var styleId = GetStyleId(categoryId);

				ClearActiveNetwork();

				//Highlight all nodes/paths belonging to category
				for(var i = 0; i < nodeCount; i++) {
					layer.select("#" + networkNodes[i].id).classed("set" + styleId, true);
				}

				for(var i = 0; i < linkCount; i++) {
					var linkId = GetLinkId(networkLinks[i]);
					var path = layer.select("#" + linkId);

					path.classed("active", true);
				}

				//Highlight network in network box
				networkBox.select("[data-id='" + categoryId + "'].categoryGroup").classed("active", true);

				RefreshPathStyles();
				RefreshPathWidths();
				RefreshEditNetworkButtons();
			}

			//Resets nodes and paths to an inactive state
			function ClearActiveNetwork() {

				//Clear class of all other nodes/paths
				layer.select("#nodes").selectAll("g").attr("class", "");
				layer.select("#paths").selectAll("path")
					.classed("active", false);

				networkBox.selectAll("g").classed("active", false);
			}

			//Highlights specified networks on the network box with their assigned colors
			function HighlightNetworkNames(categoryArray) {

				//TODO: Decide implementation
				networkBox.selectAll(".categoryGroup text").style("fill", "");
				// networkBox.selectAll(".categoryGroup text").style("font-weight", "");

				var count = categoryArray.length;
				for(var i = 0; i < count; i++) {
					networkBox.select("text[data-id=" + categoryArray[i].id + "]").style("fill", "inherit");
					// networkBox.select("text[data-id=" + categoryArray[i].id + "]").style("font-weight", "bold");
				}

			}

			//Fixes path styles based on classes
			function RefreshPathStyles() {

				//Remove styling
				//TODO: Find better implementation
				layer.selectAll("path.link").style("marker-end", "url(#end-arrow)");
				layer.selectAll("path.set0").classed("set0", false);
				layer.selectAll("path.set1").classed("set1", false);
				layer.selectAll("path.set2").classed("set2", false);
				layer.selectAll("path.set3").classed("set3", false);
				layer.selectAll("path.set4").classed("set4", false);
				layer.selectAll("path.set5").classed("set5", false);
				layer.selectAll("path.set6").classed("set6", false);
				layer.selectAll("path.set7").classed("set7", false);
				layer.selectAll("path.set8").classed("set8", false);
				layer.selectAll("path.set9").classed("set9", false);


				//Active paths
				var selection = layer.selectAll("path.active");
				if (!selection.empty()){
					var categoryId = GetActiveNetworkId();

					if (categoryId)
					{
						var styleId = GetStyleId(categoryId);

						selection.classed("set" + styleId, true);
						selection.style("marker-end", "url(#end-arrow-set" + styleId + ")");
					}
				}

				//Select Info Path
				selection = layer.selectAll("path.selectInfo");
				if (!selection.empty()) {
					selection.style("marker-end", "url(#end-arrow-selectInfo)");
				}

				//Selected paths
				selection = layer.selectAll("path.selected");
				if (!selection.empty()) {
					selection.style("marker-end", "url(#end-arrow-selected)");
				}

				//Hovered paths
				selection = layer.selectAll("path.hover");
				if (!selection.empty()) {
					selection.style("marker-end", "url(#end-arrow-hover)");
				}
			}

			//Calculates path widths based on the state of the path
			//If path is part of an active/selected network, width will depend on the laneCount of the link
			//If path is inactive, default calculation is used
			//TODO: Optimize - check for better implementation
			function RefreshPathWidths() {

				//Reset all paths
				paths.style("stroke-width", CalculateLinkWidth);

				var network = GetActiveCategory();

				if (network) {

					var networkLinks = network.links;
					var count = networkLinks.length;

					for(var i = 0; i < count; i++) {

						var path = layer.select("#" + networkLinks[i].id);

						path.style("stroke-width", CalculateLinkWidth(networkLinks[i]));
					}
				}
			}

			//Calculates base node data
			function RefreshNodesData() {
				//NOTE: Add node calculation here, if any
			}

			//Calculates base link data
			//ie. LANECOUNT varies per network the link belongs to. This function calculates the lane count (width) to be used when it is not part of an active network
			function RefreshLinksData() {

				//LANE COUNT
				//Calculate base link widths

				//TODO: Confirm calculation
				//Current implementation: max value of the links laneCount in the networks

				var count = links.length;
				for(var i = 0; i < count; i++) {

					var link = links[i];
					var linkId = GetLinkId(link);
					var categories = GetLinkNetworks(linkId);

					var maxLaneCount = link.laneCount ? link.laneCount : 1;	//Default laneCount to 1 if undefined
					var selectionCount = categories.length;
					for (var j = 0; j < selectionCount; j++) {

						var networkLink = categories[j].links.find(x => GetLinkId(x) === linkId);
						if (networkLink.laneCount > maxLaneCount) {
							maxLaneCount = networkLink.laneCount;
						}
					}

					link.laneCount = maxLaneCount;
				}
			}

			//Disables the assign/remove buttons on the network box
			function ResetAssignToNetworkIcons() {

				networkBox.selectAll("[data-type='add']")
						.classed("disabled", true)
						.on("click", null);

				networkBox.selectAll("[data-type='remove']")
						.classed("disabled", true)
						.on("click", null);

			}

			//Determines if the assign/remove button per network in the network box should be enabled or disabled
			//Assign is enabled when there is atleast 1 node/path selected that doesn't belong to the network
			//Remove is enabled when there is atleast 1 node/path selected that already belongs to the network
			//TODO: Create a separate method that will trigger for individual
			// 		select of nodes/links so that it will not iterate through everything always
			function RefreshCategoryIcons() {

				/** ALTERNATE IMPLEMENTATION
				//Option 1: Checks if there are nodes/links in the network
				//			doesn't check if selected node/link belongs to network.

				If there are selected nodes/links, enable add
				if (selectedNodes.length > 0 || selectedLinks.length > 0) {
					networkBox.selectAll("[data-type='add']")
						.classed("disabled", false)
						.on("click", AssignToNetwork_Clicked);
				}
				else
				{
					networkBox.selectAll("[data-type='add']")
						.classed("disabled", true)
						.on("click", null);
				}

				//Only enable remove if network has assigned nodes/paths
				var count = networks.length;
				for(var i = 0; i < count; i++) {

					var nodeCount = networks[i].nodes.length;
					var linkCount = networks[i].links.length;

					if (nodeCount > 0 || linkCount > 0) {
						networkBox.select("[data-id='" + networks[i].id + "'][data-type='remove']")
							.classed("disabled", false)
							.on("click", RemoveFromNetwork_Clicked);
					}
					else {
						networkBox.select("[data-id='" + networks[i].id + "'][data-type='remove']")
							.classed("disabled", true)
							.on("click", null);
					}
				}
				**/


				//If select is disbaled do not show add and remove buttons
				networkBox.selectAll(".layerIcon").style("display", "block");
				networkBox.selectAll("[data-type='info']").attr("xlink:href", "#icon-info");

				if (!selectEnabled) {

					networkBox.selectAll("[data-type='add'").style("display", "none");
					networkBox.selectAll("[data-type='remove'").style("display", "none");
					return;
				}

				networkBox.selectAll("[data-type='delete']").style("display", "none");
				networkBox.selectAll("[data-type='info']").style("display", "none");


				//Option 2: Checks if selected node/link belongs to a network
				// 			only then does it activate the remove button
				var count = networks.length;
				for (var i = 0; i < count; i++) {

					console.log("NetworID:" + networks[i].id);
					var networkNodes = networks[i].nodes;
					var networkLinks = networks[i].links;

					var enableAdd = false;
					var enableRemove = false;

					var selectedCount = selectedNodes.length;
					for (var j = 0; j < selectedCount; j++) {
						var node = networkNodes.find(x => x.id === selectedNodes[j].id)

						if (!enableRemove && node) {
							enableRemove = true;
						}

						if (!enableAdd && !node) {
							enableAdd = true;
						}

						if (enableAdd && enableRemove) {
							break;
						}
					}

					if (!enableRemove || !enableAdd) {
						selectedCount = selectedLinks.length;
						for (var j = 0; j < selectedCount; j++) {
							var link = networkLinks.find(x => GetLinkId(x) === GetLinkId(selectedLinks[j]))

							if (!enableRemove && link) {
								enableRemove = true;
							}

							if (!enableAdd && !link) {
								enableAdd = true;
							}

							if (enableAdd && enableRemove) {
								break;
							}
						}
					}

					//Adjacent nodes only affect adding
					if (!enableAdd) {
						selectedCount = adjacentNodes.length;
						for (var j = 0; j < selectedCount; j++) {
							if (!networkNodes.find(x => x.id === adjacentNodes[j].id)) {
								enableAdd = true;
								break;
							}
						}
					}

					//Adjacent links only affect removing
					if (!enableRemove) {
						selectedCount = adjacentLinks.length;
						for (var j = 0; j < selectedCount; j++) {
							if (networkLinks.find(x => GetLinkId(x) === GetLinkId(adjacentLinks[j]))) {
								enableRemove = true;
								break;
							}
						}
					}


					if (enableRemove) {
						networkBox.select("[data-id='" + networks[i].id + "'][data-type='remove']")
							.classed("disabled", false)
							.on("click", RemoveFromNetwork_Clicked);
					}
					else {
						networkBox.select("[data-id='" + networks[i].id + "'][data-type='remove']")
							.classed("disabled", true)
							.on("click", null);
					}

					if (enableAdd) {
						networkBox.select("[data-id='" + networks[i].id + "'][data-type='add']")
							.classed("disabled", false)
							.on("click", AssignToNetwork_Clicked);
					}
					else
					{
						networkBox.select("[data-id='" + networks[i].id + "'][data-type='add']")
							.classed("disabled", true)
							.on("click", null);
					}
				}
			}


			//Shows/Hides the map background
			function ToggleMapVisibility() {
				StopPropagation();
				PreventDefault();

				var element = d3.select(this);
				var isVisible = (element.attr("xlink:href") == "#icon-map2");

				if (isVisible) {
					element.attr("xlink:href", "#icon-map");
					$("#MapLayer").hide();
				}
				else {
					element.attr("xlink:href", "#icon-map2");
					$("#MapLayer").show();
				}
			}

			//Shows/Hides specified network
			//Adds/Removes a network to the hiddenNetworks array to determine which nodes and links should be visible
			function ToggleNetworkVisibility(d) {
				StopPropagation();
				PreventDefault();

				var element = d3.select(this);
				var isVisible = (element.attr("xlink:href") == "#icon-visible");
				var categoryId = element.attr("data-id");

				var network = networks.find(x => x.id === categoryId);
				var networkNodes = network.nodes;
				var networkLinks = network.links;

				if (isVisible) {
					element.attr("xlink:href", "#icon-hidden");
					hiddenNetworks.push(categoryId);

					ClearActiveNetwork();
					RefreshPathStyles();
				}
				else {
					element.attr("xlink:href", "#icon-visible");

					hiddenNetworks = hiddenNetworks.filter(function(x) {
						return x != categoryId;
					})
				}

				//Refresh All Visibility Icon
				if (networks.length == hiddenNetworks.length) {
					networkBox.select("#AllVisibilityIcon").attr("xlink:href", "#icon-hidden");
				}
				else {
					networkBox.select("#AllVisibilityIcon").attr("xlink:href", "#icon-visible");
				}

				RefreshNetworkVisibility(networkNodes, networkLinks);
			}

			//Shows/Hides all networks
			function ToggleAllNetworkVisibility() {
				StopPropagation();
				PreventDefault();

				var element = d3.select(this);
				var isVisible = (element.attr("xlink:href") == "#icon-visible");

				if (isVisible) {
					networkBox.selectAll("[data-type='visibility']").attr("xlink:href", "#icon-hidden");
					hiddenNetworks = networks.map(x => x.id);

					//Show all elements
					$("#nodes > g").hide();
					$("#paths > path").hide();
				}
				else {
					networkBox.selectAll("[data-type='visibility']").attr("xlink:href", "#icon-visible");
					hiddenNetworks = [];

					//Show all elements
					$("#nodes > g").show();
					$("#paths > path").show();
				}
			}

			//Cycles through all the specified nodes and links to determine visibility
			//NOTE: Directive: only paths are hidden (To implement, just comment out the node part)
			//NOTE: Implemented: all nodes and paths that are unique to the hidden categories are hidden
			//	so that having only 1 visible layer will only show element from that layer, no extra nodes
			function RefreshNetworkVisibility(nodeArray, linkArray) {

				//Hides nodes
				var count = nodeArray.length;
				for(var i = 0; i < count; i++) {

					var nodeId = nodeArray[i].id;
					if (GetNodeVisibility(nodeId)) {
						$("#nodes > #" + nodeId).show();
					}
					else {
						$("#nodes > #" + nodeId).hide();
					}
				}

				var count = linkArray.length;
				for(var i = 0; i < count; i++) {

					var linkId = GetLinkId(linkArray[i])
					if (GetLinkVisibility(linkId)) {
						$("#paths > #" + linkId).show();
					}
					else {
						$("#paths > #" + linkId).hide();
					}
				}
			}

			//Determines node visibility
			function GetNodeVisibility(nodeId) {

				var count = networks.length;
				var isGrouped = false;
				for (var i = 0; i < count; i++) {

					var node = networks[i].nodes.find(x => x.id === nodeId);
					if (node) {
						isGrouped = true;
					}

					if (node && !hiddenNetworks.find(x => x === networks[i].id)) {
						return true;
					}
				}

				if (!isGrouped) {
					return true;
				}

				return false;

			}

			//Determines link visibility
			function GetLinkVisibility(linkId) {

				var count = networks.length;
				var isGrouped = false;
				for (var i = 0; i < count; i++) {

					var link = networks[i].links.find(x => GetLinkId(x) === linkId);
					if (link) {
						isGrouped = true;
					}

					if (link && !hiddenNetworks.find(x => x === networks[i].id)) {
						return true;
					}
				}

				if (!isGrouped) {
					return true;
				}

				return false;
			}

			//Gets all the networks that a base node belongs to
			function GetNodeNetworks(nodeId) {

				var count = networks.length;
				var categories = [];
				for(var i = 0; i < count; i++) {
					var node = networks[i].nodes.find(x => x.id === nodeId);

					if (node) {
						categories.push(networks[i]);
					}
				}

				return categories;
			}

			//Gets all the networks that a base link belongs to
			function GetLinkNetworks(linkId) {

				var count = networks.length;
				var categories = [];
				for(var i = 0; i < count; i++) {
					var link = networks[i].links.find(x => GetLinkId(x) === linkId);

					if (link) {
						categories.push(networks[i]);
					}
				}

				return categories;
			}

			//Assigns specified nodes and links to the category
			function AssignToNetwork(categoryId, nodeArray, linkArray) {

				if (!categoryId) {
					return;
				}

				//Get network
				var network = networks.find(x => x.id === categoryId);
				var networkNodes = network.nodes;
				var networkPaths = network.links;
				var count;

				//Add nodes if not yet part of network
				count = nodeArray.length;
				for(var i = 0; i < count; i++) {
					if (!networkNodes.find(x => x.id === nodeArray[i].id)) {

						//Only copy the ID, prevent referencing and duplication of data
						networkNodes.push({ id: nodeArray[i].id });
					}
				}

				//Add links if not yet part of network
				count = linkArray.length;
				for(var i = 0; i < count; i++) {

					if (!networkPaths.find(x => GetLinkId(x) === GetLinkId(linkArray[i]))) {

						//Only copy the ID, prevent referencing and duplication of data; default lane count: 1
						networkPaths.push({ id: GetLinkId(linkArray[i]), laneCount: 1 });
					}
				}

				//Reset canvas
				layer.selectAll("circle.selected").classed("selected", false).attr("transform", "");
				layer.selectAll("path.selected").classed("selected", false);

				ActivateNetwork(categoryId);
				ResetAssignToNetworkIcons();
			}

			//Removes specified nodes and links to the category
			//If a removed node/link does not belong to other categories, node/link is deleted from base node/link list
			function RemoveFromNetwork(categoryId, nodeArray, linkArray) {

				var deleteNodeArray = [];
				var deleteLinkArray = [];

				//Get network
				var network = networks.find(x => x.id === categoryId);
				var networkNodes = network.nodes;
				var networkLinks = network.links;
				var count;

				//Remove nodes from network
				//Check if node belongs to more than 1 networks
				//If not, remove node and adjacent links from main list
				count = nodeArray.length;
				for (var i = 0; i < count; i++) {
					var node = networkNodes.find(x => x.id === nodeArray[i].id);
					var categoryCount = GetNodeNetworks(node.id).length;

					if (node) {
						var index = networkNodes.indexOf(node);
						networkNodes.splice(index, 1);

						if (categoryCount == 1) {
							deleteNodeArray.push(node);
						}
					}
				}

				//Remove links
				count = linkArray.length;
				for (var i = 0; i < count; i++) {
					var linkId = GetLinkId(linkArray[i]);
					var link = networkLinks.find(x => GetLinkId(x) === linkId);
					var categoryCount = GetLinkNetworks(linkId).length;

					if (link) {
						var index = networkLinks.indexOf(link);
						networkLinks.splice(index, 1);

						if (categoryCount == 1) {
							deleteLinkArray.push(link);
						}
					}
				}

				//Delete elements that doesn't belong to other networks
				if (deleteNodeArray.length > 0 || deleteLinkArray.length > 0) {
					DeleteSelection(deleteNodeArray, deleteLinkArray);
				}

				//Reset canvas
				layer.selectAll("circle.selected").classed("selected", false).attr("transform", "");
				layer.selectAll("path.selected").classed("selected", false);

				ActivateNetwork(categoryId);
				ResetAssignToNetworkIcons();

			}

			//Creates a new networks and adds it to the networks list
			function InsertNewNetwork(name) {

				var categoryCount = networks.length;
				if (++categoryCount == networkMaxCount) {
					// $("#AddCategoryButton").prop("disabled", true);
					$("#AddCategoryButton").addClass("disabled");
				}
				else if (categoryCount > networkMaxCount) {
					alert("Max allowed layers reached already.");
					$("#AddCategoryButton").addClass("disabled");
					return;
				}

				//Data
				var categoryId = GetNextNetworkId();
				if (!name) {
					name = categoryId;
				}
				var network = { id: categoryId, name: name, nodes: [], links: []}	;

				networks.push(network);

				AddNetworkToStyles(categoryId);
				AddNetworkToCanvas(categoryId, categoryId, categoryCount);
				ActivateNetwork(categoryId);

			}

			//Deletes a network from the project
			//TODO: Create separate functions for trigger and task (DeleteNetwork_Clicked & DeleteNetwork(categoryId))
			function DeleteNetwork(d) {
				StopPropagation();
				PreventDefault();

				if (!confirm("Are you sure you want to delete this network?")) {
					return;
				}

				var categoryId = d3.select(this).attr("data-id");
				var styleId = GetStyleId(categoryId);
				var activeCategoryId = GetActiveNetworkId();

				networks = networks.filter(function(network) {
					return network.id != categoryId;
				});

				//Empty the style where network used to be assigned to
				networkStyles[styleId] = null;

				RefreshNetworksBox();

				//Reactivate categories
				if (activeCategoryId && categoryId != activeCategoryId) {
					ActivateNetwork(activeCategoryId);
				}

				$("#AddCategoryButton").removeClass("disabled");

			}

			// Adds the info box to the canvas
			function InitializeInfoBox() {

				//TODO: Transfer styles to css;
				infoBox = svg.append("svg:g")
					.attr("id", "InfoBox")
					.attr("transform", "translate(" + infoBoxX + "," + infoBoxY + ")");
				infoBox.append("svg:rect")
					.attr("width", infoBoxWidth)
					.attr("height", infoBoxHeight)
					.style("outline", "thin solid black")
					.style("fill","white")
					.style("fill-opacity", "0.80");

				// var currentX = infoBoxTextBox_TopPadding;
				var currentY = infoBoxFontSize + infoBoxTextBox_TopPadding;

				infoBox.append("svg:text")
					.text("INFO BOX")
					.attr("id", "InfoBoxTitle")
					.attr("class", "title")
					.attr("x", infoBoxTextBox_TopPadding)
					.attr("y", currentY);

				//Prepares canvas for max number of properties allowed
				var textBox;
				for (var i = 0; i < infoPropertyMaxCount; i++) {
					currentY = currentY + (infoBoxFontSize + infoBoxTextBox_TopPadding);
					textBox = infoBox.append("svg:text")
						.attr("id", "TextBox" + i)
						.attr("x", infoBoxLabel_LeftPadding)
						.attr("y", currentY);
					textBox.append("svg:tspan").attr("class", "label").text("Label" + i);
					textBox.append("svg:tspan").attr("class", "value").attr("x", infoBoxValue_LeftPadding).text("Value" + i);
				}

				//Hide first until needed
				$("#InfoBox").hide();
			}

			//Displays the specific node's information on the info box when selected
			function ToggleNodeInfoBox(d) {

				//Disable when canvas is moving/moved
				if (previousTranslation.toString() != currentTranslation.toString()) {
					return;
				}

				//Limit info selection to only one node & path
				selectedNodes = [];
				selectedLinks = [];

				var node = d3.select(this);
				var nodeCircle = node.select("circle");
				var isSelected = nodeCircle.classed("selectInfo");

				circles.selectAll("circle").classed("selectInfo", false).attr("transform", "");
				paths.classed("selectInfo", false).attr("transform", "");

				if (isSelected) {
					$("#InfoBox").hide();
				}
				else {
					nodeCircle.classed("selectInfo", true).attr("transform", "scale(2)");

					PrepareNodeData(d);
					HighlightNetworkNames(GetNodeNetworks(d.id));
				}

				RefreshCategoryIcons();
			}

			//Prepares the properties of the node to be displayed
			function PrepareNodeData(node) {

				selectedNodes.push(node);

				//Customize values to be displayed
				//NOTE: Add values as needed (max properties implemented)
				var title = "Node Information";
				var id = node.id
				var properties = [];
				properties.push({ key: "id", label: "ID", value: id, enableEdit: false });
				properties.push({ key: "fx", label: "X", value: node.fx, enableEdit: false });
				properties.push({ key: "fy", label: "Y", value: node.fy, enableEdit: false });

				//If there is an active category, get network specific properties
				var network = GetActiveCategory();
				if (network) {
					var networkNode = network.nodes.find(x => x.id === id);

					if (networkNode) {
						properties.push({ key: "network", label: "Network", value: network.name, dataType: "string", enableEdit: false });
						properties.push({ key: "value", label: "Value", value: networkNode.value, dataType: "string", enableEdit: true });
					}
				}

				DisplayInfoBoxData("node", title, properties, id);
			}

			//Displays the specific link's information on the info box when selected
			function TogglePathInfoBox(d) {

				//Disable when canvas is moving/moved
				if (previousTranslation.toString() != currentTranslation.toString()) {
					return;
				}

				//Limit info selection to only one node & path
				selectedNodes = [];
				selectedLinks = [];

				var path = d3.select(this);
				var isSelected = path.classed("selectInfo");

				circles.selectAll("circle").classed("selectInfo", false).attr("transform", "");
				paths.classed("selectInfo", false);

				if (isSelected) {
					$("#InfoBox").hide();
					path.style('marker-end', 'url(#end-arrow-hover)');

					ResetStyles();
				}
				else {

					path.classed("selectInfo", true);
					path.style('marker-end', 'url(#end-arrow-hover)'); //Keep hovered state

					PreparePathData(d);
					HighlightNetworkNames(GetLinkNetworks(GetLinkId(d)))
				}

				RefreshCategoryIcons();
			}

			//Prepares the properties of the link to be displayed
			function PreparePathData(link) {

				selectedLinks.push(link);

				//Customize values to be displayed
				//NOTE: Add values as needed (max properties implemented)
				var title = "Path Information";
				var id = GetLinkId(link);
				var properties = [];
				properties.push({ key: "id", label: "ID", value: id, dataType: "string", enableEdit: false });
				properties.push({ key: "source", label: "Source", value: link.source.id, dataType: "string", enableEdit: false });
				properties.push({ key: "sourceX", label: "Source X", value: link.source.fx, dataType: "string", enableEdit: false });
				properties.push({ key: "sourceY", label: "Source Y", value: link.source.fy, dataType: "string", enableEdit: false });
				properties.push({ key: "target", label: "Target", value: link.target.id, dataType: "string", enableEdit: false });
				properties.push({ key: "targetX", label: "Target X", value: link.source.fx, dataType: "string", enableEdit: false });
				properties.push({ key: "targetY", label: "Target Y", value: link.source.fy, dataType: "string", enableEdit: false });

				//If there is an active category, get network specific properties
				var network = GetActiveCategory();
				if (network) {
					var networkLink = network.links.find(x => GetLinkId(x) === id);

					if (networkLink) {
						properties.push({ key: "network", label: "Network", value: network.name, dataType: "string", enableEdit: false });
						properties.push({ key: "laneCount", label: "Lane #", value: networkLink.laneCount, dataType: "int",enableEdit: true });
						properties.push({ key: "value", label: "Value", value: networkLink.value, dataType: "string", enableEdit: true });
					}
				}

				DisplayInfoBoxData("path", title, properties, id);

			}

			//Displays the specific networks's information on the info box when selected
			function ToggleNetworkInfoBox(d) {
				StopPropagation();
				PreventDefault();

				var element = d3.select(this);
				var isSelected = (element.attr("xlink:href") == "#icon-info-alt");
				var categoryId = element.attr("data-id");

				//Limit info selection to only one node/path/network
				selectedNodes = [];
				selectedLinks = [];

				circles.selectAll("circle").classed("selectInfo", false).attr("transform", "");
				paths.classed("selectInfo", false);

				networkBox.selectAll("[data-type='info']").attr("xlink:href","#icon-info");

				if (isSelected) {
					$("#InfoBox").hide();
					return;
				}

				element.attr("xlink:href", "#icon-info-alt");

				PrepareNetworkData(categoryId);
				ActivateNetwork(categoryId);
			}

			//Prepares the properties of the network to be displayed
			function PrepareNetworkData(networkId) {

				var network = networks.find(x => x.id === networkId);

				var title = "Network Information";
				var id = networkId;

				//NOTE: Add properties as needed (max properties implemented)
				var properties = []
				properties.push({ key: "id", label: "ID", value: networkId, dataType: "string", enableEdit: false });
				properties.push({ key: "name", label: "Name", value: network.name, dataType: "string", enableEdit: true });
				properties.push({ key: "pcu", label: "PCU", value: network.pcu, dataType: "int", enableEdit: true });

				DisplayInfoBoxData("category", title, properties, id);
			}

			//Displays the info box with all the properties passed
			//objectType = node, link or network
			//title = text displayed on top of the info box
			//properties = array of propreties to be displayed
			//id = id of the specific object (for when editing values)
			function DisplayInfoBoxData(objectType, title, properties, id) {

				//Reset data values
				infoBox.select("text").attr("data-key", "").attr("data-id", "");
				infoBox.selectAll("tspan").text("").classed("edit", false);
				infoBox.selectAll("text").attr("data-type", objectType);

				infoBox.select("#InfoBoxTitle").text(title);

				//TODO: Account for text wrapping
				var propertiesCount = (infoPropertyMaxCount > properties.length) ? properties.length : infoPropertyMaxCount;
				for(var i = 0; i < propertiesCount; i++) {

					var property = properties[i];
					var value = property.value;
					if (!property.value && property.enableEdit) {
						value = infoBoxDefaultEditValue;
					}

					infoBox.select("#TextBox" + i).attr("data-id", id);
					infoBox.select("#TextBox" + i).attr("data-key", property.key);
					infoBox.select("#TextBox" + i).attr("data-dataType", property.dataType);
					infoBox.select("#TextBox" + i + " .label").text(property.label);
					infoBox.select("#TextBox" + i + " .value").text(value).classed("edit", property.enableEdit);
				}

				//Add events to editable text
				infoBox.selectAll("tspan.edit")
					.on("mouseover", function(d) { d3.select(this).classed("hover", true)})
					.on("mouseout", function(d) { d3.select(this).classed("hover", false)})
					.on("dblclick", function(d) {
						PreventDefault();
						DisplayInputBox(this, infoBox);
					});

				$("#InfoBox").show();
			}

			// Adds the network box to the canvas
			function InitializeNetworkBox() {

				//TODO: Transfer styles to css;
				networkBox = svg.append("svg:g")
					.attr("id", "LayerBox")
					.attr("transform", "translate(" + networkBoxX + "," + networkBoxY + ")");
				networkBox.append("svg:rect")
					.attr("width", networkBoxWidth)
					.attr("height", networkBoxHeight)
					.style("outline", "thin solid black")
					.style("fill","white")
					.style("fill-opacity", "0.80")
					.on("click", PreventDefault);

				// var currentX = infoBoxTextBox_TopPadding;
				var currentY = infoBoxFontSize + infoBoxTextBox_TopPadding;

				networkBox.append("svg:text")
					.text("NETWORKS")
					.attr("id", "LayerBoxTitle")
					.attr("class", "title")
					.attr("x", infoBoxTextBox_TopPadding)
					.attr("y", currentY);

				RefreshNetworksBox();

				//Hide first until needed
				// $("#LayerBox").hide();
			}

			//Creates a foreign object with in the svg canvas to take inputs when editing values
			function DisplayInputBox(d, sectionBox) {
				//Source: http://bl.ocks.org/GerHobbelt/2653660

				var element = d3.select(d);
				var parentElement = d3.select(d.parentNode);
				var elementBox = element.node().getBBox();
				var currentValue = element.text();
				var id = parentElement.attr("data-id");

				//Calculate Dimensions
				var formType, formX, formY, formWitdh, formHeight;
				if (element.classed("label")) { //Label
					formType = "label";
					formX = elementBox.x;
					formWidth = "10%";
				}
				else if (element.classed("value")) {
					formType = "value";
					formX = infoBoxValue_LeftPadding;
					formWidth = (100 - parseInt(formX)) + "%";	//TODO: Find better way of computing
				}
				else if (element.classed("layerText")) {
					formType = "layerText";
					formX = (infoBoxLabel_LeftPadding * 2) + networkBoxIconWidth;
					formWidth = (100 - parseInt(formX)) + "%";	//TODO: Find better way of computing
				}

				formY = elementBox.y - 4; //4px padding
				formHeight = 16;	//line height

				//TODO: Transfer styles to css
				var form = sectionBox.append("foreignObject");
				var input = form
					.attr("width", formWidth)
					.attr("height", formHeight)
					.attr("x", formX)
					.attr("y", formY) //4px padding
					.append("xhtml:form")
						.attr("id", "InputBox")
						.append("input")
							.attr("value", function() {
								// this.focus();
								return currentValue;
							})
							.attr("style", "width: 100%;")
							.on("focus", function() {
								this.select();

								//Disable zooming and panning while editing
								zoomEvent.on("zoom", null);
								svg.call(zoomEvent);
							})
							.on("blur", function() {

								try {
									sectionBox.select("foreignObject").remove();

									var objectType = parentElement.attr("data-type");
									var dataType = parentElement.attr("data-dataType");
									var key = parentElement.attr("data-key");
									var value = input.node().value;

									if (currentValue == value) {
										return;
									}

									if (value == "" || value == infoBoxDefaultEditValue) {
										element.text(infoBoxDefaultEditValue);
										return;
									}

									//Force dataType
									if (dataType == "int") {
										value = parseInt(value);
									}

									element.text(value);
									// UpdateValue(objectType, key, value, id);

									switch(objectType) {
										case "path": UpdatePathValue(key, value, id); break;
										case "node": UpdateNodeValue(key, value, id); break;
										case "category": UpdateNetworkValue(key, value, id); break;
										default: console.log("ERROR: objectType not recognized.");

									}

									RefreshCanvas();
									RefreshPathStyles();
									RefreshPathWidths();
								}
								finally {
									//Re-enable zooming and panning
									zoomEvent.on("zoom", Zooming);
									svg.call(zoomEvent);
								}

							})
							.on("keypress", function() {

								//NOTE: From http://bl.ocks.org/GerHobbelt/2653660
								// IE fix
                  if (!d3.event) {
                      d3.event = window.event;
                  }

                  var e = d3.event;
                  if (e.keyCode == 13) {	//ENTER

                  	//NOTE: From http://bl.ocks.org/GerHobbelt/2653660
                  	if (typeof(e.cancelBubble) !== 'undefined') {	// IE
                      	e.cancelBubble = true;
                    	}
                      if (e.stopPropagation) {
                        	e.stopPropagation();
                      }

                      e.preventDefault();

                      this.blur();
                  }
							});

					$("#InputBox input").focus();
			}

			//Updates a path based on it's id, key property and value
			function UpdatePathValue(key, value, id) {

				var link;

				//If a network is active, update properties for that network
				//If not, update base link values
				var network = GetActiveCategory();
				if (network) {
					link = network.links.find(x => GetLinkId(x) === id);
				}
				else {
					link = links.find(x => GetLinkId(x) === id);
				}

				link[key] = value;

				//Update calculated values of base links
				RefreshLinksData();
			}

			//Updates a node based on it's id, key property and value
			function UpdateNodeValue(key, value, id) {

				var node;

				//If a network is active, update properties for that network
				//If not, update base node values;
				var network = GetActiveCategory();
				if (network) {
					node = network.nodes.find(x => x.id === id);
				}
				else {
					node = nodes.find(x => x.id === id);
				}

				node[key] = value;

				//Update calculated values of base nodes if any
				RefreshNodesData();
			}

			//Updates a network based on it's id, key property and value
			function UpdateNetworkValue(key, value, id) {

				var network = networks.find(x => x.id === id);
				network[key] = value;

				if (key == "name") {
					RefreshNetworksBox();
				}

				ActivateNetwork(network.id);
			}

			//Starts zoom event
			function ZoomStart() {
				StopPropagation();

				//TODO: Implement cursor changes
				// svg.style("cursor", "move"); //Not working

				//Prevents adding a node after panning
				previousTranslation = currentTranslation;
				previousScale = currentScale;

				//If shift key is pressed, disable panning
				if (d3.event.sourceEvent && d3.event.sourceEvent.shiftKey) {
					zoomRectEnabled = true;

					zoomRectCoordinates[0] = (d3.mouse(this)[0] - currentTranslation[0]); // / currentScale;
					zoomRectCoordinates[1] = (d3.mouse(this)[1] - currentTranslation[1]); // / currentScale;
				}

			}

			//During zoom event
			function Zooming(d) {

				//Area Zoom
				if (zoomRectEnabled){

					var point = d3.mouse(this);

					//Adjust according to translation and scale
					point[0] = (point[0] - currentTranslation[0]);// / currentScale;
					point[1] = (point[1] - currentTranslation[1]);// / currentScale;

					var rectX = zoomRectCoordinates[0];
					var rectY = zoomRectCoordinates[1];
					var rectWidth = Math.abs(zoomRectCoordinates[0] - point[0]);
					var rectHeight = Math.abs(zoomRectCoordinates[1] - point[1]);

					//Invert box coordinates if negative
					if (point[0] < rectX) {
						rectX = point[0];
					}

					if (point[1] < rectY) {
						rectY = point[1];
					}

					zoomRectDimensions = [rectWidth, rectHeight];

					UpdateZoomRect(rectX, rectY, rectWidth, rectHeight);

					return;
				}

				//Default zoom event
				// currentTranslation = d3.event.translate;
			 	// currentScale = d3.event.scale;
				// canvas.attr("transform", "translate(" + currentTranslation + ") scale(" + currentScale + ")");

				currentTranslation = [zoomEvent.translate()[0] - initialZoomTranslate[0], zoomEvent.translate()[1] - initialZoomTranslate[1]];
				currentZoomScale = zoomEvent.scale();

				RefreshMapZoom();
			}

			//Displays the map as it is zoomed/panned
			function RefreshMapZoom(d) {

				invProjection
					.scale(zoomEvent.scale() / 2 / Math.PI)
					.translate(zoomEvent.translate());

				var tiles = tile
					.scale(zoomEvent.scale())
					.translate(zoomEvent.translate())();

				//Clear map
				// $("#MapLayer").html("");

				var tileImages = map
					.attr("transform","scale(" + tiles.scale + ")translate(" + tiles.translate + ")")
					.selectAll(".mapTile")
					.data(tiles, function(d) { return d; });

				tileImages.exit().remove();

				//Default tile implementation
				// tileImages.enter().append("image")
				//   	.attr("xlink:href", function(d) { return "http://" + ["a", "b", "c"][Math.random() * 3 | 0] + ".tile.openstreetmap.org/" + d[2] + "/" + d[0] + "/" + d[1] + ".png"; })
				//   	.attr("class", "mapTile")
				//     .attr("width", 1)
				//     .attr("height", 1)
				//     .attr("x", function(d) { return d[0]; })
				//     .attr("y", function(d) { return d[1]; });
				// canvas.attr("transform", "translate(" + zoomEvent.translate()+ ")scale(" + zoomEvent.scale() + ")");
				// d3.select("#nodes").selectAll(".node").attr("r", 24 / zoomEvent.scale()).style("stroke-width", 8 / zoomEvent.scale());

				var tileImage = tileImages.enter().append("g")
					.attr("class", "mapTile")
			    .attr("width", 1)
			    .attr("height", 1)
			    .attr("x", function(d) { return d[0]; })
			    .attr("y", function(d) { return d[1]; });
				tileImage.append("rect")
					.style("fill", "#E7E7E7")
			    .attr("width", 1)
			    .attr("height", 1)
			    .attr("x", function(d) { return d[0]; })
			    .attr("y", function(d) { return d[1]; });
				tileImage.append("use")
					.attr("xlink:href", "#icon-loading")
					.attr("width", 0.25)
			    .attr("height", 0.25)
			    .attr("x", function(d) { return d[0] + 0.5 - (0.25/2); })
			    .attr("y", function(d) { return d[1] + 0.5 - (0.25/2); });
				tileImage.append("image")
	      	.attr("xlink:href", function(d) { return "http://" + ["a", "b", "c"][Math.random() * 3 | 0] + ".tile.openstreetmap.org/" + d[2] + "/" + d[0] + "/" + d[1] + ".png"; })
			    .attr("width", 1)
			    .attr("height", 1)
			    .attr("x", function(d) { return d[0]; })
			    .attr("y", function(d) { return d[1]; })
					.on("load", function() {
						//Remove rectangle and loading icon once loaded
						var parent = d3.select(this.parentNode);
						parent.select("rect").remove();
						parent.select("use").remove();
					});

				canvas.attr("transform","translate(" + currentTranslation + ")");
				circles.attr("transform", CalculateNodeTranslation);
				paths.style("stroke-width", CalculateLinkWidth)
					.attr("d", CalculateLinkCoordinates);
			}

			//Formats the coordinate text
			function FormatCoordinates(p, k) {
				//SOURCE: http://bl.ocks.org/mbostock/4132797
			  var format = d3.format("." + Math.floor(Math.log(k) / 2 - 2) + "f");
			  return (p[1] < 0 ? format(-p[1]) + "°S" : format(p[1]) + "°N") + " "
			       + (p[0] < 0 ? format(-p[0]) + "°W" : format(p[0]) + "°E");
			}

			//Ends the zoom event
			function ZoomEnd(d) {
				//TODO: Implement cursor changes
				// svg.style("cursor", "auto"); //Not working
				// canvas.classed("moving", false);

				if (zoomRectEnabled) {

					var scale;
					var widthScale = width / zoomRectDimensions[0];
					var heightScale = height / zoomRectDimensions[1];

					// Choose the smaller scale so that the entire selected area is in view
					if (widthScale > heightScale) {
						scale = heightScale;
					}
					else {
						scale = widthScale;
					}

					//Calculate zoom adjustment, limit to max zoom
					scale = scale * zoomEvent.scale();
					if (scale > zoomMin) {
						scale = zoomMin
					}

					CenterView(zoomRect.node(), scale);

					//Reset zoom rectangle
					zoomRectEnabled = false;
					zoomRectCoordinates [0, 0];
					zoomRectDimensions = [0, 0];
					UpdateZoomRect(0, 0, 0, 0);
				}
			}

			//Starts drag event for moving
			function DragStart(d) {
				StopPropagation(); // silence other listeners
				d3.select(this).select("circle").classed("dragged", true);

			}

			//Ends drag event for moving
			function DragEnd(d) {
				d3.select(this).select("circle").classed("dragged", false);
			}

			//Moves all the selected nodes and links
			function MoveSelection(d) {
				//NOTE: d is the node being dragged - not used since updating all nodes selected

				//Adjust coordinates for selected nodes
				var selectedNodeCount = selectedNodes.length;
				var nodeCount = nodes.length;

				for (var i = 0; i < selectedNodeCount; i++) {

					for(var j = 0; j < nodeCount; j++) {

						if (nodes[j].id == selectedNodes[i].id) {

							var mouseTranslate = invProjection([nodes[j].fx, nodes[j].fy]);
							var newCoordinates = invProjection.invert([mouseTranslate[0] + d3.event.dx, mouseTranslate[1] + d3.event.dy]);
							nodes[j].fx = newCoordinates[0];
							nodes[j].fy = newCoordinates[1];

							UpdateAdjacentLinks(nodes[j]);
						}
					}
				}

				//Update elements
				//TODO: Recalculate only those affected nodes/links
				svg.select("#nodes").selectAll("g").attr('transform', CalculateNodeTranslation);
				svg.select("#paths").selectAll("path").attr("d", CalculateLinkCoordinates);
			}

			//Deletes all the specified nodes and links from the canvas and from the categories they belong to
			function DeleteSelection(nodeArray, linkArray) {

				var nodeCount = nodeArray.length;
				var linkCount = linkArray.length;

				var count = nodeArray.length;
				for(var i = 0; i < count; i++) {

					nodes = nodes.filter(function(x) { return x.id != nodeArray[i].id; });
				}

				count = linkArray.length;
				for(var i = 0; i < count; i++) {

					links = links.filter(function(x) { return GetLinkId(x) != GetLinkId(linkArray[i]); });
				}

				RemoveFromNetworks(nodeArray, linkArray);
				RefreshCanvas();
				RefreshPathStyles();
			}

			//Creates and adds new node if not yet existing
			function InsertNewNode() {

				//Disable when canvas is moving/moved
				if (previousTranslation.toString() != currentTranslation.toString()) {
					return;
				}

				//Get current active category
				var categoryId = GetActiveNetworkId();
				if (!categoryId) {
					alert("Please select a network first.");
					return;
				}

				//Actual lat/long
				var point = invProjection.invert(d3.mouse(this));
				var x = point[0];
				var y = point[1];

				var node = { id: GetNextNodeId(), fx: x, fy: y};

				if (CheckNodeDuplicate(node)) {
					console.log("Node already exists");
					return;
				}

				//Add to base nodes
				nodes.push(node);
				RefreshCanvas();

				//Add to current active category
				AssignToNetwork(categoryId, [node], []);
			}

			//Creates and adds link if not yet existing
			function InsertNewLink(sourceNode, endNode) {

				var link = { source: sourceNode, target: endNode, laneCount: 1 };

				if (CheckLinkDuplicate(link)) {
					console.log("Link already exists.");
					return;
				}

				//Add to base links
				links.push(link);
				RefreshCanvas();

				//Add to current active category
				var categoryId = GetActiveNetworkId();
				AssignToNetwork(categoryId, [sourceNode, endNode], [link]);
			}

			//Selects the specified node as the source node when adding links
			function SelectSourceNode(d) {

				sourceNode = d;
				dragLine.style("marker-end", "url(#end-arrow)")
					.classed("hidden", false)
					.attr("d", "M" + sourceNode.fx + "," + sourceNode.fy + "L" + sourceNode.fx + "," + sourceNode.fy);

				//Enable drag
				svg.on("mousemove", UpdateDragLine)
					.on("mousedown.zoom", null);

			}

			//Selects the specified node as the target node when adding links
			function SelectEndNode(d) {

				if (sourceNode == null) {
					HideDragLine();
					return;
				}

				//Check if same with source node
				if (sourceNode.id == d.id) {
					console.log("Cannot add link with same source and end nodes.");
					HideDragLine();
					return;
				}

				InsertNewLink(sourceNode, d);
				HideDragLine();

			}

			//Adds specified node to the selectedNodes array for moving, assigning and/or deleting
			//Adds ajacent paths to the adjacentPaths array for moving and deleting
			function SelectNode(d) {

				//Note: Prevents from deselecting node after drag
				if (d3.event.defaultPrevented) return;

				var node = d3.select(this);
				var nodeCircle = node.select("circle");
				var isSelected = nodeCircle.classed("selected");

				if (isSelected) {

					selectedNodes = selectedNodes.filter(function(node){
						return node.id != d.id;
					});

					adjacentLinks = adjacentLinks.filter(function(link) {
						return link.source.id != d.id && link.target.id != d.id;
					});

					nodeCircle.classed("selected", false)
						.attr("transform", "");

					node.call(dragNullEvent);
				}
				else {

					selectedNodes.push(d);
					adjacentLinks.push.apply(adjacentLinks, GetAdjacentLinks(d));
					nodeCircle.classed("selected", true)
						.attr("transform", "scale(2)");

					node.call(dragMoveEvent);
				}

				RefreshCategoryIcons();
			}

			//Selects all the nodes
			function SelectAllNodes() {

				var selectTransform = "scale(2)";

				selectedNodes = nodes;
				circles.selectAll("circle")
					.classed("selected", true)
					.attr("transform", "scale(2)");
				circles.call(dragMoveEvent);

				selectEnabled = true;
			}

			//Adds specified link to the selectedLinks array for moving, assigning and/or deleting
			//Adds ajacent nodes to the adjacentNodes array for assigning
			function SelectLink(d) {

				StopPropagation();

				var linkPath = d3.select(this);
				var isSelected = linkPath.classed("selected");


				if (isSelected) {

					selectedLinks = selectedLinks.filter(function(link) {
						return link.source.id != d.source.id && link.target.id != d.target.id;
					});
					linkPath.classed("selected", false);
				}
				else {

					selectedLinks.push(d);
					adjacentNodes.push(d.source);
					adjacentNodes.push(d.target);
					linkPath.classed("selected", true);
				}

				RefreshCategoryIcons();
				RefreshPathStyles();
			}

			//Selects all the links
			function SelectAllLinks() {
				selectedLinks = links;
				adjacentNodes = nodes;

				layer.select("#paths").selectAll("path").classed("selected", true);

				selectEnabled = true;

				RefreshPathStyles();
			}

			//Centers the canvas to the center of all data points
			function CenterCanvas() {

				var nodeCount = nodes.length;
				if (nodeCount == 0) {
					return;
				}

				CenterView(canvas.node(), zoomEvent.scale());

			}

			//Centers the view on the specified view node (canvas/zoomRect) with the specified scale
			function CenterView(viewNode, scale) {

				//https://bl.ocks.org/catherinekerr/b3227f16cebc8dd8beee461a945fb323
				var bbox = viewNode.getBBox();
				var midX = bbox.x + (bbox.width / 2) + currentTranslation[0];
				var midY = bbox.y + (bbox.height / 2) + currentTranslation[1];
				var midGeo = invProjection.invert([midX, midY]); //Get middle geographical coordinates


				//Reset projection center and scale
				projection.center(midGeo)
					.scale(scale / 2 / Math.PI);

				//Reset zoomEvent with new projection center
				zoomEvent
					.scale(projection.scale() * 2 * Math.PI)
					.translate(projection([0,0]));

				RefreshMapZoom();
			}

			//Resizes the zoom rectangle as it is dragged across the canvas
			function UpdateZoomRect(x, y, w, h) {

				zoomRect.attr("x", x)
					.attr("y", y)
					.attr("width", w)
					.attr("height", h);

			}

			//Recalculates the drag line as it is dragged across the canvas
			function UpdateDragLine() {

				if (!sourceNode) {
					return;
				}

				//Adjust mouse pointers with canvas translation
				// var x = (d3.mouse(this)[0] - currentTranslation[0]) / currentScale;
				// var y = (d3.mouse(this)[1] - currentTranslation[1]) / currentScale;

				var point = invProjection.invert(d3.mouse(this));
				var x = point[0];
				var y = point[1];

				var link = { source: sourceNode, target: { fx: x, fy: y}};
				var linkCoordinates = CalculateLinkCoordinates(link);
				var linkWidth = CalculateLinkWidth(link);
				dragLine.attr("d", linkCoordinates)
					.style("stroke-width", linkWidth);
			}

			//Hides drag line after adding a link
			function HideDragLine() {
				sourceNode = null;
				dragLine.classed('hidden', true)
			        .style('marker-end', '')
			        .attr("d", "");

			    //Enable zoom again
				svg.on("mousemove", UpdateCoordinates)
					.call(zoomEvent)
			}

			//Updates the coordinates text based on where the mouse cursor is on the canvas
			function UpdateCoordinates() {
				d3.select("#CoordinatesText").text(FormatCoordinates(invProjection.invert(d3.mouse(this)), zoomEvent.scale()));
			}

			// Calculates the coordinates of the path based on set adjustments (source/target padding, center offset)
			function CalculateLinkCoordinates(d) {

		    var sourceTranslate = invProjection([d.source.fx, d.source.fy]);
		    var targetTranslate = invProjection([d.target.fx, d.target.fy]);

		    var sourceTranslateX = (sourceTranslate[0] - currentTranslation[0]);
				var sourceTranslateY = (sourceTranslate[1] - currentTranslation[1]);
				var targetTranslateX = (targetTranslate[0] - currentTranslation[0]);
				var targetTranslateY = (targetTranslate[1] - currentTranslation[1]);

				//NOTE: Divide by scale if paths must not scale with map
				var sPadding = sourcePadding / (initialZoomScale / currentZoomScale);
				var tPadding = targetPadding / (initialZoomScale / currentZoomScale);

				var deltaX = targetTranslateX - sourceTranslateX,
		        deltaY = targetTranslateY - sourceTranslateY,
		        dist = Math.sqrt(deltaX * deltaX + deltaY * deltaY),
		        normX = deltaX / dist,
		        normY = deltaY / dist,
		        sourceX = sourceTranslateX + (sPadding * normX),
		        targetX = targetTranslateX - (tPadding * normX),
		        sourceY = sourceTranslateY + (sPadding * normY),
		        targetY = targetTranslateY - (tPadding * normY);

		      	// adjustments
					var gapDistance = Math.floor(distanceFromCenter / (initialZoomScale / currentZoomScale));
			    if(deltaX == 0) {
			    	var x_adj = Math.sqrt(gapDistance),
			        	y_adj = 0;
			    }
			    else{
		    		var slope = Math.abs(deltaY/deltaX),
			            y_adj = Math.sqrt(gapDistance / (1 + (slope*slope))),
			            x_adj = slope*y_adj;
		      	}

			    // to modify the link
			    if (sourceX < targetX) {
		    	  	if(sourceY < targetY) x_adj = x_adj*(-1);
			        sourceX += x_adj;
			        targetX += x_adj;
			        sourceY += y_adj;
			        targetY += y_adj;
			    }
			    else{
			        if(sourceY > targetY) x_adj = x_adj*(-1);
			        sourceX -= x_adj;
			        targetX -= x_adj;
			        sourceY -= y_adj;
			        targetY -= y_adj;
			    }

			    if (!sourceX || !sourceY || !targetX || !targetY) {
			    	return "";
			    }

			    var adjDeltaX = targetX - sourceX;
			    var adjDeltaY = targetY - sourceY;

			    //If deltas doesn't have the same sign, path has inverted
			    //TODO: Verify implementation, hide line instead?
			    if ((adjDeltaX > 0 ^ deltaX > 0) && (adjDeltaY > 0 ^ deltaY > 0)) {
			    	return "";
			    }

	        return "M" + [sourceX,sourceY]
		    	// + "L" + [sourceX,sourceY] //NOTE: Workaround - path does not react to width change if there is only one L
		    	+"L" + [targetX,targetY];

			}

			//Calculate specified link's width based on it's lane count
			function CalculateLinkWidth(d) {

				var laneCount = d.laneCount;

				var strokeWidth = defaultPathStrokeWidth;
				if (!laneCount) {
					return strokeWidth;
				}

				strokeWidth = strokeWidth + laneCount;
				if (strokeWidth > maxPathStrokeWidth) {
					return maxPathStrokeWidth;
				}

				return strokeWidth;
			}

			//Calculate specified node's position on the canvas
			function CalculateNodeTranslation(d) {

				var translate = invProjection([d.fx, d.fy]);
				var x = (translate[0] - currentTranslation[0]);
				var y = (translate[1] - currentTranslation[1]);

				//NOTE: Uncomment if nodes must scale with map
				var scale = 1; // / (initialZoomScale / currentZoomScale);

				return "translate(" + [x,y] + ")scale(" + scale + ")";
			}

			//Calculate center based on nodes
			function CalculateCanvasCenter() {

				var nodeCount = nodes.length;
				if (nodeCount == 0) {
					return {x : width/2, y: height/2 };
				}

				var minX = nodes[0].fx;
				var minY = nodes[0].fy;
				var maxX = nodes[0].fx;
				var maxY = nodes[0].fy;

				for(var i = 0; i < nodeCount; i++) {
					if (nodes[i].fx > maxX) { maxX = nodes[i].fx }
					if (nodes[i].fy > maxY) { maxY = nodes[i].fy }
					if (nodes[i].fx < minX) { minX = nodes[i].fx }
					if (nodes[i].fy < minY) { minY = nodes[i].fy }
				}

				var centerX = maxX - ((maxX - minX)/2);
				var centerY = maxY - ((maxY - minY)/2);
				return {x: centerX, y: centerY};
			}

			//Check if node already exists based on fx and fy
			function CheckNodeDuplicate(node) {

				if (nodes.find(x => x.fx === node.fx && x.fy === node.fy))
					return true;
				return false;
			}

			//Check if link already exists based on source and target fx and fy
			//TODO: Find better, faster implementation
			function CheckLinkDuplicate(link) {

				var linkCount = links.length;

				for(var i = 0; i < linkCount; i++) {

					if (link.source.fx == links[i].source.fx &&
						link.source.fy == links[i].source.fy &&
						link.target.fx == links[i].target.fx &&
						link.target.fy == links[i].target.fy) {
							return true;
					}
				}

				return false;
			}

			//Updates the link source and target coordinates when moving
			function UpdateAdjacentLinks(node) {

				var linkCount = adjacentLinks.length;
				for (var i = 0; i < linkCount; i++) {
					if (adjacentLinks[i].source.id == node.id) {
						adjacentLinks[i].source = node;
					}
					else if (adjacentLinks[i].target.id == node.id) {
						adjacentLinks[i].target = node;
					}
				}
			}

			//Removes passed nodes and array from all the categories they belong to
			function RemoveFromNetworks(nodeArray, linkArray) {

				var count = networks.length;
				for (var i = 0; i < count; i++) {

					var networkNodes = networks[i].nodes;
					var selectionCount = nodeArray.length;
					for(var j = 0; j < selectionCount; j++) {

						networkNodes = networkNodes.filter(function(thisNode) { return thisNode.id != nodeArray[j].id});

					}

					var networkLinks = networks[i].links;
					selectionCount = linkArray.length;
					for(var j = 0; j < selectionCount; j++) {

						var linkId = GetLinkId(linkArray[j]);
						networkLinks = networkLinks.filter(function(thisLink) { return GetLinkId(thisLink) != linkId});
					}

					networks[i].nodes = networkNodes;
					networks[i].links = networkLinks;
				}
			}

			//Lists all the links connected to the specified node
			function GetAdjacentLinks(node) {

				var adjacent = [];

				var linkCount = links.length;
				for (var i = 0; i < linkCount; i++) {
					if (links[i].source.id == node.id || links[i].target.id == node.id) {
						adjacent.push(links[i]);
					}
				}

				return adjacent;
			}

			function StopPropagation() {
				if (d3.event.sourceEvent) {
					d3.event.sourceEvent.stopPropagation();
				}
			}

			function PreventDefault() {
				// IE fix
        if (!d3.event) {
            d3.event = window.event;
        }

        var e = d3.event;
        if (e.stopPropagation) {
          	e.stopPropagation();
        }

        e.preventDefault();
			}

			//Combines source and target ids if the link doesn't have a set id
			//TODO: Check if still needed
			function GetLinkId(d) {
				if (!d.id)
					return d.source.id + "_" + d.target.id;
				return d.id;
			}

			//Returns the id of the active network
			function GetActiveNetworkId() {
				var active = networkBox.select(".active");
				if (!active.empty()) {
					return active.attr("data-id");
				}

				return null;
			}

			//Returns the active network
			function GetActiveCategory() {
				var active = networkBox.select(".active");
				var category = null;

				if (!active.empty()) {
					category = networks.find(x => x.id === active.attr("data-id"));
				}

				return category;
			}

			//Gets the style id of a network
			function GetStyleId(categoryId) {
				return networkStyles.indexOf(categoryId);
			}

			//Determines the next node id
			function GetNextNodeId() {

				var newNodeId = "";

				do {
						newNodeId = prefixNode + ++lastNodeId;
				}
				while (nodes.find(x => x.id == newNodeId));

				return newNodeId;

			}

			//Determines the next network id
			function GetNextNetworkId() {
				var newCategoryId = "";

				do {
					newCategoryId = prefixNetwork + ++lastCategoryId;
				}
				while (networks.find(x => x.id == newCategoryId));

				return newCategoryId;
			}

			//Initializes jquery uploader triggered by the Load Project button
			function InitializeUploader() {
	        var chunkCount = 0;
	        var dataLoaded = 0;
	        var chunkCurrent = 0;
	        var multiFileLimit = 1;

					$('#LoadProjectButton').fileupload({
						url: "Scripts/Private/Services.php",
						formData: { functionName: "FileManager.UploadFile" },
		        dataType: 'json',
	          multipart: true,
						maxFileSize: 2000000000, //2GB
						acceptFileTypes: /(\.|\/)(txt|csv|json)$/i,
	          limitMultiFileUploads: multiFileLimit,
	          add: function (e, data) {
	            if (data.autoUpload || (data.autoUpload !== false &&
	                $(this).fileupload('option', 'autoUpload'))) {
	              var $this = $(this);
	              data.process(
	                function () {
	                $("#ProgressBarDiv").show();
	                // $("#ProgressBarText").text("Preparing files. Please wait.");
	                // $(".dropZoneContainer").hide();
	                chunkCount += Math.ceil(data.files.length / 20);
	                return $this.fileupload('process', data);
	              }
	              ).done(function () {
	                data.submit();
	              });
	            }
	          },
	          progressall: function (e, data) {
	            var progressCurrent = parseInt(data.loaded / data.total * 100, 10);
	            var progressTotal = parseInt((progressCurrent + (100 * chunkCurrent)) / chunkCount, 10);
	            $('#ProgressBar').css('width',progressTotal + '%');
	            // $("#ProgressBarText").text("Uploading " + progressTotal + "%");
	          },
	          done: function (e, data) {
	            console.log("COMPLETED");
							console.log(data.result);
	            chunkCurrent++;

							var fileInfo = data.result.result.response.files[0]; //Limit to single file

							if (!fileInfo.error) {
								ExtractDataFromFile(fileInfo.relativePath);
							}
							else {
								console.log("ERROR: " + fileInfo.error);
							}

							$("#ProgressBarDiv").hide();
	          },
						processfail: function (e, data) {
			        alert(data.files[data.index].name + "\n" + data.files[data.index].error);
				    },
	          fail: function (e, data) {
	            // $("#UploadAreaFormErrorList").append("<li>An error occurred. Please try again or contact the admin.</li>");
	            $("#ProgressBarDiv").hide();
	            console.log("ERROR: " + data.errorThrown);
	          },
	          start: function(e) {
	            console.log("START");
	          },
	          stop: function(e) {
	            console.log("STOP");
	            if (chunkCurrent == chunkCount)
	            {
	              console.log("REDIRECT");
	              // $("#ProgressBarText").text("Upload done. Please wait while page is reloaded.");
	            }
	          }
		    	});
			}

		//Gets the data from the uploaded file
		function ExtractDataFromFile(filePath) {
			$.ajax({
        	type: "POST",
        	url: 'Scripts/Private/Services.php',
        	// dataType: 'json',
        	data: {functionName: 'FileManager.ExtractData', filePath: filePath },
        	success: function (obj, textstatus) {
	          var result = obj.result;
						var fileType = result.fileType;
						var data = result.data;
						console.log(result.data);

						switch(fileType) {
							case "json":
								if (!ValidateJSONData(data)) {
									alert("Invalid json format.");
									DeleteFile(filePath);
									return;
								}
								break;
							default:
								alert("Unsupported file type.");
								DeleteFile(filePath);
								return;
						}

						LoadData(data);
						DeleteFile(filePath);

	        },
	        error: function(xhr, textStatus, errorThrown ) {
						console.log(xhr);
						console.log(textStatus);
	          console.log("ERROR:" + errorThrown);
	        }
	      });
		}

		//Deletes a file from the server
		function DeleteFile(filePath) {
			$.ajax({
				type: "POST",
				url: 'Scripts/Private/Services.php',
				data: { functionName: "FileManager.DeleteFile", filePath: filePath },
				success: function(obj, textStatus) {
					var result = obj.result;

					console.log(result);
				},
				error: function(xhr, textStatus, errorThrown ) {
					console.log(xhr);
					console.log(textStatus);
					console.log("ERROR:" + errorThrown);
				}
			});
		}

		//Validates if the uploaded data fits the json format needed
		function ValidateJSONData(data) {

			if (data.constructor != Array) {
				return false;
			}

			var count = data.length;
			for (var i = 0; i < count; i++) {
				if (data[i].constructor != Object) {
					return false;
				}

				var networkNodes = data[i].nodes;
				var networkLinks = data[i].links;
				if (!data[i].name || !networkNodes || !networkLinks) {
					return false;
				}

				var subCount = networkNodes.length;
				for (var j = 0; j < subCount; j++) {
					if (!ValidateNode(networkNodes[j])) {
						return false;
					}
				}

				subCount = networkLinks.length;
				for (var j = 0; j < subCount; j++) {
					if (!ValidateLink(networkLinks[j])) {
						return false;
					}
				}
				return true;
			}
		}

		//Validates required properties of a node
		function ValidateNode(node) {

			if (!node.fx || ! node.fy) {
				return false;
			}

			return true;
		}

		//Validates required properies of a link
		function ValidateLink(link) {

			if (!link.laneCount || !link.source || !link.target
				|| !ValidateNode(link.source) || !ValidateNode(link.target)) {
				return false;
			}

			return true;
		}

			//Loads the uploaded data
			//Handles duplication of network (by name)
			function LoadData(data) {

				var count = data.length;
				if (!data || count == 0) {
					return;
				}

				var networkNames = networks.map(x => x.name);
				var newNetworks = data.filter(x => !networkNames.includes(x.name));
				var duplicateNetworks = data.filter(x => networkNames.includes(x.name));

				var enableMergeData = false;
				var createNetworkCopies = false;
				var truncateData = false;

				if (duplicateNetworks.length > 0) {
					enableMergeData = confirm("Networks with same names detected. Merge with current data?");
					if (!enableMergeData) {
						createNetworkCopies = confirm("Create as new networks instead?");
					}

					if (createNetworkCopies) {
						//Fix all duplicate network names until there is no duplicate anymore
						while (duplicateNetworks.length > 0) {
							console.log("Deduplicating")
							duplicateNetworks.forEach(function(network, index, array) {
								array[index].name = array[index].name + "_Copy";
							});
							data = newNetworks.concat(duplicateNetworks);

							newNetworks = data.filter(x => !networkNames.includes(x.name));
							duplicateNetworks = data.filter(x => networkNames.includes(x.name));

							console.log(newNetworks);
							console.log(duplicateNetworks);
						}
					}
				}

				if (newNetworks.length + networks.length > networkMaxCount) {
					truncateData = confirm("Number of new networks exceeds max allowed networks (" + networkMaxCount + "). Some data will not be loaded.");

					if (!truncateData) {
						alert("File upload cancelled.");
						return;
					}
				}

				var lastNetworkId;
				for (var j = 0; j < count; j++) {

					var networkId = LoadNetworkData(data[j]);
					if (networkId) {
						lastNetworkId = networkId;
					}
				}

				RefreshCanvas();
				CenterCanvas();

				if (lastNetworkId) {
					ActivateNetwork(lastNetworkId);
				}
			}

			//Loads a specific network to the project
			//Handles duplication of nodes (by coordinates) and links (by source and target)
			function LoadNetworkData(newNetwork) {

				var newNodes = newNetwork.nodes;
				var newLinks = newNetwork.links;
				var subCount;

				var nodeList = [];
				var linkList = [];

				//If network exists already, merge with current data
				//If not, create new new
				var network = networks.find(x => x.name == newNetwork.name);
				if (!network) {

					var categoryCount = networks.length;
					if (++categoryCount == networkMaxCount) {
						$("#AddCategoryButton").addClass("disabled");
					}
					else if (categoryCount > networkMaxCount) {
						console.log("Max allowed layers reached already.");
						return;
					}

					network = { id: GetNextNetworkId(), name: newNetwork.name, nodes: [], links: []};
					networks.push(network);
					RefreshNetworksBox();
				}

				//Check if nodes exists already
				//If exists, get id of corresponding node (identify by long and lat)
				//If not, add new node
				subCount = newNodes.length;
				for (var i = 0; i < subCount; i++) {

					var node = nodes.find(x => x.fx == newNodes[i].fx && x.fy == newNodes[i].fy);

					if (!node) {
						//Create new node
						node = { id: GetNextNodeId(), fx: newNodes[i].fx, fy: newNodes[i].fy };
						nodes.push(node);
					}

					//TODO: Add other properties unique to network nodes
					nodeList.push({ id: node.id });
				}

				//Check if links exists already
				//If exists, get id of corresponding link (identify by long and lat)
				//If not, add new link
				subCount = newLinks.length;
				for (var i = 0; i < subCount; i++) {

					//If source/target nodes doesn't exists, create
					var sourceNode = nodes.find(x => x.fx == newLinks[i].source.fx && x.fy == newLinks[i].source.fy);
					if (!sourceNode) {
						//Create new node
						sourceNode = { id: GetNextNodeId(), fx: newLinks[i].source.fx, fy:  newLinks[i].source.fy };
						nodes.push(sourceNode);
					}
					nodeList.push({ id: sourceNode.id });

					var targetNode = nodes.find(x => x.fx == newLinks[i].target.fx && x.fy == newLinks[i].target.fy);
					if (!targetNode) {
						//Create new node
						targetNode = { id: GetNextNodeId(), fx: newLinks[i].target.fx, fy:  newLinks[i].target.fy };
						nodes.push(targetNode);
					}
					nodeList.push({ id: targetNode.id });


					//TODO: Compare performance with comparing via source and target node first
					var link = links.find(x => x.source.fx == newLinks[i].source.fx && x.source.fy == newLinks[i].source.fy &&
										x.target.fx == newLinks[i].target.fx && x.target.fy == newLinks[i].target.fy);

					if (!link) {
						//Create new link
						//TODO: Figure out what to assign base lane count
						link = { source: sourceNode, target: targetNode, laneCount: 1 };
						links.push(link);
					}

					var networkLaneCount = newLinks[i].laneCount ? newLinks[i].laneCount : 1;
					linkList.push({ id: GetLinkId(link), laneCount: networkLaneCount});
				}

				AssignToNetwork(network.id, nodeList, linkList);

				return network.id;
			}

			//Formats the project data for saving
			function CompileProjectData() {

				var start = new Date();

				var projectNetworks = [];//networks.slice();
				var count;

				//For each network, assign node/link coordinates to node/link list
				//NOTE: The program keeps a "master list" of all the nodes and links separately from the network nodes and network links
				//		Network nodes/links only references the Node IDs on the master list.
				//		This is so that memory will be storing lighter data and so that movement of nodes/links will affect all networks it is involved in

				count = networks.length;
				for(var i = 0; i < count; i++) {

					var network = Object.assign({}, networks[i]);
					projectNetworks.push(network);

					var nodeList = [];
					var linkList = [];

					network.nodes.forEach(function(networkNode) {
						//Get node from master list
						var node = nodes.find(x => x.id == networkNode.id);

						//Build node data
						//TODO: Add network specific node details
						var newNode = new Object();
						newNode.fx = node.fx;
						newNode.fy = node.fy;

						nodeList.push(newNode);
					});

					network.links.forEach(function(networkLink) {
						//Get link from master list
						var link = links.find(x => GetLinkId(x) == networkLink.id);

						//Build link data
						//TODO: Add network specific link details
						var newLink = Object();
						newLink.laneCount = networkLink.laneCount;

						newLink.source = Object();
						newLink.source.fx = link.source.fx;
						newLink.source.fy = link.source.fy

						newLink.target = Object();
						newLink.target.fx = link.target.fx;
						newLink.target.fy = link.target.fy;

						//TODO: Determine which is faster
						// var newLink = Object.assign({}, networkLink);
						// newLink.source = Object.assign({}, link.source);
						// newLink.target = Object.assign({}, link.target);
						// delete newLink.source.id;
						// delete newLink.target.id;
						// delete newLink.id;


						linkList.push(newLink);
					});

					projectNetworks[i].nodes = nodeList;
					projectNetworks[i].links = linkList;
				}

				console.log("COMPILED");
				console.log(projectNetworks);
				console.log("MASTER LIST");
				console.log(networks);
				var end = new Date();
				console.log("TIME:" + (end - start));
				return projectNetworks;
			}

			//Triggers download of project data
			function DownloadProjectData(data, fileInput) {

				//Extract fileExt
				var fileExt = "json";
				if (fileInput.indexOf(".") > 0) {
					fileExt = fileInput.split('.').pop();
				}

				//Extract fileName
				var fileName = "download";
				if (fileInput) {
					//Remove file extension from file name if any
					if (fileInput.indexOf("." + fileExt) > 0) {
						fileName = fileInput.substr(0, fileInput.indexOf("." + fileExt));
					}
					else {
						fileName = fileInput;
					}
				}

				//Handle file types
				var fileMimeType;
				var formattedData;
				switch (fileExt) {
					case "json":
						fileMimeType = "application/json";
						formattedData = JSON.stringify(data);
						break;
					// case "csv":
					// 	fileMimeType = "text/csv";
					// 	break;
					case "xml":
						fileMimeType = "application/xml";
						formattedData = FormatXML(data);
						break;
					default:
						//TODO: Create pop up dialog
						alert("Unsupported file type");
						return;

				}

				var fileFullName = fileName + "." + fileExt;

				// Create a blob of the data
				//https://github.com/eligrey/FileSaver.js
				var fileToSave = new Blob([formattedData], {
				    type: fileMimeType,
				    name: fileFullName
				});

				// Save the file
				saveAs(fileToSave, fileFullName);
			}

			//Formats the project data into XML
			//NOTE: Not yet implemented
			//TODO: Make recursive
			function FormatXML(data) {

				var networkCount = data.length;
				var xmlDoc = document.implementation.createDocument("", "", null);
				var networksXML = xmlDoc.createElement("networks");

				for (var i = 0; i < networkCount; i++) {

					//For recursion
					// var networkKeys = Object.keys(data[0]);
					// var networkValues = Object.values(data[0]);
					// var count = networkKeys.length;

					var networkXML = xmlDoc.createElement("network");
					var networkAttr = xmlDoc.createAttribute("id");
					networkAttr.nodeValue = data[i].id;
					networkXML.setAttributeNode(networkAttr);

					networkAttr = xmlDoc.createAttribute("name");
					networkAttr.nodeValue = data[i].name;
					networkXML.setAttributeNode(networkAttr);

					var nodesXML = xmlDoc.createElement("nodes");
					var networkNodes = data[i].nodes;
					var nodeCount = networkNodes.length;

					for (var j = 0; j < nodeCount; j++) {

						var nodeXML = xmlDoc.createElement("node");
						var nodeAttr = xmlDoc.createAttribute("fx");
						nodeAttr.nodeValue = networkNodes[j].fx;
						nodeXML.setAttributeNode(nodeAttr);

						nodeAttr = xmlDoc.createAttribute("fy");
						nodeAttr.nodeValue = networkNodes[j].fy;
						nodeXML.setAttributeNode(nodeAttr);

						//TODO: Add other node properties

						nodesXML.appendChild(nodeXML);
					}
					networkXML.appendChild(nodesXML);

					var linksXML = xmlDoc.createElement("links");
					var networkLinks = data[i].links;
					var linkCount = networkLinks.length;

					for (var j = 0; j < linkCount; j++) {

						var linkXML = xmlDoc.createElement("link");
						var linkAttr = xmlDoc.createAttribute("laneCount");
						linkAttr.nodeValue = networkLinks[j].laneCount;
						linkXML.setAttributeNode(linkAttr);

						//TODO: Add other link properties

						var nodeXML, nodeAttr;
						nodeXML = xmlDoc.createElement("source");
						nodeAttr = xmlDoc.createAttribute("fx");
						nodeAttr.nodeValue = networkLinks[j].source.fx;
						nodeXML.setAttributeNode(nodeAttr);

						nodeAttr = xmlDoc.createAttribute("fy");
						nodeAttr.nodeValue = networkLinks[j].source.fy;
						nodeXML.setAttributeNode(nodeAttr);
						linkXML.appendChild(nodeXML);

						nodeXML = xmlDoc.createElement("target");
						nodeAttr = xmlDoc.createAttribute("fx");
						nodeAttr.nodeValue = networkLinks[j].target.fx;
						nodeXML.setAttributeNode(nodeAttr);

						nodeAttr = xmlDoc.createAttribute("fy");
						nodeAttr.nodeValue = networkLinks[j].target.fy;
						nodeXML.setAttributeNode(nodeAttr);
						linkXML.appendChild(nodeXML);

						linksXML.appendChild(linkXML);
					}
					networkXML.appendChild(linksXML);

					networksXML.appendChild(networkXML);
				}
				xmlDoc.appendChild(networksXML);

				return new XMLSerializer().serializeToString(xmlDoc);
			}

			//Adjusts all canvas components when window is resized
			function ResizeWindow() {

				RecalculateDivs();

				width = $("#D3CanvasDiv").width();
				height = $("#D3CanvasDiv").height();

				//Stop recalculating width when reached body min width
				if (width <= 480) {
					width = 480;
				}
				svg.attr("width", width)
					.attr("height", height);

				networkBoxX = width - networkBoxWidth;
				networkBoxY = 0;
				networkBox.attr("transform", "translate(" + networkBoxX + "," + networkBoxY + ")");

				infoBoxX = width - infoBoxWidth;
				infoBoxY = networkBoxY + networkBoxHeight;
				infoBox.attr("transform", "translate(" + infoBoxX + "," + infoBoxY + ")");

				canvasCenter.attr("transform", "translate(" + (width/2) + "," + (height/2) + ")");
				coordinatesText.attr("transform", "translate(0," + (height - 2) + ")");

				tile.size([width, height]);
				projection.translate([width / 2, height / 2]);

				RefreshMapZoom();
			}

			//Recalculates div dimensions based on window resizing and on visibility of the quick guide div
			function RecalculateDivs() {

				var isVisible = ($("#InstructionDiv").css("display") !== "none");
				var bodyWidth = $("body").width();

				if (!isVisible) {
					$("#D3CanvasDiv").css("width", "100%");
					$("#D3CanvasDiv").css("height", "100%");
					$("#ToolBarDiv").css("width", "calc(100% - 201px)");
				}
				else {
					if (bodyWidth > 800) {
						$("#D3CanvasDiv").css("width", "80%");
						$("#D3CanvasDiv").css("height", "100%");
						$("#ToolBarDiv").css("width", "calc(80% - 201px)");
					}
					else {
						$("#D3CanvasDiv").css("width", "100%");
						$("#D3CanvasDiv").css("height", "calc(100% - 200px)");
						$("#ToolBarDiv").css("width", "calc(100% - 201px)");
					}
				}
			}
		</script>
	</body>
</html>
