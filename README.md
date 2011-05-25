# Twitter Calculator (An Azure Sample App)

The purpose of this app is to demostrate some of the architectural and structural changes you need in order to make an app a true fit for a Cloud Computing solution. It was created to go along with a Windows Azure introduction talk.

## The functionality

The app is a simple twitter number cruncher like so many out there, its will gather information from twitter and process it to get stats for the latest retweets.

## The Upgrade

The ideia here is to look at the history of the app, from branch `non-cloud-version` to the `cloud-designed-version` branch. Comparing these two branches will show you a bit of the changes you want to do to your application in order to get it customed tailored to a cloud structure.

This does not mean the app in its original form will not work, it probably does but these changes will make use of the resources a cloud structure has to offer, form queues, to workers, to scaling and distributed relational databases, overcoming problems like needing to be stateless to allow for increse of web roles or worker roles to accomodate high usage.

### The "regular" version

The apps regular version is very simple, it does OAuth, send the user to a page with a loading icon, and starts crunching numbers, trying to avoid its own timeouts and leaving the user waiting, this data is then stored in a database and the user is sent along to his result page.

#### The "Azure Ready" version

This version follows a similar structure, but is kinder to the server and the user. Instead of a holding page it tells the user to go along and it will get in touch when its done. It shoves the users handle into a queue and uses worker roles to process this queue, chewing up whatever information it needs and storing it in the databse. Once its done with its calculations, the worker simply shoots off an email inviting the user to come see his results.