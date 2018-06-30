$(document).ready(function() {
    PageLoaded();
});

var width = $("#D3CanvasDiv").width();
var height = $("#D3CanvasDiv").height();

var networks = [];
var nodes = [];
var links = [];

var sourceNode;
var selectInfoNode = null;
var selectInfoLink = null;
var selectedNodes = [];
var selectedLinks = [];
var adjacentNodes = [];
var adjacentLinks = [];
var selectedNetworks = [];
var hiddenNetworks = [];


//DATA NAMING
var lastNodeId = -1;
var lastNetworkId = 0;
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

var overviewMode = false;
var previousMapBounds;
var currentNodeData;
var currentLinkData;

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

var currentNodeClick = null;
var currentPathClick = null;

function PageLoaded() {

  //Initial button events
  currentNodeClick = ToggleNodeInfoBox;
  currentPathClick = TogglePathInfoBox;

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

function LoadingBarCloseButton_Clicked() {
  HideLoadingDialog();
}

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

  currentNodeClick = null;
  currentPathClick = null;

  svg.on("click", InsertNewNode);
  circles.on("click", currentNodeClick);
  paths.on("click", currentPathClick);

  ResetStyles();
}

function AddLinkButton_Clicked() {
  $("#ToolBarDiv > .toolBarButton").hide();
  $("#ToggleInstructionsButton").show();
  $("#DoneButton").show();

  $(".instructionBlock.dynamic").hide();
  $("#AddLinkInstructionDiv").show();

  currentNodeClick = null;
  currentPathClick = null;

  circles.on("mousedown", SelectSourceNode)
    .on("mouseup", SelectEndNode)
    .on("click", currentNodeClick);

  svg.on("mouseup", HideDragLine);
  paths.on("click", currentPathClick);

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

  currentNodeClick = SelectNode;
  currentPathClick = SelectLink;

  circles.on("click", currentNodeClick);
  paths.on("click", currentPathClick);

  selectedLinks = [];
  selectedNodes = [];

  selectInfoLink = null;
  selectInfoNode = null;

  selectEnabled = true;

  ResetStyles();
}

function SelectAllNodesButton_Clicked() {
  SelectAllNodes();
  RefreshNetworkIcons();
}

function SelectAllLinksButton_Clicked() {
  SelectAllLinks();
  RefreshNetworkIcons();
}

function SelectAllButton_Clicked() {
  SelectAllNodes();
  SelectAllLinks();
  RefreshNetworkIcons();
}

function DeselectAllButton_Clicked() {
  circles.selectAll("circle").classed("selected", false).attr("transform", "");
  paths.classed("selected", false);

  RefreshNetworkIcons();
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

  selectInfoNode = null;
  selectInfoLink = null;
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

function AssignToNetwork_Clicked(d) {

  var networkId = d3.select(this).attr("data-id");

  //Add selectedNodes, selectedLinks and adjacentNodes
  var nodeArray = selectedNodes.concat(adjacentNodes);
  var linkArray = selectedLinks.slice();

  AssignToNetwork(networkId, nodeArray, linkArray);

  selectedNodes = [];
  selectedLinks = [];
  adjacentNodes = [];

  selectInfoNode = null;
  selectInfoLink = null;
}

function RemoveFromNetwork_Clicked(d) {

  var networkId = d3.select(this).attr("data-id");
  var nodeArray = selectedNodes.slice();
  var linkArray = selectedLinks.concat(adjacentLinks);

  RemoveFromNetwork(networkId, nodeArray, linkArray);

  selectedNodes = [];
  selectedLinks = [];
  adjacentLinks = [];

  selectInfoNode = null;
  selectInfoLink = null;
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
  selectInfoNode = null;
  selectInfoLink = null;

  selectEnabled = false;

  currentNodeClick = ToggleNodeInfoBox;
  currentPathClick = TogglePathInfoBox;

  //Reset Events
  svg.on("mouseup", null)
    .on("mousedown", null)
    .on("mousemove", UpdateCoordinates)
    .on("click", null);

  circles.on("mousedown", null)
    .on("mouseup", null)
    .on("click", currentNodeClick)
    .on('mousedown.drag', null);

  paths.on("mousedown", null)
    .on("click", currentPathClick);

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
  RefreshNetworkIcons();

  //TODO: Decide implementation
  networkBox.selectAll(".networkGroup text").style("fill", "");
  // networkBox.selectAll(".networkGroup:not(.active) text").style("font-weight", "");
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

  map = svg.append("svg:g").attr("id", "MapLayer");
  canvas = svg.append("svg:g").attr("id", "canvas");

  //TEMPORARY: Marks the center of canvas
  // canvasCenter = svg.append("circle")
  //   .attr("id", "canvascenter")
  //   .attr("transform", "translate(" + (width/2) + "," + (height/2) + ")")
  //   .attr("r",  4)
  //   .attr("fill", "black");

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

  RefreshNodeQuadTree();
  RefreshMapZoom();
  RefreshCanvas();
}

//Manages the display of nodes and paths and events bound to them
function RefreshCanvas(forceDataReload) {

  // ShowLoadingDialog();

  setTimeout( function () {

    var topLeft = invProjection.invert([0,0]);
    var bottomRight = invProjection.invert([width, height]);
    var mapBounds = {
      North: topLeft[1],
      East: bottomRight[0],
      West: topLeft[0],
      South: bottomRight[1]
    };

    if (!previousMapBounds || previousMapBounds.North != mapBounds.North || previousMapBounds.East != mapBounds.East
      || previousMapBounds.West != mapBounds.West|| previousMapBounds.South != mapBounds.South
      || forceDataReload) {
      previousMapBounds = mapBounds;

      ShowLoadingDialog();

      currentNodeData = FilterNodesByBounds(mapBounds);
      currentLinkData = FilterLinksByNodes(currentNodeData);
      var linkBounds = FilterLinksByBounds(mapBounds);
      linkBounds.filter(function(link) { return currentLinkData.indexOf(link) >= 0 ? false : currentLinkData.push(link); });
      console.log("Filtered nodes: " + currentNodeData.length + "/" + nodes.length);
      console.log("Filtered links: " + currentLinkData.length + "/" + links.length);
    }

    var nodeData = currentNodeData;
    var linkData = currentLinkData;

    if (nodeData.length == 0) {//} && linkData.length == 0) {
      HideLoadingDialog();
      return;
    }

    //NOTE: Do not render nodes if zoom scale is below 0.3 or more than 1000 nodes
    if ((currentZoomScale / initialZoomScale) < 0.3 || nodeData.length > 1000) {
      nodeData = [];
    }

    //NOTE: Enable overviewMode if zoom scale is below 0.1
    if ((currentZoomScale / initialZoomScale) < 0.1) {
      overviewMode = true;
    }
    else {
      overviewMode = false;
    }

    //Paths (links)
    // if (!overviewMode) {
      paths = paths.data(linkData, function(d){ return GetLinkId(d); });

      //TODO: Verify use - Update existing paths
      paths.classed("active", CheckLinkActive)
        .classed("selectInfo", CheckLinkSelectInfo)
        .classed("selected", CheckLinkSelect)
        .classed("overview", overviewMode)
        .attr("d", CalculateLinkCoordinates);
        // .style("stroke-width", CalculateLinkWidth);

      //Add new links to canvas
      var line = paths.enter().append('svg:path')
        .attr("id", GetLinkId)
        .attr('class', 'link')
        .classed("active", CheckLinkActive)
        .classed("selectInfo", CheckLinkSelectInfo)
        .classed("selected", CheckLinkSelect)
        .classed("overview", overviewMode)
        .attr("d", CalculateLinkCoordinates)
        // .style("stroke-width", CalculateLinkWidth)   //NOTE: Link width calculation done on RefreshLinkWidths
        .on("click", currentPathClick);
    // }
    // else {
    //   paths = paths.data([]);
    //   d3.select("#paths").html("");
    //
    //   d3.select("#paths").append("svg:path")
    //     .attr('class', 'link')
    //     // .classed("active", CheckLinkActive)
    //     // .classed("selectInfo", CheckLinkSelectInfo)
    //     // .classed("selected", CheckLinkSelect)
    //     .attr("d", function() { return GenerateMultiLinePath(links); })
    //     .style("stroke-width", 4)
    //     .style("stroke-linecap", "round");
    //     // .on("click", currentPathClick);
    // }

    //Remove old link data
    paths.exit().remove();

    //Circles (nodes)
    // NOTE: (from source) the function arg is crucial here! nodes are known by id, not by index!
    circles = layer.select("#nodes").selectAll("g").data(nodeData, function(d) { return d.id; });

    //Add new nodes to canvas
    var node = circles.enter().append('svg:g');
    node.attr("transform", CalculateNodeTranslation)
      .attr("id", function(d) { return d.id; })
      .attr("class", GetNodeStyle)
      .on("click", currentNodeClick);

    node.append('svg:circle')
      .attr('class', 'node')
      .classed("active", CheckNodeActive)
      .classed("selectInfo", CheckNodeSelectInfo)
      .classed("selected", CheckNodeSelect)
      .attr("transform", function(d) {return CheckNodeSelectInfo(d) || CheckNodeSelect(d) ? "scale(2)" : ""})
      .attr('r', radius)
      .attr("stroke-width", 4);
      // .attr("r", 24 / zoomEvent.scale()) 						//NOTE: Default tile implementation: scales node with zoom
      // .style("stroke-width", 8 / zoomEvent.scale())	//NOTE: Default tile implementation: scales node with zoom

    //Remove old nodes data
    circles.exit().remove();

    RefreshPathStyles();
    RefreshPathWidths();
    HideLoadingDialog();
  } , 0);
}

//Deletes and rebuilds all contents (icons and names) contained in  Network Box
function RefreshNetworksBox() {

  //Remove all layers/categories
  networkBox.selectAll(".networkGroup").remove();

  //Add global controls
  var baseLayer = networkBox.append("svg:g")
    .attr("id", "BaseGroup")
    .classed("networkGroup", true);

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

  var networkCount = networks.length;
  for(var i = 0; i < networkCount; i++) {

    AddNetworkToStyles(networks[i].id);
    AddNetworkToCanvas(networks[i].id, networks[i].name, i + 1);

    if (lastNetworkId < i + 1) {
      lastNetworkId = i + 1;
    }
  }
}

//Adds the network id to an array of styles to keep track of the colors used
//Ensures that a network stays on the same color even after other networks have been deleted
function AddNetworkToStyles(networkId) {

  if (networkStyles.indexOf(networkId) >= 0) {
    return;
  }

  var index = networkStyles.indexOf(null);
  networkStyles[index] = networkId;
}

//Renders the network info and icons to the network box
function AddNetworkToCanvas(networkId, networkName, networkCount) {

  var styleId = GetStyleId(networkId);

  if (networkName == "") {
    networkName = networkId;
  }

  //Network Box
  //Deactivate all other layers then add a new layer
  networkBox.selectAll("g").classed("active", false);
  var networkGroup = networkBox.append("svg:g")
    .attr("id", "NetworkGroup" + networkCount)
    .attr("data-id", networkId)
    .classed("networkGroup", true)
    // .classed("active", true)
    .classed("set" + styleId, true);

  //Compute positioning of new layer
  //NOTE: Plus 1 to account for global controls
  var y = (networkCount + 1) * (infoBoxFontSize + infoBoxTextBox_TopPadding);

  var iconVis = "#icon-visible";
  if (hiddenNetworks.find(x => x == networkId)) {
    iconVis = "#icon-hidden";
  }

  //Icons
  networkGroup.append("svg:use")
    .classed("layerIcon", true)
    .attr("xlink:href", iconVis)
    .attr("width", networkBoxIconWidth)
    .attr("height", networkBoxIconHeight)
    .attr("x", infoBoxLabel_LeftPadding)
    .attr("y", y)
    .attr("data-id", networkId)
    .attr("data-type", "visibility")
    .on("click", ToggleNetworkVisibility);

  networkGroup.append("svg:use")
    .classed("layerIcon", true)
    .classed("disabled", true)
    .attr("xlink:href", "#icon-add")
    .attr("width", networkBoxIconWidth)
    .attr("height", networkBoxIconHeight - 4)
    .attr("x", (infoBoxLabel_LeftPadding + (networkBoxIconWidth * 1)))
    .attr("y", y + 2)
    .attr("data-id", networkId)
    .attr("data-type", "add")
    .style("display", "none");

  networkGroup.append("svg:use")
    .classed("layerIcon", true)
    .classed("disabled", true)
    .attr("xlink:href", "#icon-remove")
    .attr("width", networkBoxIconWidth)
    .attr("height", networkBoxIconHeight - 4)
    .attr("x", (infoBoxLabel_LeftPadding + (networkBoxIconWidth * 2)))
    .attr("y", y + 2)
    .attr("data-id", networkId)
    .attr("data-type", "remove")
    .style("display", "none");

  networkGroup.append("svg:use")
    .classed("layerIcon", true)
    .attr("xlink:href", "#icon-delete")
    .attr("width", networkBoxIconWidth)
    .attr("height", networkBoxIconHeight - 4)
    .attr("x", (infoBoxLabel_LeftPadding + (networkBoxIconWidth * 1)))
    .attr("y", y + 2)
    .attr("data-id", networkId)
    .attr("data-type", "delete")
    .on("click", DeleteNetwork);

  networkGroup.append("svg:use")
    .classed("layerIcon", true)
    .attr("xlink:href", "#icon-info")
    .attr("width", networkBoxIconWidth)
    .attr("height", networkBoxIconHeight - 4)
    .attr("x", (infoBoxLabel_LeftPadding + (networkBoxIconWidth * 2)))
    .attr("y", y + 2)
    .attr("data-id", networkId)
    .attr("data-type", "info")
    .on("click", ToggleNetworkInfoBox);

  networkGroup.append("svg:text")
    .attr("data-type", "network")
    .attr("data-key", "name")
    .attr("data-id", networkId)
    .append("svg:tspan")
      .classed("layerText", true)
      .classed("edit", true)
      .attr("x", (infoBoxLabel_LeftPadding + (networkBoxIconWidth * 3)))
      .attr("y", y + infoBoxFontSize)
      .text(networkName);

  //TODO: Implement as hover state instead of class
  //Add events to editable text
  networkBox.selectAll("tspan.edit")
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
  var networkGroupElement = d3.select(this.parentNode.parentNode);
  var networkId = networkGroupElement.attr("data-id");
  var isActive = networkBox.select("[data-id='" + networkId + "'].networkGroup").classed("active");


  if (isActive) {
    //Do not activate new network
    ClearActiveNetwork();
    RefreshPathStyles();
    RefreshEditNetworkButtons();
  }
  else {
    ActivateNetwork(networkId);
  }

  //Adjust info data if shown
  if (selectInfoLink) {
    PreparePathData(selectInfoLink);
  }
  else if (selectInfoNode) {
    PrepareNodeData(selectInfoNode);
  }

}

//Disables add node and link buttons if there is no selected/active network
//Ensures that nodes and links added belong to a network
function RefreshEditNetworkButtons() {

  var networkId = GetActiveNetworkId();

  if (networkId) {
    $("#AddNodeButton").removeClass("disabled");
    $("#AddLinkButton").removeClass("disabled");
  }
  else {
    $("#AddNodeButton").addClass("disabled");
    $("#AddLinkButton").addClass("disabled");
  }
}

//Highlights all nodes and links that belong to a network based on the assigned color
function ActivateNetwork(networkId) {

  var network = networks.find(x => x.id == networkId);
  var networkNodes = network.nodes;
  var networkLinks = network.links;
  var nodeCount = networkNodes.length;
  var linkCount = networkLinks.length
  var styleId = GetStyleId(networkId);

  ClearActiveNetwork();

  //Highlight all nodes/paths belonging to network
  for(var i = 0; i < nodeCount; i++) {
    layer.select("#" + networkNodes[i].id).classed("set" + styleId, true);
  }

  for(var i = 0; i < linkCount; i++) {
    var linkId = GetLinkId(networkLinks[i]);
    var path = layer.select("#" + linkId);

    path.classed("active", true);
  }

  //Highlight network in network box
  networkBox.select("[data-id='" + networkId + "'].networkGroup").classed("active", true);

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
function HighlightNetworkNames(networkArray) {

  //TODO: Decide implementation
  networkBox.selectAll(".networkGroup text").style("fill", "");
  // networkBox.selectAll(".networkGroup text").style("font-weight", "");

  var count = networkArray.length;
  for(var i = 0; i < count; i++) {
    networkBox.select("text[data-id=" + networkArray[i].id + "]").style("fill", "inherit");
    // networkBox.select("text[data-id=" + networkArray[i].id + "]").style("font-weight", "bold");
  }

}

//Fixes path styles based on classes
function RefreshPathStyles() {

  //Remove styling
  //TODO: Find better implementation
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
    var networkId = GetActiveNetworkId();

    if (networkId)
    {
      var styleId = GetStyleId(networkId);

      selection.classed("set" + styleId, true);
    }
  }
}

//Calculates path widths based on the state of the path
//If path is part of an active/selected network, width will depend on the laneCount of the link
//If path is inactive, default calculation is used
//TODO: Optimize - check for better implementation
function RefreshPathWidths() {

  //Reset all paths
  paths.style("stroke-width", CalculateLinkWidth);

  var network = GetActiveNetwork();

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
function RefreshNetworkIcons() {

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
  var networkId = element.attr("data-id");

  var network = networks.find(x => x.id === networkId);
  var networkNodes = network.nodes;
  var networkLinks = network.links;

  if (isVisible) {
    element.attr("xlink:href", "#icon-hidden");
    hiddenNetworks.push(networkId);

    ClearActiveNetwork();
    RefreshPathStyles();
  }
  else {
    element.attr("xlink:href", "#icon-visible");

    hiddenNetworks = hiddenNetworks.filter(function(x) {
      return x != networkId;
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

//Assigns specified nodes and links to the network
function AssignToNetwork(networkId, nodeArray, linkArray, forceProperties) {

  if (!networkId) {
    return;
  }

  //Get network
  var network = networks.find(x => x.id === networkId);
  var networkNodes = network.nodes;
  var networkPaths = network.links;
  var count;

  //Add nodes if not yet part of network
  count = nodeArray.length;
  for(var i = 0; i < count; i++) {
    if (!networkNodes.find(x => x.id === nodeArray[i].id)) {

      if (forceProperties) {
          networkNodes.push(nodeArray[i]);
      }
      else {
        //Only copy the ID, prevent referencing and duplication of data
        networkNodes.push({ id: nodeArray[i].id });
      }
    }
  }

  //Add links if not yet part of network
  count = linkArray.length;
  for(var i = 0; i < count; i++) {

    if (!networkPaths.find(x => GetLinkId(x) === GetLinkId(linkArray[i]))) {

      if (forceProperties) {
        networkPaths.push(linkArray[i]);
      }
      else {
        //Only copy the ID, prevent referencing and duplication of data; default lane count: 1
        networkPaths.push({ id: GetLinkId(linkArray[i]), laneCount: 1 });
      }
    }
  }

  //Reset canvas
  layer.selectAll("circle.selected").classed("selected", false).attr("transform", "");
  layer.selectAll("path.selected").classed("selected", false);

  ActivateNetwork(networkId);
  ResetAssignToNetworkIcons();
}

//Removes specified nodes and links to the network
//If a removed node/link does not belong to other categories, node/link is deleted from base node/link list
function RemoveFromNetwork(networkId, nodeArray, linkArray) {

  var deleteNodeArray = [];
  var deleteLinkArray = [];

  //Prevent references
  // nodeArray = Object.assign({}, nodeArray);
  // linkArray = Object.assign({}, linkArray);

  //Get network
  var network = networks.find(x => x.id === networkId);
  var networkNodes = network.nodes;
  var networkLinks = network.links;
  var count;

  //Remove nodes from network
  //Check if node belongs to more than 1 networks
  //If not, remove node and adjacent links from main list
  count = nodeArray.length;
  for (var i = 0; i < count; i++) {
    var node = networkNodes.find(x => x.id === nodeArray[i].id);
    var networkCount = GetNodeNetworks(node.id).length;

    if (node) {
      var index = networkNodes.indexOf(node);
      networkNodes.splice(index, 1);

      if (networkCount == 1) {
        deleteNodeArray.push(node);
      }
    }
  }

  //Remove links
  count = linkArray.length;
  for (var i = 0; i < count; i++) {
    var linkId = GetLinkId(linkArray[i]);
    var link = networkLinks.find(x => GetLinkId(x) === linkId);
    var networkCount = GetLinkNetworks(linkId).length;

    if (link) {
      var index = networkLinks.indexOf(link);
      networkLinks.splice(index, 1);

      if (networkCount == 1) {
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

  ActivateNetwork(networkId);
  ResetAssignToNetworkIcons();

}

//Creates a new networks and adds it to the networks list
function InsertNewNetwork(name) {

  var networkCount = networks.length;
  if (++networkCount == networkMaxCount) {
    // $("#AddNetworkButton").prop("disabled", true);
    $("#AddNetworkButton").addClass("disabled");
  }
  else if (networkCount > networkMaxCount) {
    alert("Max allowed layers reached already.");
    $("#AddNetworkButton").addClass("disabled");
    return;
  }

  //Data
  var networkId = GetNextNetworkId();
  if (!name) {
    name = networkId;
  }
  var network = { id: networkId, name: name, nodes: [], links: []}	;

  networks.push(network);

  AddNetworkToStyles(networkId);
  AddNetworkToCanvas(networkId, networkId, networkCount);
  ActivateNetwork(networkId);

}

//Deletes a network from the project
//TODO: Create separate functions for trigger and task (DeleteNetwork_Clicked & DeleteNetwork(networkId))
function DeleteNetwork(d) {
  StopPropagation();
  PreventDefault();

  if (!confirm("Are you sure you want to delete this network?")) {
    return;
  }

  var networkId = d3.select(this).attr("data-id");
  var styleId = GetStyleId(networkId);
  var activeNetworkId = GetActiveNetworkId();

  //Delete nodes and links if they don't belong to other networks
  var deleteNetwork = networks.find(x => x.id == networkId);
  var nodeArray = Object.assign([], deleteNetwork.nodes);
  var linkArray = Object.assign([], deleteNetwork.links);
  RemoveFromNetwork(networkId, nodeArray, linkArray);

  networks = networks.filter(function(network) {
    return network.id != networkId;
  });

  //Empty the style where network used to be assigned to
  networkStyles[styleId] = null;

  RefreshNetworksBox();

  //Reactivate categories
  if (activeNetworkId && networkId != activeNetworkId) {
    ActivateNetwork(activeNetworkId);
  }

  $("#AddNetworkButton").removeClass("disabled");

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
  // selectInfoNode = null;
  selectInfoLink = null;

  var node = d3.select(this);
  var nodeCircle = node.select("circle");
  var isSelected = CheckNodeSelectInfo(d);

  circles.selectAll("circle").classed("selectInfo", false).attr("transform", "");
  paths.classed("selectInfo", false).attr("transform", "");

  if (isSelected) {
    $("#InfoBox").hide();
    selectInfoNode = null;
  }
  else {
    nodeCircle.classed("selectInfo", true).attr("transform", "scale(2)");

    PrepareNodeData(d);
    HighlightNetworkNames(GetNodeNetworks(d.id));
  }

  RefreshNetworkIcons();
}

//Prepares the properties of the node to be displayed
function PrepareNodeData(node) {

  // selectedNodes.push(node);
  selectInfoNode = node;

  //Customize values to be displayed
  //NOTE: Add values as needed (max properties implemented)
  var title = "Node Information";
  var id = node.id
  var properties = [];
  properties.push({ key: "id", label: "ID", value: id, enableEdit: false });
  properties.push({ key: "fx", label: "X", value: node.fx, enableEdit: false });
  properties.push({ key: "fy", label: "Y", value: node.fy, enableEdit: false });

  var count = nodeProperties.length;
  for (var i = 0; i < count; i++) {
    var key = nodeProperties[i].key;
    var property = Object.assign({}, nodeProperties[i]);
    property.value = node[key];

    properties.push(property);
  }

  //If there is an active network, get network specific properties
  var network = GetActiveNetwork();
  if (network) {
    var networkNode = network.nodes.find(x => x.id === id);

    if (networkNode) {
      properties.push({ key: "network", label: "Network", value: network.name, dataType: "string", enableEdit: false });

      count = networkNodeProperties.length;
      for (var i = 0; i < count; i++) {
        var key = networkNodeProperties[i].key;
        var property = Object.assign({}, networkNodeProperties[i]);
        property.value = networkNode[key];

        properties.push(property);
      }
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
  // selectedNodes = [];
  // selectedLinks = [];
  selectInfoNode = null;
  // selectInfoLink = null;

  var path = d3.select(this);
  // var isSelected = path.classed("selectInfo");
  var isSelected = CheckLinkSelectInfo(d);

  circles.selectAll("circle").classed("selectInfo", false).attr("transform", "");
  paths.classed("selectInfo", false);

  if (isSelected) {
    $("#InfoBox").hide();
    selectInfoLink = null;

    ResetStyles();
  }
  else {

    path.classed("selectInfo", true);

    PreparePathData(d);
    HighlightNetworkNames(GetLinkNetworks(GetLinkId(d)))
  }

  RefreshNetworkIcons();
}

//Prepares the properties of the link to be displayed
function PreparePathData(link) {

  // selectedLinks.push(link);
  selectInfoLink = link;

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

  var count = linkProperties.length;
  for (var i = 0; i < count; i++) {
    var key = linkProperties[i].key;
    var property = Object.assign({}, linkProperties[i]);
    property.value = link[key];

    properties.push(property);
  }

  //If there is an active network, get network specific properties
  var network = GetActiveNetwork();
  if (network) {
    var networkLink = network.links.find(x => GetLinkId(x) === id);

    if (networkLink) {
      properties.push({ key: "network", label: "Network", value: network.name, dataType: "string", enableEdit: false });
      properties.push({ key: "laneCount", label: "Lane #", value: networkLink.laneCount, dataType: "int",enableEdit: true });

      count = networkLinkProperties.length;
      for (var i = 0; i < count; i++) {
        var key = networkLinkProperties[i].key;
        var property = Object.assign({}, networkLinkProperties[i]);
        property.value = networkLink[key];

        properties.push(property);
      }
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
  var networkId = element.attr("data-id");

  //Limit info selection to only one node/path/network
  selectedNodes = [];
  selectedLinks = [];
  // selectInfoNode = null;
  selectInfoLink = null;

  circles.selectAll("circle").classed("selectInfo", false).attr("transform", "");
  paths.classed("selectInfo", false);

  networkBox.selectAll("[data-type='info']").attr("xlink:href","#icon-info");

  if (isSelected) {
    $("#InfoBox").hide();
    return;
  }

  element.attr("xlink:href", "#icon-info-alt");

  PrepareNetworkData(networkId);
  ActivateNetwork(networkId);
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
  // properties.push({ key: "pcu", label: "PCU", value: network.pcu, dataType: "int", enableEdit: true });

  var count = networkProperties.length;
  for (var i = 0; i < count; i++) {
    var property = Object.assign({}, networkProperties[i]);
    property.value = network[property.key];

    properties.push(property);
  }

  DisplayInfoBoxData("network", title, properties, id);
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

            var isSuccessful = false;
            switch(objectType) {
              case "path": isSuccessful = UpdatePathValue(key, value, id); break;
              case "node": isSuccessful = UpdateNodeValue(key, value, id); break;
              case "network": isSuccessful = UpdateNetworkValue(key, value, id); break;
              default: console.log("ERROR: objectType not recognized.");
            }

            if (isSuccessful) {
              element.text(value);

              RefreshCanvas();
              RefreshPathStyles();
              RefreshPathWidths();
            }
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

  //Check if key is a networkNodeProperty or a nodeProperty
  if (key == "laneCount" || networkLinkProperties.find(x => x.key == key)) {
    var network = GetActiveNetwork();
    if (!network) {
      return false;
    }

    link = network.links.find(x => GetLinkId(x) === id);
  }
  else if (linkProperties.find(x => x.key == key)){
    link = links.find(x => GetLinkId(x) === id);
  }
  else {
    return false;
  }

  //TODO: Validation
  link[key] = value;

  //Update calculated values of base links
  RefreshLinksData();

  return true;
}

//Updates a node based on it's id, key property and value
function UpdateNodeValue(key, value, id) {

  var node;

  //Check if key is a networkNodeProperty or a nodeProperty
  if (networkNodeProperties.find(x => x.key == key)) {
    var network = GetActiveNetwork();
    if (!network) {
      return false;
    }

    node = network.nodes.find(x => x.id === id);
  }
  else if (nodeProperties.find(x => x.key == key)){
    node = nodes.find(x => x.id === id);
  }
  else {
    return false;
  }

  //TODO: Validation
  node[key] = value;

  //Update calculated values of base nodes if any
  RefreshNodesData();

  return true;
}

//Updates a network based on it's id, key property and value
function UpdateNetworkValue(key, value, id) {

  var network = networks.find(x => x.id === id);

  //Validation
  if (key == "name") {
    if (networks.find(x => x.name === value)) {
      alert("Network name already exists. Please use a different name");
      return false;
    }
  }

  network[key] = value;
  RefreshNetworksBox();
  ActivateNetwork(network.id);

  //Update infobox if it contains network data
  if ($("#InfoBox > [data-type='network'][data-id='" + network.id + "']").length > 0) {
    PrepareNetworkData(network.id);
  }

  return true;
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
  // tileImage.append("use")
  //   .attr("xlink:href", "#icon-loading")
  //   .attr("width", 0.25)
  //   .attr("height", 0.25)
  //   .attr("x", function(d) { return d[0] + 0.5 - (0.25/2); })
  //   .attr("y", function(d) { return d[1] + 0.5 - (0.25/2); });
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

  // RefreshCanvas();
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

    CenterViewBox(zoomRect.node(), scale);

    //Reset zoom rectangle
    zoomRectEnabled = false;
    zoomRectCoordinates [0, 0];
    zoomRectDimensions = [0, 0];
    UpdateZoomRect(0, 0, 0, 0);

  }

  RefreshCanvas();
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
  RefreshNodeQuadTree();
  RefreshCanvas(true);
  RefreshPathStyles();
}

//Creates and adds new node if not yet existing
function InsertNewNode() {

  //Disable when canvas is moving/moved
  if (previousTranslation.toString() != currentTranslation.toString()) {
    return;
  }

  //Get current active network
  var networkId = GetActiveNetworkId();
  if (!networkId) {
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
  RefreshNodeQuadTree();

  //Zoom in to minimum node zoom
  if (overviewMode) {
    AdjustCenter([x,y], 23184652.18821198);
  }
  else {
    RefreshCanvas(true);
  }

  //Add to current active network
  AssignToNetwork(networkId, [node], []);
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

  //Add to current active network
  var networkId = GetActiveNetworkId();
  AssignToNetwork(networkId, [sourceNode, endNode], [link]);

  RefreshCanvas(true);
}

//Selects the specified node as the source node when adding links
function SelectSourceNode(d) {

  sourceNode = d;
  dragLine
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

  RefreshNetworkIcons();
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

  RefreshNetworkIcons();
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

  //Look for bounding coordinates of ALL data
  var xWest, xEast, yNorth, ySouth;
  var nodesFX = nodes.map(x => x.fx);
  var nodesFY = nodes.map(x => x.fy);

  xWest = Math.min(...nodesFX);
  xEast = Math.max(...nodesFX);
  yNorth = Math.max(...nodesFY);
  ySouth = Math.min(...nodesFY);

  var midGeo = [(xWest + xEast) / 2, (yNorth + ySouth) / 2];
  AdjustCenter(midGeo, zoomEvent.scale());
}

//Centers the view on the specified view node (canvas/zoomRect) with the specified scale
function CenterViewBox(viewNode, scale) {

  //Get center based on
  //https://bl.ocks.org/catherinekerr/b3227f16cebc8dd8beee461a945fb323
  var bbox = viewNode.getBBox();
  var midX = bbox.x + (bbox.width / 2) + currentTranslation[0];
  var midY = bbox.y + (bbox.height / 2) + currentTranslation[1];
  var midGeo = invProjection.invert([midX, midY]); //Get middle geographical coordinates

  AdjustCenter(midGeo, scale);

}

function AdjustCenter(midGeo, scale) {
  //Reset projection center and scale
  projection.center(midGeo)
    .scale(scale / 2 / Math.PI);

  //Reset zoomEvent with new projection center
  zoomEvent
    .scale(projection.scale() * 2 * Math.PI)
    .translate(projection([0,0]));

  currentZoomScale = zoomEvent.scale();

  RefreshMapZoom();
  RefreshCanvas(true);
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
  dragLine.classed('hidden', true);

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

  if (overviewMode) {
    return "M" + [sourceTranslateX, sourceTranslateY] + "L" + [targetTranslateX, targetTranslateY];
  }

  //NOTE: Divide by scale if paths must not scale with map
  var sPadding = sourcePadding / (initialZoomScale / currentZoomScale);
  var tPadding = targetPadding / (initialZoomScale / currentZoomScale);

  //Calculate node distances
  var deltaX = targetTranslateX - sourceTranslateX,
      deltaY = targetTranslateY - sourceTranslateY,
      dist = Math.sqrt(deltaX * deltaX + deltaY * deltaY);

  //If dist is greater than totalPadding, adjust padding (minimum 0)
  while (sPadding + tPadding > dist && Math.floor(sPadding + tPadding) > 0) {
    sPadding = sPadding / 2;
    tPadding = tPadding / 2;
  }

  //Calculate padding from source/target node
  var normX = deltaX / dist,
      normY = deltaY / dist,
      sourceX = sourceTranslateX + (sPadding * normX),
      targetX = targetTranslateX - (tPadding * normX),
      sourceY = sourceTranslateY + (sPadding * normY),
      targetY = targetTranslateY - (tPadding * normY);

    //Add gap between viceversa paths to prevent overlapping of elements
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
    //
    // var adjDeltaX = targetX - sourceX;
    // var adjDeltaY = targetY - sourceY;

    //If deltas doesn't have the same sign, path has inverted
    //TODO: Verify implementation, hide line instead?
    // if ((adjDeltaX > 0 ^ deltaX > 0) && (adjDeltaY > 0 ^ deltaY > 0)) {
    //   return "";
    // }

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
function GetActiveNetwork() {
  var active = networkBox.select(".active");
  var network = null;

  if (!active.empty()) {
    network = networks.find(x => x.id === active.attr("data-id"));
  }

  return network;
}

//Gets the style id of a network
function GetStyleId(networkId) {
  return networkStyles.indexOf(networkId);
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
  var newNetworkId = "";

  do {
    newNetworkId = prefixNetwork + ++lastNetworkId;
  }
  while (networks.find(x => x.id == newNetworkId));

  return newNetworkId;
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
            ShowLoadingDialog();
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
        // console.log("COMPLETED");
        chunkCurrent++;

        var fileInfo = data.result.result.response.files[0]; //Limit to single file

        if (!fileInfo.error) {
          ExtractDataFromFile(fileInfo.relativePath);
        }
        else {
          console.log("ERROR: " + fileInfo.error);
          HideLoadingDialog();
        }

        $("#ProgressBarDiv").hide();
      },
      processfail: function (e, data) {
        alert(data.files[data.index].name + "\n" + data.files[data.index].error);
      },
      fail: function (e, data) {
        // $("#UploadAreaFormErrorList").append("<li>An error occurred. Please try again or contact the admin.</li>");
        $("#ProgressBarDiv").hide();
        HideLoadingDialog();
        console.log("ERROR: " + data.errorThrown);
      },
      start: function(e) {
        // console.log("START");
      },
      stop: function(e) {
        // console.log("STOP");
        if (chunkCurrent == chunkCount)
        {
          // console.log("REDIRECT");
          // $("#ProgressBarText").text("Upload done. Please wait while page is reloaded.");
        }
      }
    });
}

//Gets the data from the uploaded file
var start;
function ExtractDataFromFile(filePath) {
  start = new Date();
  console.log("Extracting Data");

  $.ajax({
      type: "POST",
      url: 'Scripts/Private/Services.php',
      // dataType: 'json',
      data: {functionName: 'FileManager.ExtractData', filePath: filePath },
      success: function (obj, textstatus) {

        var result = obj.result;
        var fileType = result.fileType;
        var data = result.data;

        console.log("Data received.")
        console.log("Elapsed: " + TranslateTicksToTime(new Date() - start));

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

        console.log("Loading data")
        console.log("Elapsed: " + TranslateTicksToTime(new Date() - start));
        LoadData(data);

        console.log("Deleting file")
        console.log("Elapsed: " + TranslateTicksToTime(new Date() - start));
        DeleteFile(filePath);
        HideLoadingDialog();
      },
      error: function(xhr, textStatus, errorThrown ) {
        alert("Something went wrong. Please contact developer.");

        console.log(xhr);
        console.log(textStatus);
        console.log("ERROR:" + errorThrown);
        HideLoadingDialog();
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
  if (!data[i].name || !(networkNodes || networkLinks)) {
    //NOTE: must have nodes and/or links
    return false;
  }

  var subCount = networkNodes.length;
  for (var j = 0; j < subCount; j++) {
    if (!ValidateNode(networkNodes[j])) {
      return false;
    }
  }

  if (networkLinks) {
    subCount = networkLinks.length;
    for (var j = 0; j < subCount; j++) {
      if (!ValidateLink(networkLinks[j])) {
        return false;
      }
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

//NOTE: Removed laneCount check, must default to 1
if (!link.source || !link.target
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

  console.log("Network data fixed")
  console.log("Elapsed: " + TranslateTicksToTime(new Date() - start));

  var lastNetworkId;
  for (var j = 0; j < count; j++) {

    var networkId = LoadNetworkData(data[j]);
    if (networkId) {
      lastNetworkId = networkId;
    }
  }

  console.log("Refreshing Canvas")
  console.log("Elapsed: " + TranslateTicksToTime(new Date() - start));
  RefreshNodeQuadTree();
  RefreshCanvas();

  console.log("Centering Canvas")
  console.log("Elapsed: " + TranslateTicksToTime(new Date() - start));
  CenterCanvas();

  if (lastNetworkId) {
    console.log("Activating Network")
    console.log("Elapsed: " + TranslateTicksToTime(new Date() - start));
    ActivateNetwork(lastNetworkId);
  }
}

//Loads a specific network to the project
//Handles duplication of nodes (by coordinates) and links (by source and target)
function LoadNetworkData(newNetwork) {

  var newNodes = newNetwork.nodes;
  var newLinks = newNetwork.links;
  var subCount;

  console.log("Loading Network Data");
  console.log("Node Count: " + (newNodes ? newNodes.length : 0));
  console.log("Links Count: " + (newLinks ? newLinks.length : 0));

  var nodeList = [];
  var linkList = [];

  //If network exists already, merge with current data
  //If not, create new new
  var network = networks.find(x => x.name == newNetwork.name);
  if (!network) {

    var networkCount = networks.length;
    if (++networkCount == networkMaxCount) {
      $("#AddNetworkButton").addClass("disabled");
    }
    else if (networkCount > networkMaxCount) {
      console.log("Max allowed layers reached already.");
      return;
    }

    network = { id: GetNextNetworkId(), name: newNetwork.name, nodes: [], links: []};

    //Add other network properties
    subCount = networkProperties.length;
    for (var i = 0; i < subCount; i++) {
      var key = networkProperties[i].key;
      network[key] = newNetwork[key];
    }

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

      //Add generic node properties
      var propCount = nodeProperties.length;
      for (var j = 0; j < propCount; j++) {
        var key = nodeProperties[j].key;
        node[key] = newNodes[i][key];
      }
      nodes.push(node);
    }

    //Add other properties unique to network nodes
    var networkNode = { id: node.id };

    var propCount = networkNodeProperties.length;
    for (var j = 0; j < propCount; j++) {
      var key = networkNodeProperties[j].key;
      networkNode[key] = newNodes[i][key];
    }

    nodeList.push(networkNode);
  }

  console.log("New nodes loaded");
  console.log("Elapsed: " + TranslateTicksToTime(new Date() - start));

  //Check if links exists already
  //If exists, get id of corresponding link (identify by long and lat)
  //If not, add new link
  if (newLinks) {
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
          //Base lane count = 1
          link = { source: sourceNode, target: targetNode, laneCount: 1 };

          //Add generic link properties
          var propCount = linkProperties.length;
          for (var j = 0; j < propCount; j++) {
            var key = linkProperties[j].key;
            link[key] = newLinks[i][key];
          }

          links.push(link);
        }

        //Network specific properties
        var networkLaneCount = newLinks[i].laneCount ? newLinks[i].laneCount : 1;
        var networkLink = { id: GetLinkId(link), laneCount: networkLaneCount};

        //Add other properties unique to network links
        var propCount = networkLinkProperties.length;
        for (var j = 0; j < propCount; j++) {
          var key = networkLinkProperties[j].key;
          networkLink[key] = newLinks[i][key];
        }

        linkList.push(networkLink);
      }
  }

  console.log("New links loaded");
  console.log("Elapsed: " + TranslateTicksToTime(new Date() - start));

  AssignToNetwork(network.id, nodeList, linkList, true);

  console.log("Assigned to network");
  console.log("Elapsed: " + TranslateTicksToTime(new Date() - start));

  console.log("Finished loading network data");
  console.log("Elapsed: " + TranslateTicksToTime(new Date() - start));

  return network.id;
}

//Formats the project data for saving
function CompileProjectData() {

  start = new Date();

  var projectNetworks = [];//networks.slice();
  var count;
  var subCount;

  //For each network, assign node/link coordinates to node/link list
  //NOTE: The program keeps a "master list" of all the nodes and links separately from the network nodes and network links
  //		Network nodes/links only references the Node IDs on the master list.
  //		This is so that memory will be storing lighter data and so that movement of nodes/links will affect all networks it is involved in

  count = networks.length;
  for(var i = 0; i < count; i++) {

    var network = new Object(); //Object.assign({}, networks[i]);
    network.name = networks[i].name;

    subCount = networkProperties.length;
    for (var j = 0; j < subCount; j++) {
      var key = networkProperties[j].key;
      network[key] = networks[i][key];
    }
    projectNetworks.push(network);

    var nodeList = [];
    var linkList = [];

    networks[i].nodes.forEach(function(networkNode) {
      //Get node from master list
      var node = nodes.find(x => x.id == networkNode.id);

      //Build node data
      var newNode = new Object();
      newNode.fx = node.fx;
      newNode.fy = node.fy;

      //Adding generic node properties
      subCount = nodeProperties.length;
      for (var j = 0; j < subCount; j++) {
        var key = nodeProperties[j].key;
        newNode[key] = node[key];
      }

      //Adding network specific node properties
      subCount = networkNodeProperties.length;
      for (var j = 0; j < subCount; j++) {
        var key = networkNodeProperties[j].key;
        newNode[key] = networkNode[key];
      }

      nodeList.push(newNode);
    });

    networks[i].links.forEach(function(networkLink) {
      //Get link from master list
      var link = links.find(x => GetLinkId(x) == networkLink.id);

      //Build link data
      var newLink = Object();
      newLink.laneCount = networkLink.laneCount;

      newLink.source = Object();
      newLink.source.fx = link.source.fx;
      newLink.source.fy = link.source.fy

      newLink.target = Object();
      newLink.target.fx = link.target.fx;
      newLink.target.fy = link.target.fy;

      //Adding generic node properties
      subCount = linkProperties.length;
      for (var j = 0; j < subCount; j++) {
        var key = linkProperties[j].key;
        newLink[key] = link[key];
      }

      //Adding network specific node properties
      subCount = networkLinkProperties.length;
      for (var j = 0; j < subCount; j++) {
        var key = networkLinkProperties[j].key;
        newLink[key] = networkLink[key];
      }

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
    var networkAttr; // = xmlDoc.createAttribute("id");
    // networkAttr.nodeValue = data[i].id;
    // networkXML.setAttributeNode(networkAttr);

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

  // canvasCenter.attr("transform", "translate(" + (width/2) + "," + (height/2) + ")");
  coordinatesText.attr("transform", "translate(0," + (height - 2) + ")");

  tile.size([width, height]);
  projection.translate([width / 2, height / 2]);

  RefreshMapZoom();
  RefreshCanvas();
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

//TEMPORARY
function TranslateTicksToTime(ticks) {
  //get seconds from ticks
  var ts = ticks / 1000;

  //conversion based on seconds
  var hh = Math.floor( ts / 3600);
  var mm = Math.floor( (ts % 3600) / 60);
  var ss = (ts % 3600) % 60;

  //prepend '0' when needed
  hh = hh < 10 ? '0' + hh : hh;
  mm = mm < 10 ? '0' + mm : mm;
  ss = ss < 10 ? '0' + ss : ss;

  //use it
  var str = hh + ":" + mm + ":" + ss;

  return str;
}

//QUADTREE - http://bl.ocks.org/sumbera/9972460
var nodeQuadTree;
function RefreshNodeQuadTree() {
  nodeQuadTree = d3.geom.quadtree(nodes.map(
              function (node, i) {
                  return {
                      x: node.fx,
                      y: node.fy,
                      all: node
                  };
                }
              )
            );
}

//NOTE: West, South, East, North because coordinate system follows descending latitude
function FilterNodesByBounds(mapBounds) {
  var filteredNodes = [];
  nodeQuadTree.visit(function (node, x1, y1, x2, y2) {
      var p = node.point;
      if ((p) && (p.x >= mapBounds.West) && (p.x < mapBounds.East) && (p.y >= mapBounds.South) && (p.y < mapBounds.North)) {
          filteredNodes.push(node.point.all);
      }
      return x1 >= mapBounds.East || y1 >= mapBounds.North || x2 < mapBounds.West || y2 < mapBounds.South;
  });

  return filteredNodes;
}

function FilterLinksByNodes(nodeArray) {
  var adjacentLinks = [];

  //TODO: Optimize - Determine if there's faster way of implementing
  var count = nodeArray.length;
  for (var i = 0; i < count; i++) {
    links.filter(function(link) {
      return ((link.source.id == nodeArray[i].id || link.target.id == nodeArray[i].id) && adjacentLinks.indexOf(link)) >= 0 ? false : adjacentLinks.push(link);
    });
  }

  return adjacentLinks;
}

function FilterLinksByBounds(mapBounds) {

  return links.filter(function(link){
    //Link pointers
    var point1 = [link.source.fx, link.source.fy];
    var point2 = [link.target.fx, link.target.fy];

    //Check North, East, South, West
    if(CheckLineIntersection(point1, point2, [mapBounds.West, mapBounds.North], [mapBounds.East, mapBounds.North])) { return true; }
    if(CheckLineIntersection(point1, point2, [mapBounds.East, mapBounds.North], [mapBounds.East, mapBounds.South])) { return true; }
    if(CheckLineIntersection(point1, point2, [mapBounds.West, mapBounds.South], [mapBounds.East, mapBounds.South])) { return true; }
    if(CheckLineIntersection(point1, point2, [mapBounds.West, mapBounds.North], [mapBounds.West, mapBounds.South])) { return true; }

    return false;
  });
}

//SOURCE: https://stackoverflow.com/questions/563198/how-do-you-detect-where-two-line-segments-intersect (Gavin's answer)
function CheckLineIntersection(line1A, line1B, line2A, line2B) {
  var s1_x = line1B[0] - line1A[0];
  var s1_y = line1B[1] - line1A[1];
  var s2_x = line2B[0] - line2A[0];
  var s2_y = line2B[1] - line2A[1];

  var s = (-s1_y * (line1A[0] - line2A[0]) + s1_x * (line1A[1] - line2A[1])) / (-s2_x * s1_y + s1_x * s2_y);
  var t = ( s2_x * (line1A[1] - line2A[1]) - s2_y * (line1A[0] - line2A[0])) / (-s2_x * s1_y + s1_x * s2_y);

  if (s >= 0 && s <= 1 && t >= 0 && t <= 1)
  {
    //Collision detected
    return true;
  }

  return false; // No collision
}

// function GetLinkClass(link) {
//
//   //Check if link is part of active network
//   var activeNetwork = GetActivenetwork();
//   var isActive = activeNetwork.links.find(x => x.id == link.id) ? true : false;
//   var isSelected = selectedLinks.find(x => x.id == link.id) ? true : false;
//
//   var classes = "";
//   if (isActive) {
//     classes += "active";
//   }
//
//   if (isSelected) {
//     classes += "selected"
//   }
//
// }

function CheckLinkActive(link) {
  var activeNetwork = GetActiveNetwork();
  return (activeNetwork && activeNetwork.links.find(x => GetLinkId(x) == GetLinkId(link)));
}

function CheckLinkSelectInfo(link) {
  return (selectInfoLink && GetLinkId(link) == GetLinkId(selectInfoLink));
}

function CheckLinkSelect(link) {
  return (selectedLinks.find(x => GetLinkId(link) == GetLinkId(x)));
}

function CheckNodeActive(node) {
  var activeNetwork = GetActiveNetwork();
  return (activeNetwork && activeNetwork.nodes.find(x => x.id == node.id));
}

function CheckNodeSelectInfo(node) {
  return (selectInfoNode && node.id == selectInfoNode.id);
}

function CheckNodeSelect(node) {
  return (selectedNodes.find(x => x.id == node.id));
}

function GetNodeStyle(node) {
  if (!CheckNodeActive(node)) {
    return "";
  }

  var activeNetworkId = GetActiveNetworkId();
  return "set" + GetStyleId(activeNetworkId);
}

var pathGenerator = d3.geo.path().projection(projection);

function GenerateMultiLinePath(linkData) {
  var uniqueLinks = ExtractNonDirectionalLinks(linkData);

  var pathData = "";
  var count = uniqueLinks.length;
  for (var i = 0; i < count; i++) {

    var source = invProjection([uniqueLinks[i].source.fx, uniqueLinks[i].source.fy]);
    var target = invProjection([uniqueLinks[i].target.fx, uniqueLinks[i].target.fy]);

    //Factor in translation and scale
    source[0] = source[0] - currentTranslation[0];
    source[1] = source[1] - currentTranslation[1];
    target[0] = target[0] - currentTranslation[0];
    target[1] = target[1] - currentTranslation[1];

    pathData += "M" + source + "L" + target;
  }

  return pathData;
}

function ExtractNonDirectionalLinks(linkData){
  var uniqueLinks = [];
  var count = linkData.length;

  for (var i = 0; i < count; i++) {
    if (!uniqueLinks.find(link => (link.source.id == linkData[i].source.id && link.target.id == linkData[i].target.id)
        || (link.target.id == linkData[i].source.id && link.source.id == linkData[i].target.id))){
      uniqueLinks.push(linkData[i]);
    }
  }

  return uniqueLinks;
}

// function RefreshNodeClasses() {
//
//   // d3.select("#nodes > g")
//   //   .attr("class", "")
//   //   .attr("transform", "");
//   // d3.select("#nodes > g > circle")
//   //   .classed("active", false)
//   //   .classed("selectInfo", false)
//   //   .classed("selected", false);
//
//   var activeNetwork = GetActiveNetwork();
//   var activeNodes = [];
//   if (activeNetwork) {
//     activeNetwork.nodes.filter(function(node) {
//       var x = d3.select("#" + node.id);
//       if (x) {
//         x.select("circle").classed("active", true);
//       }
//     });
//   }
//
//   if (selectInfoNode) {
//     d3.select("#" + selectInfoNode.id).attr("transform", "scale(2)");
//     d3.select("#" + selectInfoNode.id + " > circle").classed("selectInfo", true);
//   }
//
//   if (selectedNodes.length > 0) {
//     selectedNodes.forEach(function(node) {
//       var x = d3.select("#" + node.id);
//       if (x) {
//         x.attr("transform", "scale(2)");
//         x.select("circle").classed("selected", true);
//       }
//     });
//   }
// }

function ShowLoadingDialog() {
  $("#LoadingBarDiv").show();
}

function HideLoadingDialog() {
  $("#LoadingBarDiv").hide();
}
