# Testing and Deployment!

To test and deploy our application to server, you will be using yml files in the latest folder for testing and deployment using Github Action.


# Prerequisite


## Setup Tailscale

[WIP]
configure tailscale
create authkey
add authkey

## Server Configuration

Use naming convention below to differentiate which server provider is the application deployed to. 

|Provider| Suffix | Example |
|--|--|--|
| Azure | AZ | AZ_HOSTNAME |
| PDSA | EPU | EPU_HOSTNAME|
| Amazon EC2| EC2 | EC2_HOSTNAME|
| Theta Server| THETA| THETA_HOSTNAME|



## Config to connect to server via SSH

Use the following naming format for configuration to connect to server via ssh

|Config| Format |
|--|--|
| Hostname/IP | SUFFIX_HOSTNAME |
|Username|SUFFIX_USERNAME|
|Password|SUFFIX_PASSWORD|
|Ports (if using port other than 22)|SUFFIX_PORTS|


## Configure secrets variable in github repo

[WIP]
add secrets


##  Test actions 
[WIP]

##  Status Badges 
[WIP]