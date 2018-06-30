# Ideal Flow Network Editor

The Ideal Flow Network (IFN) Editor is a tool that enables users to create and visualize multiple networks and apply network related computations* on them such as the Ideal Flow Network Analysis. The IFN Editor is created with ease of usability in mind allowing users to quickly upload and download data, easily create network layers and instantly visualize geographical graph data.

*
Version 1 release does not have integrated computations yet.

## Features
* Map visualization
* Multiple network layers
* Assigning nodes and links to multiple network layers
* Uploading and downloading of data
* Customizable network, node, link, network node and network link properties
* Editable network, node and link property values for computations

## Libraries Used
* [D3.js - Data Driven Documents](https://d3js.org/) (v3)
* [JQuery](https://jquery.com/) (v3.2.1)
* [FileSaver.js](https://github.com/eligrey/FileSaver.js)

## Installation
1. Download the source code from the [PedLab Github](https://github.com/pedlab/IFN).
2. Configure your server to run PHP if not yet configured
  * [Configuring IIS and PHP](https://docs.microsoft.com/en-us/iis/application-frameworks/scenario-build-a-php-website-on-iis/configure-a-php-website-on-iis)
  * [Configuring Wamp and PHP](http://www.instructables.com/id/How-to-Run-a-PHP-Script-With-Wamp-Server/)
3. Map the physical path of the source code on your server
4. Browse to the url assigned to the website you just added and you're good to go

## Data Structure
### Required Parameters
These parameters are necessary to load a project file successfully.

* **Networks**

Parameter | Type | Description
--------- | ---- | -------------
name | string | unique name that identifies the layer group
nodes | array | nodes belonging to the network
links | array | links belonging to the network

* **Nodes**

Parameter | Type | Description
--------- | ---- | -------------
fx | decimal | x-coordinate of node
fy | decimal | y-coordinate of node

* **Links**

Parameter | Type | Description
--------- | ---- | -------------
source | node | source node
target | node | target node
laneCount | int | number of lanes; determines path thickness

#### JSON Format

```json
[
  {
    "name": "Network1",
    "nodes": [
      {
        "fx": 121.02740239021,
        "fy": 14.571502123441
      },
      {
        "fx": 121.02779399273,
        "fy": 14.57119580299
      }
    ],
    "links": [
      {
        "laneCount": 1,
        "source": {
			"fx": 121.02740239021,
			"fy": 14.571502123441
        },
        "target": {
			"fx": 121.02779399273,
			"fy": 14.57119580299
        }
      },
      {
        "laneCount": 2,
        "source": {
			"fx": 121.02779399273,
			"fy": 14.57119580299
        },
        "target": {
			"fx": 121.02740239021,
			"fy": 14.571502123441
        }
      }
    ]
  },
  {
    "name": "Network2",
    "nodes": [
      {
        "fx": 121.02824996826,
        "fy": 14.570847947046
      },
      {
        "fx": 121.02780472157,
        "fy": 14.57028203098
      }
    ],
    "links": [
      {
        "laneCount": 3,
        "source": {
			"fx": 121.02824996826,
			"fy": 14.570847947046
        },
        "target": {
			"fx": 121.02780472157,
			"fy": 14.57028203098
        }
      },
      {
        "laneCount": 4,
        "source": {
			"fx": 121.02780472157,
			"fy": 14.57028203098
        },
        "target": {
			"fx": 121.02824996826,
			"fy": 14.570847947046
        }
      }
    ]
  }
]
```

#### Sample XML Format
*NOTE: XML Format is partially supported as of version 1. Only saving of an xml file in the following format is possible.*
```xml
<networks>
    <network name="Network1">
        <nodes>
            <node fx="121.02740239021" fy="14.571502123441"/>
            <node fx="121.02779399273" fy="14.57119580299"/>
        </nodes>
        <links>
            <link laneCount="1">
                <source fx="121.02740239021" fy="14.571502123441"/>
                <target fx="121.02779399273" fy="14.57119580299"/>
            </link>
            <link laneCount="2">
                <source fx="121.02779399273" fy="14.57119580299"/>
                <target fx="121.02740239021" fy="14.571502123441"/>
            </link>
        </links>
    </network>
    <network name="Network2">
        <nodes>
            <node fx="121.02824996826" fy="14.570847947046"/>
            <node fx="121.02780472157" fy="14.57028203098"/>
        </nodes>
        <links>
            <link laneCount="3">
                <source fx="121.02824996826" fy="14.570847947046"/>
                <target fx="121.02780472157" fy="14.57028203098"/>
            </link>
            <link laneCount="4">
                <source fx="121.02780472157" fy="14.57028203098"/>
                <target fx="121.02824996826" fy="14.570847947046"/>
            </link>
        </links>
    </network>
</networks>
```

### Customizable Properties
The parameters/properties mentioned here are added just for the purpose of demonstration. They could be added and removed depending on project requirements. (See Customizing Properties).

* **Node Properties** - properties that are specific to a node regardless of the network/s it belongs to

Parameter | Description
--------- | -------------
name |
area |

* **Link Properties** - properties that are specific to a link regardless of the network/s it belongs to

Parameter | Description
--------- | -------------
name |

* **Network Properties** - properties added to networks

Parameter | Description
--------- | -------------
pcu | Passenger Car Unit

* **Network Node Properties** - node properties that are specific to a node belonging to a network. If the node belongs to multiple networks, a change in any network node property will only reflect for the specific network it was assigned to in.

*No network node properties are set for the initial setup*
*Network Node Properties must not have the same key name as Node Properties*

* **Network Link Properties** - link properties that are specific to a link belonging to a network. If the link belongs to multiple networks, a change in any network link property will only reflect for the specific network it was assigned to.

*No network link properties are set for the initial setup*
*Network Link Properties must not have the same key name as Link Properties*

#### Sample Properties

Node | fx | fy | name | area
---- | -- | -- | ---- | ----
Node1 | 121.02740239021 | 14.571502123441 | S.OsmenacorAngono | MakatiCity
Node2 | 121.02779399273 | 14.57119580299 | S.OsmenacorBaras | MakatiCity
Node3 | 121.02824996826 | 14.570847947046 | S.OsmenacorF.Zobel | MakatiCity
Node4 | 121.02780472157 | 14.57028203098 | F.ZobelcorMorong | MakatiCity

Link | source | target | roadName
---- | ------ | ---- | ----
Link1 | Node1 | Node2 | S.Osmena
Link2 | Node2 | Node3 | F.Zobel

Network | name
------- | ----
Network1 | Private Vehicles
Network2 | Truck Network

Network | Node | {custom property}
------- | ---- | -----------------
Network1 | Node1 | {custom value1}
Network2 | Node1 | {custom value2}

Network | Link | name | laneCount
------- | ---- | ---- | ---------
Network1 | Link1 | S.Osmena - Private | 1
Network1 | Link2 | F.Zobel - Private | 2
Network2 | Link1 | S.Osmena - Truck | 2
Network2 | Link2 | F.Zobel - Truck | 3

## Customizing Properties
1. Open the dataproperties.js file.
2. Create new property objects as needed.

All kinds of properties (node, link, network, network node and network link) have the same structure.

Properties could be empty depending on the requirements of the project but the variables should not be deleted.

Parameter | Description
--------- | -------------
key | unique identifier for the property
label | text that appears on the editor panels
dataType | type of data that the system should expect when editing a property
enableEdit | boolean; determines if the property will be editable on the info panels
value | (optional) stores the value that will be shown on the info panels

### Sample Properties

```javascript
var nodeProperties = [
    { key: "name", label: "Name", dataType: "text", enableEdit: true },
    { key: "area", label: "Area", dataType: "text", enableEdit: true }];

var linkProperties = [
    { key: "name", label: "Name", dataType: "text", enableEdit: true }];

var networkProperties = [{ key: "pcu", label: "PCU", dataType: "int", enableEdit: true }];

var networkNodeProperties = [];

var networkLinkProperties = [];
```

## Known Issues
* Loading of large datasets is too slow
* Paths appear in different widths depending on zoom level
* Truncating of data on the information panel

## For Future Development and Improvement
* Integrate calculations
* Improve saving and loading of xml formatted data.
* Data type checking for properties
* Implement a default value on customizable properties
* Consolidating nearby nodes
* Zoom in/out buttons
* Create a slider bar on information panel to accommodate more info
* Check browser compatibility
* Pusblish to PedLab website
