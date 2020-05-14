# Architecture
[ ![](images/architecture.png) ](images/architecture.png)

## Firebase
Firebase provides a real-time database and back-end as a service.<br>
The service provides application developers an API that allows application data <br>
to be synchronized across clients and stored on Firebase's cloud.<br>
<br>
The major advantage here is that you can designate a collection in Firebase<br>
with a particular schema and authorization access, and queue background jobs with Firebase directly.<br>
You can then set a cloud function to trigger on creation, perform some task,<br>
and update the queue item with the status of the transaction.<br>
<br>
Other major advantages would be realtime notifications, realtime chat/messaging, synced application state.<br>

## App Engine
Google App Engine is a Platform as a Service and cloud computing platform<br>
for developing and hosting web applications in Google-managed data centers.<br>
Applications are sandboxed and run across multiple servers.<br>
<br>
The major advantages are:<br>
<ul>
    <li>no need to set up a server</li>
    <li>instant for-free nearly infinite scalability</li>
    <li>spikey traffic and rather unpredictable</li>
    <li>server monitoring tools</li>
    <li>pricing that fits your actual usage and isn't time-slot based</li>
    <li>the ability to chunk long tasks into 30 second pieces</li>
    <li>direct filesystem access</li>
</ul> 
 
## Bootstrap
Bootstrap is a free and open-source CSS framework directed at responsive,<br>
mobile-first front-end web development.<br>
It contains CSS- and JavaScript-based design templates for typography, forms, buttons, navigation,<br>
and other interface components.<br>
<br>
Major advantage - speeds up production time.<br>
<br>

## Flask
Flask is a micro web framework written in Python.<br>
It is classified as a microframework because it does not require particular tools or libraries.<br>
It has no database abstraction layer, form validation,<br>
or any other components where pre-existing third-party libraries provide common functions.<br>
<br>
Major advantages - low complexity, dependency free & easy to learn language.<br>

## Cloud Logging API		
Writes log entries and manages your Cloud Logging configuration.<br>
			
## Cloud Monitoring API		
Manages your Cloud Monitoring data and configurations.<br>
			
## Cloud Build API
Creates and manages builds on Google Cloud Platform.<br>
			
## Service Control API	
Provides control plane functionality to managed services, such as logging, monitoring, and status checks.<br>
				
## Compute Engine API		
Creates and runs virtual machines on Google Cloud Platform.<br>
				
## Cloud Deployment Manager V2 API	
The Google Cloud Deployment Manager v2 API provides services for configuring,<br>
deploying, and viewing Google Cloud services and APIs via templates which specify deployments of Cloud resources.<br>
		
## App Engine Admin API
Provisions and manages developers' App Engine applications.<br>
			
## Secret Manager API
Stores sensitive data such as API keys, passwords, and certificates. Provides convenience while improving security.<br>
			
## Cloud Pub/Sub API
Provides reliable, many-to-many, asynchronous messaging between applications.<br>

## Identity Toolkit API
The Google Identity Toolkit API lets you use open standards to verify a user's identity.<br>
			
## Cloud Source Repositories API
Accesses source code repositories hosted by Google.<br>
		
## Service Usage API
Enables services that service consumers want to use on Google Cloud Platform,<br>
lists the available or enabled services, or disables services that service consumers no longer use.<br>
				
## Service Management API
Google Service Management allows service producers to publish their services on Google Cloud Platform<br>
so that they can be discovered and used by service consumers.<br>
				
## Cloud Functions API						
Manages lightweight user-provided functions executed in response to events.<br>

## Google Cloud Memorystore for Redis API
Creates and manages Redis instances on the Google Cloud Platform.<br>

## Cloud Run API
Deploy and manage user provided container images that scale automatically based on HTTP traffic.<br>
						
## Firebase Installations API	
Manages operations on Firebase DB.<br>
			
## Cloud Trace API
Sends application trace data to Cloud Trace for viewing.<br>
					
## Token Service API
The Token Service API lets you exchange an ID token or a refresh token for an access token and a refresh token,<br>
which you can use to securely call your own APIs.<br>
				
## Endpoints API
REST APIs, developer portal, metrics.