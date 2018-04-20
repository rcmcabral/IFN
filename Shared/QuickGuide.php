<div id="InstructionDiv">
  <span id="InstructionDivTitle">Quick Guide</span>
  <div id="AddNodeInstructionDiv" class="instructionBlock dynamic">
    <span class="instructionBlockTitle">Adding Nodes [ <svg class="instructionIcon"><use xlink:href="#icon-location"></use></svg> ]</span>
    <!-- <span class="instructionBlockSubTitle"></span> -->
    <ul>
      <li>Select the network where the node should be added.</li>
      <li>Navigate to where the node must be placed.</li>
      <li>Click on the canvas to add a node.</li>
    </ul>
  </div>
  <div id="AddLinkInstructionDiv" class="instructionBlock dynamic">
    <span class="instructionBlockTitle">Adding Links [ <svg class="instructionIcon"><use xlink:href="#icon-line"></use></svg> ]</span>
    <!-- <span class="instructionBlockSubTitle"></span> -->
    <ul>
      <li>Select the network where the link should be added.</li>
      <li>Drag from one node to another to add a link.</li>
    </ul>
  </div>
  <div id="SelectInstructionDiv" class="instructionBlock dynamic">
    <span class="instructionBlockTitle">Selection [ <svg class="instructionIcon"><use xlink:href="#icon-select"></use></svg> ]</span>
    <span class="instructionBlockSubTitle">Moving</span>
    <ul>
      <li>Select all nodes that should be moved.</li>
      <li>Drag a node to move the entire selection.</li>
      <li>Non-selected nodes will be anchored to their current position.</li>
      <li>All links will moved with the nodes they are anchored to.</li>
    </ul>
    <span class="instructionBlockSubTitle">Deleting</span>
    <ul>
      <li>Select all nodes and/or links that should be deleted.</li>
      <li>Click the Delete Button [ <svg class="instructionIcon"><use xlink:href="#icon-delete"></use></svg> ] to delete selection.</li>
      <li>All links connected to a deleted node will also be deleted.</li>
    </ul>
    <span class="instructionBlockSubTitle">Assigning</span>
    <ul>
      <li>Select all nodes and/or links that should be assigned to a network.</li>
      <li>Click the Add [ <svg class="instructionIcon"><use xlink:href="#icon-add"></use></svg> ] or [ <svg class="instructionIcon"><use xlink:href="#icon-remove"></use></svg> ] Remove Buttons on the Networks Panel to add/remove them from the specific network.</li>
      <li>All nodes connected to selected links will also be assigned to the selected network.</li>
    </ul>
  </div>
  <div id="GeneralInstructionDiv" class="instructionBlock">
    <span class="instructionBlockTitle">General</span>
    <span class="instructionBlockSubTitle">Legend</span>
    <ul>
      <li><svg class="instructionIcon element"><g><circle class="node" r="4" transform="translate(7,7)"></g><path class="link" d="M14,7 L42,7"></svg> - Regular node & link</li>
      <li><svg class="instructionIcon element"><g><circle class="node selectInfo" r="4" transform="translate(7,7)"></g><path class="link selectInfo" d="M14,7 L42,7"></svg> - Info displayed</li>
      <li><svg class="instructionIcon element"><g><circle class="node selected" r="4" transform="translate(7,7)"></g><path class="link selected" d="M14,7 L42,7"></svg> - Selected for move, delete or assign</li>
      <li>Network Assigned:
        <span style="display: block;">
          <svg class="instructionIcon element"><g class="set0"><circle class="node" r="4" transform="translate(7,7)"></g><path class="link set0" d="M14,7 L42,7"></svg>
          <svg class="instructionIcon element"><g class="set1"><circle class="node" r="4" transform="translate(7,7)"></g><path class="link set1" d="M14,7 L42,7"></svg>
          <svg class="instructionIcon element"><g class="set2"><circle class="node" r="4" transform="translate(7,7)"></g><path class="link set2" d="M14,7 L42,7"></svg>
          <svg class="instructionIcon element"><g class="set3"><circle class="node" r="4" transform="translate(7,7)"></g><path class="link set3" d="M14,7 L42,7"></svg>
          <svg class="instructionIcon element"><g class="set4"><circle class="node" r="4" transform="translate(7,7)"></g><path class="link set4" d="M14,7 L42,7"></svg>
          <svg class="instructionIcon element"><g class="set5"><circle class="node" r="4" transform="translate(7,7)"></g><path class="link set5" d="M14,7 L42,7"></svg>
          <svg class="instructionIcon element"><g class="set6"><circle class="node" r="4" transform="translate(7,7)"></g><path class="link set6" d="M14,7 L42,7"></svg>
          <svg class="instructionIcon element"><g class="set7"><circle class="node" r="4" transform="translate(7,7)"></g><path class="link set7" d="M14,7 L42,7"></svg>
          <svg class="instructionIcon element"><g class="set8"><circle class="node" r="4" transform="translate(7,7)"></g><path class="link set8" d="M14,7 L42,7"></svg>
          <svg class="instructionIcon element"><g class="set9"><circle class="node" r="4" transform="translate(7,7)"></g><path class="link set9" d="M14,7 L42,7"></svg>
        </span>
        <li>NewtorkName - Unselected network</li>
        <li><span style="font-weight: bold">NewtorkName</span> - Active/Selected network</li>
        <li><span style="color: #1f77b4">NewtorkName</span> - Highlighted network (a selected node/link belongs to this network)</li>
      </li>
    </ul>
    <span class="instructionBlockSubTitle">Navigation</span>
    <ul>
      <li>Drag the canvas to pan.</li>
      <li>Use the mouse wheel to zoom in and out.</li>
      <li>Double click will zoom in.</li>
      <li>Click Center Canvas [ <svg class="instructionIcon"><use xlink:href="#icon-center"></use></svg> ] to center the current data to the view.</li>
      <li>Hold the <i>Shift Key</i> and drag across the canvas to zoom to the selected area.</li>
    </ul>
    <span class="instructionBlockSubTitle">Network Information</span>
    <ul>
      <li>On the Networks Panel, click the Info Icon [ <svg class="instructionIcon"><use xlink:href="#icon-info"></use></svg> ] beside a specific network to display its information.</li>
      <li>Activating/Selecting a network will highlight all the nodes/links associated with it to it's assigned network color.</li>
      <li>An activated/selected network will be in a <span style="font-weight: bold;">bold</span> font.</li>
      <li>To edit the name of a network, double click the network from the Network Panel.</li>
    </ul>
    <span class="instructionBlockSubTitle">Node/Path Information</span>
    <ul>
      <li>Without the Select [ <svg class="instructionIcon"><use xlink:href="#icon-select"></use></svg> ] enabled, click on a node/link to toggle information about it.</li>
      <li>A selected node/link will change to the color <span style="color: blue;">blue</span>.</li>
      <li>If a selected node/link belongs to one or more networks, the network/s will change to their designated color.</li>
      <li>To know network specific information of a node/link, select the network from the Networks Panel. A selected network will be in a <span style="font-weight: bold;">bold</span> font.</li>
      <li>Editable properties are in a <span style="font-weight: bold;">bold</span> font. Double click the property to edit.</li>
    </ul>
    <span class="instructionBlockSubTitle">Loading and Saving</span>
    <ul>
      <li>Only json files are fully supported at the moment.</li>
      <li>For full guide on the required data structure, browse our <a href="#">guide</a>.</li>
    </ul>
  </div>
</div>
