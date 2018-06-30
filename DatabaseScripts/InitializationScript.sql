CREATE TABLE Nodes (
	NodeId		bigint NOT NULL PRIMARY KEY,
    Name		nvarchar(128) NOT NULL,
    Latitude 	decimal(20, 14) NOT NULL,
    Longitude 	decimal(20, 14) NOT NULL,
    Remarks		nvarchar(1024) NOT NULL,
    Attributes	int NOT NULL,
    CreatedBy	nvarchar(128) NOT NULL,
    Created		datetime NOT NULL,
    ModifiedBy	nvarchar(128) NOT NULL,
    LastModified datetime NOT NULL
);

CREATE TABLE RoadTypes (
	RoadTypeId	int NOT NULL PRIMARY KEY,
    Name		nvarchar(128) NOT NULL,
    Remarks		nvarchar(1024) NOT NULL,
    Attributes	int NOT NULL,
    CreatedBy	nvarchar(128) NOT NULL,
    Created		datetime NOT NULL,
    ModifiedBy	nvarchar(128) NOT NULL,
    LastModified datetime NOT NULL
);

CREATE TABLE RoadLinks (
	RoadLinkId	bigint NOT NULL AUTO_INCREMENT PRIMARY KEY,
    StartNodeId	bigint NOT NULL,
    EndNodeId	bigint NOT NULL,
    Name		nvarchar(128) NOT NULL,
    Distance	decimal(8,4) NOT NULL,
    LaneCount	int	NOT NULL,
    RoadType	nvarchar(128) NOT NULL,
    Remarks		nvarchar(1024) NOT NULL,
    Attributes	int NOT NULL,
    CreatedBy	nvarchar(128) NOT NULL,
    Created		datetime NOT NULL,
    ModifiedBy	nvarchar(128) NOT NULL,
    LastModified datetime NOT NULL,
    FOREIGN KEY (StartNodeId) REFERENCES Nodes(NodeId),
    FOREIGN KEY (EndNodeId) REFERENCES Nodes(NodeId)
    /*,FOREIGN KEY (RoadTypeId) REFERENCES RoadTypes(RoadTypeId)*/
);

INSERT INTO RoadTypes
(RoadTypeId, Name, Remarks, Attributes, CreatedBy, Created, ModifiedBy, LastModified)
VALUES 
(1, 'Motorway', '', 0, 'System', utc_date(), 'System', utc_date()),
(2, 'Motorway Link', '', 0, 'System', utc_date(), 'System', utc_date()),
(3, 'Primary', '', 0, 'System', utc_date(), 'System', utc_date()),
(4, 'Primary Link', '', 0, 'System', utc_date(), 'System', utc_date()),
(5, 'Secondary', '', 0, 'System', utc_date(), 'System', utc_date()),
(6, 'Secondary Link', '', 0, 'System', utc_date(), 'System', utc_date()),
(7, 'Tertiary', '', 0, 'System', utc_date(), 'System', utc_date()),
(8, 'Tertiary Link', '', 0, 'System', utc_date(), 'System', utc_date()),
(9, 'Trunk', '', 0, 'System', utc_date(), 'System', utc_date()),
(10, 'Trunk Link', '', 0, 'System', utc_date(), 'System', utc_date()),
(11, 'Unclassified', '', 0, 'System', utc_date(), 'System', utc_date())