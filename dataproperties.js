//DATA PROPERTIES
//Node Properties - additional properties for a generic (not network specific) node
//Defaults: ID (system generated), fx, fy
var nodeProperties = [
    { key: "name", label: "Name", dataType: "text", enableEdit: true },
    { key: "area", label: "Area", dataType: "text", enableEdit: true }];

//Link Properties - additional properties for a generic (not network specific) link
//Defaults: ID, Source ID, Source X, Source Y, Target ID, Target X, Target Y
var linkProperties = [
    { key: "name", label: "Name", dataType: "text", enableEdit: true }];

//Network Properties - additional properties for a network
//Defaults: ID (system generated), Name
var networkProperties = [{ key: "pcu", label: "PCU", dataType: "int", enableEdit: true }];

//Network Node Properties - additional node properties specific to a network
//Defaults: none
//NOTE: Network Node Properties must have different keys from Node Properties
// SAMPLE : [{ key: "netnod1", label: "Value1", dataType: "text", enableEdit: true }];
var networkNodeProperties = [];

//Network Link Properties - additional link properties specific to a networkKeys
//Defaults: laneCount
//NOTE: Network Link Properties must have different keys from Link Properties
//SAMPLE: { key: "netlin1", label: "Value1", dataType: "text", enableEdit: true }];
var networkLinkProperties = [];
